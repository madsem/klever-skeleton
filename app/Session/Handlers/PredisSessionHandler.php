<?php

namespace Klever\Session\Handlers;


use Klever\Session\Contracts\SessionConfigurationInterface;
use Predis\Client;

/**
 * Notes:
 * Important php.ini settings to make sure Redis Session handler works flawlessly:
 * session.use_cookies=1
 * session.use_strict_mode=1 to prevent session fixation
 *
 * Class PredisSessionHandler
 * @package Klever\Session\Handlers
 */
class PredisSessionHandler implements
    \SessionHandlerInterface,
    \SessionUpdateTimestampHandlerInterface,
    SessionConfigurationInterface
{

    /**
     * @var $predis \League\Container\Container
     */
    protected $predis;

    protected $session_name;

    /**
     * TTL in minutes
     * @var mixed
     */
    protected $ttl;

    protected $path;

    protected $domain;

    protected $secure;

    protected $http_only;

    function __construct(Client $predis, array $settings)
    {
        $this->predis = $predis;
        $this->session_name = $settings['name'];
        $this->ttl = ($settings['ttl']['guests'] * 60) ?? (ini_get('session.gc_maxlifetime') * 60);
        $this->path = $settings['path'];
        $this->domain = getHostNoSubDomain() ?? $settings['domain'];
        $this->secure = $settings['secure'];
        $this->http_only = $settings['http_only'];

        // session_set_save_handler() is this $handler object
        if ($this instanceof \SessionHandlerInterface) {
            session_set_save_handler($this, true);
        }

        // set session name & cookie params
        session_name($this->session_name);
        session_set_cookie_params($this->ttl, $this->path, $this->domain, $this->secure, $this->http_only);

        /**
         * make sure we are using strict sessions, and cookies only
         * to prevent session fixation / hijacking through session id's in urls
         */
        ini_set('session.use_strict_mode', 1);
        ini_set('session.use_only_cookies', 1);
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
     * delete old session & update cookie
     */
    function regenerate()
    {
        session_regenerate_id(true);
        setcookie(session_name(),session_id(),time()+$this->ttl, $this->path, $this->domain, $this->secure, $this->http_only);
    }

    /**
     * @param string $session_id
     * @param string $session_data
     * @return bool
     */
    function write($session_id, $session_data)
    {
        $this->predis->setex($session_id, (int)$this->ttl, $session_data);

        return true;
    }

    /**
     * @param string $session_id
     * @return string
     */
    function read($session_id)
    {
        if ($value = $this->predis->get($session_id)) {
            return (string)$value;
        };

        return '';
    }

    /**
     * @param string $save_path
     * @param string $name
     * @return bool
     */
    function open($save_path, $name)
    {
        return true;
    }

    /**
     * @param int $maxlifetime
     * @return bool
     */
    function gc($maxlifetime)
    {
        return true;
    }

    /**
     * @param string $session_id
     * @return bool
     */
    function destroy($session_id)
    {
        $this->predis->del([$session_id]);

        return true;
    }

    /**
     * @return bool
     */
    function close()
    {
        return true;
    }

    /**
     * PHP internal method to generate session ID
     *
     * generate more unique & longer IDs to prevent colliding IDs
     */
    function create_sid()
    {
        return bin2hex(random_bytes(40));
    }

    /**
     * Validate session ID
     *
     * If session ID does not exist in redis, a new id must be generated
     * (PHP does that internally if we return false)
     *
     * @param string $session_id
     * @return bool
     */
    function validateId($session_id)
    {
        if ((bool)$this->predis->exists($session_id)) {
            return true;
        };

        return false;
    }

    /**
     * Determine if timestamp needs to be updated
     *
     * Note: must return true or PHP will throw session_write-close() errors...
     *
     * @param string $sessionId
     * @param string $sessionData
     * @return bool
     */
    function updateTimestamp($sessionId, $sessionData)
    {
        if ($this->predis->ttl($sessionId) <= $this->ttl) {

            $this->predis->expire($sessionId, $this->ttl);
        }

        return true;
    }

    /**
     * Set Time To Life
     *
     * @param int $ttl
     * @return mixed|void
     */
    public function setTTL($ttl)
    {
        $this->ttl = ($ttl * 60);
    }

}