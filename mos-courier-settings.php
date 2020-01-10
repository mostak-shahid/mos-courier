<?php
function mos_courier_settings_init() {
	register_setting( 'mos_courier', 'mos_courier_options' );
	add_settings_section('mos_courier_section_top_nav', '', 'mos_courier_section_top_nav_cb', 'mos_courier');
	add_settings_section('mos_courier_section_dash_start', '', 'mos_courier_section_dash_start_cb', 'mos_courier');
	add_settings_section('mos_courier_section_dash_end', '', 'mos_courier_section_end_cb', 'mos_courier');
	
	add_settings_section('mos_courier_section_setting_start', '', 'mos_courier_section_settings_start_cb', 'mos_courier');
	
	add_settings_field( 'field_cname', __( 'Company Name', 'mos_courier' ), 'mos_courier_field_cname_cb', 'mos_courier', 'mos_courier_section_setting_start', [ 'label_for' => 'cname' ] );
	add_settings_field( 'field_clogo', __( 'Company Logo', 'mos_courier' ), 'mos_courier_field_clogo_cb', 'mos_courier', 'mos_courier_section_setting_start', [ 'label_for' => 'clogo' ] );
	add_settings_field( 'field_address', __( 'Company Address', 'mos_courier' ), 'mos_courier_field_address_cb', 'mos_courier', 'mos_courier_section_setting_start', [ 'label_for' => 'address' ] );		
	add_settings_field( 'field_website', __( 'Company Website', 'mos_courier' ), 'mos_courier_field_website_cb', 'mos_courier', 'mos_courier_section_setting_start', [ 'label_for' => 'website' ] );	
	add_settings_field( 'field_phone', __( 'Company Phone', 'mos_courier' ), 'mos_courier_field_phone_cb', 'mos_courier', 'mos_courier_section_setting_start', [ 'label_for' => 'phone' ] );	
	add_settings_field( 'field_oprefix', __( 'Order Prefix', 'mos_courier' ), 'mos_courier_field_oprefix_cb', 'mos_courier', 'mos_courier_section_setting_start', [ 'label_for' => 'oprefix' ] );	
	add_settings_field( 'field_zone', __( 'Delivery Zone', 'mos_courier' ), 'mos_courier_field_zone_cb', 'mos_courier', 'mos_courier_section_setting_start', [ 'label_for' => 'zone' ] );
	add_settings_field( 'field_packaging', __( 'Packaging Type', 'mos_courier' ), 'mos_courier_field_packaging_cb', 'mos_courier', 'mos_courier_section_setting_start', [ 'label_for' => 'packaging' ] );
	add_settings_field( 'field_urgent', __( 'Urgent Charge', 'mos_courier' ), 'mos_courier_field_urgent_cb', 'mos_courier', 'mos_courier_section_setting_start', [ 'label_for' => 'urgent' ] );
	add_settings_field( 'field_ocharge', __( 'Other City Charge', 'mos_courier' ), 'mos_courier_field_ocharge_cb', 'mos_courier', 'mos_courier_section_setting_start', [ 'label_for' => 'ocharge' ] );
	
	add_settings_section('mos_courier_section_setting_end', '', 'mos_courier_section_end_cb', 'mos_courier');

	
	add_settings_section('mos_courier_section_scripts_start', '', 'mos_courier_section_scripts_start_cb', 'mos_courier');
	add_settings_field( 'field_jquery', __( 'JQuery', 'mos_courier' ), 'mos_courier_field_jquery_cb', 'mos_courier', 'mos_courier_section_scripts_start', [ 'label_for' => 'jquery', 'class' => 'mos_courier_row', 'mos_courier_custom_data' => 'custom', ] );
	add_settings_field( 'field_bootstrap', __( 'Bootstrap', 'mos_courier' ), 'mos_courier_field_bootstrap_cb', 'mos_courier', 'mos_courier_section_scripts_start', [ 'label_for' => 'bootstrap', 'class' => 'mos_courier_row', 'mos_courier_custom_data' => 'custom', ] );
	add_settings_field( 'field_css', __( 'Custom Css', 'mos_courier' ), 'mos_courier_field_css_cb', 'mos_courier', 'mos_courier_section_scripts_start', [ 'label_for' => 'mos_courier_css' ] );
	add_settings_field( 'field_js', __( 'Custom Js', 'mos_courier' ), 'mos_courier_field_js_cb', 'mos_courier', 'mos_courier_section_scripts_start', [ 'label_for' => 'mos_courier_js' ] );
	add_settings_section('mos_courier_section_scripts_end', '', 'mos_courier_section_end_cb', 'mos_courier');

}
add_action( 'admin_init', 'mos_courier_settings_init' );

