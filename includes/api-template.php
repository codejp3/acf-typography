<?php

/**
 *  get_typography_field()
 * 
 *  @since		3.0.0
 */
function get_typography_field( $selector, $property, $post_id = false, $format_value = true ) {
	
	// filter post_id
	$post_id = acf_get_valid_post_id( $post_id );
	
	// get field
        $field = acf_maybe_get_field( $selector, $post_id );
	
	// create dummy field
	if( !$field ) {	
		$field = acf_get_valid_field(array(
			'name'	=> $selector,
			'key'	=> '',
			'type'	=> '',
		));
		
		// prevent formatting
		$format_value = false;
	}
	
	// get value for field
        $value = acf_get_value( $post_id, $field );
	
	// format value
	if( $format_value ) {	
		// get value for field
		$value = acf_format_value( $value, $post_id, $field );
    }
    
    // get property
    if( is_array($value) && array_key_exists( $property, $value ) )
        $property_value = esc_attr($value[ $property ]);
    else
        $property_value = '';
    
    return $property_value;
 
}


/**
 *  the_typography_field()
 * 
 *  @since		3.0.0
 */
function the_typography_field( $selector, $property, $post_id = false, $format_value = true ) {
	
	$value = get_typography_field($selector, $property, $post_id, $format_value);
	
	if( is_array($value) ) {
		$value = @implode( ', ', $value );
	}
	
	echo $value;
	
}


/**
 *  get_typography_sub_field()
 * 
 *  @since		3.0.0
 */
function get_typography_sub_field( $selector, $property, $format_value = true, $load_value = true ) {

	// get sub field
	$sub_field = get_sub_field_object( $selector, $format_value );
	
	
	// bail early if no sub field
	if( !$sub_field ) return false;
	
	if( is_array($sub_field['value']) && array_key_exists( $property, $sub_field['value'] ) )
		$property_value = esc_attr($sub_field['value'][$property]);
        else
		$property_value = '';

	// return 
	return $property_value;

}


/**
 *  the_typography_sub_field()
 * 
 *  @since		3.0.0
 */
function the_typography_sub_field( $field_name, $property, $format_value = true ) {
	
	$value = get_typography_sub_field( $field_name, $property, $format_value );
	
	if( is_array($value) ) {
		
		$value = implode(', ',$value);
		
	}
	
	echo $value;
	
}


/**
*  acf_typography_shortcode()
*
*  [acf_typography field="heading" property="font_size" post_id="123" format_value="1"]
*
*  @since		3.0.0
*/
function acf_typography_shortcode( $atts ) {
	
	// extract attributes
	extract( shortcode_atts( array(
		'field'			=> '',
		'property'		=> '',
		'post_id'		=> false,
		'format_value'	=> true
	), $atts ) );
	
	// get value and return it
	$value = get_typography_field( $field, $property, $post_id, $format_value );
	
	// array
	if( is_array($value) ) {
		
		$value = @implode( ', ', $value );
		
	}
	
	// return
	return $value;
	
}

add_shortcode('acf_typography', 'acf_typography_shortcode');


/**
*  acf_typography_stylesheet_shortcode()
*
*  [acf_typography_stylesheet link_type="link" post_id="123"]
*
*  @param	$link_type (str) (optional) (default: 'link')
*               The type of stylesheet linking method
*  @param	$post_id (str) (optional) (default: current_post_id)
*               A specific $post_id to retrieve the stylesheet(s) for 
*
*  @return	$stylesheet (str) 
*               Returns standard link for link_type = 'link'
*               // <link rel="stylesheet" ... />
*               Returns style for link_type = 'style'
*               // <style> ... </style>
*
*  @since       3.3.0
*/

function acf_typography_stylesheet_shortcode( $atts ) {
    
    require_once plugin_dir_path( __FILE__ ) . 'functions.php';
    
    $post_id = acft_get_valid_post_id( (isset($atts['post_id']) ? intval($atts['post_id']) : false ) );

    // extract attributes
    extract( shortcode_atts( array(
            'link_type'     => 'link',
            'post_id'       => $post_id,
    ), $atts ) );

    // get value and return it
    $value = acft_the_typography_stylesheet( $link_type, $post_id );
    
    // return
    return $value;   

}

add_shortcode('acf_typography_stylesheet', 'acf_typography_stylesheet_shortcode');
