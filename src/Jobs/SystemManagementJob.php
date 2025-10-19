<?php

namespace Shahkochaki\Ami\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Shahkochaki\Ami\Services\SystemManager;
use Exception;

/**
 * System Management Job
 * 
 * Job for executing scheduled system operations like shutdown, restart, etc.
 */
class SystemManagementJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var string The operation to perform
     */
    protected $operation;

    /**
     * @var array Operation parameters
     */
    protected $parameters;

    /**
     * @var array Connection options
     */
    protected $connectionOptions;

    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 3;

    /**
     * The maximum number of seconds the job can run.
     *
     * @var int
     */
    public $timeout = 120;

    /**
     * Create a new job instance.
     *
     * @param string $operation
     * @param array $parameters
     * @param array $connectionOptions
     */
    public function __construct($operation, array $parameters = [], array $connectionOptions = [])
    {
        $this->operation = $operation;
        $this->parameters = $parameters;
        $this->connectionOptions = $connectionOptions;
    }

    /**
     * Execute the job.
     *
     * @return void
     * @throws Exception
     */
    public function handle()
    {
        Log::info("Starting system operation: {$this->operation}", [
            'parameters' => $this->parameters,
            'job_id' => $this->job->getJobId()
        ]);

        try {
            $systemManager = new SystemManager($this->connectionOptions);
            $result = $this->executeOperation($systemManager);

            Log::info("System operation completed successfully: {$this->operation}", [
                'result' => $result,
                'job_id' => $this->job->getJobId()
            ]);
        } catch (Exception $e) {
            Log::error("System operation failed: {$this->operation}", [
                'error' => $e->getMessage(),
                'job_id' => $this->job->getJobId(),
                'attempt' => $this->attempts()
            ]);

            throw $e;
        }
    }

    /**
     * Execute the specific operation
     *
     * @param SystemManager $systemManager
     * @return mixed
     * @throws Exception
     */
    protected function executeOperation(SystemManager $systemManager)
    {
        switch ($this->operation) {
            case 'shutdown':
                return $this->executeShutdown($systemManager);

            case 'restart':
                return $this->executeRestart($systemManager);

            case 'reload':
                return $this->executeReload($systemManager);

            case 'health_check':
                return $this->executeHealthCheck($systemManager);

            default:
                throw new Exception("Unknown operation: {$this->operation}");
        }
    }

    /**
     * Execute shutdown operation
     *
     * @param SystemManager $systemManager
     * @return mixed
     */
    protected function executeShutdown(SystemManager $systemManager)
    {
        $graceful = $this->parameters['graceful'] ?? true;
        $reason = $this->parameters['reason'] ?? 'Scheduled shutdown';

        // Check for active calls if graceful shutdown
        if ($graceful) {
            $channels = $systemManager->getActiveChannels();
            if (!empty($channels)) {
                Log::warning("Active calls detected during scheduled shutdown", [
                    'active_channels' => count($channels)
                ]);

                // You might want to implement a retry logic here
                // or wait for calls to complete
            }
        }

        return $systemManager->shutdownServer($graceful, $reason);
    }

    /**
     * Execute restart operation
     *
     * @param SystemManager $systemManager
     * @return mixed
     */
    protected function executeRestart(SystemManager $systemManager)
    {
        $graceful = $this->parameters['graceful'] ?? true;
        $reason = $this->parameters['reason'] ?? 'Scheduled restart';

        return $systemManager->restartServer($graceful, $reason);
    }

    /**
     * Execute reload operation
     *
     * @param SystemManager $systemManager
     * @return mixed
     */
    protected function executeReload(SystemManager $systemManager)
    {
        $module = $this->parameters['module'] ?? null;

        return $systemManager->reloadConfiguration($module);
    }

    /**
     * Execute health check operation
     *
     * @param SystemManager $systemManager
     * @return array
     */
    protected function executeHealthCheck(SystemManager $systemManager)
    {
        $status = $systemManager->getServerStatus();
        $resources = $systemManager->getSystemResources();
        $channels = $systemManager->getActiveChannels();

        $healthData = [
            'timestamp' => date('Y-m-d H:i:s'),
            'server_status' => $status,
            'system_resources' => $resources,
            'active_channels_count' => is_array($channels) ? count($channels) : 0
        ];

        // You can add custom health checks here
        $healthData['health_score'] = $this->calculateHealthScore($healthData);

        return $healthData;
    }

    /**
     * Calculate system health score
     *
     * @param array $healthData
     * @return int Health score (0-100)
     */
    protected function calculateHealthScore(array $healthData)
    {
        $score = 100;

        // Deduct points for errors
        if (isset($healthData['server_status']['error'])) {
            $score -= 50;
        }

        if (isset($healthData['system_resources']['error'])) {
            $score -= 30;
        }

        // Deduct points for high channel usage (example logic)
        $channelCount = $healthData['active_channels_count'];
        if ($channelCount > 100) {
            $score -= 20;
        } elseif ($channelCount > 50) {
            $score -= 10;
        }

        return max(0, $score);
    }

    /**
     * Handle a job failure.
     *
     * @param Exception $exception
     * @return void
     */
    public function failed(Exception $exception)
    {
        Log::error("System management job failed permanently", [
            'operation' => $this->operation,
            'parameters' => $this->parameters,
            'error' => $exception->getMessage(),
            'attempts' => $this->attempts()
        ]);

        // You might want to send notifications here
        // or implement fallback procedures
    }

    /**
     * Get the tags that should be assigned to the job.
     *
     * @return array
     */
    public function tags()
    {
        return ['system-management', $this->operation];
    }

    /**
     * Static helper to schedule a shutdown
     *
     * @param int $delayMinutes
     * @param bool $graceful
     * @param string $reason
     * @param array $connectionOptions
     * @return void
     */
    public static function scheduleShutdown($delayMinutes, $graceful = true, $reason = 'Scheduled shutdown', array $connectionOptions = [])
    {
        self::dispatch('shutdown', [
            'graceful' => $graceful,
            'reason' => $reason
        ], $connectionOptions)->delay(now()->addMinutes($delayMinutes));
    }

    /**
     * Static helper to schedule a restart
     *
     * @param int $delayMinutes
     * @param bool $graceful
     * @param string $reason
     * @param array $connectionOptions
     * @return void
     */
    public static function scheduleRestart($delayMinutes, $graceful = true, $reason = 'Scheduled restart', array $connectionOptions = [])
    {
        self::dispatch('restart', [
            'graceful' => $graceful,
            'reason' => $reason
        ], $connectionOptions)->delay(now()->addMinutes($delayMinutes));
    }

    /**
     * Static helper to schedule a configuration reload
     *
     * @param int $delayMinutes
     * @param string|null $module
     * @param array $connectionOptions
     * @return void
     */
    public static function scheduleReload($delayMinutes, $module = null, array $connectionOptions = [])
    {
        self::dispatch('reload', [
            'module' => $module
        ], $connectionOptions)->delay(now()->addMinutes($delayMinutes));
    }

    /**
     * Static helper to schedule periodic health checks
     *
     * @param array $connectionOptions
     * @return void
     */
    public static function scheduleHealthCheck(array $connectionOptions = [])
    {
        self::dispatch('health_check', [], $connectionOptions);
    }
}
