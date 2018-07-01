<?php

namespace Klever\Session\Handlers;


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
class PredisSessionHandler implements \SessionHandlerInterface, \SessionUpdateTimestampHandlerInterface
{

    /**
     * @var $predis \League\Container\Container
     */
    protected $predis;

    /**
     * @var int $ttl
     */
    protected $ttl;

    function __construct(Client $predis, array $settings)
    {
        $this->predis = $predis;

        if (isset($settings['ttl'])) {
            $this->ttl = (int)$settings['ttl'];
        }
        else {
            $this->ttl = ini_get('session.gc_maxlifetime');
        }
    }

    /**
     * @param string $session_id
     * @param string $session_data
     * @return bool
     */
    function write($session_id, $session_data)
    {
        $this->predis->setex($session_id, (int)($this->ttl * 60), $session_data);

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

}