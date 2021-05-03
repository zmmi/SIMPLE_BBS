<?php
class CHARA_Info
{
    // プロパティの宣言
    private $chara_id;
    private $password;
    private $pl_name;
    private $mail_address;
    private $pc_name;
    private $pc_name_spell;
    private $gender;
    private $side;
    private $color_name;
    private $color_code;
    private $height;
    private $profile;
    private $icon_url;

    // コンストラクタの宣言
    public function __construct( $chara_id ) {
        $this->chara_id = $chara_id;
    }

    // Setter
    public function set_chara_id( $chara_id ) {
        $this->chara_id = $chara_id;
    }
    public function set_password( $password ) {
        $this->password = $password;
    }
    public function set_pl_name( $pl_name ) {
        $this->pl_name = $pl_name;
    }
    public function set_mail_address( $mail_address ) {
        $this->mail_address = $mail_address;
    }
    public function set_pc_name( $pc_name ) {
        $this->pc_name = $pc_name;
    }
    public function set_pc_name_spell( $pc_name_spell ) {
        $this->pc_name_spell = $pc_name_spell;
    }
    public function set_gender( $gender ) {
        $this->gender = $gender;
    }
    public function set_side( $side ) {
        $this->side = $side;
    }
    public function set_color_name( $color_name ) {
        $this->color_name = $color_name;
    }
    public function set_color_code( $color_code ) {
        $this->color_code = $color_code;
    }
    public function set_height( $height ) {
        $this->height = $height;
    }
    public function set_profile( $profile ) {
        $this->profile = $profile;
    }
    public function set_icon_url( $icon_url ) {
        $this->icon_url = $icon_url;
    }

    // Getter
    public function get_chara_id() {
        return $this->chara_id;
    }
    public function get_password() {
        return $this->password;
    }
    public function get_pl_name() {
        return $this->pl_name;
    }
    public function get_mail_address() {
        return $this->mail_address;
    }
    public function get_pc_name() {
        return $this->pc_name;
    }
    public function get_pc_name_spell() {
        return $this->pc_name_spell;
    }
    public function get_gender() {
        return $this->gender;
    }
    public function get_side() {
        return $this->side;
    }
    public function get_color_name() {
        return $this->color_name;
    }
    public function get_color_code() {
        return $this->color_code;
    }
    public function get_height() {
        return $this->height;
    }
    public function get_profile() {
        return $this->profile;
    }
    public function get_icon_url() {
        return $this->icon_url;
    }
}
