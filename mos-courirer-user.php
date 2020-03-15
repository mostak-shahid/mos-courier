<?php
remove_role( 'marchent' );

$operator = add_role(
    'operator',
    __( 'Operator', 'testdomain' ),
    array(
        'read'			=> true,
		'create_posts'	=> true,
        'edit_posts'	=> true
    )
);
$merchant = add_role(
    'merchant',
    __( 'Merchant', 'testdomain' ),
    array(
        'read'          => true,
        'create_posts'  => true,
        'edit_posts'    => true
    )
);
/*Register fields*/
//1. Add a new form element...
/*add_action( 'register_form', 'mos_register_form' );
function mos_register_form() {
    $user = $_GET['user'];
    //user=mom
    if ($user === 'mom') {
        $first_name = ( ! empty( $_POST['first_name'] ) ) ? trim( $_POST['first_name'] ) : '';
            
            ?>
            <input name="wp_capabilities" type="hidden" value="operator">            
        <?php
    }
}

//2. Add validation. In this case, we make sure first_name is required.
//add_filter( 'registration_errors', 'mos_registration_errors', 10, 3 );
function mos_registration_errors( $errors, $sanitized_user_login, $user_email ) {    
    if ( empty( $_POST['first_name'] ) || ! empty( $_POST['first_name'] ) && trim( $_POST['first_name'] ) == '' ) {
        $errors->add( 'first_name_error', __( '<strong>ERROR</strong>: You must include a first name.', 'mydomain' ) );
    }
    return $errors;
}

//3. Finally, save our extra registration user meta.
add_action( 'user_register', 'mos_user_register' );
function mos_user_register( $user_id ) {
    if ( !empty( $_POST['wp_capabilities'] )  AND $_POST['wp_capabilities'] == 'operator') {
        $u = new WP_User( $user_id );
        $u->remove_role( 'subscriber' );
        $u->add_role( 'operator' );
    }
}*/





/*Login redirect*/
function admin_login_redirect( $redirect_to, $request, $user  ) {
    /*if (is_array( $user->roles )) {
        if (in_array( 'operator', $user->roles ) ) {
            return home_url('/dashboard/');
        } elseif (in_array( 'merchant', $user->roles ) ) {
            return home_url('/admin/');
        } else {
            return admin_url();
        }
    } else {
        return home_url('/');
    }*/
    return ( is_array( @$user->roles ) && in_array( 'administrator', @$user->roles ) ) ? admin_url() : home_url('/admin/');
}
add_filter( 'login_redirect', 'admin_login_redirect', 10, 3 );

/*Limit Admin Bar*/
add_action( 'init', 'admin_bar' );
function admin_bar(){
    if ( current_user_can( 'administrator' ) ) {
        show_admin_bar( true );
    } else {
        show_admin_bar( false );
    }
}

/*Limit admin access*/
add_action( 'init', 'blockusers_init' );
function blockusers_init() {
    /*if ( is_admin() && ! current_user_can( 'operator' ) && ! ( defined( 'DOING_AJAX' ) && DOING_AJAX ) ) {
        wp_redirect( home_url('/') );
        exit;
    }*/
    $current_user = wp_get_current_user();
    if ( is_admin() && $current_user->roles[0] != 'administrator'  && ! ( defined( 'DOING_AJAX' ) && DOING_AJAX )) {  
        wp_redirect( home_url('/admin/') );
        exit;
    }
}

/*Additional fields*/
/*function mos_additional_fields( $fields ) {
    $fields['twitter']  = 'Twitter';
    $fields['facebook'] = 'Facebook';
    $fields['youtube'] = 'Youtube';
    $fields['mobile'] = 'Mobile';
    $fields['nid'] = 'National ID';
    return $fields;
}*/
// add_filter( 'user_contactmethods', 'mos_additional_fields');

