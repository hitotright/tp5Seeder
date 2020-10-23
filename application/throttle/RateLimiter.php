<?php
namespace app\throttle;
use think\Cache;

/**
 * Created by PhpStorm.
 * User: hitotright
 * Date: 2020/10/22 0022
 * Time: 11:35
 */

class RateLimiter
{
    /**
     * The cache store implementation.
     *
     */
    protected $cache;

    /**
     * Create a new rate limiter instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->cache = Cache::init();
    }

    /**
     * Determine if the given key has been "accessed" too many times.
     *
     * @param  string  $key
     * @param  int  $maxAttempts
     * @return bool
     */
    public function tooManyAttempts($key, $maxAttempts)
    {
        if ($this->attempts($key) >= $maxAttempts) {
            if ($this->cache->get($key.':timer')) {
                return true;
            }

            $this->resetAttempts($key);
        }

        return false;
    }

    /**
     * Increment the counter for a given key for a given decay time.
     *
     * @param  string  $key
     * @param  float|int  $decayMinutes
     * @return int
     */
    public function hit($key, $decayMinutes = 1)
    {
        if(!$this->cache->get($key.':timer')){
            $this->cache->set(
                $key.':timer', time(), $decayMinutes * 60
            );
        }
        $hits = 1;
        if($num = $this->cache->get($key)){
            $this->cache->set($key,$num + 1,$decayMinutes * 60);
            $hits = $num + 1;
        }else{
            $this->cache->set($key,1,$decayMinutes * 60);
        }
        return $hits;
    }

    /**
     * Get the number of attempts for the given key.
     *
     * @param  string  $key
     * @return mixed
     */
    public function attempts($key)
    {
        return $this->cache->get($key, 0);
    }

    /**
     * Reset the number of attempts for the given key.
     *
     * @param  string  $key
     * @return mixed
     */
    public function resetAttempts($key)
    {
        return $this->cache->rm($key);
    }

    /**
     * Get the number of retries left for the given key.
     *
     * @param  string  $key
     * @param  int  $maxAttempts
     * @return int
     */
    public function retriesLeft($key, $maxAttempts)
    {
        $attempts = $this->attempts($key);

        return $maxAttempts - $attempts;
    }

    /**
     * Clear the hits and lockout timer for the given key.
     *
     * @param  string  $key
     * @return void
     */
    public function clear($key)
    {
        $this->resetAttempts($key);

        $this->cache->rm($key.':timer');
    }

    /**
     * Get the number of seconds until the "key" is accessible again.
     *
     * @param  string  $key
     * @return int
     */
    public function availableIn($key)
    {
        return time() - $this->cache->get($key.':timer');
    }
}
