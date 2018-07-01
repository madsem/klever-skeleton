<?php

namespace Klever\Storage;


use Klever\Storage\Contracts\StorageInterface;

final class Session implements StorageInterface
{

    protected $handler;

    protected $session_name = 'session';

    function __construct(\SessionHandlerInterface $handler, string $session_name)
    {
        $this->handler = $handler;
        $this->session_name = $session_name;

        // session_set_save_handler() is this $handler object
        if ($this->handler instanceof \SessionHandlerInterface) {
            session_set_save_handler($this->handler, true);
        }

        /**
         * make sure we are using strict sessions, and cookies only
         * to prevent session fixation / hijacking through session id's in urls
         */
        ini_set('session.use_strict_mode', 1);
        ini_set('session.use_only_cookies', 1);

        // set session name
        session_name($this->session_name);
    }

    /**
     * Start session
     */
    function start()
    {
        session_start();
    }

    /**
     * Regenerate session ID
     * & delete old session
     *
     * If session ID should be regenerated again
     * during the same session, this method needs to be extended.
     * Right now it is only meant to regenerate after login.
     */
    function regenerate()
    {
        session_regenerate_id(true);
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
    function destroy()
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

    /**
     * @param $handler
     */
    function setHandler($handler)
    {
        $this->handler = $handler;
    }
}