function mos_additional_profile_fields( $user ) {
    global $religion_arr, $gender_arr, $user_role_arr, $user_activation, $payment_methods;
    $profile_photo = get_the_author_meta( 'profile_photo', $user->ID );
    $brand_logo = get_the_author_meta( 'brand_logo', $user->ID );
    $address_line_1 = get_the_author_meta( 'address_line_1', $user->ID );
    $address_line_2 = get_the_author_meta( 'address_line_2', $user->ID );
    $phone = get_the_author_meta( 'phone', $user->ID );
    $mobile = get_the_author_meta( 'mobile', $user->ID );
    $religion = get_the_author_meta( 'religion', $user->ID );
    $gender = get_the_author_meta( 'gender', $user->ID );
    $user_role = get_the_author_meta( 'user_role', $user->ID );
    $activation = get_the_author_meta( 'activation', $user->ID );
    $payment = get_the_author_meta( 'payment', $user->ID );
    $bank_name = get_the_author_meta( 'bank_name', $user->ID );
    $account_holder = get_the_author_meta( 'account_holder', $user->ID );
    $payacc = get_the_author_meta( 'payacc', $user->ID );
    $delivery_charge = get_the_author_meta( 'delivery_charge', $user->ID );
    $additional_charge = get_the_author_meta( 'additional_charge', $user->ID );
    $brand_name = get_the_author_meta( 'brand_name', $user->ID );
    $national_id = get_the_author_meta( 'national_id', $user->ID );
    $village = get_the_author_meta( 'village', $user->ID );
    $poffice = get_the_author_meta( 'poffice', $user->ID );
    $thana = get_the_author_meta( 'thana', $user->ID );
    $zila = get_the_author_meta( 'zila', $user->ID );

    $nid = get_the_author_meta( 'nid', $user->ID );
    ?>
    <h3>Additional Information</h3>

    <table class="form-table">   
        <tr>
            <th><label for="profile_photo">Profile Photo</label></th>
            <td>
                <input class="regular-text" id="profile_photo" name="profile_photo" type="url" value="<?php echo $profile_photo ?>">
                <button type="button" class="button btn-success btn-half-block upload-image">Upload Image</button>
            </td>
        </tr>
        <tr>
            <th><label for="brand_name">Brand Name</label></th>
            <td>
                <input type="text" name="brand_name" id="brand_name" class="regular-text" value="<?php echo $brand_name ?>" placeholder="Brand Name">
            </td>
        </tr>
        <tr>
            <th><label for="brand_logo">Brand Logo</label></th>
            <td>
                <input class="regular-text" id="brand_logo" name="brand_logo" type="url" value="<?php echo $brand_logo ?>">
                <button type="button" class="button btn-success btn-half-block upload-image">Upload Image</button>
            </td>
        </tr>
        <tr>
            <th><label for="address_line_1">Pickup Address</label></th>
            <td>
                <input type="text" name="address_line_1" id="address_line_1" class="regular-text" value="<?php echo $address_line_1 ?>" placeholder="Address Line 1"><br />
                <input type="text" name="address_line_2" id="address_line_2" class="regular-text" value="<?php echo $address_line_2 ?>" placeholder="Address Line 2">
            </td>
        </tr>
        <tr>
            <th><label for="phone">Contact No</label></th>
            <td>
                <input type="text" name="phone" id="phone" class="regular-text" value="<?php echo $phone ?>" placeholder="Phone"><br />
                <input type="text" name="mobile" id="mobile" class="regular-text" value="<?php echo $mobile ?>" placeholder="Mobile">
            </td>
        </tr>
        <tr>
            <th><label for="religion">Religion</label></th>
            <td>
                <select id="religion" name="religion">
                    <option value="">---Select Religion---</option>
                <?php foreach ($religion_arr as $rel) : ?>
                    <option value="<?php echo $rel ?>" <?php selected( $religion, $rel ); ?>><?php echo $rel ?></option>
                <?php endforeach; ?>
                </select>
            </td>
        </tr>
        <tr>
            <th><label for="gender">Gender</label></th>
            <td>
                <select id="gender" name="gender">
                    <option value="">---Select Gender---</option>
                <?php foreach ($gender_arr as $gen) : ?>
                    <option value="<?php echo $gen ?>" <?php selected( $gender, $gen ); ?>><?php echo $gen ?></option>
                <?php endforeach; ?>
                </select>
            </td>
        </tr>
        <tr>
            <th><label for="user_role">Role</label></th>
            <td>
                <select id="user_role" name="user_role">
                    <option value="">---Select Role---</option>
                <?php foreach ($user_role_arr as $key => $rol) : ?>
                    <optgroup label="<?php echo $key?>">
                    <?php foreach ($rol as $value) : ?> 
                        <option value="<?php echo $value ?>" <?php selected( $user_role, $value ); ?>><?php echo $value ?></option>
                    <?php endforeach; ?>   
                    </optgroup>
                <?php endforeach; ?>
                </select>
            </td>
        </tr>
        <tr>
            <th><label for="activation">Status</label></th>
            <td>
                <select id="activation" name="activation">
                    <option value="">---Select Status---</option>
                <?php foreach($user_activation as $u_activation) : ?>
                    <option value="<?php echo $u_activation ?>" <?php selected( $activation, $u_activation ); ?>><?php echo $u_activation ?></option>
                <?php endforeach; ?>
                </select>
            </td>
        </tr>
        <tr>
            <th><label for="payment">Payment Method</label></th>
            <td>
                <select id="payment" name="payment">
                    <option value="">---Select Method---</option>
                <?php foreach($payment_methods as $methods) : ?>
                    <option value="<?php echo $methods ?>" <?php selected( $payment, $methods ); ?>><?php echo $methods ?></option>
                <?php endforeach; ?>
                </select>
            </td>
        </tr>
        <tr>
            <th><label for="payacc">Account</label></th>
            <td>
                <input type="text" name="payacc" id="payacc" class="regular-text" value="<?php echo $payacc ?>" placeholder="Account">
            </td>
        </tr>
        <tr>
            <th><label for="bank_name">Bank Name</label></th>
            <td>
                <input type="text" name="bank_name" id="bank_name" class="regular-text" value="<?php echo $bank_name ?>" placeholder="Bank Name">
            </td>
        </tr>
        <tr>
            <th><label for="account_holder">Account Holder</label></th>
            <td>
                <input type="text" name="account_holder" id="account_holder" class="regular-text" value="<?php echo $account_holder ?>" placeholder="Account Holder">
            </td>
        </tr>
        <tr>
            <th><label for="delivery_charge">Delivery Charge</label></th>
            <td>
                <input type="text" name="delivery_charge" id="delivery_charge" class="regular-text" value="<?php echo $delivery_charge ?>" placeholder="Delivery Charge">
            </td>
        </tr>
        <tr>
            <th><label for="additional_charge">Additional Charge</label></th>
            <td>
                <input type="text" name="additional_charge" id="additional_charge" class="regular-text" value="<?php echo $additional_charge ?>" placeholder="Additional Charge">
            </td>
        </tr>
        <tr>
            <th><label for="national_id">National ID</label></th>
            <td>
                <input type="text" name="national_id" id="national_id" class="regular-text" value="<?php echo $national_id ?>" placeholder="National ID">
            </td>
        </tr>
        <tr>
            <th><label for="village">Permanent Address</label></th>
            <td>
                <input type="text" name="village" id="village" class="regular-text" value="<?php echo $village ?>" placeholder="Village"><br />
                <input type="text" name="poffice" id="poffice" class="regular-text" value="<?php echo $poffice ?>" placeholder="Post Office"><br />
                <input type="text" name="thana" id="thana" class="regular-text" value="<?php echo $thana ?>" placeholder="Thana"><br />
                <input type="text" name="zila" id="zila" class="regular-text" value="<?php echo $zila ?>" placeholder="Zila">
            </td>
        </tr>
    </table>
    <?php
}

