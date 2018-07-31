<?php

// register web middleware
foreach (config()->get('middleware.web') as $middleware) {
    app()->add(new $middleware);
}

/**
 * Web Routes
 */
    $app->get('/', '\Klever\Controllers\HomeController:index')->setName('home');

    $app->group('/auth', function () {
        $this->get('/login', '\Klever\Controllers\Auth\AuthController:login')->setName('auth.login');
        $this->post('/login', '\Klever\Controllers\Auth\AuthController:authenticate');
    })
    ->add('csrf');

    $app->group('/admin', function () {
        $this->get('/home', '\Klever\Controllers\Admin\HomeController:index')->setName('admin.home');
        $this->post('/logout', '\Klever\Controllers\Auth\AuthController:logout')->setName('auth.logout');
    })
    ->add('csrf')
    ->add('guard')
    ->add('force-ssl');