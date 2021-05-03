<?php
// インポート
require_once( 'header-footer.php' );
require_once( 'view/display-item.php' );
require_once( 'source/client.php' );
// 初期値
$status = '';
$page = '';
$page_title = '';
$submit = 'preview';
// 表示データを取得する
$data = get_article_data();
$charainfo = get_selected_charainfo( $data->get_chara_id() );
// 全PCリストを取得する
$pc_list = get_pc_all_list();
// ヘッダーを表示
display_header();
// mainタグ
?>
<main role="main" class="container">
<?php
if( !empty( $_GET['status'] ) ) e_message( $_GET['status'] ); // エラーメッセージ
if( !empty( $_GET['page'] ) ) $page = $_GET['page'];
// 編集種別を表示
switch( $page ) {
  case 'register':
    $page_title = '新規スレッドの投稿';
    break;
  case 'add':
    $page_title = '新規レスの追加';
    break;
  case 'modify':
    $page_title = 'レス内容の修正';
    break;
}
?>
<!-- レス一覧を取得できる場合は表示する -->
<?php if( !empty( $_GET['thread_id'] ) && $_GET['page'] == 'add' ): ?>
<?php $list = get_subbbs_list( $_GET['thread_id'] ); ?>
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
  <?php endforeach; ?>
</div>
<?php endif; ?>
<!-- 投稿フォーム -->
<br /><br />
<h3><?php echo $page_title; ?></h3>
<form class="m-2" method="post" action="bbs?page=<?php echo $_GET['page']; ?>" enctype="multipart/form-data">
        <div class="form-group">
            <label for="title">タイトル</label>
            <input type="text" class="form-control" name="title" placeholder="30文字以内" value="<?php echo $data->get_title(); ?>" maxlength="30">
        </div>
        <div class="form-group">
            <label for="content">本文</label>
            <textarea class="form-control" name="content" rows="6" maxlength="2000"><?php echo $data->get_content(); ?></textarea>
        </div>
        <div class="form-group">
          <select class="form-control" name="chara_id">
          <?php foreach( $pc_list as $pc ): ?>
            <option value="<?php echo $pc->get_chara_id(); ?>"<?php echo e_selected( $charainfo, $pc ); ?>><?php echo $pc->get_pc_name(); ?></option>
          <?php endforeach; ?>
          </select>
        </div>
        <!-- hidden group -->
        <input type="hidden" name="bbs_id" value="<?php if( !empty($_GET['bbs_id']) ) echo $_GET['bbs_id']; ?>">
        <input type="hidden" name="thread_id" value="<?php if( !empty($_GET['thread_id']) ) echo $_GET['thread_id']; ?>">
        <input type="hidden" name="article_id" value="<?php if( !empty($_GET['article_id']) ) echo $_GET['article_id']; ?>">

        <div class="form-group">
            <button type="submit" class="btn btn-light" name="submit" value="confirm_bbs">confirm</button>
        </div>
    </form>
</main>
<?php
// ヘッダーを表示
display_footer();
