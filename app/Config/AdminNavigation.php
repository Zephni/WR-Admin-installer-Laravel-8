<?php

    use Illuminate\Support\Facades\Auth;

    return [

        'Dashboard'             => ['icon' => 'fa-gem', 'url' => route('admin')],

        'Documentation'         => ['icon' => 'fa-question', 'url' => route('admin-documentation')],

        'Commands'              => ['icon' => 'fa-cog', 'url' => route('admin-commands')],

        'Content Management',

        'Config' => [
           'icon'           => 'fa-cog',             'url' => route('admin-browse', ['table' => 'config']),
           'submenu' => [
               'Create new' => ['icon' => 'fa-plus', 'url' => route('admin-create', ['table' => 'config'])],
               'Browse'     => ['icon' => 'fa-eye',  'url' => route('admin-browse', ['table' => 'config'])]
           ]
        ],

        \App\Classes\Helper::properize(Auth::user()->name).' profile',

        'Manage account'       => ['icon' => 'fa-user-edit', 'url' => route('admin-manage-account')],

    ];