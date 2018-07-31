<?php

namespace Klever\Cache\Adapters\Contracts;


interface AdapterInterface
{

    function get($key);

    function put($key, $value, $minutes = null);

    function forever($key, $value);

    function remember($key, $minutes = null, callable $callback);

    function forget($key);

    function forgetPattern($pattern);
}