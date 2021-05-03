<?php
// インポート
require_once( 'client.php' );
require_once( 'class-charainfo.php' );

// 変数の初期化
$submit = '';
$charainfo = new CHARA_Info( '' );

// 新規登録・更新でない場合は処理を行わない
if( empty($_POST['submit']) || $_POST['submit'] === 'confirm' ) {
    header('Location: ' . $_SERVER['HTTP_REFERER'] );
    exit;
}
$submit = $_POST['submit'];

// パスワード認証
// パスワード未記入の場合は行わない
if( empty($_POST['password']) || empty($_POST['password_confirm']) ) {
    header('Location: ' . $_SERVER['HTTP_REFERER'] );
    exit;
}
// プレビュー画面で記入したパスワードと元パスワードが一致していることを確認
$password = password_hash( $_POST['password'], PASSWORD_DEFAULT );
if( !password_verify( $_POST['password_confirm'], $password ) ) {
    header('Location: /bbs/profile-edit?page=' . $type . '&status=password_error');
    exit;
}
$charainfo->set_password( $password );

// フォーム情報のセット
if( !empty($_POST['chara_id']) ) $charainfo->set_chara_id( $_POST['chara_id'] );
if( !empty($_POST['pl_name']) ) $charainfo->set_pl_name( $_POST['pl_name'] );
if( !empty($_POST['mail_address']) ) $charainfo->set_mail_address( $_POST['mail_address'] );
if( !empty($_POST['pc_name']) ) $charainfo->set_pc_name( $_POST['pc_name'] );
if( !empty($_POST['pc_name_spell']) ) $charainfo->set_pc_name_spell( $_POST['pc_name_spell'] );
if( !empty($_POST['gender']) ) $charainfo->set_gender( $_POST['gender'] );
if( !empty($_POST['side']) ) $charainfo->set_side( $_POST['side'] );
if( !empty($_POST['color_name']) ) $charainfo->set_color_name( $_POST['color_name'] );
if( !empty($_POST['color_code']) ) $charainfo->set_color_code( $_POST['color_code'] );
if( !empty($_POST['height']) ) $charainfo->set_height( $_POST['height'] );
if( !empty($_POST['profile']) ) $charainfo->set_profile( $_POST['profile'] );

// 一時ファイルがアップロードされていない場合は処理を中断する
if( empty($_POST['icon_url']) || !file_exists( $_SERVER['DOCUMENT_ROOT'] . $_POST['icon_url'] )) {
    header('Location: /bbs/profile-edit?page=' . $submit . '&status=error');
    exit;
}
// ファイル名を変更して一時フォルダから移動する
$icon_url = str_replace( 'tmp/', '', $_POST['icon_url'] );
$move_result = rename( $_SERVER['DOCUMENT_ROOT'] . $_POST['icon_url'], $_SERVER['DOCUMENT_ROOT'] . $icon_url );
// ファイルの移動に失敗したら処理を中断する
if( $move_result === FALSE ) {
    header('Location: /bbs/profile-edit?page=' . $submit . '&status=error');
    exit;
}
$charainfo->set_icon_url( $icon_url );

// 新規登録および更新処理
switch( $submit ) {
    case 'register':
        $charainfo->set_chara_id( 'charainfo_' . time() );
        $result = set_new_charainfo( $charainfo );
        break;
    case 'modify':
        $result = modify_charainfo( $charainfo );
        break;
}

// 新規登録および更新の成否でページ遷移先を変更する
if( $result ) {
    header('Location: /bbs/profile?chara_id=' . $charainfo->get_chara_id() . '&status=success');
    exit;
}else{
    header('Location: /bbs/profile-edit?page=' . $submit . '&status=error');
    exit;
}