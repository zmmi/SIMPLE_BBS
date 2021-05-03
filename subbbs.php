<?php
// インポート
require_once( 'header-footer.php' );
require_once( 'view/display-item.php' );
require_once( 'source/client.php' );
// 初期値
$status = '';
$bbs_id = '';
$thread_id = '';
// ヘッダーを表示
display_header();
// mainタグ
?>
<main role="main" class="container mb-5">
<?php
if( !empty( $_GET['status'] ) )  e_message( $_GET['status'] ); // エラーメッセージ
if( !empty( $_GET['bbs_id'] ) ) $bbs_id = $_GET['bbs_id'];
if( !empty( $_GET['thread_id'] ) ) $thread_id = $_GET['thread_id'];
// スレッドリストを取得する
$list = get_subbbs_list( $thread_id );
// 全PCリストを取得する
$pc_list = get_pc_all_list();
?>
<h2 class="bbs-title mt-4"><i class="fas fa-at"></i><?php echo $bbs_id; ?></h2>
<hr noshade>
<div style="text-align: right;">
  <button type="button" class="btn btn-outline-dark btn-sm" onclick="location.href='bbs-edit?bbs_id=<?php echo $bbs_id; ?>&thread_id=<?php echo $thread_id; ?>&page=add'">ADD POST</button>
  <button type="button" class="btn btn-outline-dark btn-sm" onclick="location.href='mainbbs?bbs_id=<?php echo $bbs_id; ?>'">BACK</button>
</div>
<div class="d-flex align-content-around flex-column bd-highlight mt-3 mx-auto w-auto">
    <?php foreach( $list as $item ): ?>
    <?php $charainfo = get_charainfo( $item->get_chara_id() ); ?>
        <div class="h5 m-2 p-1 bg-white" style="color: <?php echo $charainfo->get_color_code(); ?>">
            <?php echo $item->get_title(); ?>
        </div>
        <div class="d-flex bd-highlight m-1">
            <img src="<?php echo $charainfo->get_icon_url(); ?>" class="align-self-start mr-3" alt="<?php echo $charainfo->get_pc_name(); ?>">
            <div class="m-1 p-3 w-100 border bg-white" style="color: <?php echo $charainfo->get_color_code(); ?>"><?php echo $item->get_content(); ?></div>
        </div>
        <div class="btn-group btn-group-sm ml-auto" role="group">
          <h5><span class="badge badge-light"><?php if( !empty( $item->get_post_date() ) ) echo $item->get_post_date(); ?></span></h5> 
          <a class="text-info" href="bbs-edit?page=modify&bbs_id=<?php echo $bbs_id; ?>&thread_id=<?php echo $thread_id; ?>&article_id=<?php echo $item->get_article_id(); ?>"><i class="fas fa-edit"></i></a>|
          <a class="text-info" href="bbs?page=delete&bbs_id=<?php echo $bbs_id; ?>&thread_id=<?php echo $thread_id; ?>&article_id=<?php echo $item->get_article_id(); ?>"><i class="fas fa-trash-alt"></i></a>
        </div>
    <?php endforeach; ?>
    </div>
</main>
<?php
// ヘッダーを表示
display_footer();
