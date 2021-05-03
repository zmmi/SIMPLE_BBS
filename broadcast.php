<?php
// インポート
require_once( 'header-footer.php' );
require_once( 'view/display-item.php' );
require_once( 'source/client.php' );
require_once( 'source/defined.php' );
// 全PCリストを取得する
$pc_list = get_pc_all_list();
// 全アラートリストを取得する
$alert_list = get_alert_list();
// 更新日順でソート
usort($alert_list, function ($a, $b) {
  return date_create_from_format("Y/m/d H:i", $a->get_post_date()) < date_create_from_format("Y/m/d H:i", $b->get_post_date());
});

display_header(); // ヘッダーを表示
?>
<body>
<main role="main" class="container">
<?php
if( !empty( $_GET['status'] ) ) {
    e_message( $_GET['status'] ); // エラーメッセージ
}
?>
<div class="card mx-3 mt-3 mb-5">
  <div class="card-body">
  <h3 class="card-title">BROADCAST</h3>
  <form action="source/alert-controller" method="post" enctype="multipart/form-data">
    <label for="alert" class="col-sm-2 col-form-label">お返事の状況</label>
    <select class="form-control m-1" name="alert">
      <option value="danger">レス期限を越えそう</option>
      <option value="warning">いつものペースより少し遅れそう</option>
    </select>
    <label for="remark" class="col-sm-2 col-form-label">お返事のめど</label>
    <input type="text" class="form-control m-1" name="remark" maxlength="15" placeholder="本日中 / 明日の朝まで / 来週いっぱいまでペースダウン　等">
    <small class="form-text text-muted">※こちらは任意の項目です。もしお返事のめどがあれば、参考程度に簡単にご記入ください。</small>
    <label for="pc_name" class="col-sm-2 col-form-label">PC名</label>
    <select class="form-control m-1" name="chara_id">
      <?php foreach( $pc_list as $pc ): ?>
        <option value="<?php echo $pc->get_chara_id(); ?>"><?php echo $pc->get_pc_name(); ?></option>
      <?php endforeach; ?>
    </select>
    <label for="password_confirm" class="col-sm-2 col-form-label">パスワード</label>
    <input type="password" class="form-control m-1" name="password_confirm" maxlength="4" value="">
    <button type="submit" class="btn btn-light m-1" name="submit" value="send">送信</button>
    </form>
  </div>
</div>
<div class="mb-5">
<?php foreach( $alert_list as $alert ): ?>
<?php 
  $charainfo = get_charainfo( $alert->get_chara_id() ); 
  switch( $alert->get_alert() ) {
    case 'danger':
      $comment = "レス期限を越えそう";
      break;
    case 'warning':
      $comment = "いつものペースより少し遅れそう";
      break;
  } 
?>
  <div class="alert alert-<?php echo $alert->get_alert();?>" role="alert">
    <?php echo $charainfo->get_pc_name(); ?> PLさま | 
    <?php echo $comment; ?> |
    <?php echo $alert->get_remark(); ?> |
    <?php echo $alert->get_post_date(); ?>
  </div>
<?php endforeach; ?>
  <div class="alert alert-dark" role="alert">
    XXX PLさま | お返事の状況 | お返事のめど（任意） | 日時
  </div>
</div>
<br /><br /><br />
</main>
<?php
display_footer();