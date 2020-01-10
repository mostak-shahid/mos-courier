<?php
add_action( 'init', 'codex_order_init' );
function codex_order_init() {
	$labels = array(
		'name'               => _x( 'Orders', 'post type general name', 'your-plugin-textdomain' ),
		'singular_name'      => _x( 'Order', 'post type singular name', 'your-plugin-textdomain' ),
		'menu_name'          => _x( 'Orders', 'admin menu', 'your-plugin-textdomain' ),
		'name_admin_bar'     => _x( 'Order', 'add new on admin bar', 'your-plugin-textdomain' ),
		'add_new'            => _x( 'Add New', 'order', 'your-plugin-textdomain' ),
		'add_new_item'       => __( 'Add New Order', 'your-plugin-textdomain' ),
		'new_item'           => __( 'New Order', 'your-plugin-textdomain' ),
		'edit_item'          => __( 'Edit Order', 'your-plugin-textdomain' ),
		'view_item'          => __( 'View Order', 'your-plugin-textdomain' ),
		'all_items'          => __( 'All Orders', 'your-plugin-textdomain' ),
		'search_items'       => __( 'Search Orders', 'your-plugin-textdomain' ),
		'parent_item_colon'  => __( 'Parent Orders:', 'your-plugin-textdomain' ),
		'not_found'          => __( 'No orders found.', 'your-plugin-textdomain' ),
		'not_found_in_trash' => __( 'No orders found in Trash.', 'your-plugin-textdomain' )
	);
	$args = array(
		'labels'             => $labels,
		'description'        => __( 'Description.', 'your-plugin-textdomain' ),
		'public'             => true,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'query_var'          => true,
		'rewrite'            => array( 'slug' => 'courierorder' ),
		'capability_type'    => 'post',
		'has_archive'        => true,
		'hierarchical'       => false,
		'menu_position'      => 9,
		'menu_icon' => 'dashicons-groups',
		'supports'           => array( 'title', 'editor', 'excerpt', 'author', 'thumbnail', 'comments', 'revisions', 'custom-fields' ),
	);

	register_post_type( 'courierorder', $args );
}