<?php

use Routes\Route;

Route::get(app("CobaltBlog_public_path"), "CobaltBlog@index", [
    'anchor' => ['name' => app("CobaltBlog_public_name")],
    'navigation' => app("CobaltBlog_navigation_groups")
]);
