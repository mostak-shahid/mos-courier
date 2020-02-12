<?php 
global $page_list, $page_slug, $wpdb;
$current_user = wp_get_current_user();
if ( 0 == $current_user->ID ) {
	wp_redirect(home_url('/wp-login.php'));
	exit;
} else {
	$current_user_id = $current_user->ID;
	$current_user_role = get_user_meta( $current_user_id, 'user_role', true );
	$current_activation = get_user_meta( $current_user_id, 'activation', true );

	$current_url = $_SERVER['REQUEST_URI'];
	$page = @$_GET['page'];
	$id = @$_GET['id'];
	// var_dump($page);
	/*$slice = explode('=', $current_url);
	$slug = '';
	$slice = explode('=', $current_url);
	if (sizeof($slice)>1) {
		$slug = $slice[1];
		if (preg_match("/msg/i", $slice[2])) {
		    $id = $slice[2];
		}		
	}*/
	if ($current_activation == 'Active'){
		if ($current_user->roles[0] == 'merchant' AND ($page == 'order-bulk' OR $page == 'check-in' OR $page == 'check-out' OR $page == 'bill-pay' OR $page == 'report' OR $page == 'user-manage' OR $page == 'user-edit' OR $page == 'user-bulk' OR $page == 'settings' OR ($page == 'order-edit' AND @$id))) {
			wp_redirect(home_url('/admin/'));
			exit;
		}	
	} else if ($current_activation == 'Deactive' AND $page){
		wp_redirect(home_url('/admin/'));
		exit;		
	}
}
if ($_SERVER['REQUEST_METHOD'] == "POST") {
	// foreach ($_POST as $field => $value) {
	// 	echo "$"."_POST['"."$field"."']"." == '$value'<br>";
	// }
    if( isset( $_POST['edit_order_form_field'] ) && wp_verify_nonce( $_POST['edit_order_form_field'], 'edit_order_form') ) {

        // sanitize the input
    	$prefix = get_option('mos_courier_options')['oprefix'];
    	$delivery_status = 'pending';
        $usertype = sanitize_text_field( $_POST['usertype'] );
        $edit_order_sub = $_POST['edit-order-sub'];

        $product_name = sanitize_text_field( $_POST['_mos_courier_product_name'] );
        $merchant_order_id = sanitize_text_field( $_POST['_mos_courier_merchant_order_id'] );
        $product_price = sanitize_text_field( $_POST['_mos_courier_product_price'] );
        $product_quantity = sanitize_text_field( $_POST['_mos_courier_product_quantity'] );
        $receiver_name = sanitize_text_field( $_POST['_mos_courier_receiver_name'] );
        $receiver_address = sanitize_text_field( $_POST['_mos_courier_receiver_address'] );
        $receiver_number = sanitize_text_field( $_POST['_mos_courier_receiver_number'] );
        $receiver = '<h5>'.$receiver_name.'</h5><div>'.$receiver_address.'</div><div>'.$receiver_number.'</div>';
        $total_weight = sanitize_text_field( $_POST['_mos_courier_total_weight'] );
        $packaging_type = sanitize_text_field( $_POST['_mos_courier_packaging_type'] );

        $delivery_zone = sanitize_text_field( $_POST['_mos_courier_delivery_zone'] );
        $delivery_charge = sanitize_text_field( $_POST['_mos_courier_delivery_charge'] );

        $urgent_delivery = sanitize_text_field( $_POST['_mos_courier_urgent_delivery'] );

        if (@$usertype != 'operator'){
            $merchant_name = get_current_user_id();
            $address_line_1 = get_user_meta( $merchant_name, 'address_line_1', true );
            $address_line_2 = get_user_meta( $merchant_name, 'address_line_2', true );
            $phone = get_user_meta( $merchant_name, 'phone', true );
            $mobile = get_user_meta( $merchant_name, 'mobile', true );
            $address = $address_line_1;
            if ($address_line_2) $address .= ' ' . $address_line_2;       
            $merchant_address = $address; 
            $merchant_phone = ($phone)?$phone:$mobile;
        } else {
            $merchant_name = sanitize_key( $_POST['_mos_courier_merchant_name'] );
            $merchant_address = sanitize_text_field( $_POST['_mos_courier_merchant_address'] );
            $merchant_number = sanitize_text_field( $_POST['_mos_courier_merchant_number'] );
        }

       	if ($edit_order_sub =='update'){
       		$order_id = $_POST['order_id'];

        	$delivery_status = sanitize_text_field( $_POST['_mos_courier_delivery_status'] );

        	if (!$delivery_status) $delivery_status = 'pending';
        	$remarks = sanitize_text_field( $_POST['_mos_courier_remarks'] );
        	$note = sanitize_text_field( $_POST['_mos_courier_note'] );
        	$prev_note = get_post_meta( $order_id, '_mos_courier_note', true );
        	if ($prev_note) $data = $prev_note;
        	$data[date('Y-m-d-h-i-s')] = array(
        		'id' => get_current_user_id(),
        		'note' => $note,
        	); 
        	update_post_meta( $order_id, '_mos_courier_note', $data );
        	update_post_meta( $order_id, '_mos_courier_remarks', $remarks );


			update_post_meta( $post_id, '_mos_courier_update_to_table', 1 );
			$wpdb->update( 
				$wpdb->prefix.'orders', 
				array( 
					'merchant_id' => $merchant_name, 
					'receiver' => $receiver,  
					'brand' => get_user_meta( $merchant_name,'brand_name', true ), 
				), 
				array( 'post_id' => $order_id )
			);

       	} else {
       		$newTitle = $prefix.rand(1000,9999).strtotime("now");
	        // Create post object
	        $order_details = array(
	            'post_type' => 'courierorder',
	            'post_title'    => $newTitle,
	            // 'post_content'  => $_POST['post_content'],
	            'post_status'   => 'publish',
	            'post_author'   => get_current_user_id(),
	            // 'post_category' => array( 8,39 )
	        );
	         
	        // Insert the post into the database
	        $order_id = wp_insert_post( $order_details );
	        $newName = $prefix.$order_id;
			$post_update = array(
				'ID'         => $order_id,
				'post_title' => $newName
			);
	        wp_update_post( $post_update );

	        $generator = new BarcodeGeneratorPNG();
	        $barcode = "data:image/png;base64," . base64_encode($generator->getBarcode($newName, $generator::TYPE_CODE_128));
			copy($barcode,wp_upload_dir()["basedir"].'/'.$newName.".png");

	        // $qr = new QR_BarCode();
	        // $qr->text($newTitle);
	        // $qr->qrCode(150, plugin_dir_path( MOS_COURIER_FILE ) . 'images/'.$newTitle.'.png');
			update_post_meta( $post_id, '_mos_courier_update_to_table', 1 );
	        $wpdb->insert( 
				$wpdb->prefix.'orders', 
				array( 
					'post_id' => $order_id, 
					'merchant_id' => $merchant_name, 
					'receiver' => $receiver, 
					'cn' => get_the_title($order_id), 
					'booking' => date('Y-m-d'), 
					'delivery_status' => 'pending', 
					'brand' => get_user_meta( $merchant_name,'brand_name', true ), 
				) 
			);	
	    }
        $brand_name = get_user_meta( $merchant_name, 'brand_name', true );
        update_post_meta( $order_id, '_mos_courier_brand_name', $brand_name);

        update_post_meta( $order_id, '_mos_courier_delivery_status', $delivery_status);
    	$wpdb->update( 
			$wpdb->prefix.'orders', 
			array( 
				'delivery_status' => $delivery_status,	// string
			), 
			array( 'post_id' => $order_id )
		);

        update_post_meta( $order_id, '_mos_courier_payment_status', 'unpaid');
        update_post_meta( $order_id, '_mos_courier_booking_date', date('Y/m/d'));
        update_post_meta( $order_id, '_mos_courier_product_name', $product_name);
        update_post_meta( $order_id, '_mos_courier_merchant_order_id', $merchant_order_id);
        update_post_meta( $order_id, '_mos_courier_product_price', $product_price);
        update_post_meta( $order_id, '_mos_courier_product_quantity', $product_quantity);
        update_post_meta( $order_id, '_mos_courier_receiver_name', $receiver_name);
        update_post_meta( $order_id, '_mos_courier_receiver_address', $receiver_address);
        update_post_meta( $order_id, '_mos_courier_receiver_number', $receiver_number);
        update_post_meta( $order_id, '_mos_courier_total_weight', $total_weight);
        update_post_meta( $order_id, '_mos_courier_packaging_type', $packaging_type);

        update_post_meta( $order_id, '_mos_courier_merchant_name', $merchant_name);
        update_post_meta( $order_id, '_mos_courier_merchant_address', $merchant_address);
        update_post_meta( $order_id, '_mos_courier_merchant_number', $merchant_number);

        update_post_meta( $order_id, '_mos_courier_delivery_zone', $delivery_zone);
        update_post_meta( $order_id, '_mos_courier_delivery_charge', $delivery_charge);

        update_post_meta( $order_id, '_mos_courier_urgent_delivery', $urgent_delivery);
        
		
		// echo "<p>Need to add: {$post_id}</p>";
    	update_post_meta( $post_id, '_mos_courier_update_to_table', 1 );        
        // do the processing
        // add the admin notice
        //$admin_notice = "success";
        // redirect the user to the appropriate page
        // $this->custom_redirect( $admin_notice, $_POST );
        $message = "success";
        if ($edit_order_sub == 'onemore'){
        	$url = home_url( '/admin/?page=order-edit' ) . '&msg=orderadded';
        }
        else 
        	$url = home_url( '/admin/?page=order-manage' );

        wp_redirect( $url );
        exit;
    } 
    if( isset( $_POST['csv_order_form_field'] ) && wp_verify_nonce( $_POST['csv_order_form_field'], 'csv_order_form') ) {
    	$err = 0;
    	$errmsg = '';
		if ( isset($_FILES['order-csv-file']['name']) AND !$_FILES['order-csv-file']['error']){
			if (!$_FILES['order-csv-file']['size']){
				$err++;
				$errmsg = 'Please upload a file';
			}
			if ($_FILES['order-csv-file']['type'] != 'application/vnd.ms-excel'){
				$err++;
				$errmsg = 'Please upload a CSV file';
			}
			echo $err;
			if ( !$err ) {
				if ( ! function_exists('wp_handle_upload')){
					require_once( ABSPATH . 'wp-admin/includes/file.php' );
				}
				$file = $_FILES['order-csv-file'];
				$overrides = array('test_form' => false);
				$movefile = wp_handle_upload( $file, $overrides );
				// var_dump($movefile);
				if ($movefile && !isset($movefile['error'])) {
					// var_dump($movefile);
					//array(3) { ["file"]=> string(102) "/home/tcourier/web/tcourier.aiscript.net/public_html/wp-content/uploads/2019/10/AdminLTE-3-Starter.csv" ["url"]=> string(78) "http://tcourier.aiscript.net/wp-content/uploads/2019/10/AdminLTE-3-Starter.csv" ["type"]=> string(8) "text/csv" }
					$file = fopen($movefile['url'],"r");
					$rows = array();
					$header = fgetcsv($file);
					while ($row = fgetcsv($file)) {
						$rows[] = array_combine($header, $row);
					}
					foreach ($rows as $value) {
						//echo $value['CN NO'] . '<br />'; 
						// $oldOrder = get_page_by_title( $value['CN NO'], OBJECT, 'courierorder' );
						//if(!$oldOrder){
							$prefix = get_option('mos_courier_options')['oprefix'];
							$date=date_create($value['Booking Date']);
							$formatted_booking_date = date_format($date,"Y/m/d");
							$booking_date = ($value['Booking Date'])?$formatted_booking_date:date('Y/m/d');

							$delivery_charge = ($value['Delivery Charge'])?$value['Delivery Charge']:calculate_delivery_charge($value['Merchant ID'], $value['Total Weight']);

							$packaging_type = ($value['Packaging Type'])?$value['Packaging Type']:'Bag';
							$delivery_status = ($value['Delivary Status'])?$value['Delivary Status']:'pending';
							$payment_status = ($value['Payment Status'])?$value['Payment Status']:'unpaid';
							$newTitle = $prefix.rand(1000,9999).strtotime("now");
							$my_post = array(
								'post_title'    => $newTitle,
								'post_status'   => 'publish',
								'post_author'   => $current_user_id,
								'post_type' 	=> 'courierorder'
							);
							$order_id = wp_insert_post( $my_post );	

							$newName = $prefix.$order_id;
							$post_update = array(
								'ID'         => $order_id,
								'post_title' => $newName
							);
					        wp_update_post( $post_update );

					        $generator = new BarcodeGeneratorPNG();
					        $barcode = "data:image/png;base64," . base64_encode($generator->getBarcode($newName, $generator::TYPE_CODE_128));
							copy($barcode,wp_upload_dir()["basedir"].'/'.$newName.".png");
							
					        // $qr = new QR_BarCode();
					        // $qr->text($newTitle);
					        // $qr->qrCode(150, plugin_dir_path( MOS_COURIER_FILE ) . 'images/'.$newTitle.'.png');
							// var_dump($order_id);			    
					        update_post_meta( $order_id, '_mos_courier_merchant_name', $value['Merchant ID'] );
					        update_post_meta( $order_id, '_mos_courier_merchant_address', mos_user_address($value['Merchant ID']) );
					        update_post_meta( $order_id, '_mos_courier_merchant_number', mos_user_phone($value['Merchant ID']) );
					        update_post_meta( $order_id, '_mos_courier_booking_date', $booking_date );
					        update_post_meta( $order_id, '_mos_courier_product_name', $value['Product Name'] );
					        update_post_meta( $order_id, '_mos_courier_product_price', $value['Product Price'] );
					        update_post_meta( $order_id, '_mos_courier_product_quantity', $value['Product Quantity'] );
					        update_post_meta( $order_id, '_mos_courier_receiver_name', $value['Receiver Name'] );
					        update_post_meta( $order_id, '_mos_courier_receiver_address', $value['Receiver Address'] );
					        update_post_meta( $order_id, '_mos_courier_receiver_number', $value['Receiver Number'] );
					        update_post_meta( $order_id, '_mos_courier_total_weight', $value['Total Weight'] );
					        update_post_meta( $order_id, '_mos_courier_packaging_type', $packaging_type );
					        update_post_meta( $order_id, '_mos_courier_delivery_charge', $delivery_charge );
					        update_post_meta( $order_id, '_mos_courier_paid_amount', $value['Paid Amount'] );
					        update_post_meta( $order_id, '_mos_courier_payment_date', $value['Payment Date'] );
					        update_post_meta( $order_id, '_mos_courier_delivery_zone', $value['Delivery Zone'] );
					        update_post_meta( $order_id, '_mos_courier_delivery_man', $value['Delivery Man'] );
					        update_post_meta( $order_id, '_mos_courier_delivery_date', $value['Delivery Date'] );
					        update_post_meta( $order_id, '_mos_courier_delivery_status', strtolower($delivery_status));
							$wpdb->update( 
								$wpdb->prefix.'orders', 
								array( 
									'delivery_status' => strtolower($delivery_status),	// string
								), 
								array( 'post_id' => $order_id )
							);
					        update_post_meta( $order_id, '_mos_courier_delivery_date', $value['Delivery Date'] );
					        update_post_meta( $order_id, '_mos_courier_payment_status', strtolower($payment_status));
							    
						//}
					}					
					$url = home_url( '/admin/?page=order-manage' );
					wp_redirect( $url );
					exit;					
				} else {
					$errmsg = $movefile['error'];
				}
			}
		}
    }
    if( isset( $_POST['csv_user_form_field'] ) && wp_verify_nonce( $_POST['csv_user_form_field'], 'csv_user_form') ) {    	
    	$err = 0;
    	$errmsg = '';
		if ( isset($_FILES['user-csv-file']['name']) AND !$_FILES['user-csv-file']['error']){
			if (!$_FILES['user-csv-file']['size']){
				$err++;
				$errmsg = 'Please upload a file';
			}
			if ($_FILES['user-csv-file']['type'] != 'application/vnd.ms-excel'){
				$err++;
				$errmsg = 'Please upload a CSV file';
			}
			if ( !$err ) {
				if ( ! function_exists('wp_handle_upload')){
					require_once( ABSPATH . 'wp-admin/includes/file.php' );
				}
				$file = $_FILES['user-csv-file'];
				$overrides = array('test_form' => false);
				$movefile = wp_handle_upload( $file, $overrides );
				if ($movefile && !isset($movefile['error'])) {
					// var_dump($movefile);
					//array(3) { ["file"]=> string(102) "/home/tcourier/web/tcourier.aiscript.net/public_html/wp-content/uploads/2019/10/AdminLTE-3-Starter.csv" ["url"]=> string(78) "http://tcourier.aiscript.net/wp-content/uploads/2019/10/AdminLTE-3-Starter.csv" ["type"]=> string(8) "text/csv" }
					$file = fopen($movefile['url'],"r");
					$rows = array();
					$header = fgetcsv($file);
					while ($row = fgetcsv($file)) {
						$rows[] = array_combine($header, $row);
					}
					// var_dump($rows);
					foreach ($rows as $value) {	
						if ($value['Email'] && $value['Password']){							
				       		// $user_name = sanitize_text_field( $_POST['user_name'] );
				       		$user_email = sanitize_text_field( $value['Email'] );
				       		$password = $value['Password'];
							$user_id = username_exists( $user_email );			 
							if ( ! $user_id && false == email_exists( $user_email ) ) {
							    // $random_password = wp_generate_password( $length = 12, $include_standard_special_chars = false );
							    // $user_id = wp_create_user( $user_name, $random_password, $user_email );
							    $user_id = wp_create_user( $user_email, $password, $user_email );							    
							}
					       	update_user_meta( $user_id, 'first_name', $value['First Name'] );
					       	update_user_meta( $user_id, 'last_name', $value['Last Name'] );
					       	update_user_meta( $user_id, 'brand_name', $value['Brand Name'] );
					       	update_user_meta( $user_id, 'payment', $value['Payment Method'] );
					       	update_user_meta( $user_id, 'bank_name', $value['Bank Name'] );
					       	update_user_meta( $user_id, 'account_holder', $value['Account Holder'] );
					       	update_user_meta( $user_id, 'payacc', $value['Account'] );
					       	update_user_meta( $user_id, 'delivery_charge', $value['Delivery Charge'] );
					       	update_user_meta( $user_id, 'additional_charge', $value['Additional Charge'] );
					       	update_user_meta( $user_id, 'address_line_1', $value['Address Line 1'] );
					       	update_user_meta( $user_id, 'address_line_2', $value['Address Line 2'] );
					       	update_user_meta( $user_id, 'phone', $value['Phone'] );
					       	update_user_meta( $user_id, 'mobile', $value['Mobile'] );
					       	update_user_meta( $user_id, 'user_role', $value['User Role'] );
					       	update_user_meta( $user_id, 'national_id', $value['National ID'] );
					       	update_user_meta( $user_id, 'religion', $value['Religion'] );
					       	update_user_meta( $user_id, 'gender', $value['Gender'] );
					       	update_user_meta( $user_id, 'activation', $value['Status'] );
					       	update_user_meta( $user_id, 'village', $value['Village'] );
					       	update_user_meta( $user_id, 'poffice', $value['Post Office'] );
					       	update_user_meta( $user_id, 'thana', $value['Thana'] );
					       	update_user_meta( $user_id, 'zila', $value['Zila'] );
					       	
							// NOTE: Of course change 3 to the appropriate user ID
							$u = new WP_User( $user_id );
							// Remove role
							$u->remove_role( 'subscriber' );
							// Add role
							if ($value['User Role'] == 'Regular' OR $value['User Role'] == 'Corporate')
								$u->add_role( 'merchant' );
							else 
								$u->add_role( 'operator' );

							$display_name = ($value['Last Name']) ? $value['First Name'] . ' ' . $value['Last Name'] : $value['First Name'];
							$user_id = wp_update_user( array( 'ID' => $user_id, 'display_name' => $display_name ) );
						}				
					}					
					$url = home_url( '/admin/?page=user-manage' );
					wp_redirect( $url );
					exit;					
				} else {
					$errmsg = $movefile['error'];
				}
			}
		}
    }
    if( isset( $_POST['edit_user_form_field'] ) && wp_verify_nonce( $_POST['edit_user_form_field'], 'edit_user_form') ) {
       	$first_name = sanitize_text_field( $_POST['first_name'] );
       	$last_name = sanitize_text_field( $_POST['last_name'] );
       	$brand_name = sanitize_text_field( $_POST['brand_name'] );
       	$payment = sanitize_text_field( $_POST['payment'] );
       	$bank_name = sanitize_text_field( $_POST['bank_name'] );
       	$account_holder = sanitize_text_field( $_POST['account_holder'] );
       	$payacc = sanitize_text_field( $_POST['payacc'] );
       	$delivery_charge = sanitize_text_field( $_POST['delivery_charge'] );
       	$additional_charge = sanitize_text_field( $_POST['additional_charge'] );
       	$address_line_1 = sanitize_text_field( $_POST['address_line_1'] );
       	$address_line_2 = sanitize_text_field( $_POST['address_line_2'] );
       	$phone = sanitize_text_field( $_POST['phone'] );
       	$mobile = sanitize_text_field( $_POST['mobile'] );
       	$user_role = sanitize_text_field( $_POST['user_role'] );
       	$national_id = sanitize_text_field( $_POST['national_id'] );
       	$religion = sanitize_text_field( $_POST['religion'] );
       	$gender = sanitize_text_field( $_POST['gender'] );
       	$activation = sanitize_text_field( $_POST['activation'] );
       	$village = sanitize_text_field( $_POST['village'] );
       	$poffice = sanitize_text_field( $_POST['poffice'] );
       	$thana = sanitize_text_field( $_POST['thana'] );
       	$zila = sanitize_text_field( $_POST['zila'] );
       	$edit_user_sub = sanitize_text_field( $_POST['edit-user-sub'] );
		if ($edit_user_sub =='update'){
       		$user_id = $_POST['user_id'];
       	} else {
       		// $user_name = sanitize_text_field( $_POST['user_name'] );
       		$user_email = sanitize_text_field( $_POST['email'] );
       		$password = $_POST['password'];

			$user_id = username_exists( $user_email );			 
			if ( ! $user_id && false == email_exists( $user_email ) ) {
			    // $random_password = wp_generate_password( $length = 12, $include_standard_special_chars = false );
			    // $user_id = wp_create_user( $user_name, $random_password, $user_email );
			    $user_id = wp_create_user( $user_email, $password, $user_email );
			}       		
       	} 
       	update_user_meta( $user_id, 'first_name', $first_name );
       	update_user_meta( $user_id, 'last_name', $last_name );
       	update_user_meta( $user_id, 'brand_name', $brand_name );
       	update_user_meta( $user_id, 'payment', $payment );
       	update_user_meta( $user_id, 'bank_name', $bank_name );
       	update_user_meta( $user_id, 'account_holder', $account_holder );
       	update_user_meta( $user_id, 'payacc', $payacc );
       	update_user_meta( $user_id, 'delivery_charge', $delivery_charge );
       	update_user_meta( $user_id, 'additional_charge', $additional_charge );
       	update_user_meta( $user_id, 'address_line_1', $address_line_1 );
       	update_user_meta( $user_id, 'address_line_2', $address_line_2 );
       	update_user_meta( $user_id, 'phone', $phone );
       	update_user_meta( $user_id, 'mobile', $mobile );
       	update_user_meta( $user_id, 'user_role', $user_role );
       	update_user_meta( $user_id, 'national_id', $national_id );
       	update_user_meta( $user_id, 'religion', $religion );
       	update_user_meta( $user_id, 'gender', $gender );
       	update_user_meta( $user_id, 'activation', $activation );
       	update_user_meta( $user_id, 'village', $village );
       	update_user_meta( $user_id, 'poffice', $poffice );
       	update_user_meta( $user_id, 'thana', $thana );
       	update_user_meta( $user_id, 'zila', $zila ); 


		// NOTE: Of course change 3 to the appropriate user ID
		$u = new WP_User( $user_id );
		// Remove role
		$u->remove_role( 'subscriber' );
		// Add role
		if ($user_role == 'Regular' OR $user_role == 'Corporate')
			$u->add_role( 'merchant' );
		else 
			$u->add_role( 'operator' );

		$display_name = ($last_name) ? $first_name . ' ' . $last_name : $first_name;
		$user_id = wp_update_user( array( 'ID' => $user_id, 'display_name' => $display_name ) );

       	$message = "success";
        if ($edit_user_sub == 'onemore'){
        	$url = home_url( '/admin/?page=user-edit' ) . '&msg=useradded';
        }
        else 
        	$url = home_url( '/admin/?page=user-manage' );

        wp_redirect( $url );
        exit;       	  	
    }
    if( isset( $_POST['edit_profile_form_field'] ) && wp_verify_nonce( $_POST['edit_profile_form_field'], 'edit_profile_form') ) {
		$user_id = get_current_user_id();
		$first_name = sanitize_text_field( $_POST['first_name'] );
		$last_name = sanitize_text_field( $_POST['last_name'] );
		$brand_name = sanitize_text_field( $_POST['brand_name'] );

		$payment = sanitize_text_field( $_POST['payment'] );
		$bank_name = sanitize_text_field( $_POST['bank_name'] );
		$account_holder = sanitize_text_field( $_POST['account_holder'] );
		$payacc = sanitize_text_field( $_POST['payacc'] );
		$address_line_1 = sanitize_text_field( $_POST['address_line_1'] );
		$address_line_2 = sanitize_text_field( $_POST['address_line_2'] );
		$phone = sanitize_text_field( $_POST['phone'] );
		$mobile = sanitize_text_field( $_POST['mobile'] );
		$national_id = sanitize_text_field( $_POST['national_id'] );
		$religion = sanitize_text_field( $_POST['religion'] );
		$gender = sanitize_text_field( $_POST['gender'] );
		$village = sanitize_text_field( $_POST['village'] );
		$poffice = sanitize_text_field( $_POST['poffice'] );
		$thana = sanitize_text_field( $_POST['thana'] );
		$zila = sanitize_text_field( $_POST['zila'] );

       	update_user_meta( $user_id, 'first_name', $first_name );
       	update_user_meta( $user_id, 'last_name', $last_name );
       	update_user_meta( $user_id, 'brand_name', $brand_name );

		$wpdb->update( 
			$wpdb->prefix.'orders', 
			array( 
				'brand' => $brand_name,	// string
			), 
			array( 'merchant_id' => $user_id )
		);
       	update_user_meta( $user_id, 'payment', $payment );
       	update_user_meta( $user_id, 'bank_name', $bank_name );
       	update_user_meta( $user_id, 'account_holder', $account_holder );
       	update_user_meta( $user_id, 'payacc', $payacc );	
       	update_user_meta( $user_id, 'address_line_1', $address_line_1 );
       	update_user_meta( $user_id, 'address_line_2', $address_line_2 );
       	update_user_meta( $user_id, 'phone', $phone );
       	update_user_meta( $user_id, 'mobile', $mobile );
       	update_user_meta( $user_id, 'national_id', $national_id );
       	update_user_meta( $user_id, 'religion', $religion );
       	update_user_meta( $user_id, 'gender', $gender );
       	update_user_meta( $user_id, 'activation', $activation );
       	update_user_meta( $user_id, 'village', $village );
       	update_user_meta( $user_id, 'poffice', $poffice );
       	update_user_meta( $user_id, 'thana', $thana );
       	update_user_meta( $user_id, 'zila', $zila );  	

		$display_name = ($last_name) ? $first_name . ' ' . $last_name : $first_name;
		$user_id = wp_update_user( array( 'ID' => $user_id, 'display_name' => $display_name ) );
		$url = home_url( '/admin/?page=edit-profile' )  . '&msg=profileupdated';
        wp_redirect( $url );
        exit;     	
    }
    if( isset( $_POST['change_password_form_field'] ) && wp_verify_nonce( $_POST['change_password_form_field'], 'change_password_form') ) {
    	$msg='wrongpass';
    	$old_pass = sanitize_text_field( $_POST['old-pass'] );
    	$check = wp_authenticate( wp_get_current_user()->data->user_login, $_POST['old-pass'] );
    	if (!$check->errors){
    		if ($_POST['old-pass'] != $_POST['new-pass'] AND $_POST['new-pass'] == $_POST['con-pass'] ){
    			wp_set_password( $_POST['new-pass'], wp_get_current_user()->ID );
    			$msg='changepass';
    		}
    	}
		$url = home_url( '/admin/?page=change-password' )  . '&msg='.$msg;
        wp_redirect( $url );
        exit;     	
    }
    if( isset( $_POST['action_order_form_field'] ) && wp_verify_nonce( $_POST['action_order_form_field'], 'action_order_form') ) {
    	$table_name = $wpdb->prefix.'orders';
    	if ($_POST['order_table_action'] == 'Print'){
    		foreach($_POST['orders'] as $order){
    			update_post_meta( $order, '_mos_courier_delivery_status', 'received' );
		    	$wpdb->update( 
					$wpdb->prefix.'orders', 
					array( 
						'delivery_status' => 'received',	// string
					), 
					array( 'post_id' => $order )
				);
    		}
			$string = implode(",",$_POST['orders']);
			$url = home_url( '/invoice-print/' )  . '?string='.$string;
			wp_redirect( $url );
			exit;
		}
    }
    if( isset( $_POST['delivery_man_form_field'] ) && wp_verify_nonce( $_POST['delivery_man_form_field'], 'delivery_man_form') ) {
    	$table_name = $wpdb->prefix.'orders';
    	if (sizeof($_POST['orders'])){
    		$n = 0;
    		$orders = '';
    		foreach($_POST['orders'] as $order) {
    			update_post_meta( $order, '_mos_courier_delivery_status', 'way' );
    			update_post_meta( $order, '_mos_courier_delivery_man', $_POST['delivery_man'] );
    			$wpdb->update( 
					$table_name, 
					array( 
						'delivery_status' => 'way',	// string
					), 
					array( 'post_id' => $order )
				);

    			$string = implode(",",$_POST['orders']);
    		}
    		$data = array();
    		$old_data = get_user_meta( $_POST['delivery_man'], 'check-out', true );
    		if ($old_data){
    			$data = $old_data;
    		}
    		$data[date('Y-m-d-h-i-s')] = $_POST['orders'];
    		update_user_meta( $_POST['delivery_man'], 'check-out', $data ); 
    		// var_dump($old_data);
        	$url = home_url( '/admin/delivery-print/' )  . '?string='.$string.'&d='.$_POST['delivery_man'];
			wp_redirect( $url );
			exit;
    	}	
    }
    if( isset( $_POST['check_in_form_field'] ) && wp_verify_nonce( $_POST['check_in_form_field'], 'check_in_form') ) {

    	$table_name = $wpdb->prefix.'expence';
    	// var_dump($_POST);
    	if (sizeof($_POST["order"])){
    		$total_commission = @$_POST['order_commission_extra'];
    		$total_amount = 0;
    		foreach ($_POST["order_remark"] as $post_id => $remarks) {
    			update_post_meta( $post_id, '_mos_courier_remarks', $remarks);
    		}
    		foreach ($_POST["order_commission"] as $post_id => $commission) {
    			update_post_meta( $post_id, '_mos_courier_order_commission', $commission);
    			$total_commission = intval($total_commission) + intval($commission);
    		}
    		foreach ($_POST["order_delivery_status"] as $post_id => $delivery_status) {
    			update_post_meta( $post_id, '_mos_courier_delivery_status', $delivery_status);
    			$wpdb->update( 					
    				$wpdb->prefix.'orders', 
					array( 
						'delivery_status' => $delivery_status,	// string
					), 
					array( 'post_id' => $post_id)
				);

    		}
    		foreach ($_POST["order"] as $post_id => $amount) {
    			$string = $string . ',' .$post_id;   
    			$total_amount = $total_amount + $amount;			
    			update_post_meta( $post_id, '_mos_courier_delivery_date', date('Y/m/d'));
    			update_post_meta( $post_id, '_mos_courier_paid_amount', $amount);
    			update_post_meta( $post_id, '_mos_courier_checkinby', $current_user_id);
    		}
    		$string = ltrim($string, ',');
    		$delivery_man_id = get_post_meta($post_id,'_mos_courier_delivery_man',true); 
    		$delivery_man_name = get_userdata( $delivery_man_id )->display_name;
    		$wpdb->insert( 
				$table_name, 
				array( 
					'author' => get_current_user_id(), 
					'date' => date("Y-m-d"), 
					'title' => 'Bill From '.$delivery_man_name.' (' . $delivery_man_id .')', 
					'description' => $string,
					'type' => 'cashin',
					'amount' => $total_amount,
					'editable' => false
				) 
			);
    		$wpdb->insert( 
				$table_name, 
				array( 
					'author' => get_current_user_id(), 
					'date' => date("Y-m-d"), 
					'title' => 'Commission to '.$delivery_man_name.' (' . $delivery_man_id .')', 
					'description' => $string,
					'type' => 'cashout',
					'amount' => $total_commission,
					'editable' => false
				) 
			);
			// var_dump($post_id);
    		
        	$url = home_url( '/admin/checkin-print' )  . '?string='.$string.'&c='.$total_commission;
			wp_redirect( $url );
			exit;
    	}
    }
    if( isset( $_POST['bill_pay_form_field'] ) && wp_verify_nonce( $_POST['bill_pay_form_field'], 'bill_pay_form') ) {
    	
    	$table_name = $wpdb->prefix.'expence';
    	if (sizeof($_POST['order'])){
			$methods = @$_POST['method'];
			$commission = (@$_POST['commission'])?$_POST['commission']:0;
			$notes = @$_POST['note'];
			foreach ($methods as $post_id => $method) {
				update_post_meta( $post_id, '_mos_courier_payment_method', $method);	
			}
			foreach ($notes as $post_id => $note) {
				update_post_meta( $post_id, '_mos_courier_payment_note', $note);	
			}
    		foreach($_POST['order'] as $post_id => $amount) {
    			$string = $string . ',' .$post_id;

    			$delivery_charge = get_post_meta( $post_id, '_mos_courier_delivery_charge', true );
    			$paid_amount = get_post_meta( $order->ID, '_mos_courier_paid_amount', true );
    			$payments = get_post_meta( $order->ID, '_mos_courier_payments', true );
    			$tpayment = $amount;
        		if (@$payments){
	        		foreach ($payments as $date => $amount) {
	        			$tpayment = $tpayment + $amount;
	        		}	
	        	} 
	        	$payments[date('Y-m-d-h-i-s')] = $amount;
	        	update_post_meta( $post_id, '_mos_courier_payments', $payments);
	        	// if ($tpayment >= ($paid_amount - $delivery_charge))
	        	update_post_meta( $post_id, '_mos_courier_payment_status', 'paid');	
	        	update_post_meta( $post_id, '_mos_courier_payment_date', date("Y/m/d"));	
    		}
    		$merchant_brand = get_user_meta(get_post_meta( $post_id, '_mos_courier_merchant_name', true ),'brand_name',true);
    		$string = ltrim($string, ',');
    		$total_payment = $tpayment + $commission;
    		$wpdb->insert( 
				$table_name, 
				array( 
					'author' => get_current_user_id(), 
					'date' => date("Y-m-d"), 
					'title' => 'Pay Bill to '.$merchant_brand, 
					'description' => $string,
					'type' => 'cashout',
					'amount' => $total_payment,
					'editable' => false
				) 
			);

        	$url = home_url( '/admin/bill-print' )  . '?commission='.$commission.'&string='.$string;
			wp_redirect( $url );
			exit;
    	}
    }
    if( isset( $_POST['edit_settings_area_form_field'] ) && wp_verify_nonce( $_POST['edit_settings_area_form_field'], 'edit_settings_area_form') ) {
    	$options = get_option( 'mos_courier_options' );
    	$zone = sanitize_text_field( $_POST['zone'] );
    	if ($zone) $options['zone'] = $zone;

    	$regular_charge = $_POST['regular-charge'] ;
    	if ($regular_charge) $options['regular-charge'] = $regular_charge;
    	$extra_charge = $_POST['extra-charge'] ;
    	if ($extra_charge) $options['extra-charge'] = $extra_charge;
    	$urgent_charge = $_POST['urgent-charge'] ;
    	if ($urgent_charge) $options['urgent-charge'] = $urgent_charge;
    	$ocharge = $_POST['mos_courier_options'] ;    	
    	if ($ocharge){
    		$n = 0;
    		foreach($ocharge as $charge){
    			if($charge['zone-name'] AND $charge['area-name']){
    				$charge_setup[$n]['zone-name'] = $charge['zone-name'];
    				$charge_setup[$n]['area-name'] = $charge['area-name'];
    				$charge_setup[$n]['regular'] = $charge['regular'];
    				$charge_setup[$n]['extra'] = $charge['extra'];
    				$charge_setup[$n]['urgent'] = $charge['urgent'];
    				$n++;
    			}
    		}
    	}
    	$options['charge_setup'] = $charge_setup;
   		update_option( 'mos_courier_options', $options );
    }
    if( isset( $_POST['edit_settings_form_field'] ) && wp_verify_nonce( $_POST['edit_settings_form_field'], 'edit_settings_form') ) {
    	// var_dump($_POST);
    	// var_dump($_FILES);

    	$options = get_option( 'mos_courier_options' );

    	$cname = sanitize_text_field( $_POST['cname'] );
    	if ($cname) $options['cname'] = $cname;
    	$address = sanitize_text_field( $_POST['address'] );
    	if ($address) $options['address'] = $address;
    	$website = sanitize_text_field( $_POST['website'] );
    	if ($website) $options['website'] = $website;
    	$phone = sanitize_text_field( $_POST['phone'] );
    	if ($phone) $options['phone'] = $phone;
    	$oprefix = sanitize_text_field( $_POST['oprefix'] );
    	if ($oprefix) $options['oprefix'] = $oprefix;

    	$packaging = sanitize_text_field( $_POST['packaging'] );
    	if ($packaging) $options['packaging'] = $packaging;
    	$urgent = $_POST['urgent'] ;
    	if ($urgent) $options['urgent'] = $urgent;

    	// var_dump($ocharge);

    	
    	if($_POST["ext-logo"]){
    		// echo "Done<br/>";
    		$path_parts = pathinfo($_POST["ext-logo"]);
    		$newFile = $prefix.rand(1000,9999).strtotime("now").'.'.$path_parts['extension'];
    		$int = copy($_POST["ext-logo"],wp_upload_dir()["basedir"].'/'.$newFile);
    		if($int){
    			$img_url = aq_resize(wp_upload_dir()["baseurl"].'/'.$newFile,100,100,true);
    		}
    	}
    	if ($_FILES["image-file"]["type"] == "image/jpeg"){
    		//echo "Done<br/>";
	    	require_once( ABSPATH . 'wp-admin/includes/image.php' );
			require_once( ABSPATH . 'wp-admin/includes/file.php' );
			require_once( ABSPATH . 'wp-admin/includes/media.php' );
			$attachment_id = media_handle_upload('image-file', 0);
			$img_url = aq_resize(wp_get_attachment_url( $attachment_id ),100,100,true);
		}
		if($img_url){
			$options['clogo'] = $img_url;
		}
    	update_option( 'mos_courier_options', $options );
    }
    /*
require_once( ABSPATH . 'wp-admin/includes/image.php' );
require_once( ABSPATH . 'wp-admin/includes/file.php' );
require_once( ABSPATH . 'wp-admin/includes/media.php' );

	if ( !empty( $_FILES['feature_image']["tmp_name"]) ) {		
		$attachment_id = media_handle_upload( 'feature_image', 0 );
		if ( !is_wp_error( $attachment_id ) ) {
			set_post_thumbnail( $product_id, $attachment_id );
		}		
	}
    */
}
$base_url = home_url( '/admin/' );
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta http-equiv="x-ua-compatible" content="ie=edge">

	<title><?php echo get_option('mos_courier_options')['cname']; ?></title>

	<!-- Font Awesome Icons -->
	<link rel="stylesheet" href="<?php echo plugin_dir_url( __FILE__ ) ?>adminlte/plugins/font-awesome/css/font-awesome.min.css">

	<!-- DataTables -->
	<link rel="stylesheet" href="<?php echo plugin_dir_url( __FILE__ ) ?>adminlte/plugins/datatables/dataTables.bootstrap4.css">
	<!-- Select2 -->
	<link rel="stylesheet" href="<?php echo plugin_dir_url( __FILE__ ) ?>adminlte/plugins/select2/select2.min.css">
	
	<!-- daterange picker -->
	<link rel="stylesheet" href="<?php echo plugin_dir_url( __FILE__ ) ?>adminlte/plugins/daterangepicker/daterangepicker.css">

	<!-- Bootstrap Imageupload -->
	<link rel="stylesheet" href="<?php echo plugin_dir_url( __FILE__ ) ?>adminlte/plugins/bootstrap-imageupload/dist/css/bootstrap-imageupload.css">

	<link rel="stylesheet" href="<?php echo plugin_dir_url( __FILE__ ) ?>css/mos-courier.css">

	<!-- Theme style -->
	<link rel="stylesheet" href="<?php echo plugin_dir_url( __FILE__ ) ?>adminlte/dist/css/adminlte.min.css">
	<!-- Google Font: Source Sans Pro -->
	<link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
	
