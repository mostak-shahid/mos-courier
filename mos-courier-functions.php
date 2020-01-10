<?php
mos_courier_add_page('admin', 'Admin', '', 'default');
mos_courier_add_page('register', 'Register', '', 'default');
mos_courier_add_page('invoice-print', 'Invoice Print', '', 'default');
mos_courier_add_page('delivery-print', 'Delivery Print', '', 'default');
mos_courier_add_page('bill-print', 'Bill Print', '', 'default');
function mos_courier_add_page($page_slug, $page_title, $page_content, $page_template) {
    $page = get_page_by_path( $page_slug , OBJECT );
    //var_dump($page);
    if(!$page){
        $page_details = array(
            'post_title' => $page_title,
            'post_name' => $page_slug,
            'post_date' => gmdate("Y-m-d h:i:s"),
            'post_content' => $page_content,
            'post_status' => 'publish',
            'post_type' => 'page',
        );
        $page_id = wp_insert_post( $page_details );
        add_post_meta( $page_id, '_wp_page_template', $page_template );
    }
}

function mos_courier_admin_enqueue_scripts(){
    wp_enqueue_script( 'jquery' );
    wp_enqueue_media();
    $page = @$_GET['page'];
    global $pagenow, $typenow;
    /*var_dump($pagenow); //options-general.php(If under settings)/edit.php(If under post type)
    var_dump($typenow); //post type(If under post type)
    var_dump($page); //mos_courier_settings(If under settings)*/
    
    if ($pagenow == 'options-general.php' AND $page == 'mos_courier_settings') {
        wp_enqueue_style( 'mos-courier-admin', plugins_url( 'css/mos-courier-admin.css', __FILE__ ) );

        //wp_enqueue_media();

        
        /*Editor*/
        //wp_enqueue_style( 'docs', plugins_url( 'plugins/CodeMirror/doc/docs.css', __FILE__ ) );
        wp_enqueue_style( 'codemirror', plugins_url( 'plugins/CodeMirror/lib/codemirror.css', __FILE__ ) );
        wp_enqueue_style( 'show-hint', plugins_url( 'plugins/CodeMirror/addon/hint/show-hint.css', __FILE__ ) );

        wp_enqueue_script( 'codemirror', plugins_url( 'plugins/CodeMirror/lib/codemirror.js', __FILE__ ), array('jquery') );
        wp_enqueue_script( 'css', plugins_url( 'plugins/CodeMirror/mode/css/css.js', __FILE__ ), array('jquery') );
        wp_enqueue_script( 'javascript', plugins_url( 'plugins/CodeMirror/mode/javascript/javascript.js', __FILE__ ), array('jquery') );
        wp_enqueue_script( 'show-hint', plugins_url( 'plugins/CodeMirror/addon/hint/show-hint.js', __FILE__ ), array('jquery') );
        wp_enqueue_script( 'css-hint', plugins_url( 'plugins/CodeMirror/addon/hint/css-hint.js', __FILE__ ), array('jquery') );
        wp_enqueue_script( 'javascript-hint', plugins_url( 'plugins/CodeMirror/addon/hint/javascript-hint.js', __FILE__ ), array('jquery') );
        /*Editor*/

        wp_enqueue_script( 'mos-courier-functions', plugins_url( 'js/mos-courier-functions.js', __FILE__ ), array('jquery') );
        wp_enqueue_script( 'mos-courier-admin', plugins_url( 'js/mos-courier-admin.js', __FILE__ ), array('jquery') );
    }

}
add_action( 'admin_enqueue_scripts', 'mos_courier_admin_enqueue_scripts' );
function mos_courier_enqueue_scripts(){
    global $mos_courier_option;
    if ($mos_courier_option['jquery']) {
        wp_enqueue_script( 'jquery' );
    }
    if ($mos_courier_option['bootstrap']) {
        wp_enqueue_style( 'bootstrap.min', plugins_url( 'css/bootstrap.min.css', __FILE__ ) );
        wp_enqueue_script( 'bootstrap.min', plugins_url( 'js/bootstrap.min.js', __FILE__ ), array('jquery') );
    }
    if ($mos_courier_option['awesome']) {
        wp_enqueue_style( 'font-awesome.min', plugins_url( 'fonts/font-awesome-4.7.0/css/font-awesome.min.css', __FILE__ ) );
    }
    wp_enqueue_style( 'mos-courier', plugins_url( 'css/mos-courier.css', __FILE__ ) );
    wp_enqueue_script( 'mos-courier-functions', plugins_url( 'js/mos-courier-functions.js', __FILE__ ), array('jquery') );
    wp_enqueue_script( 'mos-courier', plugins_url( 'js/mos-courier.js', __FILE__ ), array('jquery') );
}
add_action( 'wp_enqueue_scripts', 'mos_courier_enqueue_scripts' );
function mos_courier_ajax_scripts(){
    wp_enqueue_script( 'mos-courier-ajax', plugins_url( 'js/mos-courier-ajax.js', __FILE__ ), array('jquery') );
    $ajax_params = array(
        'ajax_url' => admin_url('admin-ajax.php'),
        // 'ajax_nonce' => wp_create_nonce('mos_courier_verify'),
    );
    wp_localize_script( 'mos-courier-ajax', 'ajax_obj', $ajax_params );
}
add_action( 'wp_enqueue_scripts', 'mos_courier_ajax_scripts' );
add_action( 'admin_enqueue_scripts', 'mos_courier_ajax_scripts' );
function mos_courier_scripts() {
    global $mos_courier_option;
    if ($mos_courier_option['css']) {
        ?>
        <style>
            <?php echo $mos_courier_option['css'] ?>
        </style>
        <?php
    }
    if ($mos_courier_option['js']) {
        ?>
        <style>
            <?php echo $mos_courier_option['js'] ?>
        </style>
        <?php
    }
}
add_action( 'wp_footer', 'mos_courier_scripts', 100 );
function mos_str_to_arr($data, $separator){
    $output = array();
    $temp = explode($separator,$data);
    foreach ($temp as $value) {
        $output[trim($value)] = trim($value);
    }
    return $output;
}
function mos_user_list($role, $meta_key='', $meta_value=''){
    $args = array();
    $roles = array();
    $slice = explode(',', $role);
    foreach ($slice as $value) {
        $roles[] = trim($value);
    }
    $output = array();
    if ($role){
        $args['role__in'] =  $roles;
    } 
    if ($meta_key AND $meta_value) {
        $args['meta_key'] = $meta_key;
        $args['meta_value'] = $meta_value;
    }
    $all_data = get_users( $args );
    foreach ($all_data as $value) {
        $output[$value->data->ID] = $value->data->display_name;
    }
    return $output;
}
function mos_user_phone($user_id){
    $phone = get_user_meta( $user_id, 'phone', true );
    $mobile = get_user_meta( $user_id, 'mobile', true );
    $output = ($phone)?$phone:$mobile;
    return $output;
}
function mos_user_address($user_id){
    $address_line_1 = get_user_meta( $user_id, 'address_line_1', true );
    $address_line_2 = get_user_meta( $user_id, 'address_line_2', true ); 
    $output = $address_line_1;
    if ($address_line_2) $output .= ' ' . $address_line_2; 
    return $output;  
}
function calculate_delivery_charge($user_id, $weight) {
    $delivery_charge = get_user_meta( $user_id, 'delivery_charge', true );
    $additional_charge = get_user_meta( $user_id, 'additional_charge', true );                    
    if ($weight > 1){
        $additional_weight = $weight - 1;
        $output = $delivery_charge + $additional_weight * $additional_charge;
    } else {
       $output = $delivery_charge; 
    }
    return $output;
}
function mos_get_percentage($x,$y){
    if ($x)
        return number_format($y * 100/$x,2);
    return 0;
}