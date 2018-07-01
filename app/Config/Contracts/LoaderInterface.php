<?php

namespace Klever\Config\Contracts;

interface LoaderInterface
{
    function parse();
    function isCached();
}