<?php
$current_user = wp_get_current_user();
if ( 0 == $current_user->ID ) {
  wp_redirect(home_url('/wp-login.php'));
  exit;
}
?>
<?php 
if (!in_array( 'operator', $current_user->roles ) ){
  $url = home_url( '/admin/?p=order-manage' );
  wp_redirect( $url );
  exit;
}
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Invoice</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- Bootstrap 4 -->

  <!-- Font Awesome -->
  <link rel="stylesheet" href="<?php echo plugin_dir_url( __FILE__ ) ?>adminlte/plugins/fontawesome-free/css/all.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <link rel="stylesheet" href="<?php echo plugin_dir_url( __FILE__ ) ?>css/mos-courier.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="<?php echo plugin_dir_url( __FILE__ ) ?>adminlte/dist/css/adminlte.min.css">

  <!-- Google Font: Source Sans Pro -->
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
  <style>
    .h1, .h2, .h3, .h4, .h5, .h6, h1, h2, h3, h4, h5, h6,.table{margin-bottom: 5px;}
    .table td, .table th{padding: 0;}
    @media print {
      .invisible-btn {
        opacity: 0;
        filter: alpha(opacity=0);
      }
    }
  </style>
</head>
<body>
<div class="wrapper">
  <!-- Main content -->
  <section id="bill-print" class="invoice">
    <?php 
    $total_paid = 0;
    $orders = explode(',', @$_GET['string']);
    $Commission = @$_GET['c'];
    $n = 1;
    ?>
      <div class="text-center">
      <?php $logo =get_option('mos_courier_options')['clogo'];?>
      <?php if ($logo) : ?>
        <img class="mb-1" src="<?php echo $logo ?>" width="100" height="100">
      <?php endif; ?>
        <h4><?php echo get_option('mos_courier_options')['cname']; ?></h4>
        <h5><?php echo get_option('mos_courier_options')['address']; ?></h5>
        <h6><?php echo get_option('mos_courier_options')['website']; ?></h6>
        <h6><?php echo get_option('mos_courier_options')['phone']; ?></h6>
        <?php $delivery_man = get_post_meta( $orders['0'], '_mos_courier_delivery_man', true ); ?>
        <h6>Receiver Name: <?php echo get_user_meta( $delivery_man, 'brand_name', true ); ?></h6>
        <h6>Date: <?php echo date('d/m/Y'); ?></h6>
      </div>
    <table class="table table-bordered"> 
      <tr>
        <th>ID</th>
        <th>CL NO</th>
        <th>Remarks</th>
        <th>Status</th>
        <th>Payable Amount</th>
        <th>Paid Amount</th>
      </tr>
    <?php foreach($orders as $order) : ?>  
      <?php
        $product_price = get_post_meta( $order, '_mos_courier_product_price', true );
        $paid_amount = get_post_meta( $order, '_mos_courier_paid_amount', true );
        $total_paid = $total_paid + $paid_amount;
        $remarks = get_post_meta( $order, '_mos_courier_remarks', true );
        $status = get_post_meta( $order, '_mos_courier_delivery_status', true );
      ?>    
      <tr>
        <td><?php echo $n ?></td>
        <td><?php echo get_the_title($order); ?></td>
        <td><?php echo $remarks ?></td>
        <td><?php echo $status ?></td>
        <td><?php echo $product_price ?></td>
        <td><?php echo $paid_amount ?></td>
      </tr>
        
      <?php $n++; ?>
    <?php endforeach; ?>
      <tr>
        <th colspan="5">Sub Total</th>
        <th><?php echo $total_paid ?></th>
      </tr>
      <tr>
        <th colspan="5">Commission</th>
        <th><?php echo $Commission ?></th>
      </tr>
      <tr>
        <th colspan="5">Total</th>
        <th><?php echo $total_paid - $Commission ?></th>
      </tr>
    </table>
    <div class="d-table mt-5 w-100">
      <div class="d-table-cell text-left">Company Signiture</div>
      <div class="d-table-cell text-right">Receiver Signiture</div>
    </div>
  </section>
  <!-- /.content -->
</div>
<a href="<?php echo home_url( '/admin/?p=order-manage' ); ?>" class="btn btn-sm invisible-btn">Back to Home</a>
<!-- ./wrapper -->

  <!-- jQuery -->
  <script>
    window.print();
  </script>
</body>
</html>
