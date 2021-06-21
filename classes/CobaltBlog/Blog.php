<?php

namespace CobaltBlog;

class Blog extends \Drivers\Database {

    final public function public_query($date = null) {
        return [
            'published' => ['$lte' => $this->__date()],
            'is_published' => true
        ];
    }

    public function get_collection_name() {
        return app("CobaltBlog_collection_name");
    }

    final public function getArticleByUrlSafeName($id) {
        return $this->findOne(['url_safe_name' => $id]);
    }

    final public function getArticleById($id) {
        return $this->findOne(['_id' => $this->__id($id)]);
    }

    final public function getAdminIndex($page, $limit, $sort = -1) {
        $result = $this->find([], ['skip' => $page * $limit, 'limit' => $limit, 'sort' => ['published' => $sort, 'date' => $sort]]);
        $html = "";
        foreach ($result as $article) {
            $html .= with("CobaltBlog/admin-index-entry.html", ['article' => $article]);
        }
        return $html;
    }

    final public function getIndex($page = 0, $limit, $sort = -1) {
        $result = $this->find(
            $this->public_query(),
            [
                'skip' => $page * $limit,
                'limit' => $limit,
                'sort' => [
                    'published' => $sort
                ]
            ]
        );

        $html = "";
        foreach ($result as $article) {
            $html .= with("CobaltBlog/article.html", [
                'article' => $article,
                'anchor_start' => "<a href='" . app("CobaltBlog_public_path") . "/read/$article[url_safe_name]'>",
                'anchor_end' => "</a>"
            ]);
        }
        if (!$html) return $this->noBlogsIndex();
        return $html;
    }

    final public function getCount($mode = 'public') {
        $filter = [];
        if ($mode === "public") $filter = $this->public_query();
        return $this->count($filter);
    }

    final private function noBlogsIndex() {
        return "<section class='CobaltBlog'><h1>No posts yet.</h1>
        <article>
        <p>But don't worry! There'll be some cool stuff here, soon!</p>
        </article></section>";
    }
}
