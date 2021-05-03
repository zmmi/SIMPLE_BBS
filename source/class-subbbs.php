<?php
class BBS_Sub
{
    // プロパティの宣言
    private $article_id;
    private $chara_id;
    private $title;
    private $content;
    private $post_date;

    // コンストラクタの宣言
    public function __construct( $article_id, $chara_id, $title, $content, $post_date ) {
        $this->article_id = $article_id;
        $this->chara_id = $chara_id;
        $this->title = $title;
        $this->content = $content;
        $this->post_date = $post_date;
    }

    // Setter
    public function set_article_id( $article_id ) {
        $this->article_id = $article_id;
    }
    public function set_chara_id( $chara_id ) {
        $this->chara_id = $chara_id;
    }
    public function set_title( $title ) {
        $this->title = $title;
    }
    public function set_content( $content ) {
        $this->content = $content;
    }
    public function set_post_date( $post_date ) {
        $this->post_date = $post_date;
    }

    // Getter
    public function get_article_id() {
        return $this->article_id;
    }
    public function get_chara_id() {
        return $this->chara_id;
    }
    public function get_title() {
        return $this->title;
    }
    public function get_content() {
        return $this->content;
    }
    public function get_post_date() {
        return $this->post_date;
    }
}
