<?php

namespace Shahkochaki\Ami\Listeners;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Event;

/**
 * AMI Event Listener
 * 
 * Advanced event handling for AMI events
 */
class AmiEventListener
{
    /**
     * @var array Event handlers
     */
    protected $handlers = [];

    /**
     * @var bool Enable logging
     */
    protected $enableLogging = true;

    /**
     * @var string Log channel
     */
    protected $logChannel = 'ami';

    /**
     * Register event handlers
     *
     * @return void
     */
    public function register()
    {
        // Call events
        $this->registerCallEvents();
        
        // SMS events
        $this->registerSmsEvents();
        
        // System events
        $this->registerSystemEvents();
        
        // Queue events
        $this->registerQueueEvents();
    }

    /**
     * Register call-related events
     *
     * @return void
     */
    protected function registerCallEvents()
    {
        Event::listen('ami.event.dial', [$this, 'handleDialEvent']);
        Event::listen('ami.event.hangup', [$this, 'handleHangupEvent']);
        Event::listen('ami.event.newchannel', [$this, 'handleNewChannelEvent']);
        Event::listen('ami.event.bridge', [$this, 'handleBridgeEvent']);
    }

    /**
     * Register SMS-related events
     *
     * @return void
     */
    protected function registerSmsEvents()
    {
        Event::listen('ami.event.donglesmsstatus', [$this, 'handleSmsStatusEvent']);
        Event::listen('ami.event.donglenewsms', [$this, 'handleNewSmsEvent']);
    }

    /**
     * Register system events
     *
     * @return void
     */
    protected function registerSystemEvents()
    {
        Event::listen('ami.event.reload', [$this, 'handleReloadEvent']);
        Event::listen('ami.event.shutdown', [$this, 'handleShutdownEvent']);
        Event::listen('ami.event.peerstatus', [$this, 'handlePeerStatusEvent']);
    }

    /**
     * Register queue events
     *
     * @return void
     */
    protected function registerQueueEvents()
    {
        Event::listen('ami.event.queuemember', [$this, 'handleQueueMemberEvent']);
        Event::listen('ami.event.queueparams', [$this, 'handleQueueParamsEvent']);
        Event::listen('ami.event.queuesummary', [$this, 'handleQueueSummaryEvent']);
    }

    /**
     * Handle Dial event
     *
     * @param array $event
     * @return void
     */
    public function handleDialEvent($event)
    {
        $this->log('info', 'New call initiated', [
            'caller' => $event['CallerIDNum'] ?? 'Unknown',
            'destination' => $event['Destination'] ?? 'Unknown',
            'channel' => $event['Channel'] ?? 'Unknown',
            'timestamp' => now()->toISOString()
        ]);

        // Fire custom application event
        Event::fire('ami.call.started', $event);
    }

    /**
     * Handle Hangup event
     *
     * @param array $event
     * @return void
     */
    public function handleHangupEvent($event)
    {
        $this->log('info', 'Call ended', [
            'channel' => $event['Channel'] ?? 'Unknown',
            'cause' => $event['Cause'] ?? 'Unknown',
            'cause_text' => $event['Cause-txt'] ?? 'Unknown',
            'timestamp' => now()->toISOString()
        ]);

        // Fire custom application event
        Event::fire('ami.call.ended', $event);
    }

    /**
     * Handle NewChannel event
     *
     * @param array $event
     * @return void
     */
    public function handleNewChannelEvent($event)
    {
        $this->log('debug', 'New channel created', [
            'channel' => $event['Channel'] ?? 'Unknown',
            'state' => $event['ChannelState'] ?? 'Unknown',
            'caller_id' => $event['CallerIDNum'] ?? 'Unknown',
            'timestamp' => now()->toISOString()
        ]);

        // Fire custom application event
        Event::fire('ami.channel.created', $event);
    }

    /**
     * Handle Bridge event
     *
     * @param array $event
     * @return void
     */
    public function handleBridgeEvent($event)
    {
        $this->log('info', 'Channels bridged', [
            'channel1' => $event['Channel1'] ?? 'Unknown',
            'channel2' => $event['Channel2'] ?? 'Unknown',
            'timestamp' => now()->toISOString()
        ]);

        // Fire custom application event
        Event::fire('ami.call.bridged', $event);
    }

