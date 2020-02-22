<?php
add_action('courier_content', 'courier_dashboard_content', 10, 1 );
if (!function_exists('courier_dashboard_content')) {
	function courier_dashboard_content($args) {
		if ( $args == 'dashboard') :
			global $wpdb;
			$current_user = wp_get_current_user();
			$user_role = get_user_meta( get_current_user_id(), 'true', true );
			if(in_array( 'operator', $current_user->roles )) :
			?>
					<input type="hidden" name="firechart" id="firechart" value="1">
					<div class="row">
						<div class="col-lg-3 col-6">
							<!-- small box -->
							<div class="small-box bg-info">
								<div class="inner">
								<?php $order_count = $wpdb->get_var( "SELECT COUNT(*) FROM {$wpdb->prefix}posts WHERE post_type='courierorder'" ); ?>
									<h3><?php echo $order_count ?></h3>
									<p>Total order</p>
								</div>
							</div>
						</div>
						<!-- ./col -->
						<div class="col-lg-3 col-6">
							<!-- small box -->
							<div class="small-box bg-success">
								<div class="inner">
								<?php $delivered_count = $wpdb->get_var( "SELECT COUNT(*) FROM {$wpdb->prefix}postmeta WHERE meta_value='delivered'" );?>
									<h3><?php echo @$delivered_count ?></h3>
									<p>Total delivery</p>
								</div>
							</div>
						</div>
						<!-- ./col -->
						<div class="col-lg-3 col-6">
							<!-- small box -->
							<div class="small-box bg-warning">
								<div class="inner">
								<?php $hold_count = $wpdb->get_var( "SELECT COUNT(*) FROM {$wpdb->prefix}postmeta WHERE meta_value='hold'" );?>
									<h3><?php echo @$hold_count ?></h3>
									<p>Total hold</p>
								</div>
							</div>
						</div>
						<!-- ./col -->
						<div class="col-lg-3 col-6">
							<!-- small box -->
							<div class="small-box bg-danger">
								<div class="inner">
								<?php $returned_count = $wpdb->get_var( "SELECT COUNT(*) FROM {$wpdb->prefix}postmeta WHERE meta_value='returned'" );?>
									<h3><?php echo @$returned_count ?></h3>
									<p>Total Return</p>
								</div>
							</div>
						</div>
						<!-- ./col -->
						<div class="col-6">
							<!-- small box -->
							<div class="small-box bg-warning">
								<div class="inner">
								<?php
								$results = $wpdb->get_results( "SELECT SUM(meta_value) AS total FROM {$wpdb->prefix}postmeta WHERE meta_key = '_mos_courier_paid_amount'", OBJECT );
								?>
									<h3><?php echo number_format($results[0]->total,2,".",","); ?></h3>
									<p>Collective amount</p>
								</div>
							</div>
						</div>
						<!-- ./col -->
						<div class="col-6">
							<!-- small box -->
							<div class="small-box bg-danger">
								<div class="inner">
								<?php
								$total_paid = 0;
								$results = $wpdb->get_results( "SELECT SUM(meta_value) AS total FROM {$wpdb->prefix}postmeta WHERE meta_key = '_mos_courier_paid_amount'", OBJECT );
								$results = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}postmeta WHERE meta_key = '_mos_courier_payments'", OBJECT );
								// var_dump($results[0]->post_id);
								foreach($results as $result){
									$payments = get_post_meta( $result->post_id, '_mos_courier_payments', true );
									if (@$payments){
								        foreach($payments as $date => $paid_amount){
								        	$total_paid = $total_paid + intval($paid_amount);
								        }							        	
							        }
								}
								/*$args = array(
									'post_type' => 'courierorder',
									'posts_per_page' => -1,
									// 'meta_key'   => '_mos_courier_paid_amount',
									// 'meta_value' => true
								);
								$query = new WP_Query( $args );
								$total_paid = 0;
								if ( $query->have_posts() ) {
								    while ( $query->have_posts() ) {
								        $query->the_post();
								        $payments = get_post_meta( get_the_ID(), '_mos_courier_payments', true );
								        if (@$payments){
									        foreach($payments as $date => $paid_amount){
									        	$total_paid = $total_paid + intval($paid_amount);
									        }							        	
								        }
	
								    }
								}
								wp_reset_postdata();*/
								?>
									<h3><?php echo number_format($total_paid,2,".",","); ?></h3>
									<p>Pay bill</p>
								</div>
							</div>
						</div>
						<!-- ./col -->
					</div>					
					
			<?php elseif (in_array( 'merchant', $current_user->roles )) : ?>
					<div class="row">
						<div class="col-lg-3 col-6">
							<!-- small box -->
							<div class="small-box bg-info">
								<div class="inner">
								<?php
								$args = array(
									'post_type' => 'courierorder',
									'posts_per_page' => -1,
								    'meta_query' => array(
								        'relation' => 'AND',
								        'merchant_name' => array(
								            'key' => '_mos_courier_merchant_name',
								            'value' => $current_user->ID,
								        ) 
								    ),
								);
								$query = new WP_Query( $args );
								$total_post = $query->post_count;
								wp_reset_postdata();
								?>
									<h3><?php echo @$total_post ?></h3>
									<p>Total order</p>
								</div>
							</div>
						</div>
						<!-- ./col -->

						<div class="col-lg-3 col-6">
							<!-- small box -->
							<div class="small-box bg-success">
								<div class="inner">
								<?php
								$args = array(
									'post_type' => 'courierorder',
									'posts_per_page' => -1,
								    'meta_query' => array(
								        'relation' => 'AND',
								        'merchant_name' => array(
								            'key' => '_mos_courier_merchant_name',
								            'value' => $current_user->ID,
								        ),
								        'delivery_status' => array(
								            'key' => '_mos_courier_delivery_status',
								            'value' => 'delivered',
								        ), 
								    ),
								);
								$query = new WP_Query( $args );
								$total_post = $query->post_count;
								wp_reset_postdata();
								?>
									<h3><?php echo @$total_post ?></h3>
									<p>Total delivery</p>
								</div>
							</div>
						</div>
						<!-- ./col -->
						<div class="col-lg-3 col-6">
							<!-- small box -->
							<div class="small-box bg-warning">
								<div class="inner">
								<?php
								$args = array(
									'post_type' => 'courierorder',
									'posts_per_page' => -1,
								    'meta_query' => array(
								        'relation' => 'AND',
								        'merchant_name' => array(
								            'key' => '_mos_courier_merchant_name',
								            'value' => $current_user->ID,
								        ),
								        'delivery_status' => array(
								            'key' => '_mos_courier_delivery_status',
								            'value' => 'hold',
								        ), 
								    ),
								);
								$query = new WP_Query( $args );
								$total_post = $query->post_count;
								wp_reset_postdata();
								?>
									<h3><?php echo @$total_post ?></h3>
									<p>Total hold</p>
								</div>
							</div>
						</div>
						<!-- ./col -->
						<div class="col-lg-3 col-6">
							<!-- small box -->
							<div class="small-box bg-danger">
								<div class="inner">
								<?php
								$args = array(
									'post_type' => 'courierorder',
									'posts_per_page' => -1,
								    'meta_query' => array(
								        'relation' => 'AND',
								        'merchant_name' => array(
								            'key' => '_mos_courier_merchant_name',
								            'value' => $current_user->ID,
								        ),
								        'delivery_status' => array(
								            'key' => '_mos_courier_delivery_status',
								            'value' => 'returned',
								        ), 
								    ),
								);
								$query = new WP_Query( $args );
								$total_post = $query->post_count;
								wp_reset_postdata();
								?>
									<h3><?php echo @$total_post ?></h3>
									<p>Total Return</p>
								</div>
							</div>
						</div>
						<!-- ./col -->
						<div class="col-6">
							<!-- small box -->
							<div class="small-box bg-warning">
								<div class="inner">
								<?php
								$args = array(
									'post_type' => 'courierorder',
									'posts_per_page' => -1,
								    'meta_query' => array(
								        'relation' => 'AND',
								        'merchant_name' => array(
								            'key' => '_mos_courier_merchant_name',
								            'value' => $current_user->ID,
								        ) 
								    ),
									// 'meta_key'   => '_mos_courier_paid_amount',
									// 'meta_value' => true
								);
								$query = new WP_Query( $args );
								$total_collect = 0;
								if ( $query->have_posts() ) {
								    while ( $query->have_posts() ) {
								        $query->the_post();
								        $paid_amount = get_post_meta( get_the_ID(), '_mos_courier_paid_amount', true );
								        $total_collect = $total_collect + intval($paid_amount);
								    }
								}
								wp_reset_postdata();
								?>
									<h3><?php echo number_format($total_collect,2,".",","); ?></h3>
									<p>Tolal Bill</p>
								</div>
							</div>
						</div>
						<!-- ./col -->
						<div class="col-6">
							<!-- small box -->
							<div class="small-box bg-danger">
								<div class="inner">
								<?php
								$args = array(
									'post_type' => 'courierorder',
									'posts_per_page' => -1,
								    'meta_query' => array(
								        'relation' => 'AND',
								        'merchant_name' => array(
								            'key' => '_mos_courier_merchant_name',
								            'value' => $current_user->ID,
								        ) 
								    ),
								);
								$query = new WP_Query( $args );
								$total_paid = 0;
								if ( $query->have_posts() ) {
								    while ( $query->have_posts() ) {
								        $query->the_post();
								        $payments = get_post_meta( get_the_ID(), '_mos_courier_payments', true );
								        if (@$payments){
									        foreach($payments as $date => $paid_amount){
									        	$total_paid = $total_paid + intval($paid_amount);
									        }
									    }
								    }
								}
								wp_reset_postdata();
								?>
									<h3><?php echo number_format(intval($total_paid),2,".",","); ?></h3>
									<p>Get Paid</p>
								</div>
							</div>
						</div>
						<!-- ./col -->
					</div>
			<?php endif; ?>	
			<?php
		endif;
	}
}
add_action('courier_content', 'courier_transaction_content', 10, 1 );
if (!function_exists('courier_transaction_content')) {
	function courier_transaction_content($args) {
		if ( $args == 'transaction') :

	    	global $wpdb;
	    	$table_name = $wpdb->prefix.'expence';	
	    	$maxdate = date('Y-m-d');
	    	$mindate = date('Y-m-d', strtotime('-29 days'));	    		
			$maxdatebtn = date("F j, Y");
			$mindatebtn = date("F j, Y", strtotime('-29 days'));
			if (@$_POST['addtransaction']){
		    	global $wpdb;
		    	$table_name = $wpdb->prefix.'expence';
				if (@$_POST['id']){
					// Update Table
					$wpdb->update( 
						$table_name, 
						array( 
							'author' => get_current_user_id(),  
							'title' => $_POST['title'], 
							'description' => $_POST['description'],
							'type' => $_POST['type'],
							'amount' => $_POST['amount'],
						), 
						array( 'ID' => $_POST['id'] )
					);
				}
				else{
		    		$wpdb->insert( 
						$table_name, 
						array( 
							'author' => get_current_user_id(), 
							'date' => date("Y-m-d"), 
							'title' => $_POST['title'], 
							'description' => $_POST['description'],
							'type' => $_POST['type'],
							'amount' => $_POST['amount'],
							'editable' => true
						) 
					);
				}
			}
	    	if (@$_POST['datechange']){
	    		if ($_POST['daterangevalue']){
		    		$slice = explode('|',$_POST['daterangevalue']);
			    	$maxdate = $slice[1];
			    	$mindate = $slice[0];

			    	$date=date_create($maxdate);
					$maxdatebtn = date_format($date,"F j, Y");
			    	$date=date_create($mindate);
					$mindatebtn = date_format($date,"F j, Y");
		    	}
		    }
	    	$results = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}expence WHERE date BETWEEN '{$mindate}' AND '{$maxdate}'", OBJECT );
	    	// var_dump($results);
			?>
			<!-- Modal -->
			<div class="modal fade" id="transactionModal" tabindex="-1" role="dialog" aria-labelledby="transactionModalLabel" aria-hidden="true">
				<div class="modal-dialog" role="document">
					<div class="modal-content">
						<form class="needs-validation" novalidate method="post">
							<div class="modal-header">
							<h5 class="modal-title" id="transactionModalLabel">Add Transaction</h5>
								<button type="button" class="close" data-dismiss="modal" aria-label="Close">
									<span aria-hidden="true">&times;</span>
								</button>
							</div>
							<div class="modal-body">
								<div class="form-group">
									<input type="text" class="form-control" name="title" id="title" placeholder="Title" required>
								</div>
								<div class="form-row">
									<div class="form-group col-md-6">
										<div class="form-check form-check-inline">
											<input class="form-check-input" type="radio" name="type" id="typecashout" value="cashout" checked>
											<label class="form-check-label" for="typecashout">Cashout</label>
										</div>
										<div class="form-check form-check-inline">
											<input class="form-check-input" type="radio" name="type" id="typecashin" value="cashin">
											<label class="form-check-label" for="typecashin">Cashin</label>
										</div>
									</div>
									<div class="form-group col-md-6">								
										<input type="number" class="form-control" name="amount" id="amount" placeholder="Amount" min="1" value="1" required>								
									</div>
								</div>
								<div class="form-group">
									<textarea class="form-control" name="description" id="description" rows="3" placeholder="Description"></textarea>
								</div>
							</div>
							<div class="modal-footer">
								<button type="button" class="btn btn-sm btn-danger" data-dismiss="modal">Close</button>
								<button name="addtransaction" value="1" type="submit" class="btn btn-sm btn-success">Save changes</button>
								<input type="hidden" id="id" name="id" value="0">
							</div>
						</form>
					</div>
				</div>
			</div>
            <div class="card card-info">
            	<div class="card-header">
            		<button type="button" class="btn btn-success transactionModal">+ Add New</button>
            		<div class="card-tools">
	            		<form method="post">
	            			<div class="input-group">
	            				<button type="button" class="btn btn-default float-right daterange-btn" id="daterange-btn">
	            					<i class="fa fa-calendar"></i> 
	            					<span><?php echo $mindatebtn ?> - <?php echo $maxdatebtn ?></span>
	            					<i class="fa fa-caret-down"></i>
	            				</button>
	            				<button class="btn btn-default" type="submit" name="datechange" value="1"><i class="fa fa-search"></i></button>
	            			</div>
	            			<input type="hidden" id="daterangevalue" name="daterangevalue" value="<?php echo $mindate .'|'.$maxdate; ?>">
	            		</form>
            		</div>
            	</div>
            	<div class="card-body">            		
					<table class="table">
						<thead>
							<tr>
								<th>SL</th>
								<th>Title</th>
								<th>Type</th>
								<th class="text-right">Amount</th>
							</tr>
						</thead>
						<tbody>
							<?php
							$total = 0;
							if($results):
								$n = 1;
								foreach($results as $result) :?>
							<tr>
								<td><?php echo $n ?></td>
								<td><span data-toggle="tooltip" data-placement="bottom" title="<?php echo $result->description ?>"><?php echo $result->title ?></span><?php if ($result->editable) : ?> <a class="transactionModal" href="javascript:void(0)" data-id="<?php echo $result->ID ?>" data-title="<?php echo $result->title ?>" data-amount="<?php echo $result->amount ?>" data-description="<?php echo $result->description ?>" data-type="<?php echo $result->type ?>"><i class="fa fa-edit"></i></a><?php endif; ?></td>
								<td><?php echo $result->type ?></td>
								<td class="text-right"><?php echo $result->amount ?></td>
							</tr>

								<?php 
									if ($result->type == 'cashin') $total = $total + $result->amount;
									else $total = $total - $result->amount;
									$n++;
								endforeach;
							endif;
							?>
						</tbody>
						<tfoot>
							<tr>
								<th>Total</th>
								<td colspan="3" class="text-right"><?php echo $total ?></td>
							</tr>
						</tfoot>
					</table>
            	</div>
        	</div>
			<?php
		endif;
	}
}
add_action('courier_content', 'courier_order_manage_content', 10, 1 );
if (!function_exists('courier_order_manage_content')) {
	function courier_order_manage_content($args) {
		if ( $args == 'order-manage') :
			$current_user = wp_get_current_user();
			$current_user_role = get_user_meta( get_current_user_id(), 'user_role', true );
			// var_dump($current_user_role);
			if (@$_GET['order-id']): 
				$post_id = $_GET['order-id'];
				if (($current_user->roles[0] == 'merchant' AND $current_user->ID == get_post_meta( $post_id, '_mos_courier_merchant_name', true )) OR $current_user->roles[0] == 'operator') : 
			?>
				<div class="card card-primary">
					<div class="card-header">
						<h3 class="card-title"><?php echo get_the_title($post_id)?></h3>
					</div>
					<div class="card-body">
						<div class="row">
							<div class="col-lg-6">
									<?php 
									$user_id = get_post_meta( $post_id, '_mos_courier_merchant_name', true );
									$address = get_post_meta( $post_id, '_mos_courier_merchant_address', true );
									$phone = get_post_meta( $post_id, '_mos_courier_merchant_phone', true );
									$display_name = get_userdata($user_id)->display_name;
									$brand = get_user_meta( $user_id, 'brand_name', true );
									?>
								<table class="table table-bordered">
									<tr>
										<th>Merchant Name:</th>
										<td class="text-right"><?php echo @$display_name ?></td>
									</tr>
									<tr>
										<th>Brand Name:</th>
										<td class="text-right"><?php echo @$brand ?></td>
									</tr>
									<tr>
										<th>Address:</th>
										<td class="text-right"><?php echo @$address ?></td>
									</tr>
									<tr>
										<th>Phone:</th>
										<td class="text-right"><?php echo @$phone ?></td>
									</tr>
									<tr>
										<th>Payment Date:</th>
										<td class="text-right"><?php echo @$payment_date ?></td>
									</tr>
								</table>
							</div>
							<div class="col-lg-6">
								<?php 
								$receiver_name = get_post_meta( $post_id, '_mos_courier_receiver_name', true );
								$receiver_address = get_post_meta( $post_id, '_mos_courier_receiver_address', true );
								$receiver_number = get_post_meta( $post_id, '_mos_courier_receiver_number', true );
								?>
								<table class="table table-bordered">
									<tr>
										<th>Receiver Name:</th>
										<td class="text-right"><?php echo @$receiver_name ?></td>
									</tr>
									<tr>
										<th>Receiver Address:</th>
										<td class="text-right"><?php echo @$receiver_address ?></td>
									</tr>
									<tr>
										<th>Receiver Phone:</th>
										<td class="text-right"><?php echo @$receiver_number ?></td>
									</tr>
								</table>
							</div>
						</div>
						<div class="row">
							<div class="col-lg-4">
								<?php 
								$product_name = get_post_meta( $post_id, '_mos_courier_product_name', true );
								$product_price = get_post_meta( $post_id, '_mos_courier_product_price', true );
								$product_quantity = get_post_meta( $post_id, '_mos_courier_product_quantity', true );
								$total_weight = get_post_meta( $post_id, '_mos_courier_total_weight', true );
								$packaging_type = get_post_meta( $post_id, '_mos_courier_packaging_type', true );
								$delivery_man = get_post_meta( $post_id, '_mos_courier_delivery_man', true );
								?>
								<table class="table table-bordered">
									<tr>
										<th>Product Name:</th>
										<td class="text-right"><?php echo @$product_name ?></td>
									</tr>
									<tr>
										<th>Product Price:</th>
										<td class="text-right"><?php echo @$product_price ?></td>
									</tr>
									<tr>
										<th>Product Quantity:</th>
										<td class="text-right"><?php echo @$product_quantity ?></td>
									</tr>
									<tr>
										<th>Packaging:</th>
										<td class="text-right"><?php echo @$packaging_type ?></td>
									</tr>
									<tr>
										<th>Delivered By:</th>
										<td class="text-right"><?php echo get_userdata($delivery_man)->display_name; ?></td>
									</tr>
								</table>								
							</div>
							<div class="col-lg-4">
								<?php 
								$delivery_charge = get_post_meta( $post_id, '_mos_courier_delivery_charge', true );
								$paid_amount = get_post_meta( $post_id, '_mos_courier_paid_amount', true );
								$delivery_status = get_post_meta( $post_id, '_mos_courier_delivery_status', true );
								$payment_status = get_post_meta( $post_id, '_mos_courier_payment_status', true );
								$payment_date = get_post_meta( $post_id, '_mos_courier_payment_date', true );
								$urgent_delivery = get_post_meta( $post_id, '_mos_courier_urgent_delivery', true );
								?>
								<table class="table table-bordered">
									<tr>
										<th>Delivery Charce:</th>
										<td class="text-right"><?php echo @$delivery_charge ?></td>
									</tr>
									<tr>
										<th>Paid Amount:</th>
										<td class="text-right"><?php echo @$paid_amount ?></td>
									</tr>
									<tr>
										<th>Delivery Status:</th>
										<td class="text-right"><?php echo @$delivery_status ?></td>
									</tr>
									<tr>
										<th>Payment Stgatus:</th>
										<td class="text-right"><?php echo @$payment_status ?></td>
									</tr>
									<tr>
										<th>Urgent Delivery:</th>
										<td class="text-right"><?php echo @$urgent_delivery ?></td>
									</tr>
								</table>								
							</div>
							<div class="col-lg-4">
								<?php 
								$booking_date = get_post_meta( $post_id, '_mos_courier_booking_date', true );
								$merchant_order_id = get_post_meta( $post_id, '_mos_courier_merchant_order_id', true );
								$delivery_zone = get_post_meta( $post_id, '_mos_courier_delivery_zone', true );
								?>
								<table class="table table-bordered">
									<tr>
										<th>Booking Date:</th>
										<td class="text-right"><?php echo @$booking_date ?></td>
									</tr>
									<tr>
										<th>Order ID:</th>
										<td class="text-right"><?php echo @$merchant_order_id ?></td>
									</tr>
									<tr>
										<th>CN Number:</th>
										<td class="text-right"><?php echo get_the_title($post_id) ?></td>
									</tr>
									<tr>
										<th>Area:</th>
										<td class="text-right"><?php echo @$delivery_zone ?></td>
									</tr>
									<tr>
										<td  colspan="2"><img class="img-fluid" src="<?php echo home_url('/wp-content/uploads/').get_the_title($post_id).'.png'; ?>"></td>
									</tr>
								</table>								
							</div>
						</div>
					</div>
				</div>
			<?php // else : wp_redirect(home_url('/admin/'));exit; ?>
			<?php endif; else : ?>
					<div class="modal modal-danger fade" id="modal-danger">
						<div class="modal-dialog">
							<div class="modal-content">
								<div class="modal-body">
									<p>Do you really like to delete these orders?</p>
									<div class="btn-group btn-group-sm" role="group">
										<button id="post-delete" type="button" class="btn btn-success">Yes</button>
										<button type="button" class="btn btn-danger" data-dismiss="modal">No</button>
									</div>
								</div>
							</div>
							<!-- /.modal-content -->
						</div>
						<!-- /.modal-dialog -->
					</div>
					<!-- /.modal -->
					<div class="modal modal-danger fade" id="order-desc">
						<div class="modal-dialog">
							<div class="modal-content">
								<div class="modal-body">
									<div class="result"></div>
									<button type="button" class="btn btn-danger btn-xs" data-dismiss="modal">Close</button>
								</div>
							</div>
							<!-- /.modal-content -->
						</div>
						<!-- /.modal-dialog -->
					</div>
					<!-- /.modal -->
					<div class="card card-primary">
						
						<form id="action_order_form" role="form" method="post" action="" class="needs-validation" novalidate>
							<?php wp_nonce_field( 'action_order_form', 'action_order_form_field' ); ?>
						<div class="card-header bg-white">
							<!-- <div class="form-inline">
								<div class="form-group mr-1">
									<select name="order_table_action" class="form-control" id="order_table_action" required> 
										<option value="">Bulk Actions</option>
										<option value="Print">Print</option>
										<option value="Delete">Delete</option>
									</select>
								</div>
								<button type="submit" class="btn btn-primary">Apply</button>
							</div> -->
							<div class="btn-group">
								<button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
									Action
								</button>
								<div class="dropdown-menu">
									<a target="_blank" href="<?php echo home_url()?>/invoice-print/?string=" class="dropdown-item order-print-btn">Print</a>
									<div class="dropdown-divider"></div>
									<a target="_blank" href="<?php echo home_url()?>/invoice-print/?type=pos&string=" class="dropdown-item order-pos-print-btn">POS Print</a>
								<?php if ($current_user->roles[0] == 'operator' AND $current_user_role!= 'Delivery Man') : ?>	
									<div class="dropdown-divider"></div>
									<a class="dropdown-item order-delete-btn" href="#">Delete</a>
								<?php endif; ?>
								</div>
							</div>
						</div>
						<!-- /.card-header -->
						<div class="card-body">
							<div class="table-responsive">
								<div class="container-fluid">										
								</div>
								<?php // var_dump($current_user_role)  ?>
								<table id="order-table<?php if ($current_user_role == 'Delivery Man') echo '-delivery-man' ?><?php if ($current_user->roles[0] == 'merchant') echo '-merchant' ?>" class="table table-bordered table-striped">
									<thead>
										<tr>
											<th class="no-sort"><input type="checkbox" id="checkAll" /></th>
											<th>#</th>
											<th>CN NO</th>
											<th>Booking Date</th>
											<th>Status</th>
											<th>Merchant Name</th>
											<th>Receiver Name</th>
											<th class="no-sort">Action</th>
										</tr>
									</thead>
								</table>
								
							</div>
							
						</div>
						<!-- /.card-body -->
						</form>
					</div>
			<?php
			endif;
		endif;
	}
}
add_action('courier_content', 'courier_order_edit_content', 10, 1 );
if (!function_exists('courier_order_edit_content')) {
	function courier_order_edit_content($args) {
		$id = @$_GET['id'];
		if ( $args == 'order-edit') :
			$merchants = mos_user_list('merchant');
			$options = get_option( 'mos_courier_options' );
			$zones = mos_str_to_arr($options['zone'], '|');
			$packaging = mos_str_to_arr($options['packaging'], '|');
			$current_user = wp_get_current_user();
			
			// var_dump($current_user);
			
			if (@$id) {
				$merchant_name = get_post_meta( $id, '_mos_courier_merchant_name', true );
				$order_id = get_post_meta( $id, '_mos_courier_order_id', true );
				$merchant_address = get_post_meta( $id, '_mos_courier_merchant_address', true );
				$merchant_number = get_post_meta( $id, '_mos_courier_merchant_number', true );
				$dzone = get_post_meta( $id, '_mos_courier_delivery_zone', true );
				$delivery_charge = get_post_meta( $id, '_mos_courier_delivery_charge', true );
				$product_name = get_post_meta( $id, '_mos_courier_product_name', true );
				$product_price = get_post_meta( $id, '_mos_courier_product_price', true );
				$product_quantity = get_post_meta( $id, '_mos_courier_product_quantity', true );
				$receiver_name = get_post_meta( $id, '_mos_courier_receiver_name', true );
				$receiver_address = get_post_meta( $id, '_mos_courier_receiver_address', true );
				$receiver_number = get_post_meta( $id, '_mos_courier_receiver_number', true );
				$total_weight = get_post_meta( $id, '_mos_courier_total_weight', true );
				$packaging_type = get_post_meta( $id, '_mos_courier_packaging_type', true );
				$delivery_status = get_post_meta( $id, '_mos_courier_delivery_status', true );
				$remarks = get_post_meta( $id, '_mos_courier_remarks', true );
				$urgent_delivery = get_post_meta( $id, '_mos_courier_urgent_delivery', true );
				$urgent_charge = get_post_meta( $id, '_mos_courier_urgent_charge', true );
			} 
			?>
			<?php if (@$_GET['msg'] == 'orderadded') :?>
				    <div class="alert alert-success alert-dismissible fade show" role="alert">
				        <strong>Done!</strong> Your order has been added. From here you can add new order.
				        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
				        <span aria-hidden="true">&times;</span>
				        </button>
				    </div>
			<?php endif; ?>
					<div class="card card-primary">
						<div class="card-header">
							<h3 class="card-title">Order Details</h3>
						</div>
						<!-- /.card-header -->
						<!-- form start -->
						<form role="form" method="post" action="" class="needs-validation" novalidate>
							<?php wp_nonce_field( 'edit_order_form', 'edit_order_form_field' ); ?>
							<?php if (@$id) : ?>
								<input type='hidden' name='order_id' value='<?php echo $id; ?>'/>
							<?php endif; ?>
							<div class="card-body">
							<?php if (in_array( 'operator', $current_user->roles ) ) : ?>
								<input type='hidden' name='usertype' value='operator'/>
								<div class="for-admin">
									<div class="form-row">
										<div class="col-lg-4">
											<div class="form-group">
												<label for="_mos_courier_merchant_name">Marchent Name</label>
												<select id="_mos_courier_merchant_name" name="_mos_courier_merchant_name" class="form-control" required>
													<option value="">---Select Marchent---</option>
												<?php foreach ($merchants as $key => $value) : ?>
													<!-- <option value="<?php echo $key ?>" <?php selected( $merchant_name, $key ); ?>><?php echo $value ?> (<?php echo get_user_meta( $key, 'brand_name', true ); ?>)</option> -->
													<option value="<?php echo $key ?>" <?php selected( $merchant_name, $key ); ?>><?php echo get_user_meta( $key, 'brand_name', true ); ?></option>
												<?php endforeach; ?>
												</select>
												<div class="valid-feedback">Valid.</div>
												<div class="invalid-feedback">Please fill out this field.</div>
											</div>								
										</div>
										<div class="col-lg-4">
											<div class="form-group">
												<label for="_mos_courier_merchant_address">Marchent Address</label>
												<input type="text" class="form-control" name="_mos_courier_merchant_address" id="_mos_courier_merchant_address" placeholder="Marchent Address" value="<?php echo @$merchant_address ?>">
											</div>
										</div>
										<div class="col-lg-4">
											<div class="form-group">
												<label for="_mos_courier_merchant_number">Marchent Number</label>
												<input type="text" class="form-control" name="_mos_courier_merchant_number" id="_mos_courier_merchant_number" placeholder="Marchent Number" value="<?php echo @$merchant_number ?>">
											</div>
										</div>
									</div>
								</div>
							<?php elseif(in_array( 'merchant', $current_user->roles ) ) :?>
								<?php $merchant_name = $current_user->ID ?>
								<input type="hidden" id="_mos_courier_merchant_name" value="<?php echo $merchant_name ?>">
								<input type="hidden" name="_mos_courier_total_weight" value="1">
								<?php if (!@$id) $delivery_charge = get_user_meta( $merchant_name, 'delivery_charge', true ); ?>
							<?php endif; ?>
								<div class="form-row">
									<div class="col-lg-6">
										<div class="form-group">
											<label for="_mos_courier_product_name">Product Name</label>
											<input type="text" class="form-control" name="_mos_courier_product_name" id="_mos_courier_product_name" placeholder="Product Name" value="<?php echo @$product_name ?>">
										</div>								
									</div>
									<div class="col-lg-6">
										<div class="form-group">
											<label for="_mos_courier_merchant_order_id">Order ID</label>
											<input type="text" class="form-control" name="_mos_courier_merchant_order_id" id="_mos_courier_merchant_order_id" placeholder="Order ID" value="<?php echo @$order_id ?>">
										</div>								
									</div>
								</div>
								<div class="form-row">
									<div class="col-lg-6">
										<div class="form-group">
											<label for="_mos_courier_product_price">Product Price</label>
											<input type="number" min="0" class="form-control" name="_mos_courier_product_price" id="_mos_courier_product_price" placeholder="Product Price" value="<?php echo @$product_price ?>">
											<div class="valid-feedback">Valid.</div>
											<div class="invalid-feedback">Please fill out this field.</div>
										</div>
									</div>
									<div class="col-lg-6">
										<div class="form-group">
											<label for="_mos_courier_product_quantity">Product Quantity</label>
											<input type="number" class="form-control" name="_mos_courier_product_quantity" id="_mos_courier_product_quantity" placeholder="Product Quantity" value="<?php echo @$product_quantity?>">
											<div class="valid-feedback">Valid.</div>
											<div class="invalid-feedback">Please fill out this field.</div>
										</div>
									</div>
								</div>
								<div class="form-row">
									<div class="col-lg-4">
										<div class="form-group">
											<label for="_mos_courier_receiver_name">Receiver Name</label>
											<input type="text" class="form-control" name="_mos_courier_receiver_name" id="_mos_courier_receiver_name" placeholder="Receiver Name"  value="<?php echo @$receiver_name?>" required>					
											<div class="valid-feedback">Valid.</div>
											<div class="invalid-feedback">Please fill out this field.</div>
										</div>								
									</div>
									<div class="col-lg-4">
										<div class="form-group">
											<label for="_mos_courier_receiver_address">Receiver Address</label>
											<input type="text" class="form-control" name="_mos_courier_receiver_address" id="_mos_courier_receiver_address" placeholder="Receiver Address" value="<?php echo @$receiver_address?>" required>					
											<div class="valid-feedback">Valid.</div>
											<div class="invalid-feedback">Please fill out this field.</div>
										</div>
									</div>
									<div class="col-lg-4">
										<div class="form-group">
											<label for="_mos_courier_receiver_number">Receiver Number</label>
											<input type="text" class="form-control" name="_mos_courier_receiver_number" id="_mos_courier_receiver_number" placeholder="Receiver Number" value="<?php echo @$receiver_number?>" required>					
											<div class="valid-feedback">Valid.</div>
											<div class="invalid-feedback">Please fill out this field.</div>
										</div>
									</div>
								</div>
								<div class="form-row">
									<div class="col-lg-4">
										<div class="form-group">
											<label for="_mos_courier_delivery_zone">Delivery Area</label>
											<select id="_mos_courier_delivery_zone" name="_mos_courier_delivery_zone" class="form-control select2" required> 
												<option value="">---Select Area---</option>
											<?php if (@$options['charge_setup']) : ?>
												<?php // foreach($zones as $zone) : ?>
												<?php foreach($options['charge_setup'] as $charge) : ?>
													<option data-rcharge="<?php echo $charge['regular']?>" data-acharge="<?php echo $charge['extra']?>" data-ucharge="<?php echo $charge['urgent']?>" <?php selected( $charge['zone-name'] . ' - ' .$charge['area-name'], $zone ) ?>><?php echo $charge['zone-name'] . ' - ' .$charge['area-name']; ?></option>
												<?php endforeach; ?>
											<?php endif;?>
											</select>
											<div class="valid-feedback">Valid.</div>
											<div class="invalid-feedback">Please fill out this field.</div>
										</div>
									</div>
									<div class="col-lg-4">
										<div class="form-group">
											<label for="_mos_courier_urgent_delivery">Urgent Delivery</label>
											<select class="form-control" name="_mos_courier_urgent_delivery" id="_mos_courier_urgent_delivery">
												<option value="no" <?php selected( $urgent_delivery, 'no' ); ?>>No</option>
												<option value="yes" <?php selected( $urgent_delivery, 'yes' ); ?>>Yes</option>
											</select>
										</div>
									</div>
									<div class="col-lg-4">
										<div class="form-group">
											<label for="_mos_courier_delivery_charge">Delivery Charge</label>
											<input type="number" readonly class="form-control-plaintext" name="_mos_courier_delivery_charge" id="_mos_courier_delivery_charge" placeholder="Delivery Charge" value="0" >
											<!-- <input type="hidden" class="dc" value="<?php // echo get_user_meta( $merchant_name, 'delivery_charge', true ); ?>">
											<input type="hidden" class="ac" value="<?php // echo get_user_meta( $merchant_name, 'additional_charge', true ); ?>"> -->
										</div>
									</div>
								</div>								
								<div class="row <?php if (in_array( 'merchant', $current_user->roles ) ) echo 'd-none'?>">
									<div class="col-lg-3">
										<div class="form-group">
											<label for="_mos_courier_total_weight">Total Weight</label>
											<input type="number" min="0" class="form-control" name="_mos_courier_total_weight" id="_mos_courier_total_weight" placeholder="Total Weight" value="<?php if (@$total_weight) echo $total_weight; else echo 1?>" required>					
											<div class="valid-feedback">Valid.</div>
											<div class="invalid-feedback">Please fill out this field.</div>											
										</div>								
									</div>
									<div class="col-lg-3">
										<div class="form-group">
											<label for="_mos_courier_packaging_type">Packaging Type</label>
											<select id="_mos_courier_packaging_type" name="_mos_courier_packaging_type" class="form-control select2" required>
												
                                            <?php foreach($packaging as $package) : ?>
												<option value="<?php echo $package ?>" <?php selected( $packaging_type, $package ) ?>><?php echo $package;?></option>
                                            <?php endforeach; ?>    
                                            </select>					
											<div class="valid-feedback">Valid.</div>
											<div class="invalid-feedback">Please fill out this field.</div>										
										</div>								
									</div>
								</div>
							<?php if ($id) : ?>
								<div class="form-row">
									<div class="col-lg-6">
										<label for="_mos_courier_delivery_status">Do you like to hold this order?</label>
										<div class="form-group form-check">
											<input type="checkbox" class="form-check-input" id="_mos_courier_delivery_status" name="_mos_courier_delivery_status" value="hold" <?php checked( 'hold', $delivery_status ); ?>>
											<label class="form-check-label" for="_mos_courier_delivery_status">Yes</label>
										</div>
									</div>
									<div class="col-lg-6">
										<div class="form-group">
											<label for="_mos_courier_remarks">Remarks</label>
											<input type="text" class="form-control" name="_mos_courier_remarks" id="_mos_courier_remarks" placeholder="Remarks" value="<?php echo @$remarks?>" >
											<div class="valid-feedback">Valid.</div>
											<div class="invalid-feedback">Please fill out this field.</div>	
										</div>
									</div>
								</div>
								<div class="form-group">
									<label for="_mos_courier_note">Note for Edit</label>
									<textarea class="form-control" name="_mos_courier_note" id="_mos_courier_note" required></textarea>
								</div>
								<button type="submit" class="btn btn-sm btn-success" name="edit-order-sub" value="update">Update</button>
								<div class="w-100">
									<?php $notes = get_post_meta( $id, '_mos_courier_note', true ); ?>
									<?php if (@$notes) : ?>
										<ul class="list-unstyled">
										<?php // var_dump($notes) ?>
										<?php foreach ($notes as $key => $value):?>
											<li class="border-secondary border-bottom">
												<div class="name"><strong>Name:</strong> <?php echo get_userdata($value['id'])->display_name ?></div>
												<div class="user_id"><strong>User ID:</strong> <?php echo $value['id']?></div>
												<div class="notes"><?php echo $value['note'] ?></div>												
											</li>
										<?php endforeach; ?>
										</ul>
									<?php endif; ?>
								</div>
							<?php else : ?>
								<button type="submit" class="btn btn-sm btn-primary" name="edit-order-sub" value="save">Save</button>
								<button type="submit" class="btn btn-sm btn-primary" name="edit-order-sub" value="onemore">Add one more</button>
							<?php endif; ?>
								
							</div>
							<!-- /.card-body -->

							<!-- <div class="card-footer">
							</div> -->
						</form>
					</div>
			<?php
		endif;
	}
}
add_action('courier_content', 'courier_order_bulk_content', 10, 1 );
if (!function_exists('courier_order_bulk_content')) {
	function courier_order_bulk_content($args) {
			if ( $args == 'order-bulk') :
			?>	
						<div class="card card-primary">
							<div class="card-header">
								<h3 class="card-title">Import Order</h3>
							</div>
							<div class="card-body">							
								<form role="form" method="post" action="" class="needs-validation" novalidate enctype="multipart/form-data">
								<?php wp_nonce_field( 'csv_order_form', 'csv_order_form_field' ); ?>
									<div class="form-row">
										<div class="col-lg-6 offset-lg-3 text-center">
											<div class="form-group">
												<input type="file" name="order-csv-file" id="order-csv-file" required>
												<p class="mute-text">Please download <a href="<?php echo plugin_dir_url( __FILE__ ) ?>assets/demo-order-upload.csv" target="_blank">this template</a> if you don't have one.</p>
											</div>
											<button type="submit" class="btn btn-sm btn-success" id="order-csv-sub" value="submit">Submit</button>
										</div>
									</div>
								</form>
							</div>
						</div>
			<?php endif;
	}
}
add_action('courier_content', 'courier_check_out_content', 10, 1 );
if (!function_exists('courier_check_out_content')) {
	function courier_check_out_content($args) {
		if ( $args == 'check-out') :
			$options = get_option( 'mos_courier_options' );
			$zones = mos_str_to_arr($options['zone'], '|');
		?>	
					<div class="card card-primary">
						<div class="card-header">
							<h3 class="card-title">Check Out</h3>
						</div>
						<div class="card-body">							
							<form id="check_out_form" role="form" method="post" action="">
								<div class="form-row">
									<div class="col-lg-4">
										<div class="form-group">
											<div class="input-group mb-3">
												<input type="text" class="form-control" name="check_out_cn_no" id="check_out_cn_no" placeholder="CN NO" list="orders">												
												<?php
												$args = array(
													'post_type' => 'courierorder',
													'posts_per_page'=>-1,
												    'meta_query' => array(
												        array(
												            'key'     => '_mos_courier_delivery_status',
												            'value'   => array('received','hold'),
												            'compare' => 'IN',
												        ),
												    ),
												);
												$the_query = new WP_Query( $args );
												if ( $the_query->have_posts() ) :
												    echo '<datalist id="orders">';
												    while ( $the_query->have_posts() ) : $the_query->the_post();
												        echo '<option value="' . get_the_title() . '">';
												    endwhile;
												    echo '</datalist>';
												endif;
												wp_reset_postdata();
												?>
												
												<div class="input-group-append">
													<button class="btn btn-outline-secondary" type="submit" id="check-out-cn-no">Add</button>
												</div>
											</div>
										</div>
									</div>
									<div class="col-lg-4">
										<div class="form-group">
											<div class="input-group mb-3">
												<select class="form-control" name="check_out_zone" id="check_out_zone">
													<option value="">--Select Zone--</option>
												<?php foreach($zones as $zone) : ?>
													<option value="<?php echo $zone ?>" ><?php echo $zone ?></option>
												<?php endforeach; ?>
												</select>
												<div class="input-group-append">
													<button class="btn btn-outline-secondary" type="submit" id="check-out-zone">Add</button>
												</div>
											</div>
										</div>
									</div>
									<div class="col-lg-4">
										<div class="form-group">
											<div class="input-group mb-3">
												<input type="text" class="form-control" name="check_out_cn_multi_no" id="check_out_cn_multi_no" placeholder="Multiple CN NO seperated by ,">
												<div class="input-group-append">
													<button class="btn btn-outline-secondary" type="submit" id="check-out-cn-multi-no">Add</button>
												</div>
											</div>
										</div>
									</div>
								</div>
							</form>
							<form role="form" method="post" action="" class="needs-validation" novalidate>
								<?php wp_nonce_field( 'delivery_man_form', 'delivery_man_form_field' ); ?>
								<div class="card card-primary">
									<div class="card-header">
										<!-- <h3 class="card-title">Order List</h3> -->							
										<div class="form-inline">
											<div class="form-group mr-1">
											<?php $deliveryman =mos_user_list('operator', 'user_role', 'Delivery Man');?>
												<select name="delivery_man" class="form-control" id="delivery_man" required> 
													<option value="">Select Deliveryman</option>
												<?php foreach($deliveryman as $key => $value) : ?>
													<option value="<?php echo $key ?>"><?php echo $value ?></option>
												<?php endforeach; ?>
												</select>
											</div>
											<button type="submit" class="btn btn-success" name="set-delivery-man-sub" value="set">Set Deliveryman</button>
										</div>
									</div>
									<!-- /.card-header -->
									<div class="card-body">
										<?php $deliveryman =mos_user_list('operator', 'user_role', 'Delivery Man');?>
										<div class="table-responsive">
											<table class="table">
												<thead>
													<tr>
														<th scope="col">#</th>
														<th scope="col">CL NO</th>
														<th scope="col">Receiver Address</th>
														<th scope="col">Deliovery Zone</th>
													</tr>
												</thead>
												<tbody id="check_out_form_result">
												</tbody>
												<tfoot>
													<tr>
														<th scope="col">#</th>
														<th scope="col">CL NO</th>
														<th scope="col">Receiver Address</th>
														<th scope="col">Deliovery Zone</th>
													</tr>
												</tfoot>
											</table>
										</div>							
									</div>
									<!-- /.card-body -->
								</div>
							</form>
						</div>
					</div>



		<?php endif;
	}
}
add_action('courier_content', 'courier_check_in_content', 10, 1 );
if (!function_exists('courier_check_in_content')) {
	function courier_check_in_content($args) {
		if ( $args == 'check-in') :
		?>	
					<div class="card card-primary">
						<div class="card-header">
							<h3 class="card-title">Check In</h3>
						</div>
						<div class="card-body">							
							<form id="check_in_form" role="form" method="post" action="">
								<div class="form-row">
									<div class="col-lg-6">
										<div class="form-group">
											<div class="input-group mb-3">
												<input type="text" class="form-control" name="check_in_cn_no" id="check_in_cn_no" placeholder="CN NO" list="orders">												
												<?php
												$args = array(
													'post_type' => 'courierorder',
													'posts_per_page'=>-1,
												    'meta_key'     => '_mos_courier_delivery_status',
												    'meta_value'   => 'way',
												    'meta_compare' => '='
												);
												$the_query = new WP_Query( $args );
												if ( $the_query->have_posts() ) :
												    echo '<datalist id="orders">';
												    while ( $the_query->have_posts() ) : $the_query->the_post();
												        echo '<option value="' . get_the_title() . '">';
												    endwhile;
												    echo '</datalist>';
												endif;
												wp_reset_postdata();
												?>
												
												<div class="input-group-append">
													<button class="btn btn-outline-secondary" type="submit" id="check-in-cn-no">Add</button>
												</div>
											</div>
										</div>
									</div>
									<div class="col-lg-6">
										<div class="form-group">
											<div class="input-group mb-3">
												<?php $deliveryman =mos_user_list('operator', 'user_role', 'Delivery Man');?>
												<select name="delivery_man" class="form-control" id="delivery_man"> 
													<option value="">Select Deliveryman</option>
												<?php foreach($deliveryman as $key => $value) : ?>
													<option value="<?php echo $key ?>"><?php echo $value ?></option>
												<?php endforeach; ?>
												</select>
												<div class="input-group-append">
													<button class="btn btn-outline-secondary" type="submit" id="check-in-zone">Add</button>
												</div>
											</div>
										</div>
									</div>
								</div>
							</form>
							<form role="form" method="post" action="" class="needs-validation" novalidate>
								<?php wp_nonce_field( 'check_in_form', 'check_in_form_field' ); ?>
								<div class="table-responsive">
									<table class="table">
										<thead>
											<tr>
												<th scope="col">ID</th>
												<th scope="col">CL NO</th>
												<th scope="col">Payable Amount</th>
												<th scope="col">Paid Amount</th>
												<th scope="col">Commission</th>
												<th scope="col">Remarks</th>
												<th scope="col">Action</th>
											</tr>
										</thead>
										<tbody id="check_in_form_result">
											<tr>
												<th colspan="2">Commission</th>
												<th scope="col">
													<input type="number" min="0" class="form-control commission" id="order_commission" name="order_commission_extra" value="0">
												</th>
												<td scope="col" colspan="2">
													<button type="button" class="btn btn-info btn-calculate"><i class="fa fa-calculator"></i></button>
													<span class="calculated-value pl-2">0</span>
												</td>
												<td scope="col">Submit Check in</td>
												<td scope="col"><button type="submit" class="btn btn-block btn-success">Check in</button></td>
											</tr>
										</tbody>
										<tfoot>
											<tr>
												<th scope="col">ID</th>
												<th scope="col">CL NO</th>
												<th scope="col">Payable Amount</th>
												<th scope="col">Paid Amount</th>
												<th scope="col">Commission</th>
												<th scope="col">Remarks</th>
												<th scope="col">Action</th>
											</tr>
										</tfoot>
									</table>
								</div>								
							</form>
						</div>
					</div>



		<?php endif;
	}
}

