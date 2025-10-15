<?php

namespace Shahkochaki\Ami;

use Illuminate\Support\Arr;

/**
 * Connection Manager for AMI
 * 
 * Provides advanced connection management features
 */
class ConnectionManager
{
    /**
     * @var array Active connections pool
     */
    protected static $connections = [];

    /**
     * @var array Connection configurations
     */
    protected static $configs = [];

    /**
     * @var bool Enable connection pooling
     */
    protected static $enablePooling = true;

    /**
     * @var int Maximum connections per pool
     */
    protected static $maxConnections = 5;

    /**
     * Store connection in pool
     *
     * @param string $key
     * @param mixed $connection
     * @param array $config
     * @return void
     */
    public static function store($key, $connection, array $config = [])
    {
        if (self::$enablePooling && count(self::$connections) < self::$maxConnections) {
            self::$connections[$key] = $connection;
            self::$configs[$key] = $config;
        }
    }

    /**
     * Get connection from pool
     *
     * @param string $key
     * @return mixed|null
     */
    public static function get($key)
    {
        return Arr::get(self::$connections, $key);
    }

    /**
     * Remove connection from pool
     *
     * @param string $key
     * @return void
     */
    public static function remove($key)
    {
        unset(self::$connections[$key]);
        unset(self::$configs[$key]);
    }

    /**
     * Generate connection key
     *
     * @param array $options
     * @return string
     */
    public static function generateKey(array $options)
    {
        return md5(serialize([
            'host' => Arr::get($options, 'host'),
            'port' => Arr::get($options, 'port'),
            'username' => Arr::get($options, 'username'),
        ]));
    }

    /**
     * Close all connections
     *
     * @return void
     */
    public static function closeAll()
    {
        foreach (self::$connections as $connection) {
            try {
                if (method_exists($connection, 'close')) {
                    $connection->close();
                }
            } catch (\Exception $e) {
                // Ignore errors during cleanup
            }
        }

        self::$connections = [];
        self::$configs = [];
    }

    /**
     * Get connection statistics
     *
     * @return array
     */
    public static function getStats()
    {
        return [
            'active_connections' => count(self::$connections),
            'max_connections' => self::$maxConnections,
            'pooling_enabled' => self::$enablePooling,
            'connection_keys' => array_keys(self::$connections),
        ];
    }

    /**
     * Enable/disable connection pooling
     *
     * @param bool $enable
     * @return void
     */
    public static function setPooling($enable)
    {
        self::$enablePooling = $enable;

        if (!$enable) {
            self::closeAll();
        }
    }

    /**
     * Set maximum connections
     *
     * @param int $max
     * @return void
     */
    public static function setMaxConnections($max)
    {
        self::$maxConnections = $max;
    }

    /**
     * Check if connection exists and is valid
     *
     * @param string $key
     * @return bool
     */
    public static function exists($key)
    {
        return isset(self::$connections[$key]);
    }
}
