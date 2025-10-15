<?php

namespace Shahkochaki\Ami\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Shahkochaki\Ami\Services\BulkSmsService;
use Illuminate\Support\Facades\Log;

/**
 * Background job for bulk SMS processing
 */
class BulkSmsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var array Recipients
     */
    protected $recipients;

    /**
     * @var string Message
     */
    protected $message;

    /**
     * @var array Options
     */
    protected $options;

    /**
     * @var int Number of tries
     */
    public $tries = 3;

    /**
     * @var int Timeout in seconds
     */
    public $timeout = 300;

    /**
     * Create a new job instance.
     *
     * @param array $recipients
     * @param string $message
     * @param array $options
     */
    public function __construct(array $recipients, string $message, array $options = [])
    {
        $this->recipients = $recipients;
        $this->message = $message;
        $this->options = $options;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            $device = $this->options['device'] ?? null;
            $connectionOptions = $this->options['connection'] ?? [];

            $smsService = new BulkSmsService($device, $connectionOptions);

            // Set additional options if provided
            if (isset($this->options['delay'])) {
                $smsService->setDelay($this->options['delay']);
            }

            if (isset($this->options['max_retries'])) {
                $smsService->setMaxRetries($this->options['max_retries']);
            }

            if (isset($this->options['pdu_threshold'])) {
                $smsService->setPduThreshold($this->options['pdu_threshold']);
            }

            $results = $smsService->sendBulkSms(
                collect($this->recipients),
                $this->message,
                $this->options
            );

            // Log results
            $successCount = count(array_filter($results, function ($result) {
                return $result['status'] === 'success';
            }));

            $failureCount = count($this->recipients) - $successCount;

            Log::info('Bulk SMS job completed', [
                'total_recipients' => count($this->recipients),
                'successful' => $successCount,
                'failed' => $failureCount,
                'message_length' => strlen($this->message),
                'device' => $device,
            ]);

            // Fire completion event
            event('ami.bulk_sms.completed', [
                'results' => $results,
                'recipients' => $this->recipients,
                'message' => $this->message,
                'options' => $this->options,
            ]);
        } catch (\Exception $e) {
            Log::error('Bulk SMS job failed', [
                'error' => $e->getMessage(),
                'recipients_count' => count($this->recipients),
                'message_length' => strlen($this->message),
                'attempt' => $this->attempts(),
            ]);

            // Fire failure event
            event('ami.bulk_sms.failed', [
                'error' => $e->getMessage(),
                'recipients' => $this->recipients,
                'message' => $this->message,
                'options' => $this->options,
                'attempt' => $this->attempts(),
            ]);

            throw $e;
        }
    }

    /**
     * Handle a job failure.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function failed(\Exception $exception)
    {
        Log::critical('Bulk SMS job permanently failed', [
            'error' => $exception->getMessage(),
            'recipients_count' => count($this->recipients),
            'message_length' => strlen($this->message),
            'total_attempts' => $this->attempts(),
        ]);

        // Fire permanent failure event
        event('ami.bulk_sms.permanently_failed', [
            'error' => $exception->getMessage(),
            'recipients' => $this->recipients,
            'message' => $this->message,
            'options' => $this->options,
            'total_attempts' => $this->attempts(),
        ]);
    }
}
