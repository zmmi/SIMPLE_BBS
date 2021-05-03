<?php
// インポート
require_once( 'header-footer.php' );
require_once( 'view/display-item.php' );
require_once( 'source/client.php' );
// 変数の初期化
$status = '';
$chara_id = '';
$type = '';
// ヘッダーを表示
display_header( 'BOARD' );
// mainタグ
?>
<main role="main" class="container">
<?php
// エラーメッセージ
if( !empty( $_GET['status'] ) ) {
    e_message( $_GET['status'] );
}
// chara_idの読み込み
if( !empty( $_GET['chara_id'] ) ) {
    $chara_id = $_GET['chara_id'];
}
// typeの読み込み
if( !empty( $_GET['type'] ) ) {
    $type = $_GET['type'];
}
// キャラクター情報の抽出
$charainfo = get_charainfo( $chara_id );
// コメント情報の抽出
$comment = get_comment( $chara_id );
?>
<div class="d-flex align-content-around flex-column bd-highlight mt-3 mx-auto w-75">
        <div class="d-flex bd-highlight m-1"><!-- name and image -->
            <img src="<?php echo $charainfo->get_icon_url(); ?>" class="align-self-start mr-3" alt="<?php echo $charainfo->get_chara_id(); ?>">
            <div class="p-1 w-100 bg-white">
                <p class="h2 profile-name" style="color:<?php echo $charainfo->get_color_code(); ?>;"><?php echo $charainfo->get_pc_name(); ?></p>
                <p class="profile-spell"><?php echo $charainfo->get_pc_name_spell(); ?></p> 
            </div>
        </div>
        <div class="profile-comment m-2 p-3 border"><!-- comment -->
            <font color="<?php echo $charainfo->get_color_code(); ?>">
            <p><?php echo $comment->get_comment(); ?></p>
            </font>
            <span style="margin: 0 0 0 auto;float: right;">
              <a href="comment-edit?chara_id=<?php echo $charainfo->get_chara_id(); ?>"><i class="fas fa-edit"></i></a>
            </span>
        </div>
        <div class="d-flex bd-highlight mx-1"><!-- column1 -->
            <div class="profile-item m-1 p-3 w-100 border bg-light">Gender: <?php echo $charainfo->get_gender(); ?></div>
            <div class="profile-item m-1 p-3 w-100 border bg-light">Side: <?php echo $charainfo->get_side(); ?></div>
        </div>
        <div class="d-flex bd-highlight mx-1"><!-- column2 -->
            <div class="profile-item m-1 p-3 w-100 border bg-light">color: <font color="<?php echo $charainfo->get_color_code(); ?>"><?php echo $charainfo->get_color_name(); ?></font></div>
            <div class="profile-item m-1 p-3 w-100 border bg-light">Height: <?php echo $charainfo->get_height(); ?>cm</div>
        </div>
        <div class="m-2 p-3 bg-white border rounded"><!-- profile -->
            <p class="text-secondary"><?php echo $charainfo->get_profile(); ?></p>
        </div>
        <?php if( !isset( $_GET['page']) ): ?>
        <div class="btn-group btn-group-sm ml-auto" role="group" aria-label="First group">
            <button type="button" class="btn btn-light" onclick="location.href='profile-edit.php?type=modify&chara_id=<?php echo $charainfo->get_chara_id(); ?>'" disabled>Edit</button>
        </div>
        <br /><br />
        </div>
        <?php endif; ?>
    </div>
    <!-- Preview decade -->
    <?php if( !empty( $_GET['page'] ) && $_GET['page']  === 'preview' ): ?>
    <form class="m-3" method="post" action="source/profile-controller" enctype="multipart/form-data">
      <input type="hidden" name="chara_id" value="<?php echo $charainfo->get_chara_id(); ?>">
      <input type="hidden" name="password" value="<?php echo $charainfo->get_password(); ?>">
      <input type="hidden" name="pl_name" value="<?php echo $charainfo->get_pl_name(); ?>">
      <input type="hidden" name="mail_address" value="<?php echo $charainfo->get_mail_address(); ?>">
      <input type="hidden" name="pc_name" value="<?php echo $charainfo->get_pc_name(); ?>">
      <input type="hidden" name="pc_name_spell" value="<?php echo $charainfo->get_pc_name_spell(); ?>">
      <input type="hidden" name="gender" value="<?php echo $charainfo->get_gender(); ?>">
      <input type="hidden" name="side" value="<?php echo $charainfo->get_side(); ?>">
      <input type="hidden" name="color_name" value="<?php echo $charainfo->get_color_name(); ?>">
      <input type="hidden" name="color_code" value="<?php echo $charainfo->get_color_code(); ?>">
      <input type="hidden" name="height" value="<?php echo $charainfo->get_height(); ?>">
      <input type="hidden" name="profile" value="<?php echo $charainfo->get_profile(); ?>">
      <input type="hidden" name="icon_url" value="<?php echo $charainfo->get_icon_url(); ?>">
      <div class="form-group row">
        <label for="pl_name" class="col-sm-2 col-form-label">パスワードをもう一度入力</label>
        <div class="col-sm-10">
            <input type="password" class="form-control" name="password_confirm" value="">
          </div>
      </div>
      <div class="form-group row">
        <button type="submit" class="btn btn-light m-1" name="submit" value="back">戻る</button>
        <button type="submit" class="btn btn-light m-1" name="submit" value="<?php echo $type; ?>">登録</button>
      </div>
    </form>
    <?php endif; ?>
</main>
<?php
// ヘッダーを表示
display_footer();
