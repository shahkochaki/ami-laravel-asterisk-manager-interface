<?php

namespace Shahkochaki\Ami\Services;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Collection;

/**
 * Call Management Service
 * 
 * Provides high-level call management functionality
 */
class CallManager
{
    /**
     * @var array Default connection options
     */
    protected $connectionOptions = [];

    /**
     * Constructor
     *
     * @param array $connectionOptions
     */
    public function __construct(array $connectionOptions = [])
    {
        $this->connectionOptions = $connectionOptions;
    }

    /**
     * Initiate a call
     *
     * @param string $from
     * @param string $to
     * @param string $context
     * @param int $priority
     * @param array $variables
     * @return mixed
     */
    public function makeCall($from, $to, $context = 'default', $priority = 1, array $variables = [])
    {
        $arguments = [
            'Channel' => $this->formatChannel($from),
            'Context' => $context,
            'Exten' => $to,
            'Priority' => (string)$priority,
            'CallerID' => $from,
            'Timeout' => '30000', // 30 seconds
        ];

        // Add custom variables
        foreach ($variables as $key => $value) {
            $arguments["Variable"] = "{$key}={$value}";
        }

        return $this->executeAction('Originate', $arguments);
    }

    /**
     * Hangup a call
     *
     * @param string $channel
     * @param string $cause
     * @return mixed
     */
    public function hangupCall($channel, $cause = 'Normal Clearing')
    {
        return $this->executeAction('Hangup', [
            'Channel' => $channel,
            'Cause' => $cause
        ]);
    }

    /**
     * Transfer a call
     *
     * @param string $channel
     * @param string $extension
     * @param string $context
     * @return mixed
     */
    public function transferCall($channel, $extension, $context = 'default')
    {
        return $this->executeAction('Redirect', [
            'Channel' => $channel,
            'Context' => $context,
            'Exten' => $extension,
            'Priority' => '1'
        ]);
    }

    /**
     * Park a call
     *
     * @param string $channel
     * @param string $parkingLot
     * @return mixed
     */
    public function parkCall($channel, $parkingLot = 'default')
    {
        return $this->executeAction('Park', [
            'Channel' => $channel,
            'ParkingLot' => $parkingLot
        ]);
    }

    /**
     * Bridge two channels
     *
     * @param string $channel1
     * @param string $channel2
     * @param bool $tone
     * @return mixed
     */
    public function bridgeChannels($channel1, $channel2, $tone = true)
    {
        return $this->executeAction('Bridge', [
            'Channel1' => $channel1,
            'Channel2' => $channel2,
            'Tone' => $tone ? 'yes' : 'no'
        ]);
    }

    /**
     * Get channel status
     *
     * @param string|null $channel
     * @return mixed
     */
    public function getChannelStatus($channel = null)
    {
        $arguments = [];
        if ($channel) {
            $arguments['Channel'] = $channel;
        }

        return $this->executeAction('Status', $arguments);
    }

    /**
     * Get active channels list
     *
     * @return mixed
     */
    public function getActiveChannels()
    {
        return $this->executeAction('CoreShowChannels');
    }

    /**
     * Send DTMF to channel
     *
     * @param string $channel
     * @param string $digit
     * @param int $duration
     * @return mixed
     */
    public function sendDtmf($channel, $digit, $duration = 250)
    {
        return $this->executeAction('PlayDTMF', [
            'Channel' => $channel,
            'Digit' => $digit,
            'Duration' => (string)$duration
        ]);
    }

    /**
     * Monitor a channel
     *
     * @param string $channel
     * @param string $file
     * @param string $format
     * @param bool $mix
     * @return mixed
     */
    public function monitorChannel($channel, $file, $format = 'wav', $mix = true)
    {
        return $this->executeAction('Monitor', [
            'Channel' => $channel,
            'File' => $file,
            'Format' => $format,
            'Mix' => $mix ? 'true' : 'false'
        ]);
    }

    /**
     * Stop monitoring a channel
     *
     * @param string $channel
     * @return mixed
     */
    public function stopMonitor($channel)
    {
        return $this->executeAction('StopMonitor', [
            'Channel' => $channel
        ]);
    }

    /**
     * Format channel string
     *
     * @param string $channel
     * @return string
     */
    protected function formatChannel($channel)
    {
        // If already formatted, return as is
        if (strpos($channel, '/') !== false) {
            return $channel;
        }

        // Default to SIP if no technology specified
        return "SIP/{$channel}";
    }

    /**
     * Execute AMI action
     *
     * @param string $action
     * @param array $arguments
     * @return mixed
     */
    protected function executeAction($action, array $arguments = [])
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
     * Set connection options
     *
     * @param array $options
     * @return self
     */
    public function setConnectionOptions(array $options)
    {
        $this->connectionOptions = $options;
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