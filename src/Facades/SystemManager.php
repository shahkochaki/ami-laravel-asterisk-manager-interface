<?php

namespace Shahkochaki\Ami\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * SystemManager Facade
 * 
 * @method static mixed shutdownServer(bool $graceful = true, string $reason = 'System maintenance')
 * @method static mixed restartServer(bool $graceful = true, string $reason = 'System maintenance')
 * @method static mixed reloadConfiguration(string|null $module = null)
 * @method static array getServerStatus()
 * @method static mixed emergencyShutdown()
 * @method static mixed emergencyRestart()
 * @method static mixed getActiveChannels()
 * @method static array scheduleShutdown(int $delayMinutes, bool $graceful = true, string $reason = 'Scheduled maintenance')
 * @method static array scheduleRestart(int $delayMinutes, bool $graceful = true, string $reason = 'Scheduled maintenance')
 * @method static bool cancelScheduledOperation(string $operationId)
 * @method static array getSystemResources()
 * @method static self setConnectionOptions(array $options)
 * @method static array getConnectionOptions()
 * 
 * @see \Shahkochaki\Ami\Services\SystemManager
 */
class SystemManager extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'ami.system.manager';
    }
}