add_action('courier_content', 'courier_bill_pay_content', 10, 1 );
if (!function_exists('courier_bill_pay_content')) {
	function courier_bill_pay_content($args) {
		if ( $args == 'bill-pay') :
		?>	
					<div class="card card-primary">
						<div class="card-header">
							<h3 class="card-title">Bill Pay</h3>
						</div>
						<div class="card-body">							
							<form id="bill_pay_form" role="form" method="post" action="">
								<div class="form-row">
									<div class="col-lg-6">
										<div class="form-group">
											<div class="input-group mb-3">
												<input type="text" class="form-control" name="bill_pay_cn_no" id="bill_pay_cn_no" placeholder="CN NO" list="orders">			
												<?php
												$args = array(
													'post_type' => 'courierorder',
													'posts_per_page'=>-1,

												    'meta_query' => array(
												        array(
												            'key'     => '_mos_courier_delivery_status',
														    'value'   => array('delivered','pdelivered','returned'),
														    'compare' => 'IN'
												        ),
												        array(
												            'key'     => '_mos_courier_payment_status',
														    'value'   => 'unpaid',
														    'compare' => '='
												        ),
												    ),
												);
												$the_query = new WP_Query( $args );
												if ( $the_query->have_posts() ) :
												    echo '<datalist id="orders">';
												    while ( $the_query->have_posts() ) : $the_query->the_post();
												        echo '<option value="' . get_the_title() . '">';
												    endwhile;
												    echo '</datalist>';
												endif;
												wp_reset_postdata();
												?>
												
												<div class="input-group-append">
													<button class="btn btn-outline-secondary" type="submit" id="bill-pay-cn-no">Add</button>
												</div>
											</div>
										</div>
									</div>
									<div class="col-lg-6">
										<div class="form-group">
											<div class="input-group mb-3">
												<?php $merchant =mos_user_list('merchant');?>
												<select name="merchant" class="form-control" id="merchant"> 
													<option value="">Select Merchant</option>
												<?php foreach($merchant as $key => $value) : ?>
													<option value="<?php echo $key ?>"><?php echo get_user_meta( $key, 'brand_name', true ); ?></option>
												<?php endforeach; ?>
												</select>
												<div class="input-group-append">
													<button class="btn btn-outline-secondary" type="submit" id="bill-pay-zone">Add</button>
												</div>
											</div>
										</div>
									</div>
								</div>
							</form>
							<form role="form" method="post" action="" class="needs-validation" novalidate>
								<?php wp_nonce_field( 'bill_pay_form', 'bill_pay_form_field' ); ?>
								<div class="table-responsive">
									<table class="table">
										<thead>
											<tr>
												<th scope="col">ID</th>
												<th scope="col">CL NO</th>
												<th scope="col">Product Price</th>
												<th scope="col">Paid Amount</th>
												<th scope="col">Delivery Charge</th>
												<th scope="col">Delivery Date</th>
												<th scope="col">Paid to Merchant</th>
												<th scope="col">Payable Amount</th>
												<th scope="col">Payment Method</th>
												<th scope="col">Payment Note</th>
											</tr>
										</thead>
										<tbody id="bill_pay_form_result">
											<tr>
												<th colspan="6">Pay Bill</th>
												<td scope="col">
													<input class="form-control merchant_commission" id="Commission" name="commission" placeholder="Commission">
												</td>
												<td scope="col">
													<button type="button" class="btn btn-info btn-calculate"><i class="fa fa-calculator"></i></button>
													<span class="calculated-value pl-2">0</span>
												</td>
												<td scope="col" colspan="2"><button type="submit" class="btn btn-block btn-success">Submit</button></td>
											</tr>
										</tbody>
										<tfoot>
											<tr>
												<th scope="col">ID</th>
												<th scope="col">CL NO</th>
												<th scope="col">Product Price</th>
												<th scope="col">Paid Amount</th>
												<th scope="col">Delivery Charge</th>
												<th scope="col">Delivery Date</th>
												<th scope="col">Paid to Merchant</th>
												<th scope="col">Payable Amount</th>
												<th scope="col">Payment Method</th>
												<th scope="col">Payment Note</th>
											</tr>
										</tfoot>
									</table>
								</div>								
							</form>
						</div>
					</div>



		<?php endif;
	}
}
add_action('courier_content', 'courier_user_manage_content', 10, 1 );
if (!function_exists('courier_user_manage_content')) {
	function courier_user_manage_content($args) {
		if ( $args == 'user-manage') :
			$current_user = wp_get_current_user();
			$users = mos_user_list('operator,merchant');
			// var_dump($users);
			?>
					<div class="card card-primary">
						<div class="card-header">
							<h3 class="card-title">Order List</h3>
						</div>
						<!-- /.card-header -->
						<div class="card-body">
							<div class="table-responsive">
								<table id="user-table" class="table table-bordered table-striped">
									<thead>
										<tr>
											<!-- <th class="no-sort"><input id="cb-select-all-1" type="checkbox"></th> -->
											<th>ID</th>
											<th>Brand Name</th>
											<th>First Name</th>
											<th>Last Name</th>
											<th>Email</th>
											<th>Mobile No</th>
											<th>Payment Method</th>
											<th>Bank Name</th>
											<th>Account Holder</th>
											<th>Account Number</th>
											<th>Role</th>
											<th>Status</th>
										</tr>
									</thead>
									<tbody>
									<?php foreach ($users as $user_id => $name) : ?>
										<?php
										$brand_name = get_user_meta( $user_id, 'brand_name', true );
										$first_name = get_user_meta( $user_id, 'first_name', true );
										$last_name = get_user_meta( $user_id, 'last_name', true );
										$email = get_userdata($user_id)->user_email;
										$phone = get_user_meta( $user_id, 'phone', true );
										$payment = get_user_meta( $user_id, 'payment', true );
										$bank_name = get_user_meta( $user_id, 'bank_name', true );
										$account_holder = get_user_meta( $user_id, 'account_holder', true );
										$payacc = get_user_meta( $user_id, 'payacc', true );
										$user_role = get_user_meta( $user_id, 'user_role', true );
										$activation = get_user_meta( $user_id, 'activation', true );
										?>
										<tr <?php if ($activation == 'Deactive') echo 'class="bg-warning text-dark"' ?>>
											<!-- <td><input type="checkbox" name="users[]" id="<?php echo $user_id ?>" class="administrator" value="1"></td> -->
											<td>
											<?php if (in_array( 'operator', $current_user->roles )) : ?>
												<a href="<?php echo home_url( '/admin/?page=user-edit&id='.$user_id); ?>">
												<?php echo $user_id ?></a>
											<?php else : ?>
												<?php echo $user_id ?>
											<?php endif ?>
												
											</td>
											<td><?php echo $brand_name ?></td>
											<td><?php echo $first_name ?></td>
											<td><?php echo $last_name ?></td>
											<td><?php echo $email ?></td>
											<td><?php echo $phone ?></td>
											<td><?php echo $payment ?></td>
											<td><?php echo $bank_name ?></td>
											<td><?php echo $account_holder ?></td>
											<td><?php echo $payacc ?></td>
											<td><?php echo $user_role ?></td>
											<td><?php echo $activation ?></td>
										</tr>							
									<?php endforeach; ?>	
									</tbody>
									<tfoot>
										<tr>
											<!-- <th class="no-sort"></th> -->
											<th>ID</th>
											<th>Brand Name</th>
											<th>First Name</th>
											<th>Last Name</th>
											<th>Email</th>
											<th>Mobile No</th>
											<th>Payment Method</th>
											<th>Bank Name</th>
											<th>Account Holder</th>
											<th>Account Number</th>
											<th>Role</th>
											<th>Status</th>
										</tr>
									</tfoot>
								</table>
							</div>
							
						</div>
						<!-- /.card-body -->
					</div>
			<?php
		endif;
	}
}
add_action('courier_content', 'courier_user_edit_content', 10, 1 );
if (!function_exists('courier_user_edit_content')) {
	function courier_user_edit_content($args) {
		global $religion_arr, $gender_arr, $user_role_arr, $user_activation, $payment_methods;
		if ( $args == 'user-edit') :
			$id = @$_GET['id'];
			$first_name = get_user_meta( $id, 'first_name', true );
			$last_name = get_user_meta( $id, 'last_name', true );
			$user_role = get_user_meta( $id, 'user_role', true );
			$brand_name = get_user_meta( $id, 'brand_name', true );
			$payment = get_user_meta( $id, 'payment', true );
			$bank_name = get_user_meta( $id, 'bank_name', true );
			$account_holder = get_user_meta( $id, 'account_holder', true );
			$payacc = get_user_meta( $id, 'payacc', true );
			$delivery_charge = get_user_meta( $id, 'delivery_charge', true );
			$additional_charge = get_user_meta( $id, 'additional_charge', true );
			$address_line_1 = get_user_meta( $id, 'address_line_1', true );
			$address_line_2 = get_user_meta( $id, 'address_line_2', true );
			$phone = get_user_meta( $id, 'phone', true );
			$mobile = get_user_meta( $id, 'mobile', true );
			$national_id = get_user_meta( $id, 'national_id', true );
			$religion = get_user_meta( $id, 'religion', true );
			$gender = get_user_meta( $id, 'gender', true );
			$activation = get_user_meta( $id, 'activation', true );
			$village = get_user_meta( $id, 'village', true );
			$poffice = get_user_meta( $id, 'poffice', true );
			$thana = get_user_meta( $id, 'thana', true );
			$zila = get_user_meta( $id, 'zila', true );
			?>
			<?php if (@$_GET['msg'] == 'useradded') :?>
				    <div class="alert alert-success alert-dismissible fade show" role="alert">
				        <strong>Done!</strong> Your user has been added. From here you can add new user.
				        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
				        <span aria-hidden="true">&times;</span>
				        </button>
				    </div>
			<?php endif; ?>
					<div class="card card-primary">
						<div class="card-header">
							<h3 class="card-title">User Details</h3>
						</div>
						<!-- /.card-header -->
						<!-- form start -->
						<form role="form" method="post" action="" class="needs-validation" novalidate>
							<?php wp_nonce_field( 'edit_user_form', 'edit_user_form_field' ); ?>
							<?php if (@$id) : ?>
								<input type='hidden' name='user_id' value='<?php echo $id; ?>'/>
							<?php endif; ?>
							<div class="card-body">
								<div class="form-row">
									<div class="col-lg-6">
										<div class="form-group">
											<label for="first_name">First Name</label>
											<input type="text" class="form-control" name="first_name" id="first_name" placeholder="First Name" value="<?php echo @$first_name ?>" required pattern="^[A-Za-z._- ]*$">
											<div class="valid-feedback">Valid.</div>
											<div class="invalid-feedback">Please fill out this field.</div>
										</div>								
									</div>
									<div class="col-lg-6">
										<div class="form-group">
											<label for="last_name">Last Name</label>
											<input type="text" class="form-control" name="last_name" id="last_name" placeholder="Last Name" value="<?php echo @$last_name ?>" pattern="^[A-Za-z._- ]*$">
											<div class="valid-feedback">Valid.</div>
											<div class="invalid-feedback">Please fill out this field.</div>
										</div>								
									</div>
								</div>
							<?php if (!@$id) : ?>
								<div class="form-row">
									<div class="col-lg-6">
										<div class="form-group">
											<label for="email">Email</label>
											<input type="email" class="form-control" name="email" id="email" placeholder="Email" value="<?php echo @$email ?>" required>
											<div class="valid-feedback">Valid.</div>
											<div class="invalid-feedback">Please fill out this field.</div>
										</div>								
									</div>
									<div class="col-lg-6">										
										<div class="form-group">
											<label for="password">Password</label>
											<input type="password" class="form-control" name="password" id="password" placeholder="Password" required>
										</div>
									</div>
								</div>
							<?php endif ?>
								<div class="form-row">
									<div class="col-lg-6">
										<div class="form-group">
											<label for="user_role">User Role</label>
											<select id="user_role" name="user_role" class="form-control" required>
												<option value="">---Select Role---</option>
								                <?php foreach ($user_role_arr as $key => $rol) : ?>
								                    <optgroup label="<?php echo $key?>">
								                    <?php foreach ($rol as $value) : ?> 
								                        <option value="<?php echo $value ?>" <?php selected( $user_role, $value ); ?>><?php echo $value ?></option>
								                    <?php endforeach; ?>   
								                    </optgroup>
								                <?php endforeach; ?>							
											</select>
										</div>
									</div>
									<div class="col-lg-6">										
										<div class="form-group">
											<label for="brand_name">Brand Name</label>
											<input type="text" class="form-control" name="brand_name" id="brand_name" placeholder="Brand Name" value="<?php echo @$brand_name ?>">
										</div>	
									</div>
								</div>
								<div class="form-row">
									<div class="col-lg-6">										
										<div class="form-group">
											<label for="payment">Payment Method</label>
							                <select class="form-control" id="payment" name="payment">
							                    <option value="">---Select Method---</option>
							                <?php foreach($payment_methods as $methods) : ?>
							                    <option value="<?php echo $methods ?>" <?php selected( $payment, $methods ); ?>><?php echo $methods ?></option>
							                <?php endforeach; ?>
							                </select>
											<div class="valid-feedback">Valid.</div>
											<div class="invalid-feedback">Please fill out this field.</div>
										</div>	
									</div>
									<div class="col-lg-6">										
										<div class="form-group">
											<label for="payacc">Account Number</label>
											<input type="text" class="form-control" name="payacc" id="payacc" placeholder="Account Number" value="<?php echo @$payacc ?>">
										</div>	
									</div>
									<div class="col-lg-6">										
										<div class="form-group">
											<label for="bank_name">Bank Name</label>
											<input type="text" class="form-control" name="bank_name" id="bank_name" placeholder="Bank Name" value="<?php echo @$bank_name ?>">
										</div>	
									</div>
									<div class="col-lg-6">										
										<div class="form-group">
											<label for="account_holder">Account Holder</label>
											<input type="text" class="form-control" name="account_holder" id="account_holder" placeholder="Account Holder" value="<?php echo @$account_holder ?>">
										</div>	
									</div>
								</div>
								<div class="form-row">
									<div class="col-lg-6">										
										<div class="form-group">
											<label for="address_line_1">Address</label>
											<input type="text" class="form-control mb-2" name="address_line_1" id="address_line_1" placeholder="Address Line 1" value="<?php echo @$address_line_1 ?>" required>
											<div class="valid-feedback">Valid.</div>
											<div class="invalid-feedback">Please fill out this field.</div>
											<input type="text" class="form-control" name="address_line_2" id="address_line_2" placeholder="Address Line 2" value="<?php echo @$address_line_2 ?>">
										</div>	
									</div>
									<div class="col-lg-6">										
										<div class="form-group">
											<label for="phone">Contact No.</label>
											<input type="text" class="form-control mb-2" name="phone" id="phone" placeholder="Phone" value="<?php echo @$phone ?>" required>
											<div class="valid-feedback">Valid.</div>
											<div class="invalid-feedback">Please fill out this field.</div>
											<input type="text" class="form-control" name="mobile" id="mobile" placeholder="Mobile" value="<?php echo @$mobile ?>">
										</div>	
									</div>
								</div>
								<div class="form-row">
									<div class="col-lg-6">
										<div class="form-group">
											<label for="national_id">National ID</label>
											<input type="text" class="form-control" name="national_id" id="national_id" placeholder="National ID" value="<?php echo @$national_id ?>">
										</div>
									</div>
									<div class="col-lg-6">
										<div class="form-group">
											<label for="religion">Religion</label>
										    <select id="religion" name="religion" class="form-control">
			                                    <option value="">---Select Religion---</option>
							                <?php foreach ($religion_arr as $rel) : ?>
							                    <option value="<?php echo $rel ?>" <?php selected( $religion, $rel ); ?>><?php echo $rel ?></option>
							                <?php endforeach; ?>
											</select>
			                            </div>								
									</div>
								</div>
								<div class="form-row">
									<div class="col-lg-6">
										<div class="form-group">
											<label for="gender">Gender</label>
										    <select id="gender" name="gender" class="form-control">
			                                    <option value="">---Select Gender---</option>
							                <?php foreach ($gender_arr as $gen) : ?>
							                    <option value="<?php echo $gen ?>" <?php selected( $gender, $gen ); ?>><?php echo $gen ?></option>
							                <?php endforeach; ?>
											</select>
			                            </div>
									</div>
									<div class="col-lg-6">
										<div class="form-group">
											<label for="activation">Status</label>
										    <select id="activation" name="activation" class="form-control" required>
			                                    <?php foreach($user_activation as $user_activ) : ?>
			                                    	<option value="<?php echo $user_activ ?>" <?php selected( $activation, $user_activ ); ?>><?php echo $user_activ ?></option>
			                                    <?php endforeach; ?>
											</select>
				                        </div>
									</div>
								</div>
								<label for="village">Permanent Address</label>
								<div class="form-row">
									<div class="col-lg-6">
										<div class="form-group">
											<input type="text" class="form-control mb-2" name="village" id="village" placeholder="Village" value="<?php echo @$village ?>">
											<input type="text" class="form-control" name="poffice" id="poffice" placeholder="Post Office" value="<?php echo @$poffice ?>">
										</div>
									</div>
									<div class="col-lg-6">
										<div class="form-group">
											<!-- <label for="village">&nbsp;</label> -->
											<input type="text" class="form-control mb-2" name="thana" id="thana" placeholder="Thans" value="<?php echo @$thana ?>">
											<input type="text" class="form-control" name="zila" id="zila" placeholder="Zila" value="<?php echo @$zila ?>">
										</div>								
									</div>
								</div>

							<?php if ($id) : ?>
								<button type="submit" class="btn btn-sm btn-success" name="edit-user-sub" value="update">Update</button>
							<?php else : ?>
								<button type="submit" class="btn btn-sm btn-primary" name="edit-user-sub" value="save">Save</button>
								<button type="submit" class="btn btn-sm btn-primary" name="edit-user-sub" value="onemore">Add one more</button>
							<?php endif; ?>
								
							</div>
							<!-- /.card-body -->

							<!-- <div class="card-footer">
							</div> -->
						</form>
					</div>
			<?php
		endif;
	}
}
add_action('courier_content', 'courier_user_bulk_content', 10, 1 );
if (!function_exists('courier_user_bulk_content')) {
	function courier_user_bulk_content($args) {
		if ( $args == 'user-bulk') :
		?>	
					<div class="card card-primary">
						<div class="card-header">
							<h3 class="card-title">Import User</h3>
						</div>
						<div class="card-body">							
							<form role="form" method="post" action="" class="needs-validation" novalidate enctype="multipart/form-data">
							<?php wp_nonce_field( 'csv_user_form', 'csv_user_form_field' ); ?>
								<div class="form-row">
									<div class="col-lg-6 offset-lg-3 text-center">
										<div class="form-group">
											<input type="file" name="user-csv-file" id="user-csv-file" required>
											<p class="mute-text">Please download <a href="<?php echo plugin_dir_url( __FILE__ ) ?>assets/demo-user-upload.csv" target="_blank">this template</a> if you don't have one.</p>
										</div>
										<button type="submit" class="btn btn-sm btn-success" name="user-csv-sub" id="user-csv-sub" value="submit">Submit</button>
									</div>
								</div>
							</form>
						</div>
					</div>



		<?php endif;
	}
}
add_action('courier_content', 'courier_edit_profile_content', 10, 1 );
if (!function_exists('courier_edit_profile_content')) {
	function courier_edit_profile_content($args) {
		global $religion_arr, $gender_arr, $user_role_arr, $user_activation, $payment_methods;
		if ( $args == 'edit-profile') :
			$id = get_current_user_id();
			$email = get_userdata( $id )->user_email;			
			$user_role = get_user_meta( $id, 'user_role', true );
			$activation = get_user_meta( $id, 'activation', true );

			$first_name = get_user_meta( $id, 'first_name', true );
			$last_name = get_user_meta( $id, 'last_name', true );
			$brand_name = get_user_meta( $id, 'brand_name', true );
			$payment = get_user_meta( $id, 'payment', true );
			$payacc = get_user_meta( $id, 'payacc', true );
			$delivery_charge = get_user_meta( $id, 'delivery_charge', true );
			$additional_charge = get_user_meta( $id, 'additional_charge', true );
			$address_line_1 = get_user_meta( $id, 'address_line_1', true );
			$address_line_2 = get_user_meta( $id, 'address_line_2', true );
			$phone = get_user_meta( $id, 'phone', true );
			$mobile = get_user_meta( $id, 'mobile', true );
			$national_id = get_user_meta( $id, 'national_id', true );
			$religion = get_user_meta( $id, 'religion', true );
			$gender = get_user_meta( $id, 'gender', true );
			$village = get_user_meta( $id, 'village', true );
			$poffice = get_user_meta( $id, 'poffice', true );
			$thana = get_user_meta( $id, 'thana', true );
			$zila = get_user_meta( $id, 'zila', true );
			?>
			<?php if (@$_GET['msg'] == 'profileupdated') :?>
				    <div class="alert alert-success alert-dismissible fade show" role="alert">
				        <strong>Done!</strong> Your profile has been updated.
				        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
				        <span aria-hidden="true">&times;</span>
				        </button>
				    </div>
			<?php endif; ?>
					<div class="card card-primary">
						<div class="card-header">
							<h3 class="card-title">Edit Profile</h3>
						</div>
						<!-- /.card-header -->
						<!-- form start -->
						<form role="form" method="post" action="" class="needs-validation" novalidate>
							<?php wp_nonce_field( 'edit_profile_form', 'edit_profile_form_field' ); ?>
							<input type='hidden' name='user_id' value='<?php echo $id; ?>'/>

							<div class="card-body">
								<div class="form-row">
									<div class="col-lg-4">
										<div class="form-group row">
											<label for="email" class="col-lg-3 col-form-label">Email</label>
											<div class="col-lg-9">
												<input type="text" class="form-control-plaintext" name="email" id="email" value="<?php echo @$email ?>" readonly>
											</div>
										</div>
									</div>
									<div class="col-lg-4">
										<div class="form-group row">
											<label for="user_role" class="col-lg-3 col-form-label">Role</label>
											<div class="col-lg-9">
												<input type="text" class="form-control-plaintext" name="user_role" id="user_role" value="<?php echo @$user_role ?>" readonly>
											</div>
										</div>
									</div>
									<div class="col-lg-4">
										<div class="form-group row">
											<label for="activation" class="col-lg-3 col-form-label">Status</label>
											<div class="col-lg-9">
												<input type="text" class="form-control-plaintext" name="activation" id="activation" value="<?php echo @$activation ?>" readonly>
											</div>
										</div>
									</div>
								</div>
								<div class="form-row">
									<div class="col-lg-6">
										<div class="form-group">
											<label for="first_name">First Name</label>
											<input type="text" class="form-control" name="first_name" id="first_name" placeholder="First Name" value="<?php echo @$first_name ?>" required pattern="^[A-Za-z ]*$">
											<div class="valid-feedback">Valid.</div>
											<div class="invalid-feedback">Please fill out this field.</div>
										</div>								
									</div>
									<div class="col-lg-6">
										<div class="form-group">
											<label for="last_name">Last Name</label>
											<input type="text" class="form-control" name="last_name" id="last_name" placeholder="Last Name" value="<?php echo @$last_name ?>" pattern="^[A-Za-z ]*$">
											<div class="valid-feedback">Valid.</div>
											<div class="invalid-feedback">Please fill out this field.</div>
										</div>								
									</div>
								</div>
								<div class="form-row">
									<div class="col-lg-6">										
										<div class="form-group">
											<label for="brand_name">Brand Name</label>
											<input type="text" class="form-control" name="brand_name" id="brand_name" placeholder="Brand Name" value="<?php echo @$brand_name ?>">
											<div class="valid-feedback">Valid.</div>
											<div class="invalid-feedback">Please fill out this field.</div>
										</div>	
									</div>
								</div>
								<div class="form-row">
									<div class="col-lg-6">										
										<div class="form-group">
											<label for="payment">Payment Method</label>
							                <select class="form-control" id="payment" name="payment">
							                    <option value="">---Select Method---</option>
							                <?php foreach($payment_methods as $methods) : ?>
							                    <option value="<?php echo $methods ?>" <?php selected( $payment, $methods ); ?>><?php echo $methods ?></option>
							                <?php endforeach; ?>
							                </select>
										</div>	
									</div>
									<div class="col-lg-6">										
										<div class="form-group">
											<label for="payacc">Account Number</label>
											<input type="text" class="form-control" name="payacc" id="payacc" placeholder="Account Number" value="<?php echo @$payacc ?>">
										</div>	
									</div>
								</div>
								<div class="form-row">
									<div class="col-lg-6">										
										<div class="form-group">
											<label for="delivery_charge">Delivery Charge</label>
											<input type="text" class="form-control-plaintext" name="delivery_charge" id="delivery_charge" placeholder="Delivery Charge" value="<?php echo @$delivery_charge ?>" readonly>
										</div>	
									</div>
									<div class="col-lg-6">										
										<div class="form-group">
											<label for="additional_charge">Additional Charge</label>
											<input type="text" class="form-control-plaintext" name="additional_charge" id="additional_charge" placeholder="Additional Charge" value="<?php echo @$additional_charge ?>" readonly>
										</div>	
									</div>
								</div>
								<div class="form-row">
									<div class="col-lg-6">										
										<div class="form-group">
											<label for="address_line_1">Address</label>
											<input type="text" class="form-control mb-2" name="address_line_1" id="address_line_1" placeholder="Address Line 1" value="<?php echo @$address_line_1 ?>" required>
											<div class="valid-feedback">Valid.</div>
											<div class="invalid-feedback">Please fill out this field.</div>
											<input type="text" class="form-control" name="address_line_2" id="address_line_2" placeholder="Address Line 2" value="<?php echo @$address_line_2 ?>">
										</div>	
									</div>
									<div class="col-lg-6">										
										<div class="form-group">
											<label for="phone">Contact No.</label>
											<input type="text" class="form-control mb-2" name="phone" id="phone" placeholder="Phone" value="<?php echo @$phone ?>" required>
											<div class="valid-feedback">Valid.</div>
											<div class="invalid-feedback">Please fill out this field.</div>
											<input type="text" class="form-control" name="mobile" id="mobile" placeholder="Mobile" value="<?php echo @$mobile ?>">
										</div>	
									</div>
								</div>
								<div class="form-row">
									<div class="col-lg-6">
										<div class="form-group">
											<label for="national_id">National ID</label>
											<input type="text" class="form-control" name="national_id" id="national_id" placeholder="National ID" value="<?php echo @$national_id ?>">
										</div>
									</div>
									<div class="col-lg-6">
										<div class="form-group">
											<label for="religion">Religion</label>
										    <select id="religion" name="religion" class="form-control">
			                                    <option value="">---Select Religion---</option>
							                <?php foreach ($religion_arr as $rel) : ?>
							                    <option value="<?php echo $rel ?>" <?php selected( $religion, $rel ); ?>><?php echo $rel ?></option>
							                <?php endforeach; ?>
											</select>
			                            </div>								
									</div>
								</div>
								<div class="form-row">
									<div class="col-lg-6">
										<div class="form-group">
											<label for="gender">Gender</label>
										    <select id="gender" name="gender" class="form-control">
			                                    <option value="">---Select Gender---</option>
							                <?php foreach ($gender_arr as $gen) : ?>
							                    <option value="<?php echo $gen ?>" <?php selected( $gender, $gen ); ?>><?php echo $gen ?></option>
							                <?php endforeach; ?>
											</select>
			                            </div>
									</div>
								</div>
								<label for="village">Permanent Address</label>
								<div class="form-row">
									<div class="col-lg-6">
										<div class="form-group">
											<input type="text" class="form-control mb-2" name="village" id="village" placeholder="Village" value="<?php echo @$village ?>">
											<input type="text" class="form-control" name="poffice" id="poffice" placeholder="Post Office" value="<?php echo @$poffice ?>">
										</div>
									</div>
									<div class="col-lg-6">
										<div class="form-group">
											<!-- <label for="village">&nbsp;</label> -->
											<input type="text" class="form-control mb-2" name="thana" id="thana" placeholder="Thans" value="<?php echo @$thana ?>">
											<input type="text" class="form-control" name="zila" id="zila" placeholder="Zila" value="<?php echo @$zila ?>">
										</div>								
									</div>
								</div>
								<button type="submit" class="btn btn-sm btn-success" name="edit-profile-sub" value="update">Update</button>
								
							</div>
							<!-- /.card-body -->

							<!-- <div class="card-footer">
							</div> -->
						</form>
					</div>
			<?php
		endif;
	}
}
add_action('courier_content', 'courier_change_password_content', 10, 1 );
if (!function_exists('courier_change_password_content')) {
	function courier_change_password_content($args) {
		global $religion_arr, $gender_arr, $user_role_arr, $user_activation, $payment_methods;
		if ( $args == 'change-password') :?>
			<?php if (@$_GET['msg'] == 'changepass') :?>
				    <div class="alert alert-success alert-dismissible fade show" role="alert">
				        <strong>Done!</strong> Your password has been updated.
				        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
				        <span aria-hidden="true">&times;</span>
				        </button>
				    </div>
			<?php elseif (@$_GET['msg'] == 'wrongpass') :?>
				    <div class="alert alert-danger alert-dismissible fade show" role="alert">
				        <strong>Alert!</strong> Please check again before submit.
				        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
				        <span aria-hidden="true">&times;</span>
				        </button>
				    </div>
			<?php endif; ?>
					<div class="card card-primary">
						<div class="card-header">
							<h3 class="card-title">Change Password</h3>
						</div>
						<!-- /.card-header -->
						<!-- form start -->
						<form role="form" method="post" action="" class="needs-validation" novalidate>
							<?php wp_nonce_field( 'change_password_form', 'change_password_form_field' ); ?>

							<div class="card-body">
								<div class="form-inline">
									<div class="form-group mx-sm-3 mb-2">
										<label for="old-pass" class="sr-only">Password</label>
										<input type="password" class="form-control" name="old-pass" id="old-pass" placeholder="Password" required>
									</div>
									<div class="form-group mx-sm-3 mb-2">
										<label for="new-pass" class="sr-only">New Password</label>
										<input type="password" class="form-control" name="new-pass" id="new-pass" placeholder="New Password" required>
									</div>
									<div class="form-group mx-sm-3 mb-2">
										<label for="con-pass" class="sr-only">Confirm Password</label>
										<input type="password" class="form-control" name="con-pass" id="con-pass" placeholder="Confirm Password" required>
									</div>
									<button type="submit" class="btn btn-primary mb-2">Change Password</button>									
								</div>
								
							</div>
							<!-- /.card-body -->

							<!-- <div class="card-footer">
							</div> -->
						</form>
					</div>
			<?php
		endif;
	}
}
add_action('courier_content', 'courier_settings_content', 10, 1 );
if (!function_exists('courier_settings_content')) {
	function courier_settings_content($args) {
		if ( $args == 'settings') :
		$options = get_option( 'mos_courier_options' );
		?>

					<div class="card card-primary">
						<div class="card-header">
							<h3 class="card-title">Settings</h3>
						</div>
						<!-- /.card-header -->
						<!-- form start -->
						<form role="form" method="post" action="" class="needs-validation" novalidate enctype="multipart/form-data">
							<?php wp_nonce_field( 'edit_settings_form', 'edit_settings_form_field' ); ?>
							<div class="card-body">
							
								<div class="form-group row">
									<label for="cname" class="col-lg-4 col-form-label text-left text-lg-right">Company Name</label>
									<div class="col-lg-8">
										<input type="text" class="form-control" id="cname" name="cname" placeholder="Company Name" value="<?php echo @$options['cname']; ?>">
									</div>
								</div>
								<div class="form-group row">
									<label for="address" class="col-lg-4 col-form-label text-left text-lg-right">Company Address</label>
									<div class="col-lg-8">
										<input type="text" class="form-control" id="address" name="address" placeholder="Company Address" value="<?php echo @$options['address']; ?>">
									</div>
								</div>
								<div class="form-group row">
									<label for="website" class="col-lg-4 col-form-label text-left text-lg-right">Company Website</label>
									<div class="col-lg-8">
										<input type="text" class="form-control" id="website" name="website" placeholder="Company Website" value="<?php echo @$options['website']; ?>">
									</div>
								</div>
								<div class="form-group row">
									<label for="phone" class="col-lg-4 col-form-label text-left text-lg-right">Company Phone</label>
									<div class="col-lg-8">
										<input type="text" class="form-control" id="phone" name="phone" placeholder="Company Phone" value="<?php echo @$options['phone']; ?>">
									</div>
								</div>
								<div class="form-group row">
									<label for="oprefix" class="col-lg-4 col-form-label text-left text-lg-right">Order Prefix</label>
									<div class="col-lg-8">
										<input type="text" class="form-control" id="oprefix" name="oprefix" placeholder="Order Prefix" value="<?php echo @$options['oprefix']; ?>">
									</div>
								</div>
								<div class="form-group row">
									<label for="zone" class="col-lg-4 col-form-label text-left text-lg-right">Delivery Zone</label>
									<div class="col-lg-8">
										<input type="text" class="form-control" id="zone" name="zone" placeholder="Delivery Zone" value="<?php echo @$options['zone']; ?>">
										<small class="form-text text-muted">Separate options by |</small> 
									</div>
								</div>
								<div class="form-group row">
									<label for="packaging" class="col-lg-4 col-form-label text-left text-lg-right">Packaging Type</label>
									<div class="col-lg-8">
										<input type="text" class="form-control" id="packaging" name="packaging" placeholder="Packaging Type" value="<?php echo @$options['packaging']; ?>">
										<small class="form-text text-muted">Separate options by |</small>  
									</div>
								</div>
								<div class="form-group row">
									<label for="urgent" class="col-lg-4 col-form-label text-left text-lg-right">Urgent Charge</label>
									<div class="col-lg-8"> 
										<div class="input-group">
											<input name="urgent[amount]" type="text" class="form-control" placeholder="Urgent Charge" value="<?php echo @$options['urgent']['amount']; ?>">
											<div class="input-group-append">
												<select name="urgent[type]" class="form-control" id="exampleFormControlSelect1">
													<option value="taka" <?php selected( $options['urgent']['type'], 'taka' ); ?>>Taka</option>
													<option value="%" <?php selected( $options['urgent']['type'], '%' ); ?>>%</option>
												</select>
											</div>
										</div>
									</div>
								</div>
								<div class="form-group row">
									<label for="packaging" class="col-lg-4 col-form-label text-left text-lg-right">Other City Charge</label>
									<div class="col-lg-8">
									<?php 
									$zone = $options["zone"];
									$zoneArr = mos_str_to_arr($zone, '|');
									?>
										<table class="table">
											<?php
											foreach ($zoneArr as $value) {
												?>
												<tr>
													<th><?php echo $value; ?></th>
													<td><input class="form-control" name="mos_courier_options[ocharge][<?php echo $value ?>]" value="<?php echo isset( $options['ocharge'][$value] ) ? esc_html_e($options['ocharge'][$value]) : '';?>"></td>
												</tr>
												<?php
											}
											?>
										</table>
									</div>
								</div>
								<div class="form-group row">
									<label for="packaging" class="col-lg-4 col-form-label text-left text-lg-right">Upload Logo</label>
									<div class="col-lg-4">
										<!-- bootstrap-imageupload. -->
							            <div class="imageupload panel panel-default">
							                <div class="panel-heading">
							                    <div class="btn-group mb-2">
							                        <button type="button" class="btn btn-default active">File</button>
							                        <button type="button" class="btn btn-default">URL</button>
							                    </div>
							                </div>
							                <div class="file-tab panel-body">
							                    <span class="btn btn-default btn-file">
							                        <span>Browse</span>
							                        <input type="file" name="image-file">
							                    </span>
							                    <button type="button" class="btn btn-default">Remove</button>
							                </div>
							                <div class="url-tab panel-body">
							                    <input type="text" name="ext-logo" class="form-control hasclear mb-2" placeholder="Image URL">
							                    <button type="button" class="btn btn-default">Remove</button>
							                    <!-- The URL is stored here. -->
							                    <input type="hidden" name="image-url">
							                </div>
							            </div>									
									</div>
									<div class="col-lg-4 text-center">
										<?php if (@$options['clogo']) : ?>
											<h4 class="prev-img">Previous Logo</h4>
											<img class="img-responsive img-fluid" src="<?php echo $options['clogo']; ?>" alt="<?php  ?>">
										<?php endif; ?>
									</div>
								</div>
								<div class="form-group row">
									<div class="col-lg-8 offset-lg-4">
									<button type="submit" class="btn btn-primary">Save</button>
									</div>
								</div>


							</div>
							<!-- /.card-body -->

							<!-- <div class="card-footer">
							</div> -->
						</form>
					</div>
	<?php
		endif;
	}
}
add_action('courier_content', 'courier_settings_area_content', 10, 1 );
if (!function_exists('courier_settings_area_content')) {
	function courier_settings_area_content($args) {
		if ( $args == 'settings-area') :
		?>

					<div class="card card-primary">
						<div class="card-header">
							<h3 class="card-title">Area Setup</h3>
						</div>
						<!-- /.card-header -->
						<!-- form start -->
						<form role="form" method="post" action="" class="needs-validation" novalidate>
							<?php wp_nonce_field( 'edit_settings_area_form', 'edit_settings_area_form_field' ); ?>
							<div class="card-body">
								<div class="form-group row">
									<label for="zone" class="col-lg-4 col-form-label text-left text-lg-right">Delivery Zone</label>
									<div class="col-lg-8">
										<input type="text" class="form-control" id="zone" name="zone" placeholder="Delivery Zone" value="<?php echo @$options['zone']; ?>">
										<small class="form-text text-muted">Separate options by |</small> 
									</div>
								</div>
								<div class="form-group row">
									<label for="regular-charge" class="col-lg-4 col-form-label text-left text-lg-right">Regular Charge</label>
									<div class="col-lg-8">
										<input type="text" class="form-control" id="regular-charge" name="regular-charge" placeholder="Regular Charge" value="<?php echo @$options['regular-charge']; ?>">
									</div>
								</div>
								<div class="form-group row">
									<label for="extra-charge" class="col-lg-4 col-form-label text-left text-lg-right">Extra Charge</label>
									<div class="col-lg-8">
										<input type="text" class="form-control" id="extra-charge" name="extra-charge" placeholder="Extra Charge" value="<?php echo @$options['extra-charge']; ?>">
									</div>
								</div>
								<div class="form-group row">
									<label for="urgent-charge" class="col-lg-4 col-form-label text-left text-lg-right">Urgent Charge</label>
									<div class="col-lg-8">
										<input type="text" class="form-control" id="urgent-charge" name="urgent-charge" placeholder="Urgent Charge" value="<?php echo @$options['urgent-charge']; ?>">
									</div>
								</div>
								<div class="form-group">
									<!-- <label for="packaging" class="col-lg-4 col-form-label text-left text-lg-right">Area Setup</label> -->
									<!-- <div class="col-lg-8"> -->
									<?php 
									// var_dump($options['charge_setup']);
									$zone = $options["zone"];
									$zoneArr = mos_str_to_arr($zone, '|');
									?>
										<table class="table">
											<thead>
												<tr>													
													<th style="min-width: 150px">Zone</th>
													<th>Area Name</th>
													<th>1KG Charge</th>
													<th>Extra Charge</th>
													<th>Urgent Charge</th>
												</tr>
											</thead>
											<tbody>	
											<?php if (@$options['charge_setup']) : ?>
												<?php $n=1; ?>
												<?php foreach($options['charge_setup'] as $charge) : ?>
												<tr>													
													<td>
														<select class="form-control zone-name" name="mos_courier_options[<?php echo $n; ?>][zone-name]">
															<option value="">--Zone--</option>
														<?php foreach ($zoneArr as $value) : ?>
															<option <?php selected( $charge['zone-name'], $value ); ?>><?php echo $value; ?></option>
														<?php endforeach; ?>
														</select>
													</td>
													<td><input type="text" class="form-control area-name" name="mos_courier_options[<?php echo $n; ?>][area-name]" placeholder="Area name" value="<?php echo @$charge['area-name'] ?>"></td>
													<td><input type="text" class="form-control regular" name="mos_courier_options[<?php echo $n; ?>][regular]" placeholder="Regular 1KG" value="<?php echo @$charge['regular'] ?>"></td>
													<td><input type="text" class="form-control extra" name="mos_courier_options[<?php echo $n; ?>][extra]" placeholder="Extra Charge" value="<?php echo @$charge['extra'] ?>"></td>
													<td><input type="text" class="form-control urgent" name="mos_courier_options[<?php echo $n; ?>][urgent]" placeholder="Urgent Charge" value="<?php echo @$charge['urgent'] ?>"></td>
												</tr>
													<?php $n++; ?>
												<?php endforeach; ?>
											<?php else : ?>
												<tr>													
													<td>
														<select class="form-control zone-name" name="mos_courier_options[0][zone-name]">
															<option value="">--Zone--</option>
														<?php foreach ($zoneArr as $value) : ?>
															<option><?php echo $value; ?></option>
														<?php endforeach; ?>
														</select>
													</td>
													<td><input type="text" class="form-control area-name" name="mos_courier_options[0][area-name]" placeholder="Area name"></td>
													<td><input type="text" class="form-control regular" name="mos_courier_options[0][regular]" placeholder="Regular 1KG"></td>
													<td><input type="text" class="form-control extra" name="mos_courier_options[0][extra]" placeholder="Extra Charge"></td>
													<td><input type="text" class="form-control urgent" name="mos_courier_options[0][urgent]" placeholder="Urgent Charge"></td>
												</tr>
											<?php endif; ?>

												<tr class="d-none last-row">													
													<td>
														<select class="form-control zone-name" name="mos_courier_options['x'][zone-name]">
															<option value="">--Zone--</option>
														<?php foreach ($zoneArr as $value) : ?>
															<option><?php echo $value; ?></option>
														<?php endforeach; ?>
														</select>
													</td>
													<td><input type="text" class="form-control area-name" name="mos_courier_options['x'][area-name]" placeholder="Area name"></td>
													<td><input type="text" class="form-control regular" name="mos_courier_options['x'][regular]" placeholder="Regular 1KG"></td>
													<td><input type="text" class="form-control extra" name="mos_courier_options['x'][extra]" placeholder="Extra Charge"></td>
													<td><input type="text" class="form-control urgent" name="mos_courier_options['x'][urgent]" placeholder="Urgent Charge"></td>
												</tr>										
											</tbody>											
										</table>
										<button type="button" class="btn btn-success btn-sm btn-add-charge" value="<?php echo $n; ?>"><i class="fa fa-plus-circle"></i> Add More</button>	
									<!-- </div> -->
								</div>
								<div class="form-group row">
									<div class="col-lg-8 offset-lg-4">
									<button type="submit" class="btn btn-primary">Save</button>
									</div>
								</div>


							</div>
							<!-- /.card-body -->

							<!-- <div class="card-footer">
							</div> -->
						</form>
					</div>
	<?php
		endif;
	}
}
add_action('courier_content', 'courier_report_content', 10, 1 );
if (!function_exists('courier_report_content')) {
	function courier_report_content($args) {
		global $religion_arr, $gender_arr, $user_role_arr, $user_activation, $payment_methods;
		if ( $args == 'report') :?>
		<?php
		$args = array(
			'post_type' => 'courierorder',
			'posts_per_page' => -1,
		);
		if (@$_POST["_mos_courier_merchant_name"]){
			$args['meta_query']['merchant_name'] = array(
				'key' => '_mos_courier_merchant_name',
				'value' => $_POST["_mos_courier_merchant_name"],
				//'value'   => array( 3, 4 ),
			);
		}
		if (@$_POST["_mos_courier_merchant_number"]){
			$args['meta_query']['merchant_number'] = array(
				'key' => '_mos_courier_merchant_number',
				'value' => $_POST["_mos_courier_merchant_number"],
				//'value'   => array( 3, 4 ),
			);
		}
		if (@$_POST["_mos_courier_booking_date_from"]){
			$date_from = date("Y-m-d", strtotime($_POST["_mos_courier_booking_date_from"]));
			$args['meta_query']['booking_date'] = array(
				'key' => '_mos_courier_booking_date',
				'value' => array($date_from,date('Y-m-d')),
				'type' => 'date',
				'compare' => 'BETWEEN',
			);

			if (@$_POST["_mos_courier_booking_date_to"]){
				$date_to = date("Y-m-d", strtotime($_POST["_mos_courier_booking_date_to"]));
				$args['meta_query']['booking_date'] = array(
					'key' => '_mos_courier_booking_date',
					'value' => array($date_from,$date_to),
					'type' => 'date',
					'compare' => 'BETWEEN',
				);
			}
		}
		if (@$_POST["_mos_courier_receiver_name"]){
			$args['meta_query']['receiver_name'] = array(
				'key' => '_mos_courier_receiver_name',
				'value' => $_POST["_mos_courier_receiver_name"],
				//'value'   => array( 3, 4 ),
			);
		}
		if (@$_POST["_mos_courier_receiver_number"]){
			$args['meta_query']['receiver_number'] = array(
				'key' => '_mos_courier_receiver_number',
				'value' => $_POST["_mos_courier_receiver_number"],
				//'value'   => array( 3, 4 ),
			);
		}
		if (@$_POST["_mos_courier_delivery_date_from"]){
			$date_from = date("Y-m-d", strtotime($_POST["_mos_courier_delivery_date_from"]));
			$args['meta_query']['delivery_date'] = array(
				'key' => '_mos_courier_delivery_date',
				'value' => array($date_from,date('Y-m-d')),
				'type' => 'date',
				'compare' => 'BETWEEN',
			);

			if (@$_POST["_mos_courier_delivery_date_to"]){
				$date_to = date("Y-m-d", strtotime($_POST["_mos_courier_delivery_date_to"]));
				$args['meta_query']['delivery_date'] = array(
					'key' => '_mos_courier_delivery_date',
					'value' => array($date_from,$date_to),
					'type' => 'date',
					'compare' => 'BETWEEN',
				);
			}
		}

		if (@$_POST["_mos_courier_delivery_zone"]){
			$args['meta_query']['delivery_zone'] = array(
				'key' => '_mos_courier_delivery_zone',
				'value' => $_POST["_mos_courier_delivery_zone"],
				//'value'   => array( 3, 4 ),
			);
		}
		if (@$_POST["_mos_courier_delivery_man"]){
			$args['meta_query']['delivery_man'] = array(
				'key' => '_mos_courier_delivery_man',
				'value' => $_POST["_mos_courier_delivery_man"],
				//'value'   => array( 3, 4 ),
			);
		}
		if (@$_POST["_mos_courier_delivery_status"]){
			$args['meta_query']['delivery_status'] = array(
				'key' => '_mos_courier_delivery_status',
				'value' => $_POST["_mos_courier_delivery_status"],
				//'value'   => array( 3, 4 ),
			);
		}
		if (@$_POST["_mos_courier_payment_status"]){
			$args['meta_query']['payment_status'] = array(
				'key' => '_mos_courier_payment_status',
				'value' => $_POST["_mos_courier_payment_status"],
				//'value'   => array( 3, 4 ),
			);
		}
		// var_dump($args);
		?>
					<div class="card card-primary">
						<div class="card-header">
							<h3 class="card-title">Report</h3>
						</div>
						<!-- /.card-header -->
						

							<div class="card-body">
								<form id="report_form" role="form" method="post" action="">
									<?php wp_nonce_field( 'report_form', 'report_form_field' ); ?>
									<div class="accordion" id="accordionExample">											
										<a href="javascript:void(0)" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne"><h4 class="text-success pb-2 border-success border-bottom">Filter Options</h4></a>
										<div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordionExample">
											<div class="form-row">
												<div class="col-lg-3">
													<div class="form-group">
														<label for="_mos_courier_merchant_name">Merchant Info</label>
														<select name="_mos_courier_merchant_name" id="_mos_courier_merchant_name" class="form-control select2">
															<option value="">All Merchant</option>
														<?php $merchant = mos_user_list('merchant'); ?>
		     											<?php foreach ($merchant as $key => $value) : ?>
															<option value="<?php echo $key ?>" <?php selected( $_POST['_mos_courier_merchant_name'], $key ); ?>><?php echo $value ?>(<?php echo get_user_meta( $key, 'brand_name', true ); ?>)</option>
														<?php endforeach; ?>
														</select>
													</div>
													<div class="form-group">
														<input name="_mos_courier_merchant_number" id="_mos_courier_merchant_number" type="tel" class="form-control" placeholder="Merchant Phone" value="<?php echo @$_POST['_mos_courier_merchant_number'] ?>">
													</div>
												</div>
												<div class="col-lg-3">
													<div class="form-group">
														<label for="_mos_courier_booking_date_from">Booking Info</label>
														<input name="_mos_courier_booking_date_from" id="_mos_courier_booking_date_from" type="date" class="form-control" value="<?php echo @$_POST['_mos_courier_booking_date_from'] ?>">
													</div>
													<div class="form-group">
														<input name="_mos_courier_booking_date_to" id="_mos_courier_booking_date_to" type="date" class="form-control" value="<?php echo @$_POST['_mos_courier_booking_date_to'] ?>">
													</div>
												</div>
												<div class="col-lg-3">
													<div class="form-group">
														<label for="_mos_courier_receiver_name">Receiver Info</label>
														<input name="_mos_courier_receiver_name" id="_mos_courier_receiver_name" type="text" class="form-control" placeholder="Receiver Name" value="<?php echo @$_POST['_mos_courier_receiver_name'] ?>">
													</div>
													<div class="form-group">
														<input name="_mos_courier_receiver_number" id="_mos_courier_receiver_number" type="tel" class="form-control" placeholder="Receiver Phone" value="<?php echo @$_POST['_mos_courier_receiver_number'] ?>">
													</div>
												</div>
												<div class="col-lg-3">
													<div class="form-group">
														<label for="_mos_courier_delivery_date_from">Delivery Info</label>
														<input name="_mos_courier_delivery_date_from" id="_mos_courier_delivery_date_from" type="date" class="form-control" value="<?php echo @$_POST['_mos_courier_delivery_date_from'] ?>">
													</div>
													<div class="form-group">
														<input name="_mos_courier_delivery_date_to" id="_mos_courier_delivery_date_to" type="date" class="form-control" value="<?php echo @$_POST['_mos_courier_delivery_date_to'] ?>">
													</div>
												</div>
												<div class="col-lg-3">
													<div class="form-group">
														<label for="_mos_courier_delivery_zone">Delivery Zone</label>
														<select name="_mos_courier_delivery_zone[]" id="_mos_courier_delivery_zone" class="form-control select2" multiple>
															<option value="">Select Zone</option>
														<?php 
														$options = get_option( 'mos_courier_options' );
														$zone = mos_str_to_arr($options['zone'], '|');
														?>
		     											<?php foreach ($zone as $value) : ?>
															<option value="<?php echo $value ?>" 
															<?php if (in_array($value, @$_POST['_mos_courier_delivery_zone'])) echo 'selected';?>
															><?php echo $value ?></option>
														<?php endforeach; ?>
														</select>
													</div>
												</div>
												<div class="col-lg-3">
													<div class="form-group">
														<label for="_mos_courier_delivery_man">Delivery Man</label>
														<select name="_mos_courier_delivery_man[]" id="_mos_courier_delivery_man" class="form-control select2" multiple>
															<option value="">Select Delivery Man</option>
														<?php 
														$delivery_man = mos_user_list('operator', 'user_role', 'Delivery Man');
	     	
														?>
		     											<?php foreach ($delivery_man as $key => $value) : ?>
															<option value="<?php echo $key ?>" 
															<?php if (in_array($key, @$_POST['_mos_courier_delivery_man'])) echo 'selected';?>
															><?php echo $value ?></option>
														<?php endforeach; ?>
														</select>
													</div>
												</div>
												<div class="col-lg-3">
													<div class="form-group">
														<label for="_mos_courier_delivery_status">Delivery Status</label>
														<select name="_mos_courier_delivery_status[]" id="_mos_courier_delivery_status" class="form-control select2" multiple>
															<option value="">Select Delivery Status</option>
														<?php global $order_status_arr;?>
		     											<?php foreach ($order_status_arr as $key => $value) : ?>
															<option value="<?php echo $key ?>"
															<?php if (in_array($key, @$_POST['_mos_courier_delivery_status'])) echo 'selected';?>
															><?php echo $value ?></option>
														<?php endforeach; ?>
														</select>
													</div>
												</div>
												<div class="col-lg-3">
													<div class="form-group">
														<label for="_mos_courier_payment_status">Payment Status</label>
														<select name="_mos_courier_payment_status[]" id="_mos_courier_payment_status" class="form-control select2" multiple>
															<option value="">Select Payment Status</option>
														<?php global $payment_status_arr;?>
		     											<?php foreach ($payment_status_arr as $key => $value) : ?>
															<option value="<?php echo $key ?>"
															<?php if (in_array($key, @$_POST['_mos_courier_payment_status'])) echo 'selected';?>
															><?php echo $value ?></option>
														<?php endforeach; ?>
														</select>
													</div>
												</div>
											</div>
										</div>											
										<a href="javascript:void(0)" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="true" aria-controls="collapseTwo"><h4 class="text-success pb-2 border-success border-bottom">Table Columns</h4></a>
										<div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionExample">
											<div class="form-check form-check-inline">
												<input class="form-check-input" type="checkbox" id="output_cl_no" name="output_cl_no" value="1" <?php checked( $_POST['output_cl_no'], 1 ); ?>>
												<label class="form-check-label" for="output_cl_no">CL NO</label>
											</div>
											<div class="form-check form-check-inline">
												<input class="form-check-input" type="checkbox" id="output_order_id" name="output_order_id" value="1" <?php checked( $_POST['output_order_id'], 1 ); ?>>
												<label class="form-check-label" for="output_order_id">Merchant Order ID</label>
											</div>
											<div class="form-check form-check-inline">
												<input class="form-check-input" type="checkbox" id="output_merchant_name" name="output_merchant_name" value="1" <?php checked( $_POST['output_merchant_name'], 1 ); ?>>
												<label class="form-check-label" for="output_merchant_name">Merchant Name</label>
											</div>
											<div class="form-check form-check-inline">
												<input class="form-check-input" type="checkbox" id="output_merchant_address" name="output_merchant_address" value="1" <?php checked( $_POST['output_merchant_address'], 1 ); ?>>
												<label class="form-check-label" for="output_merchant_address">Merchant Address</label>
											</div>
											<div class="form-check form-check-inline">
												<input type="checkbox" class="form-check-input" id="output_merchant_number" name="output_merchant_number" value="1" <?php checked( $_POST['output_merchant_number'], 1 ); ?>>
												<label class="form-check-label" for="output_merchant_number">Merchant Number</label>
											</div>									
											<div class="form-check form-check-inline">
												<input type="checkbox" class="form-check-input" id="output_booking_date" name="output_booking_date" value="1" <?php checked( $_POST['output_booking_date'], 1 ); ?>>
												<label class="form-check-label" for="output_booking_date">Booking Date</label>
											</div>										
											<div class="form-check form-check-inline">
												<input type="checkbox" class="form-check-input" id="output_product_name" name="output_product_name" value="1" <?php checked( $_POST['output_product_name'], 1 ); ?>>
												<label class="form-check-label" for="output_product_name">Product Name</label>
											</div>									
											<div class="form-check form-check-inline">
												<input type="checkbox" class="form-check-input" id="output_product_price" name="output_product_price" value="1" <?php checked( $_POST['output_product_price'], 1 ); ?>>
												<label class="form-check-label" for="output_product_price">Product Price</label>
											</div>								
											<div class="form-check form-check-inline">
												<input type="checkbox" class="form-check-input" id="output_product_quantity" name="output_product_quantity" value="1" <?php checked( $_POST['output_product_quantity'], 1 ); ?>>
												<label class="form-check-label" for="output_product_quantity">Product Quantity</label>
											</div>							
											<div class="form-check form-check-inline">
												<input type="checkbox" class="form-check-input" id="output_receiver_name" name="output_receiver_name" value="1" <?php checked( $_POST['output_receiver_name'], 1 ); ?>>
												<label class="form-check-label" for="output_receiver_name">Receiver Name</label>
											</div>						
											<div class="form-check form-check-inline">
												<input type="checkbox" class="form-check-input" id="output_receiver_address" name="output_receiver_address" value="1" <?php checked( $_POST['output_receiver_address'], 1 ); ?>>
												<label class="form-check-label" for="output_receiver_address">Receiver Address</label>
											</div>					
											<div class="form-check form-check-inline">
												<input type="checkbox" class="form-check-input" id="output_receiver_number" name="output_receiver_number" value="1" <?php checked( $_POST['output_receiver_number'], 1 ); ?>>
												<label class="form-check-label" for="output_receiver_number">Receiver Number</label>
											</div>				
											<div class="form-check form-check-inline">
												<input type="checkbox" class="form-check-input" id="output_total_weight" name="output_total_weight" value="1" <?php checked( $_POST['output_total_weight'], 1 ); ?>>
												<label class="form-check-label" for="output_total_weight">Total Weight</label>
											</div>			
											<div class="form-check form-check-inline">
												<input type="checkbox" class="form-check-input" id="output_packaging_type" name="output_packaging_type" value="1" <?php checked( $_POST['output_packaging_type'], 1 ); ?>>
												<label class="form-check-label" for="output_packaging_type">Packaging Type</label>
											</div>	
											<div class="form-check form-check-inline">
												<input type="checkbox" class="form-check-input" id="output_delivery_charge" name="output_delivery_charge" value="1" <?php checked( $_POST['output_delivery_charge'], 1 ); ?>>
												<label class="form-check-label" for="output_delivery_charge">Delivery Charge</label>
											</div>
											<div class="form-check form-check-inline">
												<input type="checkbox" class="form-check-input" id="output_paid_amount" name="output_paid_amount" value="1" <?php checked( $_POST['output_paid_amount'], 1 ); ?>>
												<label class="form-check-label" for="output_paid_amount">Paid by Customer</label>
											</div>
											<div class="form-check form-check-inline">
												<input type="checkbox" class="form-check-input" id="output_payment_date" name="output_payment_date" value="1" <?php checked( $_POST['output_payment_date'], 1 ); ?>>
												<label class="form-check-label" for="output_payment_date">Payment Date</label>
											</div>	
											<div class="form-check form-check-inline">
												<input type="checkbox" class="form-check-input" id="output_delivery_zone" name="output_delivery_zone" value="1" <?php checked( $_POST['output_delivery_zone'], 1 ); ?>>
												<label class="form-check-label" for="output_delivery_zone">Delivery Zone</label>
											</div>
											<div class="form-check form-check-inline">
												<input type="checkbox" class="form-check-input" id="output_delivery_man" name="output_delivery_man" value="1" <?php checked( $_POST['output_delivery_man'], 1 ); ?>>
												<label class="form-check-label" for="output_delivery_man">Delivery Man</label>
											</div>
											<div class="form-check form-check-inline">
												<input type="checkbox" class="form-check-input" id="output_delivery_date" name="output_delivery_date" value="1" <?php checked( $_POST['output_delivery_date'], 1 ); ?>>
												<label class="form-check-label" for="output_delivery_date">Delivery Date</label>
											</div>
											<div class="form-check form-check-inline">
												<input type="checkbox" class="form-check-input" id="output_delivery_status" name="output_delivery_status" value="1" <?php checked( $_POST['output_delivery_status'], 1 ); ?>>
												<label class="form-check-label" for="output_delivery_status">Delivery Status</label>
											</div>
											<div class="form-check form-check-inline">
												<input type="checkbox" class="form-check-input" id="output_payment_status" name="output_payment_status" value="1" <?php checked( $_POST['output_payment_status'], 1 ); ?>>
												<label class="form-check-label" for="output_payment_status">Payment Status</label>
											</div>
											<div class="form-check form-check-inline">
												<input type="checkbox" class="form-check-input" id="output_payments" name="output_payments" value="1" <?php checked( $_POST['output_payments'], 1 ); ?>>
												<label class="form-check-label" for="output_payments">Paid to Merchant</label>
											</div>	
										</div>
									</div>
									<button type="submit" class="btn btn-sm btn-success" name="report-filter-sub" value="submit">Filter</button>
								</form>
							<!-- form start -->
							<!-- <form id="report_step_one" role="form" method="post" action="">
								<?php // wp_nonce_field( 'report_step_one_form', 'report_step_one_form_field' ); ?>
								<div class="row mb-3">
									<div class="col-lg-6">
										<h4>Filter Options</h4>
										<div class="form-part">
											<div class="custom-control custom-checkbox">
												<input type="checkbox" class="custom-control-input" id="input_cl_no" name="input_cl_no" value="1" disabled>
												<label class="custom-control-label" for="input_cl_no">CL NO</label>
											</div>
											<div class="custom-control custom-checkbox">
												<input type="checkbox" class="custom-control-input" id="input_order_id" name="input_order_id" value="1" disabled>
												<label class="custom-control-label" for="input_order_id">Merchant Order ID</label>
											</div>
											<div class="custom-control custom-checkbox">
												<input type="checkbox" class="custom-control-input" id="input_merchant_name" name="input_merchant_name" value="1">
												<label class="custom-control-label" for="input_merchant_name">Merchant Name</label>
											</div>
											<div class="custom-control custom-checkbox">
												<input type="checkbox" class="custom-control-input" id="input_merchant_address" name="input_merchant_address" value="1">
												<label class="custom-control-label" for="input_merchant_address">Merchant Address</label>
											</div>
											<div class="custom-control custom-checkbox">
												<input type="checkbox" class="custom-control-input" id="input_merchant_number" name="input_merchant_number" value="1">
												<label class="custom-control-label" for="input_merchant_number">Merchant Number</label>
											</div>											
											<div class="custom-control custom-checkbox">
												<input type="checkbox" class="custom-control-input" id="input_booking_date" name="input_booking_date" value="1">
												<label class="custom-control-label" for="input_booking_date">Booking Date</label>
											</div>										
											<div class="custom-control custom-checkbox">
												<input type="checkbox" class="custom-control-input" id="input_product_name" name="input_product_name" value="1">
												<label class="custom-control-label" for="input_product_name">Product Name</label>
											</div>									
											<div class="custom-control custom-checkbox">
												<input type="checkbox" class="custom-control-input" id="input_product_price" name="input_product_price" value="1">
												<label class="custom-control-label" for="input_product_price">Product Price</label>
											</div>								
											<div class="custom-control custom-checkbox">
												<input type="checkbox" class="custom-control-input" id="input_product_quantity" name="input_product_quantity" value="1">
												<label class="custom-control-label" for="input_product_quantity">Product Quantity</label>
											</div>							
											<div class="custom-control custom-checkbox">
												<input type="checkbox" class="custom-control-input" id="input_receiver_name" name="input_receiver_name" value="1">
												<label class="custom-control-label" for="input_receiver_name">Receiver Name</label>
											</div>						
											<div class="custom-control custom-checkbox">
												<input type="checkbox" class="custom-control-input" id="input_receiver_address" name="input_receiver_address" value="1">
												<label class="custom-control-label" for="input_receiver_address">Receiver Address</label>
											</div>					
											<div class="custom-control custom-checkbox">
												<input type="checkbox" class="custom-control-input" id="input_receiver_number" name="input_receiver_number" value="1">
												<label class="custom-control-label" for="input_receiver_number">Receiver Number</label>
											</div>				
											<div class="custom-control custom-checkbox">
												<input type="checkbox" class="custom-control-input" id="input_total_weight" name="input_total_weight" value="1">
												<label class="custom-control-label" for="input_total_weight">Total Weight</label>
											</div>			
											<div class="custom-control custom-checkbox">
												<input type="checkbox" class="custom-control-input" id="input_packaging_type" name="input_packaging_type" value="1">
												<label class="custom-control-label" for="input_packaging_type">Packaging Type</label>
											</div>	
											<div class="custom-control custom-checkbox">
												<input type="checkbox" class="custom-control-input" id="input_delivery_charge" name="input_delivery_charge" value="1">
												<label class="custom-control-label" for="input_delivery_charge">Delivery Charge</label>
											</div>
											<div class="custom-control custom-checkbox">
												<input type="checkbox" class="custom-control-input" id="input_paid_amount" name="input_paid_amount" value="1">
												<label class="custom-control-label" for="input_paid_amount">Paid by Customer</label>
											</div>
											<div class="custom-control custom-checkbox">
												<input type="checkbox" class="custom-control-input" id="input_payment_date" name="input_payment_date" value="1">
												<label class="custom-control-label" for="input_payment_date">Payment Date</label>
											</div>	
											<div class="custom-control custom-checkbox">
												<input type="checkbox" class="custom-control-input" id="input_delivery_zone" name="input_delivery_zone" value="1">
												<label class="custom-control-label" for="input_delivery_zone">Delivery Zone</label>
											</div>
											<div class="custom-control custom-checkbox">
												<input type="checkbox" class="custom-control-input" id="input_delivery_man" name="input_delivery_man" value="1">
												<label class="custom-control-label" for="input_delivery_man">Delivery Man</label>
											</div>
											<div class="custom-control custom-checkbox">
												<input type="checkbox" class="custom-control-input" id="input_delivery_date" name="input_delivery_date" value="1">
												<label class="custom-control-label" for="input_delivery_date">Delivery Date</label>
											</div>
											<div class="custom-control custom-checkbox">
												<input type="checkbox" class="custom-control-input" id="input_delivery_status" name="input_delivery_status" value="1">
												<label class="custom-control-label" for="input_delivery_status">Delivery Status</label>
											</div>
											<div class="custom-control custom-checkbox">
												<input type="checkbox" class="custom-control-input" id="input_payment_status" name="input_payment_status" value="1">
												<label class="custom-control-label" for="input_payment_status">Payment Status</label>
											</div>
											<div class="custom-control custom-checkbox">
												<input type="checkbox" class="custom-control-input" id="input_payments" name="input_payments" value="1" disabled>
												<label class="custom-control-label" for="input_payments">Paid to Merchant</label>
											</div>											
										</div>
									</div>
								</div>
								<button type="submit" class="btn btn-sm btn-success" name="report-generate-sub" value="submit">Generate Form</button>			
							</form>
							<div id="final-report" style="display: none">
								<form id="report_step_two" role="form" method="post" action="" sty>
									<?php // wp_nonce_field( 'report_step_two_form', 'report_step_two_form_field' ); ?>
									<div class="form-row mb-3">
									</div>
									<button type="submit" class="btn btn-sm btn-success" name="report-filter-sub" value="submit">Filter</button>
								</form>
							</div> -->
							<?php $the_query = new WP_Query( $args ); ?>
								<?php if ( $the_query->have_posts() ) : ?>

								<div class="table-responsive mt-3">
									<table id="example1" class="table table-bordered table-striped">
										<thead>
											<tr>
											<?php if ($_POST['output_cl_no']) : ?>
												<th>CN NO</th>
											<?php endif; ?>
											<?php if ($_POST['output_order_id']) : ?>
												<th>Merchant Order ID</th>
											<?php endif; ?>
											<?php if ($_POST['output_merchant_name']) : ?>
												<th>Merchant Name</th>
											<?php endif; ?>
											<?php if ($_POST['output_merchant_address']) : ?>
												<th>Merchant Address</th>
											<?php endif; ?>
											<?php if ($_POST['output_merchant_number']) : ?>
												<th>Merchant Number</th>
											<?php endif; ?>
											<?php if ($_POST['output_booking_date']) : ?>
												<th>Booking Date</th>
											<?php endif; ?>
											<?php if ($_POST['output_product_name']) : ?>
												<th>Product Name</th>
											<?php endif; ?>
											<?php if ($_POST['output_product_price']) : ?>
												<th>Product Price</th>
											<?php endif; ?>
											<?php if ($_POST['output_product_quantity']) : ?>
												<th>Product Quantity</th>
											<?php endif; ?>
											<?php if ($_POST['output_receiver_name']) : ?>
												<th>Receiver Name</th>
											<?php endif; ?>
											<?php if ($_POST['output_receiver_address']) : ?>
												<th>Receiver Address</th>
											<?php endif; ?>
											<?php if ($_POST['output_receiver_number']) : ?>
												<th>Receiver Number</th>
											<?php endif; ?>
											<?php if ($_POST['output_total_weight']) : ?>
												<th>Total Weight</th>
											<?php endif; ?>
											<?php if ($_POST['output_packaging_type']) : ?>
												<th>Packaging Type</th>
											<?php endif; ?>
											<?php if ($_POST['output_delivery_charge']) : ?>
												<th>Delivery Charge</th>
											<?php endif; ?>
											<?php if ($_POST['output_paid_amount']) : ?>
												<th>Paid by Customer</th>
											<?php endif; ?>
											<?php if ($_POST['output_payment_date']) : ?>
												<th>Payment Date</th>
											<?php endif; ?>
											<?php if ($_POST['output_delivery_zone']) : ?>
												<th>Delivery Zone</th>
											<?php endif; ?>
											<?php if ($_POST['output_delivery_man']) : ?>
												<th>Delivery Man</th>
											<?php endif; ?>
											<?php if ($_POST['output_delivery_date']) : ?>
												<th>Delivery Date</th>
											<?php endif; ?>
											<?php if ($_POST['output_delivery_status']) : ?>
												<th>Delivery Status</th>
											<?php endif; ?>
											<?php if ($_POST['output_payment_status']) : ?>
												<th>Payment Status</th>
											<?php endif; ?>
											<?php if ($_POST['output_payments']) : ?>
												<th>Merchant Bill</th>
											<?php endif; ?>
											</tr>
										</thead>
										<tbody>
									<?php while ( $the_query->have_posts() ) : $the_query->the_post(); ?>
										<?php $post_id = get_the_ID()?>
											<tr>
											<?php if ($_POST['output_cl_no']) : ?>
												<th><?php echo get_the_title(); ?></th>
											<?php endif; ?>
											<?php if ($_POST['output_order_id']) : ?>
												<th><?php echo $post_id; ?></th>
											<?php endif; ?>
											<?php if ($_POST['output_merchant_name']) : ?>
												<th>
												<?php 
												$merchant_id = get_post_meta( $post_id, '_mos_courier_merchant_name', true );
												echo get_userdata($merchant_id)->display_name;
												?>	
												</th>
											<?php endif; ?>
											<?php if ($_POST['output_merchant_address']) : ?>
												<th><?php echo get_post_meta( $post_id, '_mos_courier_merchant_address', true ) ?></th>
											<?php endif; ?>
											<?php if ($_POST['output_merchant_number']) : ?>
												<th><?php echo get_post_meta( $post_id, '_mos_courier_merchant_number', true ) ?></th>
											<?php endif; ?>
											<?php if ($_POST['output_booking_date']) : ?>
												<th><?php echo get_post_meta( $post_id, '_mos_courier_booking_date', true ) ?></th>
											<?php endif; ?>
											<?php if ($_POST['output_product_name']) : ?>
												<th><?php echo get_post_meta( $post_id, '_mos_courier_product_name', true ) ?></th>
											<?php endif; ?>
											<?php if ($_POST['output_product_price']) : ?>
												<th><?php echo get_post_meta( $post_id, '_mos_courier_product_price', true ) ?></th>
											<?php endif; ?>
											<?php if ($_POST['output_product_quantity']) : ?>
												<th><?php echo get_post_meta( $post_id, '_mos_courier_product_quantity', true ) ?></th>
											<?php endif; ?>
											<?php if ($_POST['output_receiver_name']) : ?>
												<th><?php echo get_post_meta( $post_id, '_mos_courier_receiver_name', true ) ?></th>
											<?php endif; ?>
											<?php if ($_POST['output_receiver_address']) : ?>
												<th><?php echo get_post_meta( $post_id, '_mos_courier_receiver_address', true ) ?></th>
											<?php endif; ?>
											<?php if ($_POST['output_receiver_number']) : ?>
												<th><?php echo get_post_meta( $post_id, '_mos_courier_receiver_number', true ) ?></th>
											<?php endif; ?>
											<?php if ($_POST['output_total_weight']) : ?>
												<th><?php echo get_post_meta( $post_id, '_mos_courier_total_weight', true ) ?></th>
											<?php endif; ?>
											<?php if ($_POST['output_packaging_type']) : ?>
												<th><?php echo get_post_meta( $post_id, '_mos_courier_packaging_type', true ) ?></th>
											<?php endif; ?>
											<?php if ($_POST['output_delivery_charge']) : ?>
												<th><?php echo get_post_meta( $post_id, '_mos_courier_delivery_charge', true ) ?></th>
											<?php endif; ?>
											<?php if ($_POST['output_paid_amount']) : ?>
												<th><?php echo get_post_meta( $post_id, '_mos_courier_paid_amount', true ) ?></th>
											<?php endif; ?>
											<?php if ($_POST['output_payment_date']) : ?>
												<th><?php echo get_post_meta( $post_id, '_mos_courier_payment_date', true ) ?></th>
											<?php endif; ?>
											<?php if ($_POST['output_delivery_zone']) : ?>
												<th><?php echo get_post_meta( $post_id, '_mos_courier_delivery_zone', true ) ?></th>
											<?php endif; ?>
											<?php if ($_POST['output_delivery_man']) : ?>
												<th>
												<?php
												$merchant_id = get_post_meta( $post_id, '_mos_courier_delivery_man', true );
												echo get_userdata($merchant_id)->display_name;
												?>													
												</th>
											<?php endif; ?>
											<?php if ($_POST['output_delivery_date']) : ?>
												<th><?php echo get_post_meta( $post_id, '_mos_courier_delivery_date', true ) ?></th>
											<?php endif; ?>
											<?php if ($_POST['output_delivery_status']) : ?>
												<th><?php echo get_post_meta( $post_id, '_mos_courier_delivery_status', true ) ?></th>
											<?php endif; ?>
											<?php if ($_POST['output_payment_status']) : ?>
												<th><?php echo get_post_meta( $post_id, '_mos_courier_payment_status', true ) ?></th>
											<?php endif; ?>
											<?php if ($_POST['output_payments']) : ?>
												<th>
													<?php
													$payments = get_post_meta( $post_id, '_mos_courier_payments', true );
													foreach ($payments as $rawdate => $bill) {
														echo $bill . '<br />';
													}
													?>
													
												</th>
											<?php endif; ?>
											</tr>
									<?php endwhile; ?>
										</tbody>
										<tfoot>
											<tr>
											<?php if ($_POST['output_cl_no']) : ?>
												<th>CN NO</th>
											<?php endif; ?>
											<?php if ($_POST['output_order_id']) : ?>
												<th>Merchant Order ID</th>
											<?php endif; ?>
											<?php if ($_POST['output_merchant_name']) : ?>
												<th>Merchant Name</th>
											<?php endif; ?>
											<?php if ($_POST['output_merchant_address']) : ?>
												<th>Merchant Address</th>
											<?php endif; ?>
											<?php if ($_POST['output_merchant_number']) : ?>
												<th>Merchant Number</th>
											<?php endif; ?>
											<?php if ($_POST['output_booking_date']) : ?>
												<th>Booking Date</th>
											<?php endif; ?>
											<?php if ($_POST['output_product_name']) : ?>
												<th>Product Name</th>
											<?php endif; ?>
											<?php if ($_POST['output_product_price']) : ?>
												<th>Product Price</th>
											<?php endif; ?>
											<?php if ($_POST['output_product_quantity']) : ?>
												<th>Product Quantity</th>
											<?php endif; ?>
											<?php if ($_POST['output_receiver_name']) : ?>
												<th>Receiver Name</th>
											<?php endif; ?>
											<?php if ($_POST['output_receiver_address']) : ?>
												<th>Receiver Address</th>
											<?php endif; ?>
											<?php if ($_POST['output_receiver_number']) : ?>
												<th>Receiver Number</th>
											<?php endif; ?>
											<?php if ($_POST['output_total_weight']) : ?>
												<th>Total Weight</th>
											<?php endif; ?>
											<?php if ($_POST['output_packaging_type']) : ?>
												<th>Packaging Type</th>
											<?php endif; ?>
											<?php if ($_POST['output_delivery_charge']) : ?>
												<th>Delivery Charge</th>
											<?php endif; ?>
											<?php if ($_POST['output_paid_amount']) : ?>
												<th>Paid by Customer</th>
											<?php endif; ?>
											<?php if ($_POST['output_payment_date']) : ?>
												<th>Payment Date</th>
											<?php endif; ?>
											<?php if ($_POST['output_delivery_zone']) : ?>
												<th>Delivery Zone</th>
											<?php endif; ?>
											<?php if ($_POST['output_delivery_man']) : ?>
												<th>Delivery Man</th>
											<?php endif; ?>
											<?php if ($_POST['output_delivery_date']) : ?>
												<th>Delivery Date</th>
											<?php endif; ?>
											<?php if ($_POST['output_delivery_status']) : ?>
												<th>Delivery Status</th>
											<?php endif; ?>
											<?php if ($_POST['output_payment_status']) : ?>
												<th>Payment Status</th>
											<?php endif; ?>
											<?php if ($_POST['output_payments']) : ?>
												<th>Merchant Bill</th>
											<?php endif; ?>
											</tr>
										</tfoot>
									</table>
								</div>
								<?php endif; ?>
								<?php wp_reset_postdata(); ?>
							</div>
					</div>
			<?php
		endif;
	}
}
/*Ajax*/

