<?php
require_once( 'class-charainfo.php' );
require_once( 'class-mainbbs.php' );
require_once( 'class-subbbs.php' );
require_once( 'handler-csv.php' );

// -----------------
// 共通メソッド群
// -----------------

/**
 * verify_password ( string $chara_id, string $password )
 * ハッシュ済みのパスワードを認証する
 */ 
function verify_password( $chara_id, $password ) {
    $handler = new CHARA_Handler();
    $charainfo = $handler->get_charainfo( $chara_id );
    $return = password_verify( $password, $charainfo->get_password() );
    return $return;
}

// -----------------
// キャラクター情報を操作するためのメソッド群
// -----------------

/**
 * get_charainfo ( string chara_id )
 * IDをもとにキャラクター情報をCSVファイルから抽出する
 * IDがnullの場合、POST送信されたデータがあれば抽出する
 */
function get_charainfo( $chara_id = null ) {
    // 初期化
    $charainfo = new CHARA_Info( '' );
    if ( $chara_id != null ) {
        $handler = new CHARA_Handler();
        $charainfo = $handler->get_charainfo( $chara_id );
    }else if ( $chara_id == null && $_SERVER["REQUEST_METHOD"] == "POST" ) {
        // 各要素を変数にセット
        if( !empty( $_POST['chara_id'] ) ) $charainfo->set_chara_id( $_POST['chara_id'] );
        if( !empty( $_POST['password'] ) ) $charainfo->set_password( $_POST['password'] );
        if( !empty( $_POST['pl_name'] ) ) $charainfo->set_pl_name( $_POST['pl_name'] );
        if( !empty( $_POST['mail_address'] ) ) $charainfo->set_mail_address( $_POST['mail_address'] );
        if( !empty( $_POST['pc_name'] ) ) $charainfo->set_pc_name( $_POST['pc_name'] );
        if( !empty( $_POST['pc_name_spell'] ) ) $charainfo->set_pc_name_spell( $_POST['pc_name_spell'] );
        if( !empty( $_POST['gender'] ) ) $charainfo->set_gender( $_POST['gender'] );
        if( !empty( $_POST['side'] ) ) $charainfo->set_side( $_POST['side'] );
        if( !empty( $_POST['color_name'] ) ) $charainfo->set_color_name( $_POST['color_name'] );
        if( !empty( $_POST['color_code'] ) ) $charainfo->set_color_code( $_POST['color_code'] );
        if( !empty( $_POST['height'] ) ) $charainfo->set_height( $_POST['height'] );
        if( !empty( $_POST['profile'] ) ) $charainfo->set_profile( $_POST['profile'] );
        if( !empty( $_POST['icon_url'] ) ) {
            $charainfo->set_icon_url( $_POST['icon_url'] );
        }else{
          $charainfo->set_icon_url( upload_tmp_file() );
        }
    }
    return $charainfo;
}

/**
 * get_side_list ( string $side )
 * 指定されたサイドのキャラクターを一覧で取得する
 */
function get_side_list( $side ) {
    $handler = new CHARA_Handler();
    $list_item = $handler->get_cahrainfo_list( $side );
    return $list_item;
}

/**
 * get_sample_role( string $chara_id )
 * 指定されたキャラクターIDのサンプルロールを取得する
 */
function get_sample_role( $chara_id ) {
    // 戻り値
    $samplerole = "";
    // キャラクターIDに対応するスレッド情報を取得
    $handler = new SAMPLEROLE_Handler();
    $samplerole_id = $handler->get_samplerole_id( $chara_id );
    if( $samplerole_id !== "" ) {
        $handler_sub = new SUB_Handler();
        $subbbs = $handler_sub->get_subbbs_list( $samplerole_id );
        $samplerole = $subbbs[1]->get_content();
    }
    return $samplerole;
}

/**
 * get_pair_list()
 *  - return:: array( (SIDE_A_ID, SIDE_B_ID), (SIDE_A_ID, SIDE_B_ID), ... )
 * ペア一覧のキャラクターIDを取得する
 */
function get_pair_list() {
    // 戻り値
    $pair_list = array();
    // ペアリスト情報を取得
    $handler = new PAIR_Handler();
    $pair_list = $handler->get_pair_list();
    return $pair_list;
}

// -----------------
// プロフィール情報を登録するためのメソッド群
// -----------------

