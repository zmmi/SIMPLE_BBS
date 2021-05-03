<?php
class CHARA_Alert
{
    // プロパティの宣言
    private $alert_id;
    private $chara_id;
    private $alert;
    private $remark;
    private $post_date;

    // コンストラクタの宣言
    public function __construct( $alert_id, $chara_id, $alert, $remark, $post_date ) {
        $this->alert_id = $alert_id;
        $this->chara_id = $chara_id;
        $this->alert = $alert;
        $this->remark = $remark;
        $this->post_date = $post_date;
    }

    // Setter
    public function set_alert_id( $alert_id ) {
        $this->alert_id = $alert_id;
    }
    public function set_chara_id( $chara_id ) {
        $this->chara_id = $chara_id;
    }
    public function set_alert( $alert ) {
        $this->alert = $alert;
    }
    public function set_remark( $remark ) {
        $this->remark = $remark;
    }
    public function set_post_date( $post_date ) {
        $this->post_date = $post_date;
    }

    // Getter
    public function get_alert_id() {
        return $this->alert_id;
    }
    public function get_chara_id() {
        return $this->chara_id;
    }
    public function get_alert() {
        return $this->alert;
    }
    public function get_remark() {
        return $this->remark;
    }
    public function get_post_date() {
        return $this->post_date;
    }
}
