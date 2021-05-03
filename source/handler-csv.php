<?php
require_once( 'class-charainfo.php' );
require_once( 'class-comment.php' );
require_once( 'class-mainbbs.php' );
require_once( 'class-subbbs.php' );
require_once( 'class-alert.php' );
// 定義
define( 'DATA_PATH', $_SERVER['DOCUMENT_ROOT'] . '/bbs/data/' );
define( 'CHARA_CSV', $_SERVER['DOCUMENT_ROOT']. '/bbs/data/charainfo.csv' );
define( 'COMMENT_CSV', $_SERVER['DOCUMENT_ROOT'] . '/bbs/data/comment.csv' );
define( 'SAMPLEROLE_CSV', $_SERVER['DOCUMENT_ROOT'] . '/bbs/data/rolelist.csv' );
define( 'PAIRLIST_CSV', $_SERVER['DOCUMENT_ROOT'] . '/bbs/data/pairlist.csv' );
define( 'ALERT_CSV', $_SERVER['DOCUMENT_ROOT'] . '/bbs/data/alert.csv' );

class CHARA_Handler {
    //フォーム送信データをcsvにセット
    public function set_charainfo( CHARA_Info $charainfo ) {
        // 戻り値
        $return = true;
        // csvレコード作成
        $record = [$charainfo->get_chara_id(), $charainfo->get_password(), $charainfo->get_pl_name(), 
        $charainfo->get_mail_address(), $charainfo->get_pc_name(), $charainfo->get_pc_name_spell(), 
        $charainfo->get_gender(), $charainfo->get_side(), $charainfo->get_color_name(), $charainfo->get_color_code(),
        $charainfo->get_height(), $charainfo->get_profile(), $charainfo->get_icon_url()];
        //書き込み
        $handle = fopen( CHARA_CSV, 'ab' );
        if( $handle ) {
            // ロックは排他ロック（読み込み中はflock使わないので排他の影響を受けない）
            if ( flock( $handle, LOCK_EX ) ){
                if ( fputcsv( $handle,  $record ) === FALSE ){
                    $return = false;
                }
            }
            flock( $handle, LOCK_UN );
        }else{
            $return = false;
        }
        fclose( $handle );
        return $return;
    }
    //フォーム送信データでcsvの内容を書き換える
    public function update_charainfo( CHARA_Info $charainfo ) {
        // 戻り値
        $return = true;
        // 指定csvデータを一旦取得する
        $chara_data_list = $this->get_cahrainfo_list();
        // フォーム送信データの内容を適用してcsv書き込み
        if( !is_array( $chara_data_list ) ) {
            return false;
            exit;
        }        
        if( file_exists( CHARA_CSV ) ) {
            $handle = fopen( CHARA_CSV, 'w' );
           if( $handle ) {
               // ロックは排他ロック（読み込み中はflock使わないので排他の影響を受けない）
               if ( flock( $handle, LOCK_EX ) ){
                   foreach( $chara_data_list as $data ) {
                        if( $data->get_chara_id() === $charainfo->get_chara_id() ) {
                            if( $charainfo->get_password() !== '' ) $data->set_password( $charainfo->get_password() );
                            if( $charainfo->get_pl_name() !== '' ) $data->set_pl_name( $charainfo->get_pl_name() );
                            if( $charainfo->get_mail_address() !== '' ) $data->set_mail_address( $charainfo->get_mail_address() );
                            if( $charainfo->get_pc_name() !== '' ) $data->set_pc_name( $charainfo->get_pc_name() );
                            if( $charainfo->get_pc_name_spell() !== '' ) $data->set_pc_name_spell( $charainfo->get_pc_name_spell() );
                            if( $charainfo->get_gender() !== '' ) $data->set_side( $charainfo->get_gender() );
                            if( $charainfo->get_side() !== '' ) $data->set_side( $charainfo->get_side() );
                            if( $charainfo->get_color_name() !== '' ) $data->set_color_name( $charainfo->get_color_name() );
                            if( $charainfo->get_color_code() !== '' ) $data->set_color_code( $charainfo->get_color_code() );
                            if( $charainfo->get_height() !== '' ) $data->set_height( $charainfo->get_height() );
                            if( $charainfo->get_profile() !== '' ) $data->set_profile( $charainfo->get_profile() );
                            if( $charainfo->get_icon_url() !== '' ) $data->set_icon_url( $charainfo->get_icon_url() );
                        }
                        $record = [$data->get_chara_id(), $data->get_password(), $data->get_pl_name(), 
                        $data->get_mail_address(), $data->get_pc_name(), $data->get_pc_name_spell(), 
                        $data->get_gender(), $data->get_side(), $data->get_color_name(), $data->get_color_code(),
                        $data->get_height(), $data->get_profile(), $data->get_icon_url()];
                        if ( fputcsv( $handle,  $record ) === FALSE ){
                            $return = false;
                        }
                   }
                   flock( $handle, LOCK_UN );
               }
           }else{
               $return = false;
           }
           fclose( $handle );
       }
       return $return;
    }
    //csvからデータを取得
    //サイド指定がない場合は全取得
    public function get_cahrainfo_list( $side = null ) {
        //戻り値
        $return = [];
        //読み込み
        if( file_exists( CHARA_CSV ) ) {
            $handle = fopen( CHARA_CSV, 'r' );
            if( $side === null ) {
                while ( ( $data = fgetcsv( $handle ) ) !== FALSE ) {
                    //戻り値
                    $charainfo = new CHARA_Info( $data[0] );
                    $charainfo->set_password( $data[1] );
                    $charainfo->set_pl_name( $data[2] );
                    $charainfo->set_mail_address( $data[3] );
                    $charainfo->set_pc_name( $data[4] );
                    $charainfo->set_pc_name_spell( $data[5] );
                    $charainfo->set_gender( $data[6] );
                    $charainfo->set_side( $data[7] );
                    $charainfo->set_color_name( $data[8] );
                    $charainfo->set_color_code( $data[9] );
                    $charainfo->set_height( $data[10] );
                    $charainfo->set_profile( $data[11] );
                    $charainfo->set_icon_url( $data[12] );
                    array_push( $return, $charainfo );
                    $charainfo = null;
                }
            }else{
                while ( ( $data = fgetcsv( $handle ) ) !== FALSE ) {
                    if( $data[7] === $side ) {
                        //戻り値
                        $charainfo = new CHARA_Info( $data[0] );
                        $charainfo->set_password( $data[1] );
                        $charainfo->set_pl_name( $data[2] );
                        $charainfo->set_mail_address( $data[3] );
                        $charainfo->set_pc_name( $data[4] );
                        $charainfo->set_pc_name_spell( $data[5] );
                        $charainfo->set_gender( $data[6] );
                        $charainfo->set_side( $data[7] );
                        $charainfo->set_color_name( $data[8] );
                        $charainfo->set_color_code( $data[9] );
                        $charainfo->set_height( $data[10] );
                        $charainfo->set_profile( $data[11] );
                        $charainfo->set_icon_url( $data[12] );
                        array_push( $return, $charainfo );
                        $charainfo = null;
                    }
                }
            }
            fclose( $handle );
        }
        return $return;
    }
    //csvから指定データを取得
    public function get_charainfo( $chara_id ) {
        $charainfo = new CHARA_Info( $chara_id );
        if( file_exists( CHARA_CSV ) ) {
            //読み込み
            $handle = fopen( CHARA_CSV, 'r' );
            while ( ( $data = fgetcsv( $handle ) ) !== FALSE ) {   
                if( $data[0] === $chara_id ) {
                    //データをオブジェクトに格納
                    $charainfo->set_password( $data[1] );
                    $charainfo->set_pl_name( $data[2] );
                    $charainfo->set_mail_address( $data[3] );
                    $charainfo->set_pc_name( $data[4] );
                    $charainfo->set_pc_name_spell( $data[5] );
                    $charainfo->set_gender( $data[6] );
                    $charainfo->set_side( $data[7] );
                    $charainfo->set_color_name( $data[8] );
                    $charainfo->set_color_code( $data[9] );
                    $charainfo->set_height( $data[10] );
                    $charainfo->set_profile( $data[11] );
                    $charainfo->set_icon_url( $data[12] );
                    break;
                }
            }
            fclose( $handle );
        }
        return $charainfo;
    }
}

