<?php
// インポート
require_once( 'client.php' );
require_once( 'class-charainfo.php' );
require_once( 'class-comment.php' );

// 変数の初期化
$submit = '';
$chara_id = '';

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

// 新規登録 or 更新を判定する
$comment = get_comment( $chara_id );
if( empty($comment->get_comment_id()) ) {
    $comment_id = 'comment_' . time();
    $submit = 'register';
}else{
    $comment_id = $_POST['comment_id'];
    $submit = 'modify';
}

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
$comment->set_comment_id( $comment_id );
if( !empty($_POST['chara_id']) ) $comment->set_chara_id( $_POST['chara_id'] );
if( !empty($_POST['comment']) ) $comment->set_comment( $_POST['comment'] );

// 新規登録および更新処理
switch( $submit ) {
    case 'register':
        $result = register_comment( $comment );
        break;
    case 'modify':
        $result = modify_comment( $comment );
        break;
}

// 新規登録および更新の成否でページ遷移先を変更する
if( $result ) {
    header('Location: /bbs/profile?chara_id=' . $charainfo->get_chara_id() . '&status=success');
    exit;
}else{
    header('Location: ' . $_SERVER['HTTP_REFERER'] . '&status=error');
    exit;
}