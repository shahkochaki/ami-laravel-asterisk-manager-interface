<?php

namespace Shahkochaki\Ami\Services;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Collection;

/**
 * Bulk SMS Service
 * 
 * Provides bulk SMS sending functionality with queue management
 */
class BulkSmsService
{
    /**
     * @var string Default device
     */
    protected $device;

    /**
     * @var array Connection options
     */
    protected $connectionOptions = [];

    /**
     * @var int Delay between messages (microseconds)
     */
    protected $delayBetweenMessages = 500000; // 0.5 seconds

    /**
     * @var int Maximum retries for failed messages
     */
    protected $maxRetries = 3;

    /**
     * @var int PDU threshold (characters)
     */
    protected $pduThreshold = 160;

    /**
     * Constructor
     *
     * @param string|null $device
     * @param array $connectionOptions
     */
    public function __construct($device = null, array $connectionOptions = [])
    {
        $this->device = $device ?: config('ami.dongle.sms.device');
        $this->connectionOptions = $connectionOptions;
    }

    /**
     * Send bulk SMS to multiple recipients
     *
     * @param Collection|array $recipients
     * @param string $message
     * @param array $options
     * @return array
     */
    public function sendBulkSms($recipients, string $message, array $options = [])
    {
        $recipients = is_array($recipients) ? collect($recipients) : $recipients;
        $results = [];
        $device = $options['device'] ?? $this->device;
        $usePdu = $options['pdu'] ?? (strlen($message) > $this->pduThreshold);
        $delay = $options['delay'] ?? $this->delayBetweenMessages;

        foreach ($recipients as $number) {
            $retries = 0;
            $success = false;

            while ($retries < $this->maxRetries && !$success) {
                try {
                    $result = $this->sendSingleSms($number, $message, $device, $usePdu);
                    $results[$number] = [
                        'status' => 'success',
                        'result' => $result,
                        'retries' => $retries,
                        'timestamp' => now()->toISOString()
                    ];
                    $success = true;

                } catch (\Exception $e) {
                    $retries++;
                    
                    if ($retries >= $this->maxRetries) {
                        $results[$number] = [
                            'status' => 'error',
                            'message' => $e->getMessage(),
                            'retries' => $retries,
                            'timestamp' => now()->toISOString()
                        ];
                    } else {
                        // Wait before retry
                        usleep($delay * 2); // Double delay for retries
                    }
                }
            }

            // Delay between messages (if successful or final failure)
            if ($success || $retries >= $this->maxRetries) {
                usleep($delay);
            }
        }

        return $results;
    }

    /**
     * Send SMS to a single recipient
     *
     * @param string $number
     * @param string $message
     * @param string|null $device
     * @param bool $usePdu
     * @return mixed
     */
    public function sendSingleSms($number, $message, $device = null, $usePdu = null)
    {
        $device = $device ?: $this->device;
        $usePdu = $usePdu ?? (strlen($message) > $this->pduThreshold);

        $command = array_merge([
            'number' => $number,
            'message' => $message,
            'device' => $device,
        ], $this->connectionOptions);

        if ($usePdu) {
            $command['--pdu'] = true;
        }

        return Artisan::call('ami:dongle:sms', $command);
    }

    /**
     * Send scheduled SMS
     *
     * @param Collection|array $recipients
     * @param string $message
     * @param \Carbon\Carbon $scheduledAt
     * @param array $options
     * @return array
     */
    public function scheduleBulkSms($recipients, string $message, $scheduledAt, array $options = [])
    {
        // This would integrate with Laravel's job queue system
        $jobData = [
            'recipients' => is_array($recipients) ? $recipients : $recipients->toArray(),
            'message' => $message,
            'options' => $options,
            'service' => static::class,
        ];

        // Schedule the job (pseudo-code, would need actual job class)
        return \Illuminate\Support\Facades\Queue::later($scheduledAt, 'BulkSmsJob', $jobData);
    }

    /**
     * Get SMS delivery report
     *
     * @param array $messageIds
     * @return array
     */
    public function getDeliveryReport(array $messageIds)
    {
        // This would query AMI for SMS status
        $reports = [];
        
        foreach ($messageIds as $messageId) {
            try {
                $result = Artisan::call('ami:action', array_merge([
                    'action' => 'DongleSMSStatus',
                    '--arguments' => ['MessageId' => $messageId]
                ], $this->connectionOptions));
                
                $reports[$messageId] = $result;
            } catch (\Exception $e) {
                $reports[$messageId] = ['error' => $e->getMessage()];
            }
        }

        return $reports;
    }

    /**
     * Validate phone numbers
     *
     * @param Collection|array $numbers
     * @return array
     */
    public function validateNumbers($numbers)
    {
        $numbers = is_array($numbers) ? collect($numbers) : $numbers;
        $valid = [];
        $invalid = [];

        foreach ($numbers as $number) {
            if ($this->isValidPhoneNumber($number)) {
                $valid[] = $this->formatPhoneNumber($number);
            } else {
                $invalid[] = $number;
            }
        }

        return [
            'valid' => $valid,
            'invalid' => $invalid,
            'total' => count($numbers),
            'valid_count' => count($valid),
            'invalid_count' => count($invalid)
        ];
    }

    /**
     * Check if phone number is valid
     *
     * @param string $number
     * @return bool
     */
    protected function isValidPhoneNumber($number)
    {
        // Basic Iranian mobile number validation
        $pattern = '/^(\+98|0098|98|0)?9\d{9}$/';
        return preg_match($pattern, $number);
    }

    /**
     * Format phone number
     *
     * @param string $number
     * @return string
     */
    protected function formatPhoneNumber($number)
    {
        // Remove any non-digit characters except +
        $number = preg_replace('/[^\d+]/', '', $number);
        
        // Convert to international format
        if (substr($number, 0, 1) === '0') {
            $number = '+98' . substr($number, 1);
        } elseif (substr($number, 0, 2) === '98') {
            $number = '+' . $number;
        } elseif (substr($number, 0, 1) !== '+') {
            $number = '+98' . $number;
        }

        return $number;
    }

    /**
     * Set device
     *
     * @param string $device
     * @return self
     */
    public function setDevice($device)
    {
        $this->device = $device;
        return $this;
    }

    /**
     * Set delay between messages
     *
     * @param int $microseconds
     * @return self
     */
    public function setDelay($microseconds)
    {
        $this->delayBetweenMessages = $microseconds;
        return $this;
    }

    /**
     * Set maximum retries
     *
     * @param int $retries
     * @return self
     */
    public function setMaxRetries($retries)
    {
        $this->maxRetries = $retries;
        return $this;
    }

    /**
     * Set PDU threshold
     *
     * @param int $threshold
     * @return self
     */
    public function setPduThreshold($threshold)
    {
        $this->pduThreshold = $threshold;
        return $this;
    }

    /**
     * Get statistics
     *
     * @return array
     */
    public function getStats()
    {
        return [
            'device' => $this->device,
            'delay_between_messages' => $this->delayBetweenMessages,
            'max_retries' => $this->maxRetries,
            'pdu_threshold' => $this->pduThreshold,
        ];
    }
}