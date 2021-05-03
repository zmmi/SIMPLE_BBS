<?php
require_once( dirname(__FILE__) . '/../source/client.php' );
require_once( dirname(__FILE__) . '/../source/handler-csv.php' );

// メッセージを表示する
function e_message( $status ) {
    switch( $status ) {
        case 'success':
            echo '<div class="alert alert-success" role="alert">success!</div>';
            break;
        case 'error':
            echo '<div class="alert alert-danger" role="alert">[ERROR NO.N000] 再操作してください。何度もエラーが出る場合はお問い合わせください。</div>';
            break;
        case 'file_upload_error':
            echo '<div class="alert alert-danger" role="alert">[ERROR NO.F000] ファイルのアップロードに失敗しました。</div>';
            break;
        case 'extention_error':
            echo '<div class="alert alert-danger" role="alert">[ERROR NO.N001] アップロード可能な拡張子はjpg, png, gifです。</div>';
            break;
        case 'file_select_error':
            echo '<div class="alert alert-danger" role="alert">[ERROR NO.N002] ファイルを選択してください。</div>';
            break;
        case 'password_error':
            echo '<div class="alert alert-danger" role="alert">[ERROR NO.N003] パスワードが間違っています。</div>';
            break;
    }
}

// 24時間以内の更新かどうかを判定する
function e_new_mark( $bbs_id, $thread_id ) {
    // スレッド情報から日付を取得する
    $handler = new MAIN_Handler();
    $mainbbs = $handler->get_mainbbs( $bbs_id, $thread_id );
    $last_update = $mainbbs->get_last_update();
    if( ( time() - strtotime( $last_update ) ) < (3600*60) ) {
        echo '  <span class="badge badge-info"><i class="fas fa-angle-left"></i> new</span>';
    }
}

// アラートを表示する
function e_alert_list() {
    // 全アラートリストを取得する
    $alert_list = get_alert_list();
    // 2日以内のアラートを表示
    $alert_list_filter = array_filter($alert_list, function( $date ) {
        return strtotime($date->get_post_date()) > strtotime("-2 day", time());
    });
    foreach( $alert_list_filter as $alert ) {
        $charainfo = get_charainfo( $alert->get_chara_id() ); 
        switch( $alert->get_alert() ) {
            case 'danger':
                $comment = "レス期限（60時間）を越えそう";
                break;
            case 'warning':
                $comment = "いつものペースより少し遅れそう";
            break;
        }
    ?>
    <div class="alert alert-<?php echo $alert->get_alert();?> m-2" role="alert">
      <?php echo $charainfo->get_pc_name(); ?> PLさま | <?php echo $comment; ?> | <?php echo $alert->get_remark(); ?> | <?php echo $alert->get_post_date(); ?>
    </div>
    <?php
    }
}