class COMMENT_Hendler {
    //フォーム送信データをcsvにセット
    public function set_comment( CHARA_Comment $comment ) {
        // 戻り値
        $return = true;
        // csvレコード作成
        $record = [$comment->get_comment_id(), $comment->get_chara_id(), $comment->get_comment()];
        //書き込み
        $handle = fopen( COMMENT_CSV, 'ab' );
        if( $handle ) {
            // ロックは排他ロック（読み込み中はflock使わないので排他の影響を受けない）
            if ( flock( $handle, LOCK_EX ) ){
                if ( fputcsv( $handle,  $record ) === FALSE ){
                    $return = false;
                }
            }
            flock( $handle, LOCK_UN );
        }else{
            $return = false;
        }
        fclose( $handle );
        return $return;
    }
    //フォーム送信データでcsvの内容を書き換える
    public function update_comment( CHARA_Comment $comment ) {
        // 戻り値
        $return = true;
        // 指定csvデータを一旦取得する
        $comment_list = $this->get_comment_list();
        // フォーム送信データの内容を適用してcsv書き込み
        if( !is_array( $comment_list ) ) {
            return false;
            exit;
        }        
        if( file_exists( COMMENT_CSV ) ) {
            $handle = fopen( COMMENT_CSV, 'w' );
           if( $handle ) {
               // ロックは排他ロック（読み込み中はflock使わないので排他の影響を受けない）
               if ( flock( $handle, LOCK_EX ) ){
                   foreach( $comment_list as $data ) {
                        if( $data->get_chara_id() === $comment->get_chara_id() ) {
                            if( $comment->get_comment_id() !== '' ) $data->set_comment_id( $comment->get_comment_id() );
                            if( $comment->get_chara_id() !== '' ) $data->set_chara_id( $comment->get_chara_id() );
                            if( $comment->get_comment() !== '' ) $data->set_comment( $comment->get_comment() );
                        }
                        $record = [$data->get_comment_id(), $data->get_chara_id(), $data->get_comment()];
                        if ( fputcsv( $handle,  $record ) === FALSE ){
                            $return = false;
                        }
                   }
                   flock( $handle, LOCK_UN );
               }
           }else{
               $return = false;
           }
           fclose( $handle );
       }
    return $return;
    }
    //csvから全データを取得
    public function get_comment_list() {
        //戻り値
        $return = [];
        //読み込み
        if( file_exists( COMMENT_CSV ) ) {
            $handle = fopen( COMMENT_CSV, 'r' );
            while ( ( $data = fgetcsv( $handle ) ) !== FALSE ) {
                //戻り値
                $comment = new CHARA_Comment( $data[0], $data[1], $data[2] );
                array_push( $return, $comment );
                $comment = null;
                }
            fclose( $handle );
        }
        return $return;
    }
    //csvから指定データを取得
    public function get_comment( $chara_id ) {
        //戻り値
        $comment = new CHARA_Comment( '', '', '' );
        if( file_exists( COMMENT_CSV ) ) {
            //読み込み
            $handle = fopen( COMMENT_CSV, 'r' );
            while ( ( $data = fgetcsv( $handle ) ) !== FALSE ) {    
                if( $data[1] === $chara_id ) {
                    //データをオブジェクトに格納
                    $comment->set_comment_id( $data[0] );
                    $comment->set_chara_id( $data[1] );
                    $comment->set_comment( $data[2] );
                    break;
                }
            }
            fclose( $handle );
        }
        return $comment;
    }
}

