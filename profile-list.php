<?php 
require_once( 'header-footer.php' );
require_once( 'view/display-item.php' );
require_once( 'source/client.php' );
require_once( 'source/defined.php' );

// サイドごとのリスト情報を取得する
$human_list = get_side_list( SIDE_A );
$android_list = get_side_list( SIDE_B );
// ペアリストを取得する
$pair_list = get_pair_list();
// アルファベット順でソート
usort($human_list, function ($a, $b) {
  return strcmp( $a->get_pc_name_spell(), $b->get_pc_name_spell() );
});
usort($android_list, function ($a, $b) {
  return strcmp( $a->get_pc_name_spell(), $b->get_pc_name_spell() );
});
?>
<?php display_header( 'MEMBER' ); ?>

<main role="main" class="container p-3"> 
  <?php if( isset( $_GET['status'] ) ) e_message( $_GET['status'] ); ?>
  <!-- side A -->
  <h3 class="row border-bottom border-dark">Side <?php echo SIDE_A; ?></h3>
  <div class="d-flex flex-wrap bd-highlight mb-3">
    <?php foreach( $human_list as $item ): ?>
      <div class="card m-2 mx-auto pt-4 profile-card">
      <img src="<?php echo $item->get_icon_url(); ?>" class="card-img-top mx-auto" alt="..." style="width: 85px;height: 110px;">
      <div class="card-body">
        <h6 class="list-name" style="color:<?php echo $item->get_color_code(); ?>"><?php echo $item->get_pc_name(); ?></h6>
        <hr width="80%" noshade>
        <h5 class="text-center"><a href="profile?chara_id=<?php echo $item->get_chara_id(); ?>"><i class="fas fa-info-circle"></i></a></h5>
      </div>
    </div>
    <?php endforeach; ?>
  </div>
  <!-- side B -->
  <h3 class="row border-bottom border-dark">Side <?php echo SIDE_B; ?></h3>
  <div class="d-flex flex-wrap bd-highlight mb-3">
    <?php foreach( $android_list as $item ): ?>
    <div class="card m-2 mx-auto pt-4 profile-card">
      <img src="<?php echo $item->get_icon_url(); ?>" class="card-img-top mx-auto" alt="..." style="width: 85px;height: 110px;">
      <div class="card-body">
        <h6 class="list-name" style="color:<?php echo $item->get_color_code(); ?>"><?php echo $item->get_pc_name(); ?></h6>
        <hr width="80%" noshade>
        <h5 class="text-center"><a href="profile?chara_id=<?php echo $item->get_chara_id(); ?>"><i class="fas fa-info-circle"></i></a></h5>
      </div>
    </div>
    <?php endforeach; ?>
  </div>
  <br /><br />
  <!-- pair list -->
  <h3 class="row border-bottom border-dark">Pair List</h3>
  <table align="center" cellpadding="10">
    <?php foreach( $pair_list as $item ): ?>
    <?php 
    $item_a = get_charainfo( $item[0] );
    $item_b = get_charainfo( $item[1] );
    ?>
    <tr>
      <td align="center"><img src="<?php echo $item_a->get_icon_url(); ?>" /></td>
      <td align="center"><i class="fas fa-times fa-lg"></i></td>
      <td align="center"><img src="<?php echo $item_b->get_icon_url(); ?>" /></td>
    </tr>
    <tr>
      <td align="center"><?php echo $item_a->get_pc_name(); ?></td>
      <td align="center"></td>
      <td align="center"><?php echo $item_b->get_pc_name(); ?></td>
    </tr>
    <?php endforeach; ?>
  </table>
</main>
<?php
// ヘッダーを表示
display_footer();

