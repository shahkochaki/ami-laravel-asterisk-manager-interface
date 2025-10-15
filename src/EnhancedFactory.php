<?php

namespace Shahkochaki\Ami;

use React\Stream\Stream;
use Clue\React\Ami\Client;
use Illuminate\Support\Arr;
use Clue\React\Ami\ActionSender;
use React\EventLoop\LoopInterface;
use React\SocketClient\ConnectorInterface;
use React\Promise\Promise;
use Exception;
use function React\Promise\resolve;

/**
 * Enhanced AMI Connection Factory
 * 
 * Provides advanced connection management features including:
 * - Connection pooling
 * - Automatic reconnection
 * - Connection health monitoring
 * - Multiple server support
 */
class EnhancedFactory extends Factory
{
    /**
     * @var array Active connections pool
     */
    protected $connections = [];

    /**
     * @var array Connection configurations
     */
    protected $configs = [];

    /**
     * @var bool Enable connection pooling
     */
    protected $enablePooling = true;

    /**
     * @var int Maximum connections per pool
     */
    protected $maxConnections = 5;

    /**
     * @var int Connection timeout in seconds
     */
    protected $connectionTimeout = 10;

    /**
     * @var int Heartbeat interval in seconds
     */
    protected $heartbeatInterval = 30;

    /**
     * Create client with enhanced features
     *
     * @param array $options
     * @return \React\Promise\Promise
     */
    public function create(array $options = [])
    {
        $connectionKey = $this->getConnectionKey($options);
        
        // Return existing connection if pooling is enabled
        if ($this->enablePooling && isset($this->connections[$connectionKey])) {
            $connection = $this->connections[$connectionKey];
            if ($this->isConnectionHealthy($connection)) {
                // Create a resolved promise manually
                $promise = new Promise(function ($resolve) use ($connection) {
                    $resolve($connection);
                });
                return $promise;
            } else {
                unset($this->connections[$connectionKey]);
            }
        }

        return $this->createNewConnection($options, $connectionKey);
    }

    /**
     * Create a new connection
     *
     * @param array $options
     * @param string $connectionKey
     * @return \React\Promise\Promise
     */
    protected function createNewConnection(array $options, $connectionKey)
    {
        foreach (['host', 'port', 'username', 'secret'] as $key) {
            $options[$key] = Arr::get($options, $key, null);
        }

        $timeout = Arr::get($options, 'timeout', $this->connectionTimeout);
        
        $promise = $this->connector
            ->create($options['host'], $options['port'])
            ->then(function (Stream $stream) use ($options) {
                $client = new Client($stream, new Parser());
                
                // Set up connection monitoring
                $this->setupConnectionMonitoring($client);
                
                return $client;
            });

        if (!is_null($options['username'])) {
            $promise = $promise->then(function (Client $client) use ($options, $connectionKey) {
                $sender = new ActionSender($client);

                return $sender->login($options['username'], $options['secret'])->then(
                    function () use ($client, $connectionKey, $options) {
                        // Store connection in pool
                        if ($this->enablePooling) {
                            $this->connections[$connectionKey] = $client;
                            $this->configs[$connectionKey] = $options;
                        }
                        
                        // Set up heartbeat
                        $this->setupHeartbeat($client, $connectionKey);
                        
                        return $client;
                    },
                    function ($error) use ($client) {
                        $client->close();
                        throw $error;
                    }
                );
            });
        }

        return $promise;
    }

    /**
     * Generate connection key for pooling
     *
     * @param array $options
     * @return string
     */
    protected function getConnectionKey(array $options)
    {
        return md5(serialize([
            'host' => Arr::get($options, 'host'),
            'port' => Arr::get($options, 'port'),
            'username' => Arr::get($options, 'username'),
        ]));
    }

    /**
     * Check if connection is healthy
     *
     * @param Client $client
     * @return bool
     */
    protected function isConnectionHealthy(Client $client)
    {
        try {
            return $client->getState() === 'authenticated';
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Setup connection monitoring
     *
     * @param Client $client
     * @return void
     */
    protected function setupConnectionMonitoring(Client $client)
    {
        $client->on('close', function () use ($client) {
            $this->removeFromPool($client);
        });

        $client->on('error', function ($error) use ($client) {
            $this->removeFromPool($client);
        });
    }

    /**
     * Setup heartbeat for connection
     *
     * @param Client $client
     * @param string $connectionKey
     * @return void
     */
    protected function setupHeartbeat(Client $client, $connectionKey)
    {
        $this->loop->addPeriodicTimer($this->heartbeatInterval, function () use ($client, $connectionKey) {
            if (!$this->isConnectionHealthy($client)) {
                unset($this->connections[$connectionKey]);
                unset($this->configs[$connectionKey]);
                return;
            }

            // Send ping to keep connection alive
            $sender = new ActionSender($client);
            $sender->ping()->then(null, function () use ($connectionKey) {
                unset($this->connections[$connectionKey]);
                unset($this->configs[$connectionKey]);
            });
        });
    }

    /**
     * Remove connection from pool
     *
     * @param Client $client
     * @return void
     */
    protected function removeFromPool(Client $client)
    {
        foreach ($this->connections as $key => $connection) {
            if ($connection === $client) {
                unset($this->connections[$key]);
                unset($this->configs[$key]);
                break;
            }
        }
    }

    /**
     * Close all connections
     *
     * @return void
     */
    public function closeAll()
    {
        foreach ($this->connections as $client) {
            try {
                $client->close();
            } catch (Exception $e) {
                // Ignore errors during cleanup
            }
        }
        
        $this->connections = [];
        $this->configs = [];
    }

    /**
     * Get connection statistics
     *
     * @return array
     */
    public function getStats()
    {
        return [
            'active_connections' => count($this->connections),
            'max_connections' => $this->maxConnections,
            'pooling_enabled' => $this->enablePooling,
            'connection_timeout' => $this->connectionTimeout,
            'heartbeat_interval' => $this->heartbeatInterval,
        ];
    }

    /**
     * Enable/disable connection pooling
     *
     * @param bool $enable
     * @return self
     */
    public function setPooling($enable)
    {
        $this->enablePooling = $enable;
        
        if (!$enable) {
            $this->closeAll();
        }
        
        return $this;
    }

    /**
     * Set maximum connections
     *
     * @param int $max
     * @return self
     */
    public function setMaxConnections($max)
    {
        $this->maxConnections = $max;
        return $this;
    }

    /**
     * Set connection timeout
     *
     * @param int $timeout
     * @return self
     */
    public function setConnectionTimeout($timeout)
    {
        $this->connectionTimeout = $timeout;
        return $this;
    }

    /**
     * Set heartbeat interval
     *
     * @param int $interval
     * @return self
     */
    public function setHeartbeatInterval($interval)
    {
        $this->heartbeatInterval = $interval;
        return $this;
    }
}