<?php
$current_user = wp_get_current_user();
if ( 0 == $current_user->ID ) {
  wp_redirect(home_url('/wp-login.php'));
  exit;
}
?>
<?php 
if (!in_array( 'operator', $current_user->roles ) ){
  $url = home_url( '/admin/?page=order-manage' );
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
  <section id="invoice-print" class="invoice" style="width: 400px;">
    <?php 
    $orders = explode(',', @$_GET['string']);
    $n = 0;
    ?>
    <?php foreach($orders as $order) : ?>
      <?php $merchant_id = get_post_meta( $order, '_mos_courier_merchant_name', true ); ?>
      <?php if ($n != 0) : ?>
        <p style="page-break-before: always;"></p>
      <?php endif ?>
      <div class="text-center">
      <?php $logo =get_option('mos_courier_options')['clogo'];?>
      <?php if ($logo) : ?>
        <img class="mb-1" src="<?php echo $logo ?>" width="100" height="100">
      <?php endif; ?>
        <h4><?php echo get_option('mos_courier_options')['cname']; ?></h4>
        <h5><?php echo get_option('mos_courier_options')['address']; ?></h5>
        <h6><?php echo get_option('mos_courier_options')['website']; ?></h6>
        <h6><?php echo get_option('mos_courier_options')['phone']; ?></h6>
      </div>
      <p><strong>Pickup Information</strong></p>
      <table class="table table-bordered">
        <tr>
          <td>Pickup request Date</td>
          <td><?php echo get_post_meta( $order, '_mos_courier_booking_date', true ); ?></td>
        </tr> 
        <tr>
        <tr>
          <td>Merchant Name</td>
          <td><?php echo get_userdata(get_post_meta( $order, '_mos_courier_merchant_name', true ))->display_name;?></td>
        </tr>
        <tr>
          <td>Merchant Phone</td>
          <?php 
          if (get_post_meta( $order, '_mos_courier_merchant_number', true )) {
            $phone = get_post_meta( $order, '_mos_courier_merchant_number', true );            
          } else{
            $phone = get_user_meta( $merchant_id, 'phone', true );
          }
          ?>
          <td><?php echo $phone; ?></td>
        </tr>
        <tr>
          <td>Brand Name</td>
          <td><?php echo get_user_meta( $merchant_id, 'brand_name', true ); ?></td>
        </tr>
        <tr>
          <td>Merchant Order ID</td>
          <td><?php echo $order ?></td>
        </tr>        
      </table>
      <p><strong>Delivery Information</strong></p>
      <table class="table table-bordered"> 
        <tr>
          <td>Customer Name</td>
          <td><?php echo get_post_meta( $order, '_mos_courier_receiver_name', true ); ?></td>
        </tr> 
        <tr>
          <td>Customer Phone</td>
          <td><?php echo get_post_meta( $order, '_mos_courier_receiver_number', true ); ?></td>
        </tr>
        <tr>
          <td>Customer Address</td>
          <td><?php echo get_post_meta( $order, '_mos_courier_receiver_address', true ); ?></td>
        </tr>
        <tr>
          <td>Delivery Zone</td>
          <td><?php echo get_post_meta( $order, '_mos_courier_delivery_zone', true ); ?></td>
        </tr>        
      </table>
      <p><strong>Product and Pricing Information</strong></p>
      <table class="table table-bordered"> 
        <tr>
          <td>Product Name</td>
          <td><?php echo get_post_meta( $order, '_mos_courier_product_name', true ); ?></td>
        </tr> 
        <tr>
          <td>Quantity</td>
          <td><?php echo get_post_meta( $order, '_mos_courier_product_quantity', true ); ?></td>
        </tr>
        <tr>
          <!-- <td>Price</td> -->
          <td>Collective Amount</td>
          <td><?php echo get_post_meta( $order, '_mos_courier_product_price', true ); ?></td>
        </tr>        
      </table>
      <p class="text-center mb-0 mt-2">
        <img src="<?php echo wp_upload_dir()["baseurl"].'/'.get_the_title( $order );?>.png"><br/>
        <span style="font-size: 10px"><?php echo get_the_title( $order ); ?></span>
      </p>
      <div class="d-table w-100 mt-3">
        <div class="float-left">Merchant Signeture</div>
        <div class="float-right">Customer Signeture</div>
      </div>
      <?php $n++; ?>
    <?php endforeach; ?>
  </section>
  <!-- /.content -->
</div>
<a href="<?php echo home_url( '/admin/?page=order-manage' ); ?>" class="btn btn-sm invisible-btn">Back to Home</a>
<!-- ./wrapper -->

  <!-- jQuery -->
  <script>
    window.print();
  </script>
</body>
</html>
