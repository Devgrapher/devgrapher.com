<?php
/**
 * tdsimple Theme Customizer
 *
 * @package tdsimple
 * @since tdsimple 1.0
 */

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 *
 * @since tdsimple 1.0
 */
function tdsimple_customize_register( $wp_customize ) {
	/**
	 *	Section for Header
	 */
	 $wp_customize->add_section( 'tdsimple_settings_section', array(
    	'title'          => __( 'Website Settings', 'tdsimple' ),
    	'priority'       => 1001,
	) );

	$wp_customize->add_setting( 'tdsimple_settings_logo_title', array(
    	'default'        => '',
	) );
	
	$wp_customize->add_control( 'tdsimple_settings_logo_title', array(
        'type' => 'checkbox',
        'label' => 'Use Logo as a Website Title',
        'section' => 'tdsimple_settings_section',
    ));
    
    $wp_customize->add_setting( 'tdsimple_settings_logo' );
	
	$wp_customize->add_control(
		new WP_Customize_Image_Control(
			$wp_customize,
			'tdsimple_settings_logo',
			array(
				'label' => 'Custom Logo Upload',
				'section' => 'tdsimple_settings_section',
				'settings' => 'tdsimple_settings_logo'
			)
		)
	);

	$wp_customize->add_setting( 'tdsimple_settings_content_width', array(
		'default' => 'large',
	));
 
	$wp_customize->add_control( 'tdsimple_settings_content_width', array(
		'type' => 'select',
		'label' => 'Content Width',
		'section' => 'tdsimple_settings_section',
		'choices' => array(
			'large' => 'Large',
			'medium' => 'Medium',
			'small' => 'Small',
		)
	));
}
add_action( 'customize_register', 'tdsimple_customize_register');

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 *
 * @since tdsimple 1.0
 */
function tdsimple_customize_preview_js() {
	wp_register_script('tdsimple_customizer', get_template_directory_uri() . '/js/customizer.js', array('jquery'), '1.0', true );  
    wp_enqueue_script('tdsimple_customizer'); 
}
add_action( 'customize_controls_enqueue_scripts', 'tdsimple_customize_preview_js' );

?>