function get_merchant_details_func() {
	$output = array();
    if ( isset($_REQUEST) ) {     
        $merchant_id = $_REQUEST['merchant_id'];
      
        $output['address'] = mos_user_address($merchant_id); 
        $output['phone'] = mos_user_phone($merchant_id);  
        $output['delivery_charge'] = get_user_meta( $merchant_id, 'delivery_charge', true );
        $output['additional_charge'] = get_user_meta( $merchant_id, 'additional_charge', true );

        echo json_encode($output);   
    }
   die();
} 
add_action( 'wp_ajax_get_merchant_details', 'get_merchant_details_func' );
add_action( 'wp_ajax_nopriv_get_merchant_details', 'get_merchant_details_func' );

function check_out_oreder_details_func() {
	$output = array();
	$data = array();
    if ( isset($_REQUEST) ) {     
        //$output = $_REQUEST['formdata'];
        $arr = explode('&', $_REQUEST['formdata']);
        foreach($arr as $value){
        	$value1 = explode('=', $value);
        	$data[$value1[0]] = $value1[1];
        }
        if ($data['check_out_cn_no']){
        	$order = get_page_by_title( $data['check_out_cn_no'], OBJECT, 'courierorder' );
        	if ($order->ID){
	        	$delivery_status = get_post_meta( $order->ID, '_mos_courier_delivery_status', true );
	        	if ($delivery_status == 'received' OR $delivery_status == 'hold'){
	        		$output[0]['id'] = intval($order->ID);      		
	        		$output[0]['title'] = get_the_title( $order->ID );      		
	        		$output[0]['receiver_address'] = get_post_meta( $order->ID, '_mos_courier_receiver_address', true );
	        		$output[0]['delivery_zone'] = get_post_meta( $order->ID, '_mos_courier_delivery_zone', true );	        		
	        	}
	        }
        } elseif ($data['check_out_zone']) {
			$args = array(
				'post_type' => 'courierorder',
				'posts_per_page'=>-1,
			    // 'meta_key'     => '_mos_courier_delivery_status',
			    // 'meta_value'   => 'pending',
			    // 'meta_compare' => '='
			    'meta_query' => array(
			        'relation' => 'AND',
			        'delivery_status' => array(
			            'key' => '_mos_courier_delivery_status',
			            'value' => array('received', 'hold'),
			    		'compare' => 'IN',
			        ),
			        'delivery_zone' => array(
			            'key' => '_mos_courier_delivery_zone',
			            'value' => $data['check_out_zone'],
			    		'compare' => '='
			        ), 
			    ),
			);
			$n = 0;
			$the_query = new WP_Query( $args );
			if ( $the_query->have_posts() ) :
			    while ( $the_query->have_posts() ) : $the_query->the_post();
	        		$output[$n]['id'] = intval(get_the_ID());      		
	        		$output[$n]['title'] = get_the_title();      		
	        		$output[$n]['receiver_address'] = get_post_meta( get_the_ID(), '_mos_courier_receiver_address', true );
	        		$output[$n]['delivery_zone'] = get_post_meta( get_the_ID(), '_mos_courier_delivery_zone', true );
			        $n++;
			    endwhile;
			endif;
			wp_reset_postdata();
        } elseif ($data['check_out_cn_multi_no']) {
        	$titles = explode('%2C', $data['check_out_cn_multi_no']);
        	$n = 0;
        	foreach ($titles as $title) {
        		$title = trim($title);
    			$order = get_page_by_title( $title, OBJECT, 'courierorder' );
	        	if ($order->ID){
		        	$delivery_status = get_post_meta( $order->ID, '_mos_courier_delivery_status', true );
		        	if ($delivery_status == 'received' OR $delivery_status == 'hold'){
		        		$output[$n]['id'] = intval($order->ID);      		
		        		$output[$n]['title'] = get_the_title( $order->ID );      		
		        		$output[$n]['receiver_address'] = get_post_meta( $order->ID, '_mos_courier_receiver_address', true );
		        		$output[$n]['delivery_zone'] = get_post_meta( $order->ID, '_mos_courier_delivery_zone', true );	        		
		        	}
		        }
		        $n++;
        	}
        }     
		echo json_encode($output);   
    }
	die();
} 
add_action( 'wp_ajax_check_out_oreder_details', 'check_out_oreder_details_func' );
add_action( 'wp_ajax_nopriv_check_out_oreder_details', 'check_out_oreder_details_func' );

