<?php

namespace Klever\Views\Extensions;

use \Slim\Csrf\Guard;

class CsrfExtension extends \Twig_Extension implements \Twig_Extension_GlobalsInterface
{

    /**
     * @var \Slim\Csrf\Guard
     */
    protected $csrf;

    function __construct(Guard $csrf)
    {
        $this->csrf = $csrf;
    }

    function getGlobals()
    {
        $nameKey = $this->csrf->getTokenNameKey();
        $valueKey = $this->csrf->getTokenValueKey();
        $tokenName = $this->csrf->getTokenName();
        $tokenValue = $this->csrf->getTokenValue();

        // CSRF token name and value input fields
        $field = '<input type="hidden" name="' . $nameKey . '" value="' . $tokenName . '">';
        $field .= '<input type="hidden" name="' . $valueKey . '" value="' . $tokenValue . '">';

        // Javascript Object
        $object = json_encode([
            $nameKey => $tokenName,
            $valueKey => $tokenValue
        ]);

        return [
            'csrf' => [
                'field' => $field,
                'object' => $object,
            ]
        ];
    }

    function getName()
    {
        return 'slim/csrf';
    }
}