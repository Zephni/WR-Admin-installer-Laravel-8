<?php

    use Illuminate\Support\Facades\Auth;

    return [

        'Dashboard'             => ['icon' => 'fa-gem',      'url' => route('admin')],

        'Documentation'         => ['icon' => 'fa-question', 'url' => route('admin-documentation')],

        'Content Management',

        //'News articles' => [
        //    'icon'           => 'fa-newspaper',       'url' => route('admin-browse', ['table' => 'news']),
        //    'submenu' => [
        //        'Create new' => ['icon' => 'fa-plus', 'url' => route('admin-create', ['table' => 'news'])],
        //        'Browse'     => ['icon' => 'fa-eye',  'url' => route('admin-browse', ['table' => 'news'])],
        //        'Authors'    => ['icon' => 'fa-book', 'url' => route('admin-browse', ['table' => 'authors'])],
        //        'Categories' => ['icon' => 'fa-list', 'url' => route('admin-browse', ['table' => 'categories'])],
        //    ]
        //],

        //'Pages' => [
        //    'icon'           => 'fa-file-alt',        'url' => route('admin-browse', ['table' => 'pages']),
        //    'submenu' => [
        //        'Create new' => ['icon' => 'fa-plus', 'url' => route('admin-create', ['table' => 'pages'])],
        //        'Browse'     => ['icon' => 'fa-eye',  'url' => route('admin-browse', ['table' => 'pages'])]
        //    ]
        //],

        \App\Classes\Helper::properize(Auth::user()->name).' profile',

        'Manage account'       => ['icon' => 'fa-user-edit', 'url' => route('admin-manage-account')],

    ];