/**
 * upload_tmp_file ()
 * ファイルを一時フォルダにアップロードする
 */
 function upload_tmp_file() {
    if( is_uploaded_file( $_FILES['icon']['tmp_name'] ) ) {
        // 拡張子判定
        $mimetype  = mime_content_type( $_FILES['icon']['tmp_name'] );
        $extension = array_search($mimetype, [
            'jpg' => 'image/jpeg', 'png' => 'image/png', 'gif' => 'image/gif',
        ]);
        if ($extension !== FALSE ) {
            $filename = time() . '.' . $extension;
            $filepath = '/bbs/icon/tmp/' . $filename;
            // ファイルアップロード
            if ( move_uploaded_file( $_FILES["icon"]["tmp_name"], $_SERVER['DOCUMENT_ROOT'] . $filepath ) ) {
                chmod( $_SERVER['DOCUMENT_ROOT'] . $filepath, 0644 );
            }else{
                header('Location: profile-edit.php?type=register&status=file_upload_error');
                exit;
            }
        }else{
            // 拡張子エラー
            header('Location: profile-edit.php?type=register&status=extention_error');
            exit;
        }
    }else{
        // ファイル未選択
        header('Location: profile-edit.php?page=register&status=file_select_error');
        exit;
    }
    return $filepath;
}

/**
 * set_new_charainfo( CHARA_Info $charainfo )
 * フォームに入力されたキャラクター情報を新規登録する
 */
function set_new_charainfo( $charainfo ) {
    $send = new CHARA_Handler();
    $result = $send->set_charainfo( $charainfo );
    return $result;
}

/**
 * modify_charainfo( CHARA_Info $charainfo 
 * フォームに入力されたキャラクター情報を更新する
 */
function modify_charainfo( $charainfo ) {
    $send = new CHARA_Handler();
    $result = $send->update_charainfo( $charainfo );
    return $result;
}

// -----------------
// コメント情報を登録するためのメソッド群
// -----------------

/**
 * get_comment( string $chara_id )
 * 指定したキャラクターIDのコメントを取得する
 * IDがnullもしくは存在しない場合、空の情報を返却する
 */
function get_comment( $chara_id = null ) {
    $comment = new CHARA_Comment( '', '', '' );
    if( !empty( $chara_id ) ) {
        $get = new COMMENT_Hendler();
        $comment = $get->get_comment( $chara_id );
    } 
    return $comment;
}

/**
 * register_comment( CHARA_Comment comment )
 * キャラクターコメントを新規登録する
 */
function register_comment( $comment ) {
    $send = new COMMENT_Hendler();
    return $send->set_comment( $comment );

}

/**
 * modify_comment( CHARA_Comment comment )
 * キャラクターコメントを更新する
 */
function modify_comment( $comment ) {
    $send = new COMMENT_Hendler();
    return $send->update_comment( $comment );
}

// -----------------
// BBS情報を操作するためのメソッド群
// -----------------

// スレッド一覧を取得する
/**
 * get_mainbbs_list( string $bbs_id )
 * bbs_idからスレッド一覧を取得する
 */
function get_mainbbs_list( $bbs_id ) {
    // bbs_idからスレッド一覧を取得する
    $handler = new MAIN_Handler();
    $list = $handler->get_mainbbs_list( $bbs_id );
    return $list;
}

/**
 * get_subbbs_list( string $thread_id )
 * スレッド内のレス一覧を取得する
 */
function get_subbbs_list( $thread_id ) {
    // bbs_idからスレッド一覧を取得する
    $handler = new SUB_Handler();
    $list = $handler->get_subbbs_list( $thread_id );
    return $list;
}

/**
 * get_mainbbs_pc_list( string $thread_id )
 * スレッド一覧に表示するPC一覧を取得する
 */
function get_mainbbs_pc_list( $thread_id ) {
    $return = [];
    // 対象スレッドのレス一覧を取得
    $handler_sub = new SUB_Handler();
    $sub_list = $handler_sub->get_subbbs_list( $thread_id );
    // 戻り値が空だったら終了
    if( empty( $sub_list ) ) {
        return $return;
    }
    $handler_chara = new CHARA_Handler();
    foreach( $sub_list as $sub ) {
        $charainfo = $handler_chara->get_charainfo( $sub->get_chara_id() );
        if( empty( $charainfo ) ) {
            return $return;
        }
        $name = $charainfo->get_pc_name();
        array_push( $return, $name );
    }
    return $return;
}

/**
 * get_pc_all_list()
 * 登録済みのPC一覧を取得する
 */
function get_pc_all_list() {
    // PC一覧を取得
    $handler_chara = new CHARA_Handler();
    $list_chara = $handler_chara->get_cahrainfo_list();
    // アルファベット順でソート
    usort($list_chara, function ($a, $b) {
        return strcmp( $a->get_pc_name(), $b->get_pc_name() );
    });
    return $list_chara;
}

/**
 * get_article_data()
 * プレビューor記事編集画面に表示する内容を取得する
 */
function get_article_data() {
    // 初期値
    $subbbs = new BBS_Sub( '', '', '', '', '' );
    // 記事編集画面の場合は記事IDから情報取得する
    if( !empty( $_GET['thread_id'] ) && !empty( $_GET['article_id'] ) ) {
        $handler_sub = new SUB_Handler();
        $subbbs = $handler_sub->get_subbbs( $_GET['thread_id'], $_GET['article_id'] );
    // プレビュー画面の場合はPOSTデータから情報取得する
    }else if( $_SERVER["REQUEST_METHOD"] == "POST" ) {
        if( !empty( $_POST['chara_id'] ) )$subbbs->set_chara_id( $_POST['chara_id'] );
        if( !empty( $_POST['title'] ) )$subbbs->set_title( htmlspecialchars($_POST['title']) );
        if( !empty( $_POST['content'] ) )$subbbs->set_content( htmlspecialchars($_POST['content'], ENT_QUOTES) );
    }
    return $subbbs;
}

