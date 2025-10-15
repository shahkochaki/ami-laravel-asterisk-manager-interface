<?php

namespace Shahkochaki\Ami\Middleware;

/**
 * Rate Limiting Middleware for AMI Requests
 */
class RateLimitingMiddleware
{
    /**
     * @var array Request counters per IP
     */
    protected static $counters = [];

    /**
     * @var int Maximum requests per minute
     */
    protected $maxRequestsPerMinute;

    /**
     * @var bool Rate limiting enabled
     */
    protected $enabled;

    /**
     * Constructor
     *
     * @param int $maxRequestsPerMinute
     * @param bool $enabled
     */
    public function __construct($maxRequestsPerMinute = 60, $enabled = true)
    {
        $this->maxRequestsPerMinute = $maxRequestsPerMinute;
        $this->enabled = $enabled;
    }

    /**
     * Check if request is allowed
     *
     * @param string $identifier
     * @return bool
     */
    public function isAllowed($identifier = 'default')
    {
        if (!$this->enabled) {
            return true;
        }

        $currentMinute = floor(time() / 60);
        $key = $identifier . '_' . $currentMinute;

        if (!isset(static::$counters[$key])) {
            static::$counters[$key] = 0;
        }

        static::$counters[$key]++;

        // Cleanup old counters
        $this->cleanup();

        return static::$counters[$key] <= $this->maxRequestsPerMinute;
    }

    /**
     * Get current request count
     *
     * @param string $identifier
     * @return int
     */
    public function getCurrentCount($identifier = 'default')
    {
        $currentMinute = floor(time() / 60);
        $key = $identifier . '_' . $currentMinute;

        return static::$counters[$key] ?? 0;
    }

    /**
     * Get remaining requests
     *
     * @param string $identifier
     * @return int
     */
    public function getRemainingRequests($identifier = 'default')
    {
        return max(0, $this->maxRequestsPerMinute - $this->getCurrentCount($identifier));
    }

    /**
     * Reset counter for identifier
     *
     * @param string $identifier
     * @return void
     */
    public function reset($identifier = 'default')
    {
        $currentMinute = floor(time() / 60);
        $key = $identifier . '_' . $currentMinute;

        unset(static::$counters[$key]);
    }

    /**
     * Cleanup old counters
     *
     * @return void
     */
    protected function cleanup()
    {
        $currentMinute = floor(time() / 60);

        foreach (static::$counters as $key => $count) {
            $parts = explode('_', $key);
            $minute = end($parts);

            // Remove counters older than current minute
            if ($minute < $currentMinute) {
                unset(static::$counters[$key]);
            }
        }
    }

    /**
     * Set maximum requests per minute
     *
     * @param int $max
     * @return self
     */
    public function setMaxRequestsPerMinute($max)
    {
        $this->maxRequestsPerMinute = $max;
        return $this;
    }

    /**
     * Enable/disable rate limiting
     *
     * @param bool $enabled
     * @return self
     */
    public function setEnabled($enabled)
    {
        $this->enabled = $enabled;
        return $this;
    }

    /**
     * Get statistics
     *
     * @return array
     */
    public function getStats()
    {
        return [
            'enabled' => $this->enabled,
            'max_requests_per_minute' => $this->maxRequestsPerMinute,
            'active_counters' => count(static::$counters),
            'counters' => static::$counters,
        ];
    }
}
