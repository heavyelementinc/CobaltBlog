<?php

class CobaltBlog {

    function __construct() {
        $this->blog = new \CobaltBlog\Blog();
    }

    function index($options) {
        $opts = associative_array_helper($options, ['page', 'sort']);
        $opts['page'] = $opts['page'] ?? 0;
        $opts['sort'] = $this->getSort($opts['sort']);

        // $opts['page'] -= 1;

        $limit = app("CobaltBlog_index_limit");
        $result = $this->blog->getIndex($opts['page'], $limit, $opts['sort'] ?? -1);
        add_vars([
            'title' => app("CobaltBlog_main_index_title"),
            'main' => $result,
            'older' => $this->getPageChrome($opts, $limit)
        ]);
        set_template("CobaltBlog/index.html");
    }

    function read($url_safe_name) {
        $article = $this->blog->getArticleByUrlSafeName($url_safe_name);
        if (!$article) throw new \CobaltBlog\Exceptions\PostNotFound("That article doesn't exist");

        add_vars([
            'title' => "Blog",
            'main' => with("CobaltBlog/article.html", ['article' => $article]),
        ]);
        set_template("CobaltBlog/index.html");
    }

    function adminIndex($options) {
        $opts = associative_array_helper($options, ['page', 'sort']);
        $opts['page'] = $opts['page'] ?? 0;
        // $opts['page'] -= 1;

        $limit = app("CobaltBlog_index_limit") * 5;
        $result = $this->blog->getAdminIndex($opts['page'] ?? 0, $limit, $opts['sort'] ?? -1);
        add_vars([
            'title' => "Cobalt Blog Admin Index",
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

    private function getPageChrome($opts, $limit, $mode = 'public') {
        $count = $this->blog->getCount($mode);
        $currentPage = $opts['page'];
        $previousPage = ($currentPage >= 1) ? $currentPage - 1 : false;
        // $pageTotal = $currentPage * $limit;
        $nextPage = ((int)$currentPage < floor($count / $limit)) ? $currentPage + 1 : false;

        $chrome = "<span></span>";
        if ($previousPage !== false) $chrome = $this->pageLink($previousPage, $opts, "Newer Posts");
        if ($nextPage !== false) $chrome .= $this->pageLink($nextPage, $opts, "Older Posts");

        return "<nav class='CobaltBlog--index-chrome'>$chrome</nav>";
    }

    private function pageLink($page, $opts, $innerText) {
        $merge = array_merge($opts, ['page' => $page]);
        $url = app("CobaltBlog_public_path");
        $url .= associative_to_path($merge);
        return "<a href='$url' class='CobaltBlog--navigation-link'>$innerText</a>";
    }
}
