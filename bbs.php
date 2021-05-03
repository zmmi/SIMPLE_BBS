<?php
// インポート
require_once( 'header-footer.php' );
require_once( 'view/display-item.php' );
require_once( 'source/client.php' );
// 初期値
$status = '';
$page = '';
$submit = 'preview';
// 表示データを取得する
$data = get_article_data();
$charainfo = get_selected_charainfo( $data->get_chara_id() );
// 全PCリストを取得する
$pc_list = get_pc_all_list();
// ヘッダーを表示
display_header( 'BOARD' );
// mainタグ
?>
<main role="main" class="container">
<?php
if( !empty( $_GET['status'] ) ) e_message( $_GET['status'] ); // エラーメッセージ
if( !empty( $_GET['page'] ) ) $page = $_GET['page'];
?>
<div class="d-flex align-content-around flex-column bd-highlight mt-3 mx-auto w-auto">
    <div class="h5 m-2 p-1 bg-white" style="color: <?php echo $charainfo->get_color_code(); ?>">
        <?php echo $data->get_title(); ?>
    </div>
    <div class="d-flex bd-highlight m-1">
        <img src="<?php echo $charainfo->get_icon_url(); ?>" class="align-self-start mr-3" alt="<?php echo $charainfo->get_pc_name(); ?>">
        <div class="m-1 p-3 w-100 border bg-white" style="color: <?php echo $charainfo->get_color_code(); ?>"><?php echo $data->get_content(); ?></div>
    </div>
</div>
<!-- 投稿フォーム -->
<form class="m-2" method="post" action="source/bbs-controller?type=<?php echo $_GET['page']; ?>" enctype="multipart/form-data">
    <div class="form-group">
        <label for="title">パスワードをもう一度入力</label>
        <input type="password" class="form-control" name="password_confirm" maxlength="4" value="" placeholder="数字4桁">
    </div>
    <!-- hidden group -->
    <?php if( !empty($_POST['bbs_id']) ) : ?><input type="hidden" name="bbs_id" value="<?php echo $_POST['bbs_id']; ?>"><?php endif; ?>
    <?php if( !empty($_GET['bbs_id']) ) : ?><input type="hidden" name="bbs_id" value="<?php echo $_GET['bbs_id']; ?>"><?php endif; ?>

    <?php if( !empty($_POST['thread_id']) ) : ?><input type="hidden" name="thread_id" value="<?php echo $_POST['thread_id']; ?>"><?php endif; ?>
    <?php if( !empty($_GET['thread_id']) ) : ?><input type="hidden" name="thread_id" value="<?php echo $_GET['thread_id']; ?>"><?php endif; ?>

    <?php if( !empty($_POST['article_id']) ) : ?><input type="hidden" name="article_id" value="<?php echo $_POST['article_id']; ?>"><?php endif; ?>
    <?php if( !empty($_GET['article_id']) ) : ?><input type="hidden" name="article_id" value="<?php echo $_GET['article_id']; ?>"><?php endif; ?>

    <input type="hidden" name="title" value="<?php echo $data->get_title(); ?>">
    <input type="hidden" name="content" value="<?php echo $data->get_content(); ?>">
    <input type="hidden" name="chara_id" value="<?php echo $charainfo->get_chara_id(); ?>">
    <!-- submit -->
    <div class="form-group">
        <button type="submit" class="btn btn-light" name="submit" value="<?php echo $page; ?>"><?php echo $page; ?></button>
    </div>
</form>
</main>
<?php
// ヘッダーを表示
display_footer();