class SAMPLEROLE_Handler {
    //csvから指定データを取得
    public function get_samplerole_id( $chara_id ) {
        //戻り値
        $samplerole_id = "";
        if( file_exists( SAMPLEROLE_CSV ) ) {
            //読み込み
            $handle = fopen( SAMPLEROLE_CSV, 'r' );
            while ( ( $data = fgetcsv( $handle ) ) !== FALSE ) {    
                if( $data[0] === $chara_id ) {
                    //データを戻り値に格納
                    $samplerole_id = $data[1];
                    break;
                }
            }
            fclose( $handle );
        }
        return $samplerole_id;
    }
}

class PAIR_Handler {
    //csvから指定データを取得
    public function get_pair_list() {
        //戻り値
        $return = [];
        //読み込み
        if( file_exists( PAIRLIST_CSV ) ) {
            $handle = fopen( PAIRLIST_CSV, 'r' );
            while ( ( $data = fgetcsv( $handle ) ) !== FALSE ) {    
                array_push( $return, $data );
            }
            fclose( $handle );
        }
        return $return;
    }
}

class MAIN_Handler {
    //フォーム送信データをcsvにセット
    public function set_mainbbs( $bbs_id, BBS_Main $mainbbs ) {
        // 戻り値
        $return = true;
        // csvレコード作成
        $record = [$mainbbs->get_thread_id(), $mainbbs->get_title(), $mainbbs->get_chara_id(), $mainbbs->get_last_update()];
        //書き込み
        $csv = DATA_PATH . $bbs_id . '.csv';
        $handle = fopen( $csv, 'ab' );
        if( $handle ) {
            // ロックは排他ロック（読み込み中はflock使わないので排他の影響を受けない）
            if ( flock( $handle, LOCK_EX ) ){
                if ( fputcsv( $handle,  $record ) === FALSE ){
                    $return = false;
                }
            }
            flock( $handle, LOCK_UN );
        }else{
            $return = false;
        }
        fclose( $handle );
        return $return;
    }
    //フォーム送信データでcsvの内容を書き換える
    public function update_mainbbs( $bbs_id, BBS_Main $mainbbs ) {
        // 戻り値
        $return = true;
        // パス
        $csv =  DATA_PATH . $bbs_id . '.csv';
        // 指定csvデータを一旦取得する
        $mainbbs_data_list = $this->get_mainbbs_list( $bbs_id );
        // フォーム送信データの内容を適用してcsv書き込み
        if( !is_array( $mainbbs_data_list ) ) {
            return false;
            exit;
        }
        if( file_exists( $csv ) ) {
            $handle = fopen( $csv, 'w' );
           if( $handle ) {
               // ロックは排他ロック（読み込み中はflock使わないので排他の影響を受けない）
               if ( flock( $handle, LOCK_EX ) ){
                   foreach( $mainbbs_data_list as $mainbbs_data ) {
                       if( $mainbbs_data->get_thread_id() === $mainbbs->get_thread_id() ) {
                           if( $mainbbs->get_chara_id() !== '' ) $mainbbs_data->set_chara_id( $mainbbs->get_chara_id() );
                           if( $mainbbs->get_title() !== '' ) $mainbbs_data->set_title( $mainbbs->get_title() );
                           if( $mainbbs->get_last_update() !== '' ) $mainbbs_data->set_last_update( $mainbbs->get_last_update() );
                       }
                       $record = [$mainbbs_data->get_thread_id(), $mainbbs_data->get_title(), $mainbbs_data->get_chara_id(), $mainbbs_data->get_last_update()];
                       if ( fputcsv( $handle,  $record ) === FALSE ){
                           $return = false;
                       }
                   }
                   flock( $handle, LOCK_UN );
               }
           }else{
               $return = false;
           }
           fclose( $handle );
       }
       return $return;
    }
    //指定スレッドIDの要素を削除する
    public function delete_mainbbs( $bbs_id, $thread_id ) {
        // 戻り値
        $return = true;
        // パス
        $csv =  DATA_PATH . $bbs_id . '.csv';
        // 指定csvデータを一旦取得する
        $mainbbs_data_list = $this->get_mainbbs_list( $bbs_id );
        // フォーム送信データの内容を適用してcsv書き込み
        if( !is_array( $mainbbs_data_list ) ) {
            return false;
            exit;
        }
        if( file_exists( $csv ) ) {
            $handle = fopen( $csv, 'w' );
           if( $handle ) {
               // ロックは排他ロック（読み込み中はflock使わないので排他の影響を受けない）
               if ( flock( $handle, LOCK_EX ) ){
                   foreach( $mainbbs_data_list as $mainbbs_data ) {
                       if( $mainbbs_data->get_thread_id() !== $thread_id ) {
                            $record = [$mainbbs_data->get_thread_id(), $mainbbs_data->get_title(), $mainbbs_data->get_chara_id(), $mainbbs_data->get_last_update()];
                            if ( fputcsv( $handle,  $record ) === FALSE ){
                                $return = false;
                            }
                       }
                   }
                   flock( $handle, LOCK_UN );
               }
           }else{
               $return = false;
           }
           fclose( $handle );
       }
       return $return;
    }
    //csvからデータを取得
    public function get_mainbbs_list( $bbs_id ) {
        //戻り値
        $return = [];
        $csv = DATA_PATH . $bbs_id . '.csv';
        if( file_exists( $csv ) ) {
            //読み込み
            $handle = fopen( $csv, 'r' );
            while ( ( $data = fgetcsv( $handle ) ) !== FALSE ) {
                //戻り値
                $mainbbs = new BBS_Main( $data[0], $data[1], $data[2], $data[3] );
                array_push( $return, $mainbbs );
                $mainbbs = null;
            }
        fclose( $handle );
        }
    // 最新更新日順にソート
    usort( $return, 'compare_last_update' );
    return $return;
    }
    //csvから指定データを取得
    public function get_mainbbs( $bbs_id, $thread_id ) {
        $csv = DATA_PATH . $bbs_id . '.csv';
        if( file_exists( $csv ) ) {
            //読み込み
            $handle = fopen( $csv, 'r' );
            while ( ( $data = fgetcsv( $handle ) ) !== FALSE ) {    
                if( $data[0] === $thread_id ) {
                    //戻り値
                    $mainbbs = new BBS_Main( $data[0], $data[1], $data[2], $data[3] );
                    $return = $mainbbs;
                    break;
                }
            }
            fclose( $handle );
        }
        return $return;
    }
}

