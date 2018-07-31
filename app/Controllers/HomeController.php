<?php

namespace Klever\Controllers;

class HomeController
{

    function index()
    {

        session()->set('start', 'Hello Magnificient World!');
        session()->delete('start');
        session()->set('start', 'Goodbye Cruel World!');

        $data = cache()->remember('homepage', 10, function () {
            return session()->get('start');
        });

        return view('home.twig', compact('data'));
    }
}