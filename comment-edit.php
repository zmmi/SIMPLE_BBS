<?php
// インポート
require_once( 'header-footer.php' );
require_once( 'view/display-item.php' );
require_once( 'source/client.php' );
// 初期化
$chara_id = null;
if( !empty($_GET['chara_id']) ) {
  $chara_id = $_GET['chara_id'];
}
// comment
$comment = get_comment( $chara_id );
?>
<?php display_header( 'REGISTER' ); ?>
<main role="main" class="container">
<?php
if( !empty( $_GET['status'] ) ) {
    e_message( $_GET['status'] ); // エラーメッセージ
}
?>
<div class="card m-3">
  <div class="card-body">
  <h3 class="card-title">COMMENT</h3>
    <form action="source/comment-controller" method="post" enctype="multipart/form-data">
    <textarea class="form-control m-1" name="comment" rows="5"><?php echo $comment->get_comment(); ?></textarea>
    <label for="pl_name" class="col-sm-2 col-form-label">パスワード</label>
    <input type="password" class="form-control m-1" name="password_confirm" maxlength="4" value="">
    <input type="hidden" name="comment_id" value="<?php echo $comment->get_comment_id(); ?>">
    <input type="hidden" name="chara_id" value="<?php echo $_GET['chara_id']; ?>">
    <button type="submit" class="btn btn-light m-1" name="submit" value="send">保存</button>
    </form>
  </div>
</div>