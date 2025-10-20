<?php

namespace Shahkochaki\Ami\Services;

use Illuminate\Support\Facades\Artisan;
use Shahkochaki\Ami\Services\SystemManager;
use Shahkochaki\Ami\Services\CallManager;
use Shahkochaki\Ami\Services\BulkSmsService;

/**
 * Main AMI Service
 * 
 * Central service for all AMI operations
 */
class AmiService
{
    /**
     * @var array Connection options
     */
    protected $connectionOptions = [];

    /**
     * @var SystemManager
     */
    protected $systemManager;

    /**
     * @var CallManager
     */
    protected $callManager;

    /**
     * @var BulkSmsService
     */
    protected $smsService;

    /**
     * Constructor
     *
     * @param array $connectionOptions
     */
    public function __construct(array $connectionOptions = [])
    {
        $this->connectionOptions = $connectionOptions ?: config('ami', []);
        $this->initializeServices();
    }

    /**
     * Initialize sub-services
     */
    protected function initializeServices()
    {
        $this->systemManager = new SystemManager($this->connectionOptions);
        $this->callManager = new CallManager($this->connectionOptions);
        $this->smsService = new BulkSmsService();
    }

    /**
     * Execute AMI action
     *
     * @param string $action
     * @param array $arguments
     * @return mixed
     */
    public function action($action, array $arguments = [])
    {
        $command = array_merge([
            'action' => $action,
        ], $this->connectionOptions);

        if (!empty($arguments)) {
            $command['--arguments'] = $arguments;
        }

        return Artisan::call('ami:action', $command);
    }

    /**
     * Make a call
     *
     * @param string $from
     * @param string $to
     * @param string $context
     * @return mixed
     */
    public function makeCall($from, $to, $context = 'default')
    {
        return $this->callManager->makeCall($from, $to, $context);
    }

    /**
     * Hangup a call
     *
     * @param string $channel
     * @return mixed
     */
    public function hangupCall($channel)
    {
        return $this->callManager->hangupCall($channel);
    }

    /**
     * Send SMS
     *
     * @param string $number
     * @param string $message
     * @param string|null $device
     * @return mixed
     */
    public function sendSms($number, $message, $device = null)
    {
        $command = [
            'number' => $number,
            'message' => $message
        ];

        if ($device) {
            $command['device'] = $device;
        }

        $command = array_merge($command, $this->connectionOptions);

        return Artisan::call('ami:dongle:sms', $command);
    }

    /**
     * Send USSD
     *
     * @param string $device
     * @param string $ussd
     * @return mixed
     */
    public function sendUssd($device, $ussd)
    {
        $command = array_merge([
            'device' => $device,
            'ussd' => $ussd
        ], $this->connectionOptions);

        return Artisan::call('ami:dongle:ussd', $command);
    }

    /**
     * Get channel status
     *
     * @param string|null $channel
     * @return mixed
     */
    public function getChannelStatus($channel = null)
    {
        return $this->callManager->getChannelStatus($channel);
    }

    /**
     * Get active channels
     *
     * @return mixed
     */
    public function getActiveChannels()
    {
        return $this->callManager->getActiveChannels();
    }

    /**
     * Get system statistics
     *
     * @return array
     */
    public function getStats()
    {
        return [
            'server_status' => $this->systemManager->getServerStatus(),
            'active_channels' => $this->getActiveChannels(),
            'system_resources' => $this->systemManager->getSystemResources(),
            'timestamp' => date('Y-m-d H:i:s')
        ];
    }

    /**
     * Get system manager instance
     *
     * @return SystemManager
     */
    public function system()
    {
        return $this->systemManager;
    }

    /**
     * Get call manager instance
     *
     * @return CallManager
     */
    public function calls()
    {
        return $this->callManager;
    }

    /**
     * Get SMS service instance
     *
     * @return BulkSmsService
     */
    public function sms()
    {
        return $this->smsService;
    }

    /**
     * Test connection
     *
     * @return mixed
     */
    public function ping()
    {
        return $this->action('Ping');
    }

    /**
     * Listen to events
     *
     * @param bool $monitor
     * @return mixed
     */
    public function listen($monitor = false)
    {
        $command = array_merge($this->connectionOptions, [
            '--monitor' => $monitor
        ]);

        return Artisan::call('ami:listen', $command);
    }

    /**
     * Set connection options
     *
     * @param array $options
     * @return self
     */
    public function setConnectionOptions(array $options)
    {
        $this->connectionOptions = array_merge($this->connectionOptions, $options);
        $this->initializeServices(); // Reinitialize with new options
        return $this;
    }

    /**
     * Get connection options
     *
     * @return array
     */
    public function getConnectionOptions()
    {
        return $this->connectionOptions;
    }
}
