<?php

namespace Shahkochaki\Ami\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * AMI Facade
 * 
 * @method static mixed action(string $action, array $arguments = [])
 * @method static mixed makeCall(string $from, string $to, string $context = 'default')
 * @method static mixed hangupCall(string $channel)
 * @method static mixed sendSms(string $number, string $message, string $device = null)
 * @method static mixed sendUssd(string $device, string $ussd)
 * @method static array getChannelStatus(string $channel = null)
 * @method static array getActiveChannels()
 * @method static array getStats()
 */
class Ami extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'ami';
    }
}