function get_mos_courier_active_tab () {
	$output = array(
		'option_prefix' => admin_url() . "/options-general.php?page=mos_courier_settings&tab=",
		//'option_prefix' => "?post_type=p_file&page=mos_courier_settings&tab=",
	);
	if (isset($_GET['tab'])) $active_tab = $_GET['tab'];
	elseif (isset($_COOKIE['courier_active_tab'])) $active_tab = $_COOKIE['courier_active_tab'];
	else $active_tab = 'dashboard';
	$output['active_tab'] = $active_tab;
	return $output;
}
function mos_courier_section_top_nav_cb( $args ) {
	$data = get_mos_courier_active_tab ();
	?>
    <ul class="nav nav-tabs">
        <li class="tab-nav <?php if($data['active_tab'] == 'dashboard') echo 'active';?>"><a data-id="dashboard" href="<?php echo $data['option_prefix'];?>dashboard">Dashboard</a></li>
        <li class="tab-nav <?php if($data['active_tab'] == 'settings') echo 'active';?>"><a data-id="settings" href="<?php echo $data['option_prefix'];?>settings">Settings</a></li>
        <li class="tab-nav <?php if($data['active_tab'] == 'scripts') echo 'active';?>"><a data-id="scripts" href="<?php echo $data['option_prefix'];?>scripts">Advanced CSS, JS</a></li>
    </ul>
	<?php
}
function mos_courier_section_dash_start_cb( $args ) {
	$data = get_mos_courier_active_tab ();
  $options = get_option( 'mos_courier_options' );
	?>
	<div id="mos-courier-dashboard" class="tab-con <?php if($data['active_tab'] == 'dashboard') echo 'active';?>">
		<?php var_dump($options) ?>

	<?php
}
function mos_courier_section_settings_start_cb( $args ) {
	$data = get_mos_courier_active_tab ();
	?>
	<div id="mos-courier-settings" class="tab-con <?php if($data['active_tab'] == 'settings') echo 'active';?>">
	<?php
}
function mos_courier_field_cname_cb( $args ) {
	$options = get_option( 'mos_courier_options' );
	?>
	<input class="regular-text" name="mos_courier_options[<?php echo esc_attr( $args['label_for'] ); ?>]" type="text" id="<?php echo esc_attr( $args['label_for'] ); ?>" value="<?php echo isset( $options[ $args['label_for'] ] ) ? esc_html_e($options[$args['label_for']]) : '';?>">

	<?php
}

function mos_courier_field_clogo_cb( $args ) {
	$options = get_option( 'mos_courier_options' );
	?>
	<input class="regular-text" name="mos_courier_options[<?php echo esc_attr( $args['label_for'] ); ?>]" type="text" id="<?php echo esc_attr( $args['label_for'] ); ?>" value="<?php echo isset( $options[ $args['label_for'] ] ) ? esc_html_e($options[$args['label_for']]) : '';?>">

	<?php
}
function mos_courier_field_address_cb( $args ) {
	$options = get_option( 'mos_courier_options' );
	?>
	<input class="regular-text" name="mos_courier_options[<?php echo esc_attr( $args['label_for'] ); ?>]" type="text" id="<?php echo esc_attr( $args['label_for'] ); ?>" value="<?php echo isset( $options[ $args['label_for'] ] ) ? esc_html_e($options[$args['label_for']]) : '';?>">

	<?php
}

