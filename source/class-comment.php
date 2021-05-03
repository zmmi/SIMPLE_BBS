<?php
class CHARA_Comment
{
    // プロパティの宣言
    private $comment_id;
    private $chara_id;
    private $comment;

    // コンストラクタの宣言
    public function __construct( $comment_id, $chara_id, $comment ) {
        $this->comment_id = $comment_id;
        $this->chara_id = $chara_id;
        $this->comment = $comment;
    }

    // Setter
    public function set_comment_id( $comment_id ) {
        $this->comment_id = $comment_id;
    }
    public function set_chara_id( $chara_id ) {
        $this->chara_id = $chara_id;
    }
    public function set_comment( $comment ) {
        $this->comment = $comment;
    }

    // Getter
    public function get_comment_id() {
        return $this->comment_id;
    }
    public function get_chara_id() {
        return $this->chara_id;
    }
    public function get_comment() {
        return $this->comment;
    }
}
