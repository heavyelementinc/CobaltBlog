<?php

namespace CobaltBlog\Exceptions;

class PostNotFound extends \Exceptions\HTTP\NotFound {

    public $status_code = 404;
    public $name = "Not Found";

    function __construct($message, $data = []) {
        $data['template'] = 'CobaltBlog/not-found.html';
        add_vars(['title' => 'Post not found']);
        parent::__construct($message, $data);
    }
}
