<?php

namespace Klever\Session\Handlers;


use Klever\Session\Contracts\SessionConfigurationInterface;

/**
 * Notes:
 * Important php.ini settings to make sure Session handler works flawlessly:
 * session.use_cookies=1
 * session.use_strict_mode=1 to prevent session fixation
 *
 * Class NativeSessionHandler
 * @package Klever\Session\Handlers
 */
class NativeSessionHandler extends \SessionHandler implements
    \SessionHandlerInterface,
    \SessionUpdateTimestampHandlerInterface,
    SessionConfigurationInterface
{

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

    function __construct(array $settings)
    {
        $this->session_name = $settings['name'];
        $this->ttl = ($settings['ttl']['guests'] * 60) ?? (ini_get('session.gc_maxlifetime') * 60);
        $this->path = $settings['path'];
        $this->domain = getHostNoSubDomain() ?? $settings['domain'];
        $this->secure = $settings['secure'];
        $this->http_only = $settings['http_only'];

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
     * Close the session
     * @link http://php.net/manual/en/sessionhandler.close.php
     * @return bool <p>
     * The return value (usually TRUE on success, FALSE on failure).
     * Note this value is returned internally to PHP for processing.
     * </p>
     * @since 5.4.0
     */
    public function close() {
        return parent::close();
    }

    /**
     * Return a new session ID
     * @link http://php.net/manual/en/sessionhandler.create-sid.php
     * @return string <p>A session ID valid for the default session handler.</p>
     * @since 5.5.1
     */
    public function create_sid() {
        return parent::create_sid();
    }

    /**
     * Destroy a session
     * @link http://php.net/manual/en/sessionhandler.destroy.php
     * @param string $session_id The session ID being destroyed.
     * @return bool <p>
     * The return value (usually TRUE on success, FALSE on failure).
     * Note this value is returned internally to PHP for processing.
     * </p>
     * @since 5.4.0
     */
    public function destroy($session_id) {
        return parent::destroy($session_id);
    }

    /**
     * Cleanup old sessions
     * @link http://php.net/manual/en/sessionhandler.gc.php
     * @param int $maxlifetime <p>
     * Sessions that have not updated for
     * the last maxlifetime seconds will be removed.
     * </p>
     * @return bool <p>
     * The return value (usually TRUE on success, FALSE on failure).
     * Note this value is returned internally to PHP for processing.
     * </p>
     * @since 5.4.0
     */
    public function gc($maxlifetime) {
        return parent::gc($maxlifetime);
    }

    /**
     * Initialize session
     * @link http://php.net/manual/en/sessionhandler.open.php
     * @param string $save_path The path where to store/retrieve the session.
     * @param string $session_name The session name.
     * @return bool <p>
     * The return value (usually TRUE on success, FALSE on failure).
     * Note this value is returned internally to PHP for processing.
     * </p>
     * @since 5.4.0
     */
    public function open($save_path, $session_name) {
        return parent::open($save_path, $session_name);
    }


    /**
     * Read session data
     * @link http://php.net/manual/en/sessionhandler.read.php
     * @param string $session_id The session id to read data for.
     * @return string <p>
     * Returns an encoded string of the read data.
     * If nothing was read, it must return an empty string.
     * Note this value is returned internally to PHP for processing.
     * </p>
     * @since 5.4.0
     */
    public function read($session_id) {
        return parent::read($session_id);
    }

    /**
     * Write session data
     * @link http://php.net/manual/en/sessionhandler.write.php
     * @param string $session_id The session id.
     * @param string $session_data <p>
     * The encoded session data. This data is the
     * result of the PHP internally encoding
     * the $_SESSION superglobal to a serialized
     * string and passing it as this parameter.
     * Please note sessions use an alternative serialization method.
     * </p>
     * @return bool <p>
     * The return value (usually TRUE on success, FALSE on failure).
     * Note this value is returned internally to PHP for processing.
     * </p>
     * @since 5.4.0
     */
    public function write($session_id, $session_data) {
        return parent::write($session_id, $session_data);
    }

    /**
     * Validate session id
     * @param string $session_id The session id
     * @return bool <p>
     * Note this value is returned internally to PHP for processing.
     * </p>
     */
    public function validateId($session_id) {
        return parent::validateId($session_id);
    }

    /**
     * Update timestamp of a session
     * @param string $session_id The session id
     * @param string $session_data <p>
     * The encoded session data. This data is the
     * result of the PHP internally encoding
     * the $_SESSION superglobal to a serialized
     * string and passing it as this parameter.
     * Please note sessions use an alternative serialization method.
     * </p>
     * @return bool
     */
    public function updateTimestamp($session_id, $session_data) {
        return parent::updateTimestamp($session_id, $session_data);
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