class SUB_Handler {
    //フォーム送信データをcsvにセット(書き込みモード)
    public function set_subbbs( $thread_id, BBS_Sub $subbbs ) {
        // 戻り値
        $return = true;
        // csvレコード作成
        $record = [$subbbs->get_article_id(), $subbbs->get_chara_id(), 
        $subbbs->get_title(), $subbbs->get_content(), $subbbs->get_post_date()];
        //書き込み
        $csv = DATA_PATH . $thread_id . '.csv';
        $handle = fopen( $csv, 'ab' );
        if( $handle ) {
            // ロックは排他ロック（読み込み中はflock使わないので排他の影響を受けない）
            if ( flock( $handle, LOCK_EX ) ){
                if ( fputcsv( $handle,  $record ) === FALSE ){
                    $return = false;
                }
            }
            flock( $handle, LOCK_UN );
        }else{
            $return = false;
        }
        fclose( $handle );
        return $return;
    }
    //フォーム送信データでcsvの内容を書き換える
    public function update_subbbs( $thread_id, BBS_Sub $subbbs ) {
        // 戻り値
        $return = true;
        // パス
        $csv = DATA_PATH . $thread_id . '.csv';
        // 指定csvデータを一旦取得する
        $subbbs_data_list = $this->get_subbbs_list( $thread_id );
        // フォーム送信データの内容を適用してcsv書き込み
        if( !is_array( $subbbs_data_list ) ) {
            return false;
            exit;
        }
        if( file_exists( $csv ) ) {
             $handle = fopen( $csv, 'w' );
            if( $handle ) {
                // ロックは排他ロック（読み込み中はflock使わないので排他の影響を受けない）
                if ( flock( $handle, LOCK_EX ) ){
                    foreach( $subbbs_data_list as $subbbs_data ) {
                        if( $subbbs_data->get_article_id() === $subbbs->get_article_id() ) {
                            $subbbs_data->set_chara_id( $subbbs->get_chara_id() );
                            $subbbs_data->set_title( $subbbs->get_title() );
                            $subbbs_data->set_content( $subbbs->get_content() );
                        }
                        $record = [$subbbs_data->get_article_id(), $subbbs_data->get_chara_id(), $subbbs_data->get_title(), $subbbs_data->get_content(), $subbbs_data->get_post_date()];
                        if ( fputcsv( $handle,  $record ) === FALSE ){
                            $return = false;
                        }
                    }
                    flock( $handle, LOCK_UN );
                }
            }else{
                $return = false;
            }
            fclose( $handle );
        }
        return $return;
    }
    //指定記事IDの行を削除する
    public function delete_subbbs( $thread_id, $article_id ) {
        // 戻り値
        $return = true;
        // パス
        $csv = DATA_PATH . $thread_id . '.csv';
        // 指定csvデータを一旦取得する
        $subbbs_data_list = $this->get_subbbs_list( $thread_id );
        // フォーム送信データの内容を適用してcsv書き込み
        if( !is_array( $subbbs_data_list ) ) {
            return false;
            exit;
        }
        if( file_exists( $csv ) ) {
             $handle = fopen( $csv, 'w' );
            if( $handle ) {
                // ロックは排他ロック（読み込み中はflock使わないので排他の影響を受けない）
                if ( flock( $handle, LOCK_EX ) ){
                    foreach( $subbbs_data_list as $subbbs_data ) {
                        if( $subbbs_data->get_article_id() !== $article_id ) {
                            $record = [$subbbs_data->get_article_id(), $subbbs_data->get_chara_id(), $subbbs_data->get_title(), $subbbs_data->get_content(), $subbbs_data->get_post_date()];
                            if ( fputcsv( $handle,  $record ) === FALSE ){
                                $return = false;
                            }
                        }
                    }
                    flock( $handle, LOCK_UN );
                }
            }else{
                $return = false;
            }
            fclose( $handle );
        }
        return $return;
    }
    //csvからデータを取得
    public function get_subbbs_list( $thread_id ) {
        $return = [];
        //読み込み
        $csv = DATA_PATH . $thread_id . '.csv';
        if( file_exists( $csv ) ) {
            $handle = fopen( $csv, 'r' );
            while ( ( $data = fgetcsv( $handle ) ) !== FALSE ) {
                //戻り値
                $subbbs = new BBS_Sub( $data[0], $data[1], $data[2], $data[3], $data[4] );
                array_push( $return, $subbbs );
                $mainbbs = null;
            }
        fclose( $handle );
        }
    return $return;
    }
    //csvから指定データを取得
    public function get_subbbs( $thread_id, $article_id ) {
        //読み込み
        $return = new BBS_Sub( '', '', '', '', '' );
        $csv = DATA_PATH . $thread_id . '.csv';
        if( file_exists( $csv ) ) {
            $handle = fopen( $csv, 'r' );
            while ( ( $data = fgetcsv( $handle ) ) !== FALSE ) {    
                if( $data[0] === $article_id ) {
                    //戻り値
                    $subbbs = new BBS_Sub( $data[0], $data[1], $data[2], $data[3], $data[4] );
                    $return = $subbbs;
                    break;
                }
            }
            fclose( $handle );
        }
        return $return;
    }
}

