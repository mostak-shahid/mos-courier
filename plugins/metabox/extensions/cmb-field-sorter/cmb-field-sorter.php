<?php
// Useful global constants
define( 'base_URL', get_template_directory_uri() . '/inc/metabox/extensions/cmb-field-sorter/' );
define( 'TB_SORTER_VERSION', '2.0.4' );

/**
 * Enqueue scripts and styles, call requested select box field
 */
function tb_sorter_enqueue() {	
	wp_enqueue_script( 'tb-sorter-field-init', get_template_directory_uri() . '/inc/metabox/extensions/cmb-field-sorter/js/sorter-init.js', array('jquery-ui-sortable'), TB_SORTER_VERSION );	
	wp_enqueue_style( 'tb-sorter-field-mods', get_template_directory_uri() . '/inc/metabox/extensions/cmb-field-sorter/css/sorter.css', array(), TB_SORTER_VERSION );
}


/**
 * Render sorter input field
 */
function tb_sorter_render( $field, $value, $object_id, $object_type, $field_type_object ) {
	tb_sorter_enqueue();	

	if($value == ""){
		$sortlists = $field->options();
	}else{
		$sortlists = $value;
	}
		
	if ( $sortlists ) {
		echo '<fieldset class="tb-field-container">';
		echo '<fieldset id="' . $field_type_object->_id() . '" class="tb-sorter-container tb-sorter">';

		foreach ($sortlists as $group => $sortlist) {
			echo '<ul id="' . $field_type_object->_id() . '_' . $group . '" class="sortlist_' . $field_type_object->_id() . '" data-id="' . $field_type_object->_id() . '" data-group-id="' . $group . '">';
			echo '<h3>' . $group . '</h3>';

	        foreach ( $sortlist as $key => $list ) {	            	            
	            echo '<input class="sorter-placebo" type="hidden" name="' . $field_type_object->_id() . '[' . $group . '][placebo]" value="placebo">';

	            if ( $key != "placebo" ) {
	                echo '<li id="sortee-' . $key . '" class="sortee" data-id="'. $key .'">';
	                echo '<input class="position" type="hidden" name="' . $field_type_object->_id() . '[' . $group . '][' . $key . ']' .'" value="' . $list . '">';
	                echo $list;
	                echo '</li>';	       
	            }     
	        }	

			echo '</ul>';
		}

		echo '</fieldset>';		
		echo '</fieldset>';		

		echo '<p class="cmb2-metabox-description">'.$field->args( 'desc' ).'</p>';
	}

}
add_filter( 'cmb2_render_tb_sorter', 'tb_sorter_render', 10, 5 );


function tb_sorter_escape( $check, $meta_value ) {	
	if ($meta_value) {		
		foreach ( $meta_value as $groups => $sortlist ) {
			$meta_value[ $groups ] = array_map( 'esc_attr', $sortlist );
		}
	}
	return $meta_value;	

}
add_filter( 'cmb2_types_esc_tb_sorter', 'tb_sorter_escape', 10, 2 );

function tb_sorter_sanitize( $check, $meta_value) {	
	
	foreach ( $meta_value as $groups => $sortlist ) {

		$meta_value[ $groups ] = array_map( 'sanitize_text_field', $sortlist );
	}
	return $meta_value;
}
add_filter( 'cmb2_sanitize_tb_sorter', 'tb_sorter_sanitize', 10, 2 );