add_action( 'show_user_profile', 'mos_additional_profile_fields' );
add_action( 'edit_user_profile', 'mos_additional_profile_fields' );

function mos_save_profile_fields( $user_id ) {
    if ( ! current_user_can( 'edit_user', $user_id ) ) {
        return false;
    }
    $error = 0;
    if (!empty($_POST["profile_photo"])) {
        $profile_photo = sanitize_text_field($_POST["profile_photo"]);
    }
    if (!empty($_POST["brand_logo"])) {
        $brand_logo = sanitize_text_field($_POST["brand_logo"]);
    }
    if (!empty($_POST["address_line_1"])) {
        $address_line_1 = sanitize_text_field($_POST["address_line_1"]);
        // if (!preg_match("/^[a-zA-Z ]*$/", $address_line_1)) {
        //     $error++;
        // }
    }
    if (!empty($_POST["address_line_2"])) {
        $address_line_2 = sanitize_text_field($_POST["address_line_2"]);
    }
    if (!empty($_POST["phone"])) {
        $phone = sanitize_text_field($_POST["phone"]);
    }
    if (!empty($_POST["mobile"])) {
        $mobile = sanitize_text_field($_POST["mobile"]);
    }
    if (!empty($_POST["religion"])) {
        $religion = sanitize_text_field($_POST["religion"]);
    }
    if (!empty($_POST["gender"])) {
        $gender = sanitize_text_field($_POST["gender"]);
    }
    if (!empty($_POST["user_role"])) {
        $user_role = sanitize_text_field($_POST["user_role"]);
    }
    if (!empty($_POST["activation"])) {
        $activation = sanitize_text_field($_POST["activation"]);
    }
    if (!empty($_POST["payment"])) {
        $payment = sanitize_text_field($_POST["payment"]);
    }
    if (!empty($_POST["bank_name"])) {
        $bank_name = sanitize_text_field($_POST["bank_name"]);
    }
    if (!empty($_POST["account_holder"])) {
        $account_holder = sanitize_text_field($_POST["account_holder"]);
    }
    if (!empty($_POST["payacc"])) {
        $payacc = sanitize_text_field($_POST["payacc"]);
    }
    if (!empty($_POST["delivery_charge"])) {
        $delivery_charge = sanitize_text_field($_POST["delivery_charge"]);
    }
    if (!empty($_POST["additional_charge"])) {
        $additional_charge = sanitize_text_field($_POST["additional_charge"]);
    }
    if (!empty($_POST["brand_name"])) {
        $brand_name = sanitize_text_field($_POST["brand_name"]);
    }
    if (!empty($_POST["national_id"])) {
        $national_id = sanitize_text_field($_POST["national_id"]);
    }
    if (!empty($_POST["village"])) {
        $village = sanitize_text_field($_POST["village"]);
    }
    if (!empty($_POST["poffice"])) {
        $poffice = sanitize_text_field($_POST["poffice"]);
    }
    if (!empty($_POST["thana"])) {
        $thana = sanitize_text_field($_POST["thana"]);
    }
    if (!empty($_POST["zila"])) {
        $zila = sanitize_text_field($_POST["zila"]);
    }
    if (!$error) {
        update_usermeta( $user_id, 'profile_photo', $profile_photo );
        update_usermeta( $user_id, 'brand_logo', $brand_logo );
        update_usermeta( $user_id, 'address_line_1', $address_line_1 );
        update_usermeta( $user_id, 'address_line_2', $address_line_2 );
        update_usermeta( $user_id, 'phone', $phone );
        update_usermeta( $user_id, 'mobile', $mobile );
        update_usermeta( $user_id, 'religion', $religion );
        update_usermeta( $user_id, 'gender', $gender );
        update_usermeta( $user_id, 'user_role', $user_role );
        update_usermeta( $user_id, 'activation', $activation );
        update_usermeta( $user_id, 'payment', $payment );
        update_usermeta( $user_id, 'bank_name', $bank_name );
        update_usermeta( $user_id, 'account_holder', $account_holder );
        update_usermeta( $user_id, 'payacc', $payacc );
        update_usermeta( $user_id, 'delivery_charge', $delivery_charge );
        update_usermeta( $user_id, 'additional_charge', $additional_charge );
        update_usermeta( $user_id, 'brand_name', $brand_name );
        update_usermeta( $user_id, 'national_id', $national_id );
        update_usermeta( $user_id, 'village', $village );
        update_usermeta( $user_id, 'poffice', $poffice );
        update_usermeta( $user_id, 'thana', $thana );
        update_usermeta( $user_id, 'zila', $zila );
    }
}
add_action( 'personal_options_update', 'mos_save_profile_fields' );
add_action( 'edit_user_profile_update', 'mos_save_profile_fields' );