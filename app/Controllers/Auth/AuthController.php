<?php

namespace Klever\Controllers\Auth;


use Klever\Auth\Auth;

class AuthController
{

    private $auth;

    function __construct(Auth $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Show log in form
     *
     * @return \Slim\Http\Response
     */
    function login()
    {
        return view('auth/login.twig');
    }

    /**
     * Handle authentication request
     *
     * @return \Slim\Http\Response
     */
    function authenticate()
    {
        $authenticated = $this->auth->attempt(
            request()->getParam('username'),
            request()->getParam('password')
        );

        if ( ! $authenticated) {

            message('error', "Well, that didn't work out...");

            return redirect(route('auth.login'), 301);
        }

        // redirect authenticated users
        return redirect(route('admin.home'), 301);
    }

    /**
     * Destroy session and log user out
     *
     * @return \Slim\Http\Response
     */
    function logout()
    {
        $this->auth->logout();

        return redirect(route('home'), 301);
    }
}