</head>
<body class="hold-transition sidebar-mini"><!-- onload="window.print();"-->
	<div class="wrapper">

		<!-- Navbar -->
		<nav class="main-header navbar navbar-expand navbar-white navbar-light">
			<!-- Left navbar links -->
			<ul class="navbar-nav">
				<li class="nav-item">
					<a class="nav-link" data-widget="pushmenu" href="#"><i class="fa fa-bars"></i></a>
				</li>
				<li class="nav-item d-none d-sm-inline-block">
					<a href="<?php echo home_url( '/admin/' ); ?>" class="nav-link">Home</a>
				</li>
				<li class="nav-item d-none d-sm-inline-block">
					<a href="<?php echo home_url( '/contact/' ); ?>" class="nav-link" target="_blank">Contact</a>
				</li>
			</ul>

			<!-- SEARCH FORM -->
			<form class="form-inline ml-3">
				<div class="input-group input-group-sm">
					<input class="form-control form-control-navbar" type="search" placeholder="Search" aria-label="Search">
					<div class="input-group-append">
						<button class="btn btn-navbar" type="submit">
							<i class="fa fa-search"></i>
						</button>
					</div>
				</div>
			</form>

			<!-- Right navbar links -->
			<!-- <ul class="navbar-nav ml-auto">
				<li class="nav-item dropdown">
					<a class="nav-link" data-toggle="dropdown" href="#">
						<i class="fa fa-comments"></i>
						<span class="badge badge-danger navbar-badge">3</span>
					</a>
					<div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
						<a href="#" class="dropdown-item">
							<div class="media">
								<img src="<?php echo plugin_dir_url( __FILE__ ) ?>adminlte/dist/img/user1-128x128.jpg" alt="User Avatar" class="img-size-50 mr-3 img-circle">
								<div class="media-body">
									<h3 class="dropdown-item-title">
										Brad Diesel
										<span class="float-right text-sm text-danger"><i class="fa fa-star"></i></span>
									</h3>
									<p class="text-sm">Call me whenever you can...</p>
									<p class="text-sm text-muted"><i class="fa fa-clock mr-1"></i> 4 Hours Ago</p>
								</div>
							</div>
						</a>
						<div class="dropdown-divider"></div>
						<a href="#" class="dropdown-item">
							<div class="media">
								<img src="<?php echo plugin_dir_url( __FILE__ ) ?>adminlte/dist/img/user8-128x128.jpg" alt="User Avatar" class="img-size-50 img-circle mr-3">
								<div class="media-body">
									<h3 class="dropdown-item-title">
										John Pierce
										<span class="float-right text-sm text-muted"><i class="fa fa-star"></i></span>
									</h3>
									<p class="text-sm">I got your message bro</p>
									<p class="text-sm text-muted"><i class="fa fa-clock mr-1"></i> 4 Hours Ago</p>
								</div>
							</div>
						</a>
						<div class="dropdown-divider"></div>
						<a href="#" class="dropdown-item">
							<div class="media">
								<img src="<?php echo plugin_dir_url( __FILE__ ) ?>adminlte/dist/img/user3-128x128.jpg" alt="User Avatar" class="img-size-50 img-circle mr-3">
								<div class="media-body">
									<h3 class="dropdown-item-title">
										Nora Silvester
										<span class="float-right text-sm text-warning"><i class="fa fa-star"></i></span>
									</h3>
									<p class="text-sm">The subject goes here</p>
									<p class="text-sm text-muted"><i class="fa fa-clock mr-1"></i> 4 Hours Ago</p>
								</div>
							</div>
						</a>
						<div class="dropdown-divider"></div>
						<a href="#" class="dropdown-item dropdown-footer">See All Messages</a>
					</div>
				</li>
				<li class="nav-item dropdown">
					<a class="nav-link" data-toggle="dropdown" href="#">
						<i class="fa fa-bell"></i>
						<span class="badge badge-warning navbar-badge">15</span>
					</a>
					<div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
						<span class="dropdown-header">15 Notifications</span>
						<div class="dropdown-divider"></div>
						<a href="#" class="dropdown-item">
							<i class="fa fa-envelope mr-2"></i> 4 new messages
							<span class="float-right text-muted text-sm">3 mins</span>
						</a>
						<div class="dropdown-divider"></div>
						<a href="#" class="dropdown-item">
							<i class="fa fa-users mr-2"></i> 8 friend requests
							<span class="float-right text-muted text-sm">12 hours</span>
						</a>
						<div class="dropdown-divider"></div>
						<a href="#" class="dropdown-item">
							<i class="fa fa-file mr-2"></i> 3 new reports
							<span class="float-right text-muted text-sm">2 days</span>
						</a>
						<div class="dropdown-divider"></div>
						<a href="#" class="dropdown-item dropdown-footer">See All Notifications</a>
					</div>
				</li>
				<li class="nav-item">
					<a class="nav-link" data-widget="control-sidebar" data-slide="true" href="#"><i
						class="fa fa-th-large"></i></a>
				</li>
			</ul> -->
		</nav>
		<!-- /.navbar -->

		<!-- Main Sidebar Container -->
		<aside class="main-sidebar sidebar-dark-primary elevation-4">
			<!-- Brand Logo -->
			<a href="<?php echo home_url(); ?>" class="brand-link">
				<?php 
				if(in_array( 'operator', $current_user->roles )) :					
					$brand_name = get_option( 'mos_courier_options')['cname'];
					$brand_logo = aq_resize(get_option( 'mos_courier_options')['clogo'], 33, 33, true);  
				else :
					$brand_name = get_user_meta( $current_user_id, 'brand_name', true ); 
					$brand_logo = aq_resize(get_user_meta( $current_user_id, 'brand_logo', true ), 33, 33, true); 
				endif;
				?>
				<?php if ($brand_logo) : ?>
					<img src="<?php echo $brand_logo; ?>" alt="AdminLTE Logo" class="brand-image img-circle elevation-3"
				style="opacity: .8">
				<?php endif; ?>
				<span class="brand-text font-weight-light"><?php echo $brand_name ?></span>
			</a>

			<!-- Sidebar -->
			<div class="sidebar">
				<!-- Sidebar user panel (optional) -->
				<div class="user-panel mt-3 pb-3 mb-3 d-flex">
					<div class="image">
						<?php 
						$profile_image = get_user_meta( $current_user_id, 'profile_photo', true ); 
						$display_name = get_userdata( $current_user_id )->display_name;
						?>
						<?php if ($profile_image) : ?>
							<img src="<?php echo $profile_image;?>" class="img-circle elevation-2" alt="User Image">
						<?php endif; ?>
					</div>
					<div class="info">
						<a href="#" class="d-block"><?php echo $display_name ?></a>
					</div>
				</div>

				<!-- Sidebar Menu -->
				<nav class="mt-2">
					<ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="true">
						<?php $page = @$_GET['page']; ?>
						<li class="nav-item">
							<a href="<?php echo home_url( '/admin/' ); ?>" class="nav-link <?php if (!@$page) echo 'active' ?>">
								<i class="nav-icon fa fa-dashboard"></i>
								<p>
									Dashboard				
								</p>
							</a>
						</li>
						<?php if(in_array( 'operator', $current_user->roles )):?>
						<li class="nav-item">
							<a href="<?php echo $base_url ?>?page=transaction" class="nav-link <?php if (@$page == 'transaction') echo 'active' ?>">
								<i class="nav-icon fa fa-money"></i>
								<p>
									Transaction				
								</p>
							</a>
						</li>
						<li class="nav-item has-treeview <?php if (@$page == 'user-manage' OR @$page == 'user-edit' OR @$page == 'user-bulk') echo 'menu-open' ?>">
							<a href="<?php echo $base_url ?>?page=user-manage" class="nav-link <?php if (@$page == 'user-manage' OR @$page == 'user-edit' OR @$page == 'user-bulk') echo 'active' ?>">
								<i class="nav-icon fa fa-user"></i>
								<p>
									User
									<i class="right fa fa-angle-left"></i>
								</p>
							</a>
							<ul class="nav nav-treeview">
								<li class="nav-item">
									<a href="<?php echo $base_url ?>?page=user-manage" class="nav-link <?php if (@$page == 'user-manage') echo 'active' ?>">
										<!-- <i class="fa fa-circle-o nav-icon"></i> -->
										<p>All User</p>
									</a>
								</li>
								<li class="nav-item">
									<a href="<?php echo $base_url ?>?page=user-edit" class="nav-link <?php if (@$page == 'user-edit') echo 'active' ?>">
										<!-- <i class="fa fa-circle-o nav-icon"></i> -->
										<p>Add User</p>
									</a>
								</li>
								<li class="nav-item">
									<a href="<?php echo $base_url ?>?page=user-bulk" class="nav-link <?php if (@$page == 'user-bulk') echo 'active' ?>">
										<!-- <i class="fa fa-circle-o nav-icon"></i> -->
										<p>Import User</p>
									</a>
								</li>
							</ul>
						</li>
						<li class="nav-item has-treeview <?php if (@$page == 'order-manage' OR @$page == 'order-edit' OR @$page == 'order-bulk' OR @$page == 'check-in' OR @$page == 'check-out' OR @$page == 'bill-pay') echo 'menu-open' ?>">
							<a href="<?php echo $base_url ?>?page=order-manage" class="nav-link <?php if (@$page == 'order-manage' OR @$page == 'order-edit' OR @$page == 'order-bulk' OR @$page == 'check-in' OR @$page == 'check-out' OR @$page == 'bill-pay') echo 'active' ?>">
								<i class="nav-icon fa fa-database"></i>
								<p>
									Order
									<i class="right fa fa-angle-left"></i>
								</p>
							</a>
							<ul class="nav nav-treeview">
								<li class="nav-item">
									<a href="<?php echo $base_url ?>?page=order-manage" class="nav-link <?php if (@$page == 'order-manage') echo 'active' ?>">
										<!-- <i class="fa fa-circle-o nav-icon"></i> -->
										<p>All Order</p>
									</a>
								</li>
								<li class="nav-item">
									<a href="<?php echo $base_url ?>?page=order-edit" class="nav-link <?php if (@$page == 'order-edit') echo 'active' ?>">
										<!-- <i class="fa fa-circle-o nav-icon"></i> -->
										<p>Add Order</p>
									</a>
								</li>
								<li class="nav-item">
									<a href="<?php echo $base_url ?>?page=order-bulk" class="nav-link <?php if (@$page == 'order-bulk') echo 'active' ?>">
										<!-- <i class="fa fa-circle-o nav-icon"></i> -->
										<p>Import Order</p>
									</a>
								</li>
								<li class="nav-item">
									<a href="<?php echo $base_url ?>?page=check-in" class="nav-link <?php if (@$page == 'check-in') echo 'active' ?>">
										<!-- <i class="fa fa-circle-o nav-icon"></i> -->
										<p>Check In</p>
									</a>
								</li>
								<li class="nav-item">
									<a href="<?php echo $base_url ?>?page=check-out" class="nav-link <?php if (@$page == 'check-out') echo 'active' ?>">
										<!-- <i class="fa fa-circle-o nav-icon"></i> -->
										<p>Check Out</p>
									</a>
								</li>
								<li class="nav-item">
									<a href="<?php echo $base_url ?>?page=bill-pay" class="nav-link <?php if (@$page == 'bill-pay') echo 'active' ?>">
										<!-- <i class="fa fa-circle-o nav-icon"></i> -->
										<p>Bill Pay</p>
									</a>
								</li>
							</ul>
						</li>
						<!-- <li class="nav-item">
							<a href="<?php echo $base_url ?>?page=report" class="nav-link <?php if (@$page == 'report') echo 'active' ?>">
								<i class="nav-icon fa fa-bell"></i>
								<p>
									Report
								</p>
							</a>
						</li> -->
						<li class="nav-item has-treeview <?php if (@$page == 'settings' OR @$page == 'settings-area') echo 'menu-open' ?>">
							<a href="<?php echo $base_url ?>?page=settings" class="nav-link <?php if (@$page == 'settings') echo 'active' ?>">
								<i class="nav-icon fa fa-cogs"></i>
								<p>
									Settings
									<i class="right fa fa-angle-left"></i>
								</p>
							</a>
							<ul class="nav nav-treeview">
								<li class="nav-item">
									<a href="<?php echo $base_url ?>?page=settings" class="nav-link <?php if (@$page == 'settings') echo 'active' ?>">
										<!-- <i class="fa fa-circle-o nav-icon"></i> -->
										<p>General</p>
									</a>
								</li>

								<li class="nav-item">
									<a href="<?php echo $base_url ?>?page=settings-area" class="nav-link <?php if (@$page == 'settings-area') echo 'active' ?>">
										<!-- <i class="fa fa-circle-o nav-icon"></i> -->
										<p>Area Setup</p>
									</a>
								</li>
							</ul>
						</li>
						<?php else : ?>
						<li class="nav-item has-treeview">
							<a href="<?php echo $base_url ?>?page=order-manage" class="nav-link">
								<i class="nav-icon fa fa-database"></i>
								<p>
									Order
									<i class="right fa fa-angle-left"></i>
								</p>
							</a>
							<ul class="nav nav-treeview">
								<li class="nav-item">
									<a href="<?php echo $base_url ?>?page=order-manage" class="nav-link">
										<!-- <i class="fa fa-circle-o nav-icon"></i> -->
										<p>All Order</p>
									</a>
								</li>

								<li class="nav-item">
									<a href="<?php echo $base_url ?>?page=order-edit" class="nav-link">
										<!-- <i class="fa fa-circle-o nav-icon"></i> -->
										<p>Add Order</p>
									</a>
								</li>
							</ul>
						</li>
						<?php endif; ?>
						<li class="nav-item has-treeview <?php if (@$page == 'edit-profile' OR @$page == 'change-password') echo 'menu-open' ?>">
							<a href="<?php echo $base_url ?>?page=edit-profile" class="nav-link <?php if (@$page == 'edit-profile' OR @$page == 'change-password') echo 'active' ?>">
								<i class="nav-icon fa fa-user"></i>
								<p>
									Profile
									<i class="right fa fa-angle-left"></i>
								</p>
							</a>
							<ul class="nav nav-treeview">
								<li class="nav-item">
									<a href="<?php echo $base_url ?>?page=edit-profile" class="nav-link <?php if (@$page == 'edit-profile') echo 'active' ?>">
										<!-- <i class="fa fa-circle-o nav-icon"></i> -->
										<p>Edit Profile</p>
									</a>
								</li>

								<li class="nav-item">
									<a href="<?php echo $base_url ?>?page=change-password" class="nav-link <?php if (@$page == 'change-password') echo 'active' ?>">
										<!-- <i class="fa fa-circle-o nav-icon"></i> -->
										<p>Change Password</p>
									</a>
								</li>
							</ul>
						</li>
						<li class="nav-item">
							<a href="<?php echo wp_logout_url( wp_login_url() ); ?>" class="nav-link">
								<i class="nav-icon fa fa-sign-out"></i>
								<p>
									Logout				
								</p>
							</a>
						</li>
					</ul>
				</nav>
				<!-- /.sidebar-menu -->
			</div>
			<!-- /.sidebar -->
		</aside>

	