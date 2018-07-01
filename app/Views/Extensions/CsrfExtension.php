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
        // CSRF token name and value input fields
        $field = '<input type="hidden" name="' . $this->csrf->getTokenNameKey() . '" value="' . $this->csrf->getTokenName() . '">';
        $field .= '<input type="hidden" name="' . $this->csrf->getTokenValueKey() . '" value="' . $this->csrf->getTokenValue() . '">';

        return [
            'csrf' => [
                'field' => $field
            ]
        ];
    }

    function getName()
    {
        return 'slim/csrf';
    }
}