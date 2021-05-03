<!doctype html>
<html lang="ja">
  <head>
    <meta charset="utf-8">
    <meta name="robots" content="noindex,nofollow,noarchive" />
    <title>ADMIN</title>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.2/css/all.css" integrity="sha384-oS3vJWv+0UjzBfQzYUhtDYW+Pj2yciDJxpsK1OYPAYjqT085Qq/1cq5FLXAZQ7Ay" crossorigin="anonymous">
    <!-- Bootstrap core CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link href="https://use.fontawesome.com/releases/v5.6.1/css/all.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
    <style>
      .bd-placeholder-img {
        font-size: 1.125rem;
        text-anchor: middle;
        -webkit-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;
      }

      @media (min-width: 768px) {
        .bd-placeholder-img-lg {
          font-size: 3.5rem;
        }
      }
    </style>
    <!-- Custom styles for this template -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
  </head>
<body>
<?php
// インポート
require_once( 'source/client.php' );
require_once( 'source/defined.php' );
require_once( 'view/display-item.php' );

// 表示画面を分岐する
$pass = "";
if( !empty( $_POST ) ) {
  if( $_POST["security_code"] === ADMIN_PASS ) {
    // ADMIN用viewに転送
    admin_menu();
  }else{
    // エラーページに転送
    header( 'Location: admin?status=error' );
  }
}else{
  // ログイン用viewを表示
  display_login();
}

// ログイン用view
function display_login() {
?>
<main role="main" class="container">
<?php
if( !empty( $_GET['status'] ) ) {
    e_message( $_GET['status'] ); // エラーメッセージ
}
?>
<div class="card mx-5 mt-5">
  <div class="card-body">
  <h2 class="card-title text-info" style="text-align: center;">ADMIN PASSWORD</h2>
  <form action="admin" method="post" enctype="multipart/form-data">
  <div class="form-group">
    <input type="security_code" class="form-control" name="security_code">
    <p class="text-center text-light">GUEST CODE：<?php echo PASS_GUEST; ?></p>
  </div>
  <button type="submit" class="btn btn-outline-info mx-auto d-block" name="submit">SUBMIT</button>
  </form>
  </div>
</div>
</main>
<?php
}

// ADMIN用MENU
function admin_menu() {
// サイドごとのリスト情報を取得する
$list_a = get_side_list( SIDE_A );
$list_b = get_side_list( SIDE_B );
?>
<main role="main" class="container">
<?php
if( !empty( $_GET['status'] ) ) {
    e_message( $_GET['status'] ); // エラーメッセージ
}
?>
<div class="card m-3">
  <div class="card-body">
  　<h3 class="card-title">PROFILE EDIT</h3>
    <p>
    修正したいプロフィールを選択してください。
    <ul>
      <?php foreach( $list_a as $item ): ?>
      <li><a href="profile-edit.php?type=modify&chara_id=<?php echo $item->get_chara_id(); ?>"><?php echo $item->get_pc_name(); ?></a></li>
      <?php endforeach; ?>
      <?php foreach( $list_b as $item ): ?>
      <li><a href="profile-edit.php?type=modify&chara_id=<?php echo $item->get_chara_id(); ?>"><?php echo $item->get_pc_name(); ?></a></li>
      <?php endforeach; ?>
    </ul>
    </p>
  </div>
</div>
</main>
<?php
}
?>
<script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
</body>
</html>