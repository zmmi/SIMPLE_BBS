<?php
// インポート
require_once( 'client.php' );
require_once( 'class-charainfo.php' );
date_default_timezone_set('Asia/Tokyo');

// 変数の初期化
$return = true;
$submit = '';
$bbs_id = '';
$thread_id = '';
$article_id = '';
$title = '';
$content = '';
$chara_id = '';
$mainbbs = new BBS_Main( '', '', '', '' );
$subbbs = new BBS_Sub( '', '', '', '', '' );

// 新規登録・更新・削除でない場合は処理を行わない
if( empty($_POST['submit']) || $_POST['submit'] === 'confirm_bbs' ) {
    header('Location: ' . $_SERVER['HTTP_REFERER'] );
    exit;
}
$submit = $_POST['submit'];

// フォーム情報のセット
if( !empty($_POST['bbs_id']) ) $bbs_id = $_POST['bbs_id'];
if( !empty($_POST['thread_id']) ) $thread_id = $_POST['thread_id'];
if( !empty($_POST['article_id']) ) $article_id = $_POST['article_id'];
if( !empty($_POST['title']) ) { // titleはMAIN,SUB共通のため両方にセット
    $mainbbs->set_title( $_POST['title'] );
    $subbbs->set_title( $_POST['title'] );
}
if( !empty($_POST['chara_id']) ) { // chara_idはMAIN,SUB共通のため両方にセット
    $mainbbs->set_chara_id( $_POST['chara_id'] );
    $subbbs->set_chara_id( $_POST['chara_id'] );
}
if( !empty($_POST['content']) ) $subbbs->set_content( $_POST['content'] );

// パスワード認証
  // パスワード未記入の場合は行わない
if( empty($_POST['password_confirm']) ) {
    header('Location: ' . $_SERVER['HTTP_REFERER'] . '&status=error' );
    exit;
}

  // キャラクター情報からパスワード情報を抽出
if( empty($_POST['chara_id']) ) {
    header('Location: ' . $_SERVER['HTTP_REFERER'] . '&status=error' );
    exit;
}
$charainfo = get_charainfo( $_POST['chara_id'] );

  // プレビュー画面で記入したパスワードと元パスワードが一致していることを確認
if( !password_verify( $_POST['password_confirm'], $charainfo->get_password() ) ) {
    header('Location: ' . $_SERVER['HTTP_REFERER']  . '&status=password_error');
    exit;
}

// 新規登録および更新処理
switch( $submit ) {
    case 'register':
        // bbs_idがnullの場合は処理を中止する
        if( empty($bbs_id) ) {
            header('Location: ' . $_SERVER['HTTP_REFERER'] . '&status=error' );
            exit;
        }
        // thread_idがnullの場合はスレッドを新規作成する
        if( empty($thread_id) ) {
            // MainBBSデータを新規登録する
            $last_update = date( 'Y/m/d H:i' );
            $thread_id = 'mainbbs_' . time();
            $mainbbs->set_thread_id( $thread_id );
            $mainbbs->set_last_update( $last_update );
            // 登録処理
            $return = register_mainbbs( $bbs_id, $mainbbs );
        }
        // SubBBSデータを新規登録する
        if( $return ) {
            $article_id = 'subbbs_' . time();
            $subbbs->set_article_id( $article_id );
            $subbbs->set_post_date( $last_update );
            // 登録処理
            $return = register_subbbs( $bbs_id, $thread_id, $subbbs );
        }
        break;

    case 'add':
        // bbs_id, thread_idがnullの場合は処理を中止する
        if( empty($bbs_id) || empty($thread_id) ) {
            header('Location: ' . $_SERVER['HTTP_REFERER'] . '&status=error' );
            exit;
        }
        // 登録処理
        $article_id = 'subbbs_' . time();
        $subbbs->set_article_id( $article_id );
        $subbbs->set_post_date( date( 'Y/m/d H:i' ) );
        $return = register_subbbs( $bbs_id, $thread_id, $subbbs );
        break;

    case 'modify':
        // thread_id, article_idがセットされていない場合は処理を中断する
        if( empty($thread_id) || empty($article_id) ) {
            header('Location: ' . $_SERVER['HTTP_REFERER'] . '&status=error' );
            exit;
        }
        // 更新処理
        $subbbs->set_article_id( $article_id );
        $return = update_bbs( $bbs_id, $thread_id, $subbbs );
        break;

    case 'delete':
        // 成否判定の初期化
        $return = true;
        // thread_id, article_idがセットされていない場合は処理を中断する
        if( empty($thread_id) || empty($article_id) ) {
            header('Location: ' . $_SERVER['HTTP_REFERER'] . '&status=error' );
            exit;
        }
        // 削除処理
        $subbbs->set_article_id( $article_id );
        $return = delete_bbs( $bbs_id, $thread_id, $article_id );
        break;
}

// 新規登録および更新の成否でページ遷移先を変更する
if( $return ) {
    header('Location: /bbs/subbbs?bbs_id=' . $bbs_id . '&thread_id=' . $thread_id . '&status=success');
    exit;
}else{
    header('Location: ' .  $_SERVER['HTTP_REFERER'] . '&status=error');
    exit;
}