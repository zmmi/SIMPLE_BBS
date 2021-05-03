<?php
// インポート
require_once( 'header-footer.php' );
require_once( 'view/display-item.php' );
require_once( 'source/client.php' );
require_once( 'source/defined.php' );
// 初期値
$status = '';
$type = 'register';
$chara_id = '';
// プロフィール変更の場合は上書き
if( !empty( $_GET['type'] ) ) $type = $_GET['type'];
// chara_idの読み込み
if( !empty( $_GET['chara_id'] ) ) {
    $chara_id = $_GET['chara_id'];
}
// キャラクター情報の抽出
$charainfo = get_charainfo( $chara_id );
// viewの表示
?>
<?php display_header( 'REGISTER' ); ?>
<main role="main" class="container">
<?php
if( !empty( $_GET['status'] ) ) {
    e_message( $_GET['status'] ); // エラーメッセージ
}
?>
    <form class="m-3" name="profile" action="profile?type=<?php echo $type; ?>&page=preview" method="post" enctype="multipart/form-data">
        <div class="form-group row">
            <label for="pl_name" class="col-sm-2 col-form-label">PL名</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" name="pl_name" value="<?php echo $charainfo->get_pl_name(); ?>" placeholder="山田">
            </div>
        </div>
        <div class="form-group row">
            <label for="pl_name" class="col-sm-2 col-form-label">パスワード</label>
            <div class="col-sm-10">
                <input type="password" class="form-control" name="password" maxlength="4" value="" placeholder="数字4桁">
                <small class="form-text text-muted">※プロフィール修正やレス投稿で使用しますので忘れないようお願いします。</small>　
            </div>
        </div>
        <div class="form-group row">
            <label for="mail_address" class="col-sm-2 col-form-label">Twitter-ID / Mail Address</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" name="mail_address" value="<?php echo $charainfo->get_mail_address(); ?>">
            </div>
        </div>
        <div class="form-group row">
            <label for="pc_name" class="col-sm-2 col-form-label">PC名</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" name="pc_name" value="<?php echo $charainfo->get_pc_name(); ?>" placeholder="<?php echo PCNAME_NOTICE; ?>">
            </div>
        </div>
        <div class="form-group row">
            <label for="pc_name_spell" class="col-sm-2 col-form-label">PC名(spell)</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" name="pc_name_spell" value="<?php echo $charainfo->get_pc_name_spell(); ?>" placeholder="<?php echo PCNAME_SPELL_NOTICE; ?>">
            </div>
        </div>
        <div class="form-group row">
            <legend class="col-sm-2 col-form-label">性別</legend>
            <div class="col-sm-10">
                <div class="btn-group btn-group-toggle" data-toggle="buttons">
                    <label class="btn btn-light" for="A">
                        <input class="form-check-input" type="radio" autocomplete="off" name="gender" id="MALE" value="MALE" <?php if( $charainfo->get_gender()==='MALE' ) echo 'checked'; ?>>MALE
                    </label>
                    <label class="btn btn-light" for="B">
                        <input class="form-check-input" type="radio" autocomplete="off" name="gender" id="FEMALE" value="FEMALE" <?php if( $charainfo->get_gender()==='FEMALE' ) echo 'checked'; ?>>FEMALE
                    </label>
                </div>
            </div>
        </div>
        <div class="form-group row">
            <legend class="col-sm-2 col-form-label">サイド</legend>
            <div class="col-sm-10">
                <div class="btn-group btn-group-toggle" data-toggle="buttons">
                    <label class="btn btn-light" for="HUMAN">
                        <input class="form-check-input" type="radio" autocomplete="off" name="side" id="<?php echo SIDE_A; ?>" value="<?php echo SIDE_A; ?>" <?php if( $charainfo->get_side()==='HUMAN' ) echo 'checked'; ?>><?php echo SIDE_A; ?>
                    </label>
                    <label class="btn btn-light" for="ANDROID">
                        <input class="form-check-input" type="radio" autocomplete="off" name="side" id="<?php echo SIDE_B; ?>" value="<?php echo SIDE_B; ?>" <?php if( $charainfo->get_side()==='ANDROID' ) echo 'checked'; ?>><?php echo SIDE_B; ?>
                    </label>
                </div>
            </div>
        </div>
        <div class="form-group row">
            <label for="color_name" class="col-sm-2 col-form-label">カラー</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" name="color_name" value="<?php echo $charainfo->get_color_name(); ?>" placeholder="黒">
            </div>
        </div>
        <div class="form-group row">
            <label for="color_code" class="col-sm-2 col-form-label">カラーコード</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" name="color_code" value="<?php echo $charainfo->get_color_code(); ?>" placeholder="#000000">
            </div>
        </div>
        <div class="form-group row">
            <label for="height" class="col-sm-2 col-form-label">身長</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" name="height" value="<?php echo $charainfo->get_height(); ?>" placeholder="単位：cm（単位の記入は不要）">
            </div>
        </div>
        <div class="form-group row">
            <label for="inputEmail3" class="col-sm-2 col-form-label">性格備考</label>
            <div class="col-sm-10">
                <textarea class="form-control" name="profile" rows="10" placeholder="500文字上限"><?php echo $charainfo->get_profile(); ?></textarea>
            </div>
        </div>
        <?php if( !empty( $_GET['type']) && $_GET['type'] === 'modify' ): ?>
        <label><input id="hide_icon" type="checkbox" name="icon_url" value="<?php echo $charainfo->get_icon_url(); ?>">アイコンを更新しない</label>
        <?php endif; ?>
        <div class="form-group hide_update">
          <label for="exampleFormControlFile1">アイコン</label>
          <div class="col-sm-10">
              <input type="file" class="form-control-file" name="icon">
              <small id="passwordHelpBlock" class="form-text text-muted"><?php echo ICON_NOTICE; ?></small>
          </div>
        </div>
        <!-- 払い出したIDはPOSTデータとして引き継ぐ -->
        <input type="hidden" name="chara_id" value="<?php echo $charainfo->get_chara_id(); ?>">
        <div class="form-group row">
          <div class="col-sm-10">
            <button type="submit" class="btn btn-light" name="submit" value="confirm">確認</button>
          </div>
        </div>
    </form>
</main>
<script src="https://code.jquery.com/jquery-3.6.0.slim.js" integrity="sha256-HwWONEZrpuoh951cQD1ov2HUK5zA5DwJ1DNUXaM6FsY=" crossorigin="anonymous"></script>
<script type="text/javascript">
    $(function(){
        $('#hide_icon').change(function(){
            $('.hide_update').toggle();
        })
    });
</script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
<!-- Custom styles for this template -->
<script type="text/javascript" src="js/check-profile.js"></script>
</body>
</html>