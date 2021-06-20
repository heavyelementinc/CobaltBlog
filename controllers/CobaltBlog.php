<?php

class CobaltBlog {

    function __construct() {
        $this->blog = new \CobaltBlog\Blog();
    }

    function index($options) {
        $opts = associative_array_helper($options, ['page', 'sort']);
        $opts['sort'] = $this->getSort($opts['sort']);
        $result = $this->blog->getIndex($opts['page'] ?? 0, $opts['sort'] ?? -1);
        add_vars([
            'title' => "Blog",
            'main' => $result
        ]);
        set_template("CobaltBlog/index.html");
    }

    function read($url_safe_name) {
        $article = $this->blog->getArticleByUrlSafeName($url_safe_name);
        if (!$article) throw new \CobaltBlog\Exceptions\PostNotFound("That article doesn't exist");

        add_vars([
            'title' => "Blog",
            'main' => with("CobaltBlog/article.html", ['article' => $article])
        ]);
        set_template("CobaltBlog/index.html");
    }

    function adminIndex($options) {
        $opts = associative_array_helper($options, ['page', 'sort']);
        $result = $this->blog->getAdminIndex($opts['page'] ?? 0, $opts['sort'] ?? -1);
        add_vars([
            'title' => "Blog",
            'main' => $result
        ]);
        set_template("CobaltBlog/admin-index.html");
    }

    function adminEdit($id = null) {
        $article = $this->blog->getArticleByUrlSafeName($id);
        if ($id !== null && $article === null) throw new \Exceptions\HTTP\NotFound("That article was not found");
        add_vars([
            'title' => $article['title'] ?? "Create new post",
            'article' => $article
        ]);
        set_template("CobaltBlog/admin-edit.html");
    }

    function update($id = null) {
        $ident = $this->blog->__id($id);
        $validate = new \CobaltBlog\ValidateBlog($_POST);
        $validated = $validate->validate($_POST);
        if ($id === null) $validated['created'] = $this->blog->__date();
        $this->blog->updateOne(['_id' => $ident], ['$set' => $validated], ['upsert' => true]);
        return array_merge($validated, ['_id' => $ident]);
    }

    private function getSort($s) {
        $sort = ['1' => 1, '-1' => -1, null => -1];
        if (key_exists($s, $sort)) $s = $sort[$s];
        return $s;
    }
}
