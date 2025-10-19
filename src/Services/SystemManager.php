<?php

namespace Shahkochaki\Ami\Services;

use Illuminate\Support\Facades\Artisan;
use Exception;

/**
 * System Management Service
 * 
 * Provides system-level operations for Asterisk/Issabel server management
 */
class SystemManager
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
     * Shutdown the Asterisk server gracefully
     *
     * @param bool $graceful Whether to shutdown gracefully (wait for calls to complete)
     * @param string $reason Reason for shutdown
     * @return mixed
     */
    public function shutdownServer($graceful = true, $reason = 'System maintenance')
    {
        $action = $graceful ? 'CoreShowChannels' : 'Command';
        
        if ($graceful) {
            // First check if there are active calls
            $activeChannels = $this->getActiveChannels();
            
            // If there are active calls, use graceful shutdown
            if ($this->hasActiveCalls($activeChannels)) {
                return $this->executeAction('Command', [
                    'Command' => 'core stop gracefully'
                ]);
            }
        }

        // Immediate shutdown
        return $this->executeAction('Command', [
            'Command' => 'core stop now'
        ]);
    }

    /**
     * Restart the Asterisk server
     *
     * @param bool $graceful Whether to restart gracefully
     * @param string $reason Reason for restart
     * @return mixed
     */
    public function restartServer($graceful = true, $reason = 'System maintenance')
    {
        $command = $graceful ? 'core restart gracefully' : 'core restart now';
        
        return $this->executeAction('Command', [
            'Command' => $command
        ]);
    }

    /**
     * Reload Asterisk configuration
     *
     * @param string|null $module Specific module to reload (optional)
     * @return mixed
     */
    public function reloadConfiguration($module = null)
    {
        $command = $module ? "module reload {$module}" : 'core reload';
        
        return $this->executeAction('Command', [
            'Command' => $command
        ]);
    }

    /**
     * Get server status and information
     *
     * @return array
     */
    public function getServerStatus()
    {
        $status = [];
        
        try {
            // Get system info
            $status['system_info'] = $this->executeAction('Command', [
                'Command' => 'core show version'
            ]);

            // Get uptime
            $status['uptime'] = $this->executeAction('Command', [
                'Command' => 'core show uptime'
            ]);

            // Get active channels count
            $status['channels'] = $this->executeAction('CoreShowChannels');

            // Get memory usage
            $status['memory'] = $this->executeAction('Command', [
                'Command' => 'memory show summary'
            ]);

            // Get call statistics
            $status['calls'] = $this->executeAction('Command', [
                'Command' => 'core show calls'
            ]);

        } catch (Exception $e) {
            $status['error'] = $e->getMessage();
        }

        return $status;
    }

    /**
     * Emergency shutdown (force shutdown)
     *
     * @return mixed
     */
    public function emergencyShutdown()
    {
        return $this->executeAction('Command', [
            'Command' => 'core stop now'
        ]);
    }

    /**
     * Emergency restart (force restart)
     *
     * @return mixed
     */
    public function emergencyRestart()
    {
        return $this->executeAction('Command', [
            'Command' => 'core restart now'
        ]);
    }

    /**
     * Check if there are active calls
     *
     * @param mixed $channelsResponse
     * @return bool
     */
    protected function hasActiveCalls($channelsResponse)
    {
        // This is a simplified check - you might want to implement
        // more sophisticated logic based on your AMI response format
        return false; // Default to false for safety
    }

    /**
     * Get active channels
     *
     * @return mixed
     */
    public function getActiveChannels()
    {
        return $this->executeAction('CoreShowChannels');
    }

    /**
     * Schedule a shutdown
     *
     * @param int $delayMinutes Delay in minutes
     * @param bool $graceful Whether to shutdown gracefully
     * @param string $reason Reason for shutdown
     * @return array
     */
    public function scheduleShutdown($delayMinutes, $graceful = true, $reason = 'Scheduled maintenance')
    {
        // Note: This would typically require a scheduling mechanism
        // For now, we'll return the parameters that could be used by a job queue
        return [
            'action' => 'shutdown',
            'delay_minutes' => $delayMinutes,
            'graceful' => $graceful,
            'reason' => $reason,
            'scheduled_at' => date('Y-m-d H:i:s'),
            'execute_at' => date('Y-m-d H:i:s', time() + ($delayMinutes * 60))
        ];
    }

    /**
     * Schedule a restart
     *
     * @param int $delayMinutes Delay in minutes
     * @param bool $graceful Whether to restart gracefully
     * @param string $reason Reason for restart
     * @return array
     */
    public function scheduleRestart($delayMinutes, $graceful = true, $reason = 'Scheduled maintenance')
    {
        return [
            'action' => 'restart',
            'delay_minutes' => $delayMinutes,
            'graceful' => $graceful,
            'reason' => $reason,
            'scheduled_at' => date('Y-m-d H:i:s'),
            'execute_at' => date('Y-m-d H:i:s', time() + ($delayMinutes * 60))
        ];
    }

    /**
     * Cancel scheduled system operation
     *
     * @param string $operationId
     * @return bool
     */
    public function cancelScheduledOperation($operationId)
    {
        // This would integrate with your job queue system
        // Implementation depends on your scheduling mechanism
        return true;
    }

    /**
     * Get system resource usage
     *
     * @return array
     */
    public function getSystemResources()
    {
        $resources = [];
        
        try {
            $resources['memory'] = $this->executeAction('Command', [
                'Command' => 'memory show summary'
            ]);
            
            $resources['tasks'] = $this->executeAction('Command', [
                'Command' => 'core show taskprocessors'
            ]);
            
            $resources['threads'] = $this->executeAction('Command', [
                'Command' => 'core show threads'
            ]);

        } catch (Exception $e) {
            $resources['error'] = $e->getMessage();
        }

        return $resources;
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