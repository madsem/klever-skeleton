<?php

namespace Klever\Views\Extensions;


class LinkingExtension extends \Twig_Extension
{

    protected $scheme;
    protected $host;
    protected $path;
    protected $query;

    function __construct()
    {
        $this->scheme = request()->getUri()->getScheme();
        $this->host = request()->getUri()->getHost();
        $this->path = request()->getUri()->getPath();
        $this->query = request()->getUri()->getQuery();
    }

    function getFunctions()
    {
        return [
            new \Twig_Function('asset', [$this, 'getAsset']),
            new \Twig_Function('currentUrl', [$this, 'getCurrentUrl']),
        ];
    }

    /**
     * accepts a string with asset path, relative to 'public' directory.
     * Like 'assets/css/app.css' or 'build/logo.jpg'
     * and returns the path to the revisioned asset file.
     *
     * @param $relPathToAsset
     * @return string
     *
     * @throws \Exception
     */
    function getAsset($relPathToAsset)
    {
        $fullAssetPath = asset($relPathToAsset);
        return $this->scheme . '://' . $this->host . '/' . $fullAssetPath;
    }

    /**
     * Return the current url incl. path
     *
     * @return string
     */
    function getCurrentUrl()
    {
        return $this->scheme . '://' . $this->host . $this->path;
    }
}