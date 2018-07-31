<?php

namespace Klever\Storage;


use Klever\Session\Contracts\SessionConfigurationInterface;
use Klever\Storage\Contracts\StorageInterface;

class Session implements StorageInterface
{

    protected $handler;

    function __construct(SessionConfigurationInterface $handler)
    {
        $this->handler = $handler;
    }

    /**
     * Start session
     */
    function start()
    {
        $this->handler->start();

        if ( ! isset($_SESSION['init'])) {
            $_SESSION['init'] = time();
        }
    }

    /**
     * Regenerate session ID
     * & delete old session every 5 minutes
     *
     * @param int $ttl = time to life for SessionHandlers that support it
     * @param bool $force = force to regenerate disregarding timestamp
     */
    function regenerate($ttl, $force = false)
    {
        // timeout has to be set on every request
        // so that logged in users have a custom ttl
        // as specified in the config
        $this->handler->setTTL($ttl);

        if ($force
            || isset($_SESSION['init'])
            && ($_SESSION['init'] < time() - 300)) {

            $this->handler->regenerate();

            $_SESSION['init'] = time();
        }
    }

    /**
     * @param $key
     * @param $value
     * @return mixed|void
     */
    function set($key, $value)
    {
        $_SESSION[$key] = serialize($value);
    }

    /**
     * @param $key
     * @return mixed|null
     */
    function get($key)
    {
        if ( ! isset($_SESSION[$key])) {
            return null;
        }

        return unserialize($_SESSION[$key]);
    }

    /**
     * @param $key
     * @return mixed|void
     */
    function delete($key)
    {
        unset($_SESSION[$key]);
    }

    /**
     * @return mixed|void
     */
    function flush()
    {
        $_SESSION = [];
        session_destroy();
    }

    /**
     * @return array|mixed
     */
    function all()
    {
        $items = [];

        foreach ($_SESSION as $key => $item) {
            $items[$key] = unserialize($item);
        }

        return $items;
    }

    /**
     * Check if item exists
     *
     * @param string $key
     * @return bool
     */
    function exists($key)
    {
        return isset($_SESSION[$key]);
    }

}