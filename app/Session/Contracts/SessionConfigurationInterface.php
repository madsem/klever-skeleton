<?php

namespace Klever\Session\Contracts;


interface SessionConfigurationInterface
{
    /**
     * Set TTL of session handler implementation
     *
     * @param int $ttl
     * @return mixed
     */
    function setTTL($ttl);

    /**
     * Start session
     *
     * @return mixed
     */
    function start();

    /**
     * Regenerate session ID
     */
    function regenerate();
}