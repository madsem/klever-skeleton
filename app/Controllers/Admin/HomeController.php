<?php

namespace Klever\Controllers\Admin;

class HomeController
{

    function index()
    {

        session()->set('admin', 'You are logged in');

        $data = cache()->remember('admin.homepage', 10, function () {
            return session()->get('admin');
        });

        return view('admin/home.twig', [
            'data' => $data,
        ]);
    }
}