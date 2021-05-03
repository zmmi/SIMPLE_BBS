<?php
// インポート
require_once( 'header-footer.php' );
require_once( 'view/display-item.php' );
require_once( 'source/client.php' );
// 初期値
$status = '';
$bbs_id = '';
// ヘッダーを表示
display_header();
// mainタグ
?>
<main role="main" class="container">
<?php
if( !empty( $_GET['status'] ) )  e_message( $_GET['status'] ); // エラーメッセージ
if( !empty( $_GET['bbs_id'] ) ) $bbs_id = $_GET['bbs_id'];
e_alert_list();
// スレッドリストを取得する
$list = get_mainbbs_list( $bbs_id );
// 全PCリストを取得する
$pc_list = get_pc_all_list();
?>
<h2 class="bbs-title mt-4"><i class="fas fa-at"></i><?php echo $bbs_id; ?></h2>
<hr noshade>
<div style="text-align: right;">
  <button type="button" class="btn btn-outline-dark btn-sm" onclick="location.href='bbs-edit?bbs_id=<?php echo $bbs_id; ?>&page=register'">NEW POST</button>
</div>
<div class="d-flex flex-column bd-highlight m-3">
    <!-- スレッド一覧を表示 -->
    <?php foreach( $list as $item ): ?>
    <?php $charainfo = get_charainfo( $item->get_chara_id() ); ?>
    <div class="m-3 p-3 border bg-white">
        <i class="fas fa-quote-left"></i>
        <a href="subbbs?&bbs_id=<?php echo $_GET['bbs_id']; ?>&thread_id=<?php echo $item->get_thread_id(); ?>" class="h5" style="word-wrap:break-word; color: <?php echo $charainfo->get_color_code(); ?>;">
          <?php echo $item->get_title(); ?>
        </a>
         <?php e_new_mark( $_GET['bbs_id'], $item->get_thread_id() ); ?><br />
         <!-- name list -->
         <?php $namelist = get_mainbbs_pc_list( $item->get_thread_id() ); ?>
         <?php foreach( $namelist as $name ): ?>
                - <span style="font-size: 13px;"><?php echo $name; ?></span>
            <?php endforeach; ?>
    </div>
    <?php endforeach; ?>
</main>
<?php
// ヘッダーを表示
display_footer();
