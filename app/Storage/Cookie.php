<?php

namespace Klever\Storage;


use Klever\Storage\Contracts\StorageInterface;

class Cookie implements StorageInterface
{

    protected $path = '/';

    protected $domain = null;

    protected $secure = false;

    protected $http_only = true;

    function __construct(array $config)
    {
        $this->path = $config['path'] ?? $this->path;
        $this->domain = getHostNoSubDomain() ?? $config['domain'];
        $this->secure = $config['secure'] ?? $this->secure;
        $this->http_only = $config['http_only'] ?? $this->http_only;
    }

    /**
     * Set Cookie
     *
     * @param string $name
     * @param mixed $value
     * @param int $minutes
     * @return mixed|void
     */
    function set($name, $value, $minutes = 60)
    {
        $expiry = time() + ($minutes * 60);

        setcookie(
            $name, $value, $expiry,
            $this->path, $this->domain, $this->secure, $this->http_only
        );
    }

    /**
     * Get Cookie or return default
     *
     * @param string $key
     * @param null $default
     * @return mixed|null
     */
    function get($key, $default = null)
    {
        if ($this->exists($key)) {
            return $_COOKIE[$key];
        }

        return $default;
    }

    /**
     * Delete Cookie by key
     *
     * @param string $key
     * @return mixed|void
     */
    function delete($key)
    {
        $this->set($key, null, -2628000, $this->path, $this->domain);
    }

    /**
     * Destroy all cookies
     *
     * Note: Also destroys any session cookies that were set.
     *
     * @return mixed|void
     */
    function flush()
    {
        if ($this->exists()) {
            foreach ($_COOKIE as $name => $value) {
                setcookie($name, null, -2628000, $this->path, $this->domain);
            }
        }
    }

    /**
     * Return array with all cookie names and values
     *
     * @return array|mixed
     */
    function all()
    {
        $cookies = [];
        if ($this->exists()) {
            foreach ($_COOKIE as $name => $value) {
                $cookies[$name] = $value;
            }
        }

        return $cookies;
    }

    /**
     * Check if cookie by key exists
     * or if any cookie exists
     *
     * @param string|false $key
     * @return bool
     */
    function exists($key = false)
    {
        $cookie = $key ? $_COOKIE[$key] : $_COOKIE;

        return isset($cookie) && ! empty($cookie);
    }

    /**
     * Set cookie properties
     *
     * @param $property
     * @param string $value = path,domain,secure,http_only
     * @return $this
     */
    function config($property, $value)
    {
        if (property_exists($this, $property)) {
            $this->{$property} = $value;
        }

        return $this;
    }

}