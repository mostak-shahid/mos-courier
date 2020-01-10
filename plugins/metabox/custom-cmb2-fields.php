<?php
add_action( 'cmb2_render_text_number', 'sm_cmb_render_text_number', 10, 5 );
function sm_cmb_render_text_number( $field, $escaped_value, $object_id, $object_type, $field_type_object ) {
    echo $field_type_object->input( array( 'class' => 'cmb2-text-small', 'type' => 'number' ) );
}

// sanitize the field
add_filter( 'cmb2_sanitize_text_number', 'sm_cmb2_sanitize_text_number', 10, 2 );
function sm_cmb2_sanitize_text_number( $null, $new ) {
    $new = preg_replace( "/[^0-9]/", "", $new );

    return $new;
}

add_action( 'cmb2_render_image_select', 'cmb2_render_image_select', 10, 5 );
function cmb2_render_image_select( $field, $escaped_value, $object_id, $object_type, $field_type_object ) {		
    $conditional_value =(isset($field->args['attributes']['data-conditional-value'])?'data-conditional-value="' .esc_attr($field->args['attributes']['data-conditional-value']).'"':'');
    $conditional_id =(isset($field->args['attributes']['data-conditional-id'])?' data-conditional-id="'.esc_attr($field->args['attributes']['data-conditional-id']).'"':'');
    $default_value = $field->args['attributes']['default'];    
	$image_select = '<ul id="cmb2-image-select'.$field->args['_id'].'" class="cmb2-image-select-list">';
	foreach ( $field->options() as $value => $item ) {
		$selected = ($value === ($escaped_value==''?$default_value:$escaped_value )) ? 'checked="checked"' : '';		
		$image_select .= '<li class="cmb2-image-select '.($selected!= ''?'cmb2-image-select-selected':'').'"><label for="' . $field->args['_id'] . esc_attr( $value ) . '">
			<input '.$conditional_value.$conditional_id.' type="radio" id="'. $field->args['_id'] . esc_attr( $value ) . '" name="' . $field->args['_name'] . '" value="' . esc_attr( $value ) . '" ' . $selected . ' class="cmb2-option"><img class="" style=" width: auto; " alt="' . $item['alt'] . '" src="' . $item['img'] . '">
			<br><span>' . esc_html( $item['title'] ) . '</span></label></li>';
	}
	$image_select .= '</ul>';
	$image_select .= $field_type_object->_desc( true );
	echo $image_select;
}
function load_custom_cmb2_script() { 
	wp_enqueue_style( 'cmb2_imgselect-css', get_template_directory_uri() . '/inc/metabox/custom-cmb2-fields.css' );
	wp_enqueue_script( 'cmb2_imgselect-js', get_template_directory_uri() . '/inc/metabox/custom-cmb2-fields.js' );
} 
add_action( 'admin_enqueue_scripts', 'load_custom_cmb2_script', 20 );
/*
    $field_id->add_field( array( 
        'name' => __('Image Select', 'cmb2'), 
        'desc' => __('page layout using image select', 'cmb2'), 
        'id' => $prefix . 'page_custom_layout', 
        'type' => 'image_select', 
        'options' => array( 
            'disabled' => array(
                'title' => 'Full Width', 
                'alt' => 'Full Width', 
                'img' => get_template_directory_uri() . '/inc/theme-options/ReduxCore/assets/img/1col.png'
            ), 
            'sidebar-left' => array(
                'title' => 'Sidebar Left', 
                'alt' => 'Sidebar Left', 
                'img' => get_template_directory_uri() . '/inc/theme-options/ReduxCore/assets/img/2cl.png'
            ), 
            'sidebar-right' => array(
                'title' => 'Sidebar Right', 
                'alt' => 'Sidebar Right', 
                'img' => get_template_directory_uri() . '/inc/theme-options/ReduxCore/assets/img/2cr.png'
            ), 
        ), 
        'default' => 'sidebar-right', 
    ));
*/