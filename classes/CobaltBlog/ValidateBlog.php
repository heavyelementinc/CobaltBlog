<?php

namespace CobaltBlog;

use Validation\Exceptions\ValidationIssue;

class ValidateBlog extends \Validation\Validate {

    protected function __get_schema() {
        return [
            'title' => [],
            'url_safe_name' => [],
            'author' => [],
            'published' => [],
            'body_content' => []
        ];
    }

    function title($value) {
        return $value;
    }

    function url_safe_name($value) {
        $val = preg_replace(["/[^\w\d]/"], "-", $this->__to_validate['title']);
        $val = preg_replace("/-{2,}/", "-", $val);
        $val = strtolower($val);
        if ($val[0] === "-") $val = substr($val, 1);
        if ($val[strlen($val) - 1] === "-") $val = substr($val, 0, -1);

        $trunkation = 25;
        if (strlen($val) >= $trunkation) {
            $pos = strpos($val, "-", $trunkation);
            $val = substr($val, 0, ($trunkation + $pos) - 1);
        }

        // $blog = new \CobaltBlog\Blog();
        // if ($blog->count(['url_safe_name' => $val]) >= 1) throw new ValidationIssue("Url safe name is taken.");
        return $val;
    }

    function author($value) {
        return $value;
    }

    function published($value) {
        $time = $this->__to_validate['publish_time'];
        return $this->make_date("$value $time");
    }

    function body_content($value) {
        return $this->sanitize($value);
    }
}
