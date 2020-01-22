<?php
function mos_courier_metaboxes() {
    $options = get_option( 'mos_courier_options' );
    $prefix = '_mos_courier_';
    global $order_status_arr, $payment_status_arr;

    $courier_settings = new_cmb2_box(array(
        'id' => $prefix . 'courier_settings',
        'title' => __('Order Settings', 'cmb2'),
        'object_types' => array('courierorder'),
    ));
    $courier_settings->add_field( array(
        'name' => 'Merchant Name',
        'desc' => 'Select Merchant Name',
        'id'   => $prefix . 'merchant_name',
        'type' => 'select',
        'show_option_none' => true,
        'options'          => mos_user_list('merchant'),
    ));
    $courier_settings->add_field( array(
        'name' => 'Merchant Address',
        'id'   => $prefix . 'merchant_address',
        'type' => 'text',
    ));
    $courier_settings->add_field( array(
        'name' => 'Order ID',
        'id'   => $prefix . 'merchant_order_id',
        'type' => 'text',
    ));
    $courier_settings->add_field( array(
        'name' => 'Merchant Number',
        'id'   => $prefix . 'merchant_number',
        'type' => 'text',
    ));
    $courier_settings->add_field( array(
        'name' => 'Booking Date',
        'id'   => $prefix . 'booking_date',
        'type' => 'text_date',
        // 'timezone_meta_key' => 'wiki_test_timezone',
        'date_format' => 'Y-m-d',
    ) );
    $courier_settings->add_field( array(
        'name' => 'Product Name',
        'id'   => $prefix . 'product_name',
        'type' => 'text',
    ));
    $courier_settings->add_field( array(
        'name' => 'Product Price',
        'id'   => $prefix . 'product_price',
        'type' => 'text',
    ));
    $courier_settings->add_field( array(
        'name' => 'Product Quantity',
        'id'   => $prefix . 'product_quantity',
        'type' => 'text',
    ));
    $courier_settings->add_field( array(
        'name' => 'Receiver Name',
        'id'   => $prefix . 'receiver_name',
        'type' => 'text',
    ));
    $courier_settings->add_field( array(
        'name' => 'Receiver Address',
        'id'   => $prefix . 'receiver_address',
        'type' => 'text',
    ));
    $courier_settings->add_field( array(
        'name' => 'Receiver Number',
        'id'   => $prefix . 'receiver_number',
        'type' => 'text',
    ));
    $courier_settings->add_field( array(
        'name' => 'Total Weight',
        'id'   => $prefix . 'total_weight',
        'type' => 'text',
    ));
    $courier_settings->add_field( array(
        'name' => 'Packaging Type',
        'id'   => $prefix . 'packaging_type',
        'type' => 'select',        
        'show_option_none' => false,
        'options'          => mos_str_to_arr($options['packaging'], '|'),
    ));
    $courier_settings->add_field( array(
        'name' => 'Delivery Charge',
        'id'   => $prefix . 'delivery_charge',
        'type' => 'text',
    ));
    $courier_settings->add_field( array(
        'name' => 'Paid Amount',
        'id'   => $prefix . 'paid_amount',
        'type' => 'text',
    ));
    $courier_settings->add_field( array(
        'name' => 'Payment Date',
        'id'   => $prefix . 'payment_date',
        'type' => 'text_date',
        // 'timezone_meta_key' => 'wiki_test_timezone',
        'date_format' => 'Y-m-d',
    ));
    $courier_settings->add_field( array(
        'name' => 'Payment Method',
        'id'   => $prefix . 'payment_method',
        'type' => 'select',
        'show_option_none' => true,
        'options'          => array(
            'Cash' => 'Cash',
            'Bkash' => 'Bkash',
            'Bank' => 'Bank',
        ),
    ));
    $courier_settings->add_field( array(
        'name' => 'Payment Note',
        'id'   => $prefix . 'payment_note',
        'type' => 'text',
    ));
    $courier_settings->add_field( array(
        'name' => 'Delivery Zone',
        'id'   => $prefix . 'delivery_zone',
        'type' => 'text',

    ));
    $courier_settings->add_field( array(
        'name' => 'Delivery Man',
        'id'   => $prefix . 'delivery_man',
        'type' => 'select',        
        'show_option_none' => false,
        'options'          => mos_user_list('operator', 'user_role', 'Delivery Man'),

    ));
    $courier_settings->add_field( array(
        'name' => 'Delivery Man commission',
        'id'   => $prefix . 'delivery_man_commission',
        'type' => 'text',
    ));
    $courier_settings->add_field( array(
        'name' => 'Delivery Date',
        'id'   => $prefix . 'delivery_date',
        'type' => 'text_date',
        // 'timezone_meta_key' => 'wiki_test_timezone',
        'date_format' => 'Y-m-d',
    ));
    $courier_settings->add_field( array(
        'name' => 'Delivary Status',
        'id'   => $prefix . 'delivery_status',
        'type' => 'select',        
        'show_option_none' => false,
        'options'          => $order_status_arr,

    ));
    $courier_settings->add_field( array(
        'name' => 'Payment Status',
        'id'   => $prefix . 'payment_status',
        'type' => 'select',        
        'show_option_none' => false,
        'options'          => $payment_status_arr,

    ));
    $courier_settings->add_field( array(
        'name' => 'Remarks',
        'id'   => $prefix . 'remarks',
        'type' => 'text',
    ));
    $courier_settings->add_field( array(
        'name' => 'Check in by',
        'id'   => $prefix . 'checkinby',
        'type' => 'select',        
        'show_option_none' => true,
        'options'          => mos_user_list('operator'),

    ));
    $courier_settings->add_field( array(
        'name' => 'Urgent Delivery',
        'id'   => $prefix . 'urgent_delivery',
        'type' => 'select',        
        // 'show_option_none' => true,
        'options'          => array(
        	'no' => 'No',
        	'yes' => 'Yes',
        ),

    ));
    $courier_settings->add_field( array(
        'name' => 'Urgent Charge',
        'id'   => $prefix . 'urgent_charge',
        'type' => 'text',
    ));
}
add_action('cmb2_admin_init', 'mos_courier_metaboxes');