function check_in_oreder_details_func() {
	$output = array();
	$data = array();
    if ( isset($_REQUEST) ) {     
        //$output = $_REQUEST['formdata'];
        $arr = explode('&', $_REQUEST['formdata']);
        foreach($arr as $value){
        	$value1 = explode('=', $value);
        	$data[$value1[0]] = $value1[1];
        }
        if ($data['check_in_cn_no']){
        	$order = get_page_by_title( $data['check_in_cn_no'], OBJECT, 'courierorder' );
        	if ($order->ID){
	        	$delivery_status = get_post_meta( $order->ID, '_mos_courier_delivery_status', true );
	        	if ($delivery_status == 'way'){
	        		$order_id = (get_post_meta( $order->ID, '_mos_courier_merchant_order_id', true ))?get_post_meta( $order->ID, '_mos_courier_merchant_order_id', true ):$order->ID;
	        		$output[0]['id'] = intval($order->ID);      		
	        		$output[0]['merchantid'] = intval($order_id);      		
	        		$output[0]['title'] = get_the_title( $order->ID );      		
	        		$output[0]['product_price'] = get_post_meta( $order->ID, '_mos_courier_product_price', true );	        		
	        	}
	        }
        } elseif ($data['delivery_man']) {
        	$n = 0;
        	//Collect order by user
        	$orders = get_user_meta( $data['delivery_man'], 'check-out', true );
			foreach($orders as $value){
				foreach($value as $order){
					$delivery_status = get_post_meta( $order, '_mos_courier_delivery_status', true );
		        	if ($delivery_status == 'way'){
	        			$order_id = (get_post_meta( $order, '_mos_courier_merchant_order_id', true ))?get_post_meta( $order->ID, '_mos_courier_merchant_order_id', true ):$order->ID;
		        		$output[$n]['id'] = intval($order);   
		        		$output[$n]['merchantid'] = intval($order_id);    		
		        		$output[$n]['title'] = get_the_title( $order );      		
		        		$output[$n]['product_price'] = get_post_meta( $order, '_mos_courier_product_price', true );	
		        		$n++;					
		        	}
				}

			}
        }      
		echo json_encode($output);   
    }
	die();
} 
add_action( 'wp_ajax_check_in_oreder_details', 'check_in_oreder_details_func' );
add_action( 'wp_ajax_nopriv_check_in_oreder_details', 'check_in_oreder_details_func' );