// 日付降順で表示するための比較関数
function compare_last_update( $mainbbs_a, $mainbbs_b ) {
    $date_a = strtotime( $mainbbs_a->get_last_update() );
    $date_b = strtotime( $mainbbs_b->get_last_update() );
    if( $date_a === $date_b ) {
        return 0;
    }
    return ( $date_a > $date_b ) ? -1 : 1;
}

class ALERT_Hendler {
    //フォーム送信データをcsvにセット
    public function set_alert( CHARA_Alert $alert ) {
        // 戻り値
        $return = true;
        // csvレコード作成
        $record = [$alert->get_alert_id(), $alert->get_chara_id(), $alert->get_alert(), $alert->get_remark(), $alert->get_post_date()];
        //書き込み
        $handle = fopen( ALERT_CSV, 'ab' );
        if( $handle ) {
            // ロックは排他ロック（読み込み中はflock使わないので排他の影響を受けない）
            if ( flock( $handle, LOCK_EX ) ){
                if ( fputcsv( $handle,  $record ) === FALSE ){
                    $return = false;
                }
            }
            flock( $handle, LOCK_UN );
        }else{
            $return = false;
        }
        fclose( $handle );
        return $return;
    }   
    //csvから全データを取得
    public function get_alert_list() {
        //戻り値
        $return = [];
        //読み込み
        if( file_exists( ALERT_CSV ) ) {
            $handle = fopen( ALERT_CSV, 'r' );
            while ( ( $data = fgetcsv( $handle ) ) !== FALSE ) {
                //戻り値
                $alert = new CHARA_Alert( $data[0], $data[1], $data[2], $data[3], $data[4] );
                array_push( $return, $alert );
                $alert = null;
                }
            fclose( $handle );
        }
        return $return;
    }
}