    /**
     * Handle SMS Status event
     *
     * @param array $event
     * @return void
     */
    public function handleSmsStatusEvent($event)
    {
        $this->log('info', 'SMS status update', [
            'device' => $event['Device'] ?? 'Unknown',
            'status' => $event['Status'] ?? 'Unknown',
            'timestamp' => now()->toISOString()
        ]);

        // Fire custom application event
        Event::fire('ami.sms.status_updated', $event);
    }

    /**
     * Handle New SMS event
     *
     * @param array $event
     * @return void
     */
    public function handleNewSmsEvent($event)
    {
        $this->log('info', 'New SMS received', [
            'device' => $event['Device'] ?? 'Unknown',
            'from' => $event['From'] ?? 'Unknown',
            'message' => $event['Message'] ?? '',
            'timestamp' => now()->toISOString()
        ]);

        // Fire custom application event
        Event::fire('ami.sms.received', $event);
    }

    /**
     * Handle Reload event
     *
     * @param array $event
     * @return void
     */
    public function handleReloadEvent($event)
    {
        $this->log('warning', 'System reload', [
            'module' => $event['Module'] ?? 'All',
            'timestamp' => now()->toISOString()
        ]);

        // Fire custom application event
        Event::fire('ami.system.reloaded', $event);
    }

    /**
     * Handle Shutdown event
     *
     * @param array $event
     * @return void
     */
    public function handleShutdownEvent($event)
    {
        $this->log('critical', 'System shutdown', [
            'reason' => $event['Shutdown'] ?? 'Unknown',
            'timestamp' => now()->toISOString()
        ]);

        // Fire custom application event
        Event::fire('ami.system.shutdown', $event);
    }

    /**
     * Handle PeerStatus event
     *
     * @param array $event
     * @return void
     */
    public function handlePeerStatusEvent($event)
    {
        $this->log('info', 'Peer status changed', [
            'peer' => $event['Peer'] ?? 'Unknown',
            'peer_status' => $event['PeerStatus'] ?? 'Unknown',
            'timestamp' => now()->toISOString()
        ]);

        // Fire custom application event
        Event::fire('ami.peer.status_changed', $event);
    }

    /**
     * Handle QueueMember event
     *
     * @param array $event
     * @return void
     */
    public function handleQueueMemberEvent($event)
    {
        $this->log('info', 'Queue member status', [
            'queue' => $event['Queue'] ?? 'Unknown',
            'location' => $event['Location'] ?? 'Unknown',
            'status' => $event['Status'] ?? 'Unknown',
            'timestamp' => now()->toISOString()
        ]);

        // Fire custom application event
        Event::fire('ami.queue.member_updated', $event);
    }

    /**
     * Handle QueueParams event
     *
     * @param array $event
     * @return void
     */
    public function handleQueueParamsEvent($event)
    {
        $this->log('debug', 'Queue parameters', [
            'queue' => $event['Queue'] ?? 'Unknown',
            'calls' => $event['Calls'] ?? 0,
            'holdtime' => $event['Holdtime'] ?? 0,
            'timestamp' => now()->toISOString()
        ]);

        // Fire custom application event
        Event::fire('ami.queue.params_updated', $event);
    }

    /**
     * Handle QueueSummary event
     *
     * @param array $event
     * @return void
     */
    public function handleQueueSummaryEvent($event)
    {
        $this->log('info', 'Queue summary', [
            'queue' => $event['Queue'] ?? 'Unknown',
            'logged_in' => $event['LoggedIn'] ?? 0,
            'available' => $event['Available'] ?? 0,
            'callers' => $event['Callers'] ?? 0,
            'timestamp' => now()->toISOString()
        ]);

        // Fire custom application event
        Event::fire('ami.queue.summary_updated', $event);
    }

    /**
     * Log event information
     *
     * @param string $level
     * @param string $message
     * @param array $context
     * @return void
     */
    protected function log($level, $message, array $context = [])
    {
        if (!$this->enableLogging) {
            return;
        }

        try {
            Log::channel($this->logChannel)->$level($message, $context);
        } catch (\Exception $e) {
            // Fallback to default log if channel doesn't exist
            Log::$level($message, $context);
        }
    }

    /**
     * Enable/disable logging
     *
     * @param bool $enable
     * @return self
     */
    public function setLogging($enable)
    {
        $this->enableLogging = $enable;
        return $this;
    }

    /**
     * Set log channel
     *
     * @param string $channel
     * @return self
     */
    public function setLogChannel($channel)
    {
        $this->logChannel = $channel;
        return $this;
    }
}