function mos_courier_field_website_cb( $args ) {
	$options = get_option( 'mos_courier_options' );
	?>
	<input class="regular-text" name="mos_courier_options[<?php echo esc_attr( $args['label_for'] ); ?>]" type="text" id="<?php echo esc_attr( $args['label_for'] ); ?>" value="<?php echo isset( $options[ $args['label_for'] ] ) ? esc_html_e($options[$args['label_for']]) : '';?>">

	<?php
}

function mos_courier_field_phone_cb( $args ) {
	$options = get_option( 'mos_courier_options' );
	?>
	<input class="regular-text" name="mos_courier_options[<?php echo esc_attr( $args['label_for'] ); ?>]" type="text" id="<?php echo esc_attr( $args['label_for'] ); ?>" value="<?php echo isset( $options[ $args['label_for'] ] ) ? esc_html_e($options[$args['label_for']]) : '';?>">

	<?php
}

function mos_courier_field_oprefix_cb( $args ) {
	$options = get_option( 'mos_courier_options' );
	?>
	<input class="regular-text" name="mos_courier_options[<?php echo esc_attr( $args['label_for'] ); ?>]" type="text" id="<?php echo esc_attr( $args['label_for'] ); ?>" value="<?php echo isset( $options[ $args['label_for'] ] ) ? esc_html_e($options[$args['label_for']]) : '';?>">

	<?php
}
function mos_courier_field_zone_cb( $args ) {
	$options = get_option( 'mos_courier_options' );
	?>
	<input class="regular-text" name="mos_courier_options[<?php echo esc_attr( $args['label_for'] ); ?>]" type="text" id="<?php echo esc_attr( $args['label_for'] ); ?>" value="<?php echo isset( $options[ $args['label_for'] ] ) ? esc_html_e($options[$args['label_for']]) : '';?>">
	<p class="description" id="tagline-description">Separate options by |</p>

	<?php
}
function mos_courier_field_packaging_cb( $args ) {
	$options = get_option( 'mos_courier_options' );
	?>
	<input class="regular-text" name="mos_courier_options[<?php echo esc_attr( $args['label_for'] ); ?>]" type="text" id="<?php echo esc_attr( $args['label_for'] ); ?>" value="<?php echo isset( $options[ $args['label_for'] ] ) ? esc_html_e($options[$args['label_for']]) : '';?>">
	<p class="description" id="tagline-description">Separate options by |</p>

	<?php
}
function mos_courier_field_urgent_cb( $args ) {
	$options = get_option( 'mos_courier_options' );
	// var_dump($args['label_for']);
	// var_dump($options['urgent']['type']);
	?>
	<input class="regular-text" name="mos_courier_options[<?php echo esc_attr( $args['label_for'] ); ?>][amount]" type="number" id="<?php echo esc_attr( $args['label_for']['amount'] ); ?>" value="<?php echo isset( $options[ $args['label_for'] ]['amount'] ) ? esc_html_e($options[$args['label_for']]['amount']) : '';?>">
	<select name="mos_courier_options[<?php echo esc_attr( $args['label_for'] ); ?>][type]">
		<option value="taka" <?php selected( $options[ $args['label_for'] ]['type'], 'taka' ); ?>>Taka</option>
		<option value="%" <?php selected( $options[ $args['label_for'] ]['type'], '%' ); ?>>%</option>
	</select>
	<!-- <p class="description" id="tagline-description">Urgent Charge</p> -->

	<?php
}
function mos_courier_field_ocharge_cb( $args ) {
	$options = get_option( 'mos_courier_options' );
	$zone = $options["zone"];
	// var_dump($zone);
	$zoneArr = mos_str_to_arr($zone, '|');
	// var_dump($zoneArr);
	?>
	<table>		
		<?php
		foreach ($zoneArr as $value) {
			?>
			<tr>
				<th><?php echo $value; ?></th>
				<td><input class="regular-text" name="mos_courier_options[<?php echo esc_attr( $args['label_for'] ); ?>][<?php echo $value ?>]" value="<?php echo isset( $options[ $args['label_for'] ][$value] ) ? esc_html_e($options[$args['label_for']][$value]) : '';?>"></td>
			</tr>
			<?php 
		}
		?>
	</table>

	<?php
}
function mos_courier_section_scripts_start_cb( $args ) {
	$data = get_mos_courier_active_tab ();
	?>
	<div id="mos-courier-scripts" class="tab-con <?php if($data['active_tab'] == 'scripts') echo 'active';?>">
	<?php
}
function mos_courier_field_jquery_cb( $args ) {
	$options = get_option( 'mos_courier_options' );
	?>
	<label for="<?php echo esc_attr( $args['label_for'] ); ?>"><input name="mos_courier_options[<?php echo esc_attr( $args['label_for'] ); ?>]" type="checkbox" id="<?php echo esc_attr( $args['label_for'] ); ?>" value="1" <?php echo isset( $options[ $args['label_for'] ] ) ? ( checked( $options[ $args['label_for'] ], 1, false ) ) : ( '' ); ?>><?php esc_html_e( 'Yes I like to add JQuery from Plugin.', 'mos_courier' ); ?></label>
	<?php
}
function mos_courier_field_bootstrap_cb( $args ) {
	$options = get_option( 'mos_courier_options' );
	?>
	<label for="<?php echo esc_attr( $args['label_for'] ); ?>"><input name="mos_courier_options[<?php echo esc_attr( $args['label_for'] ); ?>]" type="checkbox" id="<?php echo esc_attr( $args['label_for'] ); ?>" value="1" <?php echo isset( $options[ $args['label_for'] ] ) ? ( checked( $options[ $args['label_for'] ], 1, false ) ) : ( '' ); ?>><?php esc_html_e( 'Yes I like to add JQuery from Plugin.', 'mos_courier' ); ?></label>
	<?php
}
function mos_courier_field_css_cb( $args ) {
	$options = get_option( 'mos_courier_options' );
	?>
	<textarea name="mos_courier_options[<?php echo esc_attr( $args['label_for'] ); ?>]" id="<?php echo esc_attr( $args['label_for'] ); ?>" rows="10" class="regular-text"><?php echo isset( $options[ $args['label_for'] ] ) ? esc_html_e($options[$args['label_for']]) : '';?></textarea>
	<script>
    var editor = CodeMirror.fromTextArea(document.getElementById("mos_courier_css"), {
      lineNumbers: true,
      mode: "text/css",
      extraKeys: {"Ctrl-Space": "autocomplete"}
    });
	</script>
	<?php
}
function mos_courier_field_js_cb( $args ) {
	$options = get_option( 'mos_courier_options' );
	?>
	<textarea name="mos_courier_options[<?php echo esc_attr( $args['label_for'] ); ?>]" id="<?php echo esc_attr( $args['label_for'] ); ?>" rows="10" class="regular-text"><?php echo isset( $options[ $args['label_for'] ] ) ? esc_html_e($options[$args['label_for']]) : '';?></textarea>
	<script>
    var editor = CodeMirror.fromTextArea(document.getElementById("mos_courier_js"), {
      lineNumbers: true,
      mode: "text/css",
      extraKeys: {"Ctrl-Space": "autocomplete"}
    });
	</script>
	<?php
}
function mos_courier_section_end_cb( $args ) {
	$data = get_mos_courier_active_tab ();
	?>
	</div>
	<?php
}


function mos_courier_options_page() {
	//add_menu_page( 'WPOrg', 'WPOrg Options', 'manage_options', 'mos_courier', 'mos_courier_options_page_html' );
	add_submenu_page( 'options-general.php', 'Courier Settings', 'Courier', 'manage_options', 'mos_courier_settings', 'mos_courier_admin_page' );
}
add_action( 'admin_menu', 'mos_courier_options_page' );

function mos_courier_admin_page() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}
	if ( isset( $_GET['settings-updated'] ) ) {
		add_settings_error( 'mos_courier_messages', 'mos_courier_message', __( 'Settings Saved', 'mos_courier' ), 'updated' );
	}
	settings_errors( 'mos_courier_messages' );
	?>
	<div class="wrap mos-courier-wrapper">
		<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
		<form action="options.php" method="post" enctype="multipart/form-data">
		<?php
		settings_fields( 'mos_courier' );
		do_settings_sections( 'mos_courier' );
		submit_button( 'Save Settings' );
		?>
		</form>
	</div>
	<?php
}