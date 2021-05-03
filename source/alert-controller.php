<?php
// インポート
require_once( 'client.php' );
require_once( 'class-charainfo.php' );
require_once( 'class-alert.php' );
date_default_timezone_set('Asia/Tokyo');

// 変数の初期化
$submit = '';
$chara_id = '';
$alert = new CHARA_Alert( '', '', '', '', '' );

// 新規登録・更新でない場合は処理を行わない
if( empty($_POST['submit']) ) {
    header('Location: ' . $_SERVER['HTTP_REFERER'] );
    exit;
}

// キャラクター情報を取得できない場合は処理を行わない
if( empty($_POST['chara_id']) ) {
    header('Location: ' . $_SERVER['HTTP_REFERER'] . '&status=error' );
    exit;
}else{
    $chara_id = $_POST['chara_id'];
    $charainfo = get_charainfo( $chara_id );
}

// ID発行
$alert_id = 'alert_' . time();

// パスワード認証
// パスワード未記入の場合は行わない
if( empty($_POST['password_confirm']) ) {
    header('Location: ' . $_SERVER['HTTP_REFERER'] . '&status=error' );
    exit;
}
// プレビュー画面で記入したパスワードと元パスワードが一致していることを確認
if( !verify_password( $chara_id, $_POST['password_confirm'] ) ) {
    header('Location: ' . $_SERVER['HTTP_REFERER'] . '&status=password_error');
    exit;
}

// フォーム情報のセット
$alert->set_alert_id( $alert_id );
if( !empty($_POST['chara_id']) ) $alert->set_chara_id( $_POST['chara_id'] );
if( !empty($_POST['alert']) ) $alert->set_alert( $_POST['alert'] );
if( !empty($_POST['remark']) ) $alert->set_remark( $_POST['remark'] );
$alert->set_post_date( date( 'Y/m/d H:i' ) );

// 新規登録
$result = register_alert( $alert );        ;

// 新規登録の成否でページ遷移先を変更する
if( $result ) {
    header('Location: /bbs/broadcast?status=success');
    exit;
}else{
    header('Location: ' . $_SERVER['HTTP_REFERER'] . '&status=error');
    exit;
}