function bill_pay_details_func() {
	$output = array();
	$data = array();
    if ( isset($_REQUEST) ) {     
        //$output = $_REQUEST['formdata'];
        $arr = explode('&', $_REQUEST['formdata']);
        foreach($arr as $value){
        	$value1 = explode('=', $value);
        	$data[$value1[0]] = $value1[1];
        }
        //bill_pay_cn_no=PB-54781571859172&merchant=
        if ($data['bill_pay_cn_no']){        	
        	$order = get_page_by_title( $data['bill_pay_cn_no'], OBJECT, 'courierorder' );
        	if ($order->ID){
	        	$delivery_status = get_post_meta( $order->ID, '_mos_courier_delivery_status', true );
	        	if ($delivery_status == 'delivered' OR $delivery_status == 'pdelivered' OR $delivery_status == 'returned'){
	        		$output[0]['id'] = intval($order->ID);      		
	        		$output[0]['title'] = get_the_title( $order->ID );      		
	        		$output[0]['product_price'] = get_post_meta( $order->ID, '_mos_courier_product_price', true );	        		
	        		$output[0]['paid_amount'] = get_post_meta( $order->ID, '_mos_courier_paid_amount', true );	        		
	        		$output[0]['delivery_charge'] = get_post_meta( $order->ID, '_mos_courier_delivery_charge', true );
	        		$output[0]['delivery_date'] = get_post_meta( $order->ID, '_mos_courier_delivery_date', true );
	        		$output[0]['merchant_name'] = get_post_meta( $order->ID, '_mos_courier_merchant_name', true );
	        		$payments = get_post_meta( $order->ID, '_mos_courier_payments', true );
	        		$tpayment = 0;
	        		if (@$payments){
		        		foreach ($payments as $date => $amount) {
		        			$tpayment = $tpayment + $amount;
		        		}	
		        	}
	        		$output[0]['tpayment'] =  $tpayment; 

	        		$output[0]['payable'] =  $output[0]['paid_amount'] - ( $tpayment + $output[0]['delivery_charge']);    		
	        	}
	        }
        } elseif ($data['merchant']) {
			$args = array(
				'post_type' => 'courierorder',
				'posts_per_page'=>-1,
			    // 'meta_key'     => '_mos_courier_delivery_status',
			    // 'meta_value'   => 'delivered',
			    // 'meta_compare' => '='
			    'meta_query' => array(
			        'relation' => 'AND',
			        'delivery_status' => array(
			            'key' => '_mos_courier_delivery_status',
			            'value' => array('delivered','pdelivered','returned'),
			    		'compare' => 'IN'
			        ),
			        'merchant_name' => array(
			            'key' => '_mos_courier_merchant_name',
			            'value' => $data['merchant'],
			    		'compare' => '='
			        ),
			        'payment_status' => array(
			            'key' => '_mos_courier_payment_status',
			            'value' => 'unpaid',
			    		'compare' => '='
			        ), 
			    ),
			);
			$n = 0;
			$the_query = new WP_Query( $args );
			if ( $the_query->have_posts() ) :
			    while ( $the_query->have_posts() ) : $the_query->the_post();
					$output[$n]['id'] = intval(get_the_ID());      		
	        		$output[$n]['title'] = get_the_title( get_the_ID() );      		
	        		$output[$n]['product_price'] = get_post_meta( get_the_ID(), '_mos_courier_product_price', true );	        		
	        		$output[$n]['paid_amount'] = get_post_meta( get_the_ID(), '_mos_courier_paid_amount', true );	        		
	        		$output[$n]['delivery_charge'] = get_post_meta( get_the_ID(), '_mos_courier_delivery_charge', true );	        		
	        		$output[$n]['delivery_date'] = get_post_meta( get_the_ID(), '_mos_courier_delivery_date', true );
	        		$output[$n]['merchant_name'] = get_post_meta( get_the_ID(), '_mos_courier_merchant_name', true );	        		
	        		$payments = get_post_meta( $order->ID, '_mos_courier_payments', true );
	        		$tpayment = 0;
	        		if (@$payments){
		        		foreach ($payments as $date => $amount) {
		        			$tpayment = $tpayment + $amount;
		        		}	
		        	}
	        		$output[$n]['tpayment'] =  $tpayment; 

	        		$output[$n]['payable'] =  $output[$n]['paid_amount'] - ( $tpayment + $output[$n]['delivery_charge']); 
			        $n++;
			    endwhile;
			endif;
			wp_reset_postdata();
        }      
		echo json_encode($output);   
    }
	die();
} 
add_action( 'wp_ajax_bill_pay_details', 'bill_pay_details_func' );
add_action( 'wp_ajax_nopriv_bill_pay_details', 'bill_pay_details_func' );

