<?php

namespace Shahkochaki\Ami\Providers;

use Shahkochaki\Ami\Factory;
use Shahkochaki\Ami\Commands\AmiCli;
use Shahkochaki\Ami\Commands\AmiSms;
use Shahkochaki\Ami\Commands\AmiUssd;
use Shahkochaki\Ami\Commands\AmiSystemControl;
use Shahkochaki\Ami\Services\SystemManager;
use Shahkochaki\Ami\Services\AmiService;
use React\Socket\Connector;
use Shahkochaki\Ami\Commands\AmiAction;
use Shahkochaki\Ami\Commands\AmiListen;
use React\EventLoop\LoopInterface;
use React\EventLoop\StreamSelectLoop;
use Illuminate\Support\ServiceProvider;
use React\Dns\Resolver\Factory as DnsResolver;

class AmiServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     */
    public function boot()
    {
        $this->publishes([
            realpath(__DIR__ . '/../../config/ami.php') => config_path('ami.php'),
        ], 'ami');
    }

    /**
     * Register any package services.
     */
    public function register()
    {
        $this->registerConfig();
        $this->registerEventLoop();
        $this->registerConnector();
        $this->registerFactory();
        $this->registerAmiService();
        $this->registerSystemManager();
        $this->registerDongleUssd();
        $this->registerAmiListen();
        $this->registerAmiAction();
        $this->registerAmiSystemControl();
        $this->registerDongleSms();
        $this->registerAmiCli();
        $this->commands([
            'command.ami.dongle.ussd',
            'command.ami.dongle.sms',
            'command.ami.listen',
            'command.ami.action',
            'command.ami.system',
            'command.ami.cli',
        ]);
    }

    /**
     * Register the configuration.
     */
    protected function registerConfig()
    {
        $this->mergeConfigFrom(realpath(__DIR__ . '/../../config/ami.php'), 'ami');
    }

    /**
     * Register the ami listen command.
     */
    protected function registerAmiListen()
    {
        $this->app->singleton(AmiListen::class, function ($app) {
            return new AmiListen($app['events'], $app['ami.eventloop'], $app['ami.factory'], $app['config']['ami']);
        });
        $this->app->alias(AmiListen::class, 'command.ami.listen');
    }

    /**
     * Register the ami listen command.
     */
    protected function registerAmiCli()
    {
        $this->app->singleton(AmiCli::class, function ($app) {
            return new AmiCli($app['events'], $app['ami.eventloop'], $app['ami.factory'], $app['config']['ami']);
        });
        $this->app->alias(AmiCli::class, 'command.ami.cli');
    }

    /**
     * Register the ami action sender.
     */
    protected function registerAmiAction()
    {
        $this->app->singleton(AmiAction::class, function ($app) {
            return new AmiAction($app['events'], $app['ami.eventloop'], $app['ami.factory'], $app['config']['ami']);
        });
        $this->app->alias(AmiAction::class, 'command.ami.action');
    }

    /**
     * Register the dongle sms.
     */
    protected function registerDongleSms()
    {
        $this->app->singleton(AmiSms::class, function ($app) {
            return new AmiSms($app['events'], $app['ami.eventloop'], $app['ami.factory'], $app['config']['ami']);
        });
        $this->app->alias(AmiSms::class, 'command.ami.dongle.sms');
    }

    /**
     * Register the dongle ussd.
     */
    protected function registerDongleUssd()
    {
        $this->app->singleton(AmiUssd::class, function ($app) {
            return new AmiUssd();
        });
        $this->app->alias(AmiUssd::class, 'command.ami.dongle.ussd');
    }

    /**
     * Register event loop.
     */
    protected function registerEventLoop()
    {
        $this->app->singleton(LoopInterface::class, function () {
            return new StreamSelectLoop();
        });
        $this->app->alias(LoopInterface::class, 'ami.eventloop');
    }

    /**
     * Register connector.
     */
    protected function registerConnector()
    {
        $this->app->singleton(Connector::class, function ($app) {
            $loop = $app[LoopInterface::class];

            return new Connector($loop);
        });
        $this->app->alias(Connector::class, 'ami.connector');
    }

    /**
     * Register factory.
     */
    protected function registerFactory()
    {
        $this->app->singleton(Factory::class, function ($app) {
            return new Factory($app[LoopInterface::class], $app[Connector::class]);
        });
        $this->app->alias(Factory::class, 'ami.factory');
    }

    /**
     * Register the main AMI service.
     */
    protected function registerAmiService()
    {
        $this->app->singleton(AmiService::class, function ($app) {
            return new AmiService($app['config']['ami'] ?? []);
        });
        $this->app->alias(AmiService::class, 'ami');
    }

    /**
     * Register the system manager service.
     */
    protected function registerSystemManager()
    {
        $this->app->singleton(SystemManager::class, function ($app) {
            return new SystemManager($app['config']['ami']);
        });
        $this->app->alias(SystemManager::class, 'ami.system.manager');
    }

    /**
     * Register the ami system control command.
     */
    protected function registerAmiSystemControl()
    {
        $this->app->singleton(AmiSystemControl::class, function ($app) {
            return new AmiSystemControl($app['events'], $app['ami.eventloop'], $app['ami.factory'], $app['config']['ami']);
        });
        $this->app->alias(AmiSystemControl::class, 'command.ami.system');
    }
}
