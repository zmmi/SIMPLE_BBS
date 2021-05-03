<?php
class BBS_Main
{
    // プロパティの宣言
    private $thread_id;
    private $title;
    private $pc_name;
    private $last_update;

    // コンストラクタの宣言
    public function __construct( $thread_id, $title, $chara_id, $last_update ) {
        $this->thread_id = $thread_id;
        $this->title = $title;
        $this->chara_id = $chara_id;
        $this->last_update = $last_update;
    }

    // Setter
    public function set_thread_id( $thread_id ) {
        $this->thread_id = $thread_id;
    }
    public function set_title( $title ) {
        $this->title = $title;
    }
    public function set_chara_id( $chara_id ) {
        $this->chara_id = $chara_id;
    }
    public function set_last_update( $last_update ) {
        $this->last_update = $last_update;
    }

    // Getter
    public function get_thread_id() {
        return $this->thread_id;
    }
    public function get_title() {
        return $this->title;
    }
    public function get_chara_id() {
        return $this->chara_id;
    }
    public function get_last_update() {
        return $this->last_update;
    }
}