function get_all_merchants_func() {
	$output = array();
	$data = array();
    if ( isset($_REQUEST) ) {    
    	$n = 0; 
     	$merchant = mos_user_list('merchant');
     	foreach ($merchant as $key => $value) {
     		$output[$n]['id'] = $key;
     		$output[$n]['title'] = $value;
     		$n++;
     	}
		echo json_encode($output);   
    }
	die();
} 
add_action( 'wp_ajax_get_all_merchants', 'get_all_merchants_func' );
add_action( 'wp_ajax_nopriv_get_all_merchants', 'get_all_merchants_func' );
function get_all_packaging_func() {
	$output = array();
    if ( isset($_REQUEST) ) {    
    	$n = 0; 
		$options = get_option( 'mos_courier_options' );
		$packaging = mos_str_to_arr($options['packaging'], '|');
     	foreach ($packaging as $value) {
     		$output[$n]['id'] = $value;
     		$output[$n]['title'] = $value;
     		$n++;
     	}
		echo json_encode($output);   
    }
	die();
} 
add_action( 'wp_ajax_get_all_packaging', 'get_all_packaging_func' );
add_action( 'wp_ajax_nopriv_get_all_packaging', 'get_all_packaging_func' );
function get_all_zone_func() {
	$output = array();
    if ( isset($_REQUEST) ) {    
    	$n = 0; 
		$options = get_option( 'mos_courier_options' );
		$zone = mos_str_to_arr($options['zone'], '|');
     	foreach ($zone as $value) {
     		$output[$n]['id'] = $value;
     		$output[$n]['title'] = $value;
     		$n++;
     	}
		echo json_encode($output);   
    }
	die();
} 
add_action( 'wp_ajax_get_all_zone', 'get_all_zone_func' );
add_action( 'wp_ajax_nopriv_get_all_zone', 'get_all_zone_func' );
function get_all_delivery_man_func() {
	$output = array();
    if ( isset($_REQUEST) ) {    
    	$n = 0; 
		$delivery_man = mos_user_list('operator', 'user_role', 'Delivery Man');
     	foreach ($delivery_man as $value) {
     		$output[$n]['id'] = $value;
     		$output[$n]['title'] = $value;
     		$n++;
     	}
		echo json_encode($output);   
    }
	die();
} 
add_action( 'wp_ajax_get_all_delivery_man', 'get_all_delivery_man_func' );
add_action( 'wp_ajax_nopriv_get_all_delivery_man', 'get_all_delivery_man_func' );
function get_all_delivery_status_func() {
	$output = array();
    if ( isset($_REQUEST) ) {    
		global $order_status_arr;
    	$n = 0; 
     	foreach ($order_status_arr as $key => $value) {
     		$output[$n]['id'] = $key;
     		$output[$n]['title'] = $value;
     		$n++;
     	}
		echo json_encode($output);   
    }
	die();
} 
add_action( 'wp_ajax_get_all_delivery_status', 'get_all_delivery_status_func' );
add_action( 'wp_ajax_nopriv_get_all_delivery_status', 'get_all_delivery_status_func' );
function get_all_payment_status_func() {
	$output = array();
    if ( isset($_REQUEST) ) {    
		global $payment_status_arr;
    	$n = 0; 
     	foreach ($payment_status_arr as $key => $value) {
     		$output[$n]['id'] = $key;
     		$output[$n]['title'] = $value;
     		$n++;
     	}
		echo json_encode($output);   
    }
	die();
} 
add_action( 'wp_ajax_get_all_payment_status', 'get_all_payment_status_func' );
add_action( 'wp_ajax_nopriv_get_all_payment_status', 'get_all_payment_status_func' );
function generate_report_func() {
	$output = array();
	$data = array();
    if ( isset($_REQUEST) ) {    

        $arr = explode('&', $_REQUEST['form_one_data']);
        $n = 0;
        foreach($arr as $value){
        	// $value1 = explode('=', $value);
        	// $data[$value1[0]] = $value1[1];        	
            // if ($value == "output_cl_no=1"){
                // $('<input type="hidden" name="output_teble_cl_no" value="1">').prependTo("#report_step_two");
            // }
        }
		echo json_encode($output);   
    }
	die();
} 
add_action( 'wp_ajax_generate_report', 'generate_report_func' );
add_action( 'wp_ajax_nopriv_generate_report', 'generate_report_func' );
function delete_post_func() {
	global $wpdb;
	$output = array();
	$data = array();
    if ( isset($_REQUEST) ) {    

        $arr = explode('&', $_REQUEST['form_data']);
        $n = 0;
        foreach($arr as $value){
        	$value1 = explode('=', $value);
        	$data[$value1[0]] = $value1[1];        	
            if (preg_match("/orders/i", $value)){
                $output[$n]['id'] = $value1[1];
                // wp_trash_post( $value1[1]);
                wp_delete_post( $value1[1] );
                $wpdb->delete( $wpdb->prefix.'orders', array( 'post_id' => $value1[1] ) );
                $n++;
            }
        }
		echo json_encode($output);   
    }
	die();
} 
add_action( 'wp_ajax_delete_post', 'delete_post_func' );
add_action( 'wp_ajax_nopriv_delete_post', 'delete_post_func' );
function pie_data_func() {
	$output = array();
	$data = array();
    if ( isset($_REQUEST) ) {  
		$args = array(
			'post_type' => 'courierorder',
			'posts_per_page' => -1,
		);
		$args['meta_query']['relation'] = 'AND';
		$args['meta_query']['booking_date'] = array(
			'key' => '_mos_courier_booking_date',
			'value' => array($_POST['start_date'],$_POST['end_date']),
			'type' => 'date',
			'compare' => 'BETWEEN',
		);
		$query = new WP_Query( $args );
		$output['total_post'] = $query->post_count;
		wp_reset_postdata(); 
		$args['meta_query']['delivery_status'] = array(
			'key' => '_mos_courier_delivery_status',
			'value' => 'pending',
			//'value'   => array( 3, 4 ),
		);
		$query = new WP_Query( $args );
		$output['total_pending'] = $query->post_count;
		wp_reset_postdata();
		$args['meta_query']['delivery_status'] = array(
			'key' => '_mos_courier_delivery_status',
			'value' => 'received',
			//'value'   => array( 3, 4 ),
		);
		$query = new WP_Query( $args );
		$output['total_received'] = $query->post_count;
		wp_reset_postdata();

		$args['meta_query']['delivery_status'] = array(
			'key' => '_mos_courier_delivery_status',
			'value' => 'hold',
			//'value'   => array( 3, 4 ),
		);
		$query = new WP_Query( $args );
		$output['total_hold'] = $query->post_count;
		wp_reset_postdata();

		$args['meta_query']['delivery_status'] = array(
			'key' => '_mos_courier_delivery_status',
			'value' => 'way',
			//'value'   => array( 3, 4 ),
		);
		$query = new WP_Query( $args );
		$output['total_way'] = $query->post_count;
		wp_reset_postdata(); 

		unset($args['meta_query']['booking_date']);

		$args['meta_query']['delivery_date'] = array(
			'key' => '_mos_courier_delivery_date',
			'value' => array($_POST['start_date'],$_POST['end_date']),
			'type' => 'date',
			'compare' => 'BETWEEN',
		);
		$args['meta_query']['delivery_status'] = array(
			'key' => '_mos_courier_delivery_status',
			'value' => 'delivered',
			//'value'   => array( 3, 4 ),
		);
		$query = new WP_Query( $args );
		$output['total_delivered'] = $query->post_count;
		wp_reset_postdata();
		
		$args['meta_query']['delivery_status'] = array(
			'key' => '_mos_courier_delivery_status',
			'value' => 'returned',
			//'value'   => array( 3, 4 ),
		);
		$query = new WP_Query( $args );
		$output['total_returned'] = $query->post_count;
		wp_reset_postdata();
		
		$args['meta_query']['delivery_status'] = array(
			'key' => '_mos_courier_delivery_status',
			'value' => 'pdelivered',
			//'value'   => array( 3, 4 ),
		);
		$query = new WP_Query( $args );
		$output['total_pdelivered'] = $query->post_count;
		wp_reset_postdata(); 

		echo json_encode($output);   
    }
	die();
} 
add_action( 'wp_ajax_pie_data', 'pie_data_func' );
add_action( 'wp_ajax_nopriv_pie_data', 'pie_data_func' );
/*Ajax*/
/*VC*/
function tracking_form_func( $atts = array(), $content = '' ) {	
	return '<div class="card border-0 rounded-0"><div class="card-body"><div class="form-title mb-3"><span class="font-17 font-medium pr-2">TRACK YOUR CONSIGNMENT</span><span class="font-14 text-color-11 pl-1">Now you can easily track your consignment</span></div><form class="track-form"><div class="form-row"><div class="col-8"><input type="text" name="tracking_code" placeholder="Enter your Tracking Code or Receiver Mobile Number" class="form-control"></div><div class="col-4"><button type="submit" class="btn btn-block bg-theme text-white"><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" x="0px" y="0px" viewBox="0 0 56.966 56.966" xml:space="preserve" width="18px" height="18px"><g transform="matrix(0.9999 0 0 0.9999 0.00284829 0.00284829)"><path d="M55.146,51.887L41.588,37.786c3.486-4.144,5.396-9.358,5.396-14.786c0-12.682-10.318-23-23-23s-23,10.318-23,23  s10.318,23,23,23c4.761,0,9.298-1.436,13.177-4.162l13.661,14.208c0.571,0.593,1.339,0.92,2.162,0.92  c0.779,0,1.518-0.297,2.079-0.837C56.255,54.982,56.293,53.08,55.146,51.887z M23.984,6c9.374,0,17,7.626,17,17s-7.626,17-17,17  s-17-7.626-17-17S14.61,6,23.984,6z" fill="#ffffff"></path></g></svg></button></div></div></form><div class="track-output"></div></div></div>';
}
add_shortcode( 'tracking-form', 'tracking_form_func' );
add_action( 'vc_before_init', 'your_name_integrateWithVC' );
function your_name_integrateWithVC() {
	vc_map( array(
		"name" => __( "Tracking Form", "my-text-domain" ),
		"base" => "tracking-form",
		"class" => "",
		"category" => __( "Content", "my-text-domain"),
		// 'admin_enqueue_js' => array(get_template_directory_uri().'/vc_extend/bartag.js'),
		// 'admin_enqueue_css' => array(get_template_directory_uri().'/vc_extend/bartag.css'),
		'icon'     => plugin_dir_path( MOS_COURIER_FILE ) . 'images/mos-vc.png',
				
		"params" => array()
	));
}
/*VC*/
/*Ajax*/
function order_tracking_func() {
	$output = array();
	$html = '<div class="w-100 pt-4"><div class="mt-2 pt-4 border-top"><div class="pt-2">';
	$data = array();
    if ( isset($_REQUEST) ) {
		$value = explode('=', $_REQUEST['form_data']);
		$data[$value[0]] = $value[1]; 
		$order = get_page_by_title( $value[1], OBJECT,'courierorder' );

		$args = array(
			'post_type' => 'courierorder',
			'posts_per_page' => 1,
			'meta_key'  => '_mos_courier_receiver_number',
			'meta_value'  => $value[1],
		);
		$query = new WP_Query( $args );

		if ($order) {
			$html .= '<table class="table table-borderless table-sm">';
			$html .= '<tr><th>CL No</th><td class="text-right">'.$value[1].'</td></tr>';
			$html .= '<tr><th>Mechant Order ID</th><td class="text-right">'.$order->ID.'</td></tr>';
			$html .= '<tr><th>Zone</th><td class="text-right">'.get_post_meta( $order->ID, '_mos_courier_delivery_zone', true ).'</td></tr>';
			$html .= '<tr><th>Booking Date</th><td class="text-right">'.get_post_meta( $order->ID, '_mos_courier_booking_date', true ).'</td></tr>';
			$html .= '<tr><th>Delivery Date</th><td class="text-right">'.get_post_meta( $order->ID, '_mos_courier_delivery_date', true ).'</td></tr>';
			$html .= '<tr><th>Current Status</th><td class="text-right">'.get_post_meta( $order->ID, '_mos_courier_delivery_status', true ).'</td></tr>';
			$html .= '<tr><th>Receiver Name</th><td class="text-right">'.get_post_meta( $order->ID, '_mos_courier_receiver_name', true ).'</td></tr>';
			$html .= '<tr><th>Remarks</th><td class="text-right">'.get_post_meta( $order->ID, '_mos_courier_remarks', true ).'</td></tr>';
			$html .= '</table>';
		} elseif ( $query->have_posts() ) {
			while ( $query->have_posts() ) : $query->the_post();
				$html .= '<table class="table table-borderless table-sm">';
				$html .= '<tr><th>CL No</th><td class="text-right">'.get_the_title().'</td></tr>';
				$html .= '<tr><th>Mechant Order ID</th><td class="text-right">'.get_the_ID().'</td></tr>';
				$html .= '<tr><th>Zone</th><td class="text-right">'.get_post_meta( get_the_ID(), '_mos_courier_delivery_zone', true ).'</td></tr>';
				$html .= '<tr><th>Booking Date</th><td class="text-right">'.get_post_meta( get_the_ID(), '_mos_courier_booking_date', true ).'</td></tr>';
				$html .= '<tr><th>Delivery Date</th><td class="text-right">'.get_post_meta( get_the_ID(), '_mos_courier_delivery_date', true ).'</td></tr>';
				$html .= '<tr><th>Current Status</th><td class="text-right">'.get_post_meta( get_the_ID(), '_mos_courier_delivery_status', true ).'</td></tr>';
				$html .= '<tr><th>Receiver Name</th><td class="text-right">'.get_post_meta( get_the_ID(), '_mos_courier_receiver_name', true ).'</td></tr>';
				$html .= '<tr><th>Remarks</th><td class="text-right">'.get_post_meta( get_the_ID(), '_mos_courier_remarks', true ).'</td></tr>';
				$html .= '</table>';
		    endwhile;			 
		    wp_reset_postdata();
		} else {
			$html .= '<h5 id="no-order" class="text-color-2 text-center font-18 font-md-16 mb-0">Sorry! Found Nothing. </h5>';
		}
		$html .= '</div></div></div>'; 	
		echo json_encode($html);   
    }
	die();
} 
add_action( 'wp_ajax_order_tracking', 'order_tracking_func' );
add_action( 'wp_ajax_nopriv_order_tracking', 'order_tracking_func' );
function view_order_desc_func() {
	$post_id = $_POST['id'];
	$metas = array(
		'_mos_courier_merchant_order_id' => 'Order ID',
		'_mos_courier_booking_date' => 'Booking Date',
		'_mos_courier_merchant_name' => 'Brand Name',
		'_mos_courier_merchant_address' => 'Merchant Address',
		'_mos_courier_merchant_number' => 'Merchant Phone',
		'_mos_courier_product_name' => 'Product Name',
		'_mos_courier_product_price' => 'Product Price',
		'_mos_courier_product_quantity' => 'Product Quantity',
		'_mos_courier_receiver_name' => 'Receiver Name',
		'_mos_courier_receiver_address' => 'Receiver Address',
		'_mos_courier_receiver_number' => 'Receiver Phone',
		'_mos_courier_total_weight' => 'Total Weight',
		'_mos_courier_packaging_type' => 'Packaging',
		'_mos_courier_delivery_charge' => 'Charge',
		'_mos_courier_delivery_status' => 'Delivery Status',
		'_mos_courier_payment_status' => 'Merchant Payment',
	);
	$html = '<table class="table table-borderless table-sm">';
	$html .= '<tr>';
      $html .= '<th>CN NO.</th>';
      $html .= '<td class="text-right">'.get_the_title($post_id).'</td>';
    $html .= '</tr>';
    foreach ($metas as $key => $value) {
    	$html .= '<tr>';
    	$html .= '<th>'.$value.'</th>';
    	if ($key=='_mos_courier_merchant_name'){
    		$merchant_id = get_post_meta( $post_id, $key, true );
    		$html .= '<td class="text-right">'.get_userdata($merchant_id)->display_nam.' ('.get_user_meta( $merchant_id, 'brand_name', true ).')</td>';
    	} else {
    		$html .= '<td class="text-right">'.get_post_meta( $post_id, $key, true ).'</td>';
    	}
    	$html .= '</tr>';
    }
	$html .= '</table>';
	echo json_encode($html);
	die();
}
add_action( 'wp_ajax_view_order_desc', 'view_order_desc_func' );
add_action( 'wp_ajax_nopriv_view_order_desc', 'view_order_desc_func' );
/*Ajax*/