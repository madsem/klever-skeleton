<?php

namespace Klever\Views\Extensions;


class AssetExtension extends \Twig_Extension
{

    public function getFunctions()
    {
        return [
            new \Twig_Function('asset', [$this, 'getAsset'])
        ];
    }

    /**
     * accepts a string with asset like 'css/app.css' or 'img/logo.jpg'
     * and returns the path to the revisioned asset file in the build dir.
     *
     * @param $pathToAsset
     * @return string
     */
    public function getAsset($pathToAsset)
    {
        return sprintf(request()->getUri()->getScheme() . '://' . request()->getUri()->getHost() . '/%s', $pathToAsset);
    }
}