<?php 
$user_created = 0;
if ($_SERVER['REQUEST_METHOD'] == "POST") {
	// foreach ($_POST as $field => $value) {
	// 	echo "$"."_POST['"."$field"."']"." == '$value'<br>";
	// }

    if( isset( $_POST['register_merchant_form_field'] ) && wp_verify_nonce( $_POST['register_merchant_form_field'], 'register_merchant_form') ) {
       	$brand_name = sanitize_text_field( $_POST['brand_name'] );
       	$phone = sanitize_text_field( $_POST['phone'] );
   		$user_email = sanitize_text_field( $_POST['email'] );
   		$password = $_POST['password'];
   		$user_id = username_exists( $user_email );			 
		if ( ! $user_id && false == email_exists( $user_email ) ) {
		    $user_id = wp_create_user( $user_email, $password, $user_email );
       		update_user_meta( $user_id, 'brand_name', $brand_name );
		    update_user_meta( $user_id, 'phone', $phone );
		    update_user_meta( $user_id, 'user_role', 'Regular' );
		    update_user_meta( $user_id, 'activation', 'Deactive' );
		    $u = new WP_User( $user_id );
		    $u->remove_role( 'subscriber' );
		    $u->add_role( 'merchant' );
		    $user_created = 1;
		} 	
	}	
}
$current_user = wp_get_current_user();
if ( $current_user->ID ) {
	wp_redirect(home_url('/admin/'));
	exit;
}
?><!DOCTYPE html>
<html lang="en">
<head>
	<title>Bootstrap Example</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="<?php echo plugin_dir_url( __FILE__ ) ?>css/bootstrap.min.css">
	<script src="<?php echo plugin_dir_url( __FILE__ ) ?>adminlte/plugins/jquery/jquery.min.js"></script>
	<script src="<?php echo plugin_dir_url( __FILE__ ) ?>js/popper.min.js"></script>
	<script src="<?php echo plugin_dir_url( __FILE__ ) ?>js/bootstrap.min.js"></script>
	<style>
		body {
		    background: #f1f1f1;
		}
		.form-wrapper{
		    background: #ffffff;
			padding: 26px 24px;
			font-weight: 400;
			box-shadow: 0 1px 3px rgba(0,0,0,.13);			
		}
		form {
			margin-bottom: 0;
		}
		#nav {
		    margin: 24px 0 0 0;
		    padding: 0 24px 0;
		}
	</style>
</head>
<body>
	<div class="container-fluid">
		<div class="row justify-content-center align-items-center" style="min-height:100vh">
			<div class="col-lg-4">
			<?php if ($user_created) : ?>
				<div class="alert alert-success alert-dismissible fade show" role="alert">
					<strong>Success!</strong> Your account has been created.
					<button type="button" class="close" data-dismiss="alert" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
			<?php endif; ?>							
				<div class="form-wrapper">
					<form action="" method="POST" class="needs-validation" novalidate>
						<?php wp_nonce_field( 'register_merchant_form', 'register_merchant_form_field' ); ?>
						
						<div class="form-row">
							<div class="col-lg-6">										
								<div class="form-group">
									<label for="brand_name">Brand Name</label>
									<input type="text" class="form-control" name="brand_name" id="brand_name" placeholder="Brand Name" required>
									<div class="valid-feedback">Valid.</div>
									<div class="invalid-feedback">Please fill out this field.</div>
								</div>	
							</div>
							<div class="col-lg-6">		
								<div class="form-group">
									<label for="phone">Contact No.</label>
									<input type="text" class="form-control mb-2" name="phone" id="phone" placeholder="Phone" required>
									<div class="valid-feedback">Valid.</div>
									<div class="invalid-feedback">Please fill out this field.</div>
								</div>
							</div>
						</div>
						<div class="form-row">
							<div class="col-lg-6">
								<div class="form-group">
									<label for="email">Email</label>
									<input type="email" class="form-control" name="email" id="email" placeholder="Email" required>
									<div class="valid-feedback">Valid.</div>
									<div class="invalid-feedback">Please fill out this field.</div>
								</div>								
							</div>
							<div class="col-lg-6">										
								<div class="form-group">
									<label for="password">Password</label>
									<input type="password" class="form-control" name="password" id="password" placeholder="Password" required>
									<div class="valid-feedback">Valid.</div>
									<div class="invalid-feedback">Please fill out this field.</div>
								</div>
							</div>
						</div>
						<div class="form-group form-check">
							<label class="form-check-label">
								<input class="form-check-input" type="checkbox" name="agree" required> I agree with the terms &amp; conditions.
								<div class="valid-feedback">Valid.</div>
								<div class="invalid-feedback">Check this checkbox to continue.</div>
							</label>
						</div>
						<button type="submit" class="btn btn-primary">Register</button>
					</form>
				</div>
				<p id="nav">
					<a href="<?php echo wp_login_url() ?>">Log in</a>
					| 	<a href="<?php echo wp_login_url() ?>?action=lostpassword">Lost your password?</a>
				</p>
			</div>
		</div>
	</div>

	<script>
// Disable form submissions if there are invalid fields
(function() {
	'use strict';
	window.addEventListener('load', function() {
    // Get the forms we want to add validation styles to
    var forms = document.getElementsByClassName('needs-validation');
    // Loop over them and prevent submission
    var validation = Array.prototype.filter.call(forms, function(form) {
    	form.addEventListener('submit', function(event) {
    		if (form.checkValidity() === false) {
    			event.preventDefault();
    			event.stopPropagation();
    		}
    		form.classList.add('was-validated');
    	}, false);
    });
}, false);
})();
</script>

</body>
</html>