/**
 * get_selected_charainfo( string $chara_id )
 * 指定されたPC情報を取得する
 */
function get_selected_charainfo( $chara_id ) {
    $handler = new CHARA_Handler();
    $charainfo = $handler->get_charainfo( $chara_id );
    return $charainfo;
}

// selectedの判定
function e_selected( CHARA_Info $charainfo, CHARA_Info $set_info ) {
    $selected_id = $charainfo->get_chara_id();
    $set_id = $set_info->get_chara_id();
    if( $selected_id === $set_id ) {
        echo ' selected';
    }
}

/**
 * register_mainbbs( string $bbs_id, BBS_Main $mainbbs )
 * スレッドデータの新規登録を行い、結果をbooleanで返却する
 */
function register_mainbbs( $bbs_id, $mainbbs ) {
    // フォームデータを登録（スレッドの登録）
    $send = new MAIN_Handler();
    $return = $send->set_mainbbs( $bbs_id, $mainbbs );
    return $return;
}

/**
 * register_subbbs( string $bbs_id, string $thread_id, BBS_Sub $subbbs )
 * BBSデータの新規登録を行い、結果をbooleanで返却する
 */
function register_subbbs( $bbs_id, $thread_id, $subbbs ) {
    // フォームデータを登録（記事の登録）
    $send_sub = new SUB_Handler();
    $result_sub = $send_sub->set_subbbs( $thread_id, $subbbs );
    if( $result_sub ) {
        // スレッド情報を更新（更新日を差し替え）
        $mainbbs = new BBS_Main( $thread_id, '', '', $subbbs->get_post_date() );
        $send = new MAIN_Handler();
        $return = $send->update_mainbbs( $bbs_id, $mainbbs );
    }
    return $return;
}

/**
 * update_bbs( string $bbs_id, string $thread_id, BBS_Sub $subbbs )
 * 指定記事を更新する
 */
function update_bbs( $bbs_id, $thread_id, $subbbs ) {
    $send_sub = new SUB_Handler();
    $result_sub = $send_sub->update_subbbs( $thread_id, $subbbs );
    if( $result_sub ) {
        // 更新するスレッド情報を生成
        $mainbbs = new BBS_Main( $thread_id, '', '', '' );
        // 変更対象記事がスレッド先頭の記事である場合、変更内容に追加
        $sub_list = $send_sub->get_subbbs_list( $thread_id );
        if( is_array( $sub_list ) ) {
            foreach( $sub_list as $key => $sub ) {
                if( $sub->get_article_id() === $article_id ) {
                    if( $key === 0 ) {
                        $mainbbs->set_chara_id( $sub->get_chara_id() );
                        $mainbbs->set_title( $sub->get_title() );
                        $mainbbs->set_title( $sub_list[4] );
                    }
                }
            }
        }
    // スレッド情報を更新
    $send = new MAIN_Handler();
    $return = $send->update_mainbbs( $bbs_id, $mainbbs );
    }
    return $return;
}

/**
 * delete_bbs( string $bbs_id, string $thread_id, string $article_id )
 * 指定記事を削除する
 */
function delete_bbs( $bbs_id, $thread_id, $article_id ) {
    // フォームデータをもとに記事を削除
    $send_sub = new SUB_Handler();
    $return = $send_sub->delete_subbbs( $thread_id, $article_id );
    if( $return ) {
        // 変更対象記事がスレッド先頭の記事である場合、スレッドそのものを削除
        $sub_list = $send_sub->get_subbbs_list( $thread_id );
        if( is_array( $sub_list ) && count( $sub_list ) === 0 ) {
            // スレッド情報を更新
            $send = new MAIN_Handler();
            $return = $send->delete_mainbbs( $bbs_id, $thread_id );
            unlink( $_SERVER['DOCUMENT_ROOT'] . '/sessions/data/' . $thread_id . '.csv' );
        }
    }
    return $return;
}

// -----------------
// アラート情報を登録するためのメソッド群
// -----------------

/**
 * get_alert_list()
 * 登録済のアラート情報を全て取得する
 * nullもしくは存在しない場合、空の情報を返却する
 */
function get_alert_list() {
    $get = new ALERT_Hendler();
    $list = $get->get_alert_list();
    return $list;
}

/**
 * register_alert( CHARA_Alert alert )
 * アラート情報を新規登録する
 */
function register_alert( $alert ) {
    $send = new ALERT_Hendler();
    return $send->set_alert( $alert );

}
