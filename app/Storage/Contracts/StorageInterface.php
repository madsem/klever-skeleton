<?php

namespace Klever\Storage\Contracts;


interface StorageInterface
{

    /**
     * Set item by key
     *
     * @param string $key
     * @param mixed $value
     * @return mixed
     */
    function set($key, $value);

    /**
     * Get item by key
     * @param string $key
     * @return mixed
     */
    function get($key);

    /**
     * Delete item by key
     *
     * @param string $key
     * @return mixed
     */
    function delete($key);

    /**
     * Destroy all items
     *
     * @return mixed
     */
    function destroy();

    /**
     * Retrieve all items
     *
     * @return mixed
     */
    function all();

    /**
     * Check if item exists
     *
     * @param string $key
     * @return bool
     */
    function exists($key);
}