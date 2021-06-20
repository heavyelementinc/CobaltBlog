<?php

use Routes\Route;

Route::get(app("CobaltBlog_admin_path") . "/edit/{id}?", "CobaltBlog@adminEdit");

Route::get(app("CobaltBlog_admin_path") . "/?...?", "CobaltBlog@adminIndex", [
    'anchor' => [
        'name' => plugin('CobaltBlog')->_config['name'],
        'href' => app("CobaltBlog_admin_path")
    ],
    'navigation' => ['admin_plugins']
]);
