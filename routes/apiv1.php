<?php

use Routes\Route;

Route::put(app("CobaltBlog_update_path") . "/update/?{id}?", "CobaltBlog@update");
Route::delete(app("CobaltBlog_update_path") . "/{id}", "CobaltBlog@delete");
