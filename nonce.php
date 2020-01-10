<?php
// sample form with nonce
function pippin_sample_nonce_form() {
 
	ob_start(); ?>
 
		<form id="pippin_nonce_sample" method="POST" action="">
			<p>
				<input type="hidden" name="pippin_sample_nonce" value="<?php echo wp_create_nonce('pippin-sample-nonce'); ?>"/>
				<input type="submit" value="Submit"/>
			</p>
		</form>
		<?php
	return ob_get_clean();
}
add_shortcode('nonce_form', 'pippin_sample_nonce_form');
 
// processes the data submitted by the form
function pippin_process_form_data() {
 
	if(isset($_POST['pippin_sample_nonce'])) {
		if(wp_verify_nonce($_POST['pippin_sample_nonce'], 'pippin-sample-nonce')) {
 
			echo 'Nonce verified successfully'; exit;
			// process form here
 
		} else {
			echo 'nonce verification failed'; exit;
		}
	}
}
add_action('init', 'pippin_process_form_data');
?>