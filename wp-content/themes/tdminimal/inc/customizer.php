<?php
/**
 * tdMinimal Theme Customizer
 *
 * @package tdMinimal
 */
 function tdminimal_customize_register( $wp_customize ) {
 	/* Header Section */
	$wp_customize->add_section( 'tdminimal_header_section', array(
    	'title'          => __( 'Header Settings', 'tdminimal' ),
    	'priority'       => 1000,
	) );
	
	/* background color */
	$wp_customize->add_setting( 'tdminimal_header_background_color', array(
    	'default'        => '#ecf0f1'
	) );
	
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'tdminimal_header_background_color', array(
		'label' => __( 'Background Color', 'tdminimal' ),
		'section' => 'tdminimal_header_section',
		'settings' => 'tdminimal_header_background_color',
		'priority' => 1
    ) ) );
    
    /* Primary text color */
	$wp_customize->add_setting( 'tdminimal_header_primary_text_color', array(
    	'default'        => '#2d2f30'
	) );
	
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'tdminimal_header_primary_text_color', array(
		'label' => __( 'Primary Text Color', 'tdminimal' ),
		'section' => 'tdminimal_header_section',
		'settings' => 'tdminimal_header_primary_text_color',
		'priority' => 1
    ) ) );
    
    /* Secondary text color */
	$wp_customize->add_setting( 'tdminimal_header_secondary_text_color', array(
    	'default'        => '#bdc3c7'
	) );
	
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'tdminimal_header_secondary_text_color', array(
		'label' => __( 'Secondary Text Color', 'tdminimal' ),
		'section' => 'tdminimal_header_section',
		'settings' => 'tdminimal_header_secondary_text_color',
		'priority' => 1
    ) ) );
	
 	/* Background Section */
	$wp_customize->add_section( 'tdminimal_background_section', array(
    	'title'          => __( 'Background Settings', 'tdminimal' ),
    	'priority'       => 1001,
	) );
	
	/* background color */
	$wp_customize->add_setting( 'tdminimal_background_color', array(
    	'default'        => '#f7f9f9'
	) );
	
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'tdminimal_background_color', array(
		'label' => __( 'Background Color', 'tdminimal' ),
		'section' => 'tdminimal_background_section',
		'settings' => 'tdminimal_background_color',
		'priority' => 1
    ) ) );
    
    /* background pattern */
    $wp_customize->add_setting( 'tdminimal_background_pattern' );
	
	$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'tdminimal_background_pattern', array(
		'label' => __( 'Background Pattern', 'tdminimal' ),
		'section' => 'tdminimal_background_section',
		'settings' => 'tdminimal_background_pattern'
	) ) );
	
	/* background fixed image */
    $wp_customize->add_setting( 'tdminimal_bg_fixed_image' );
	
	$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'tdminimal_bg_fixed_image', array(
		'label' => __( 'Background Fixed Image', 'tdminimal' ),
		'section' => 'tdminimal_background_section',
		'settings' => 'tdminimal_bg_fixed_image'
	) ) );
	
	/* Footer Section */
	$wp_customize->add_section( 'tdminimal_footer_section', array(
    	'title'          => __( 'Footer Settings', 'tdminimal' ),
    	'priority'       => 1002,
	) );
	
	/* primary background color */
	$wp_customize->add_setting( 'tdminimal_primary_background_color', array(
    	'default'        => '#e8eced'
	) );
	
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'tdminimal_primary_background_color', array(
		'label' => __( 'Primary Background Color', 'tdminimal' ),
		'section' => 'tdminimal_footer_section',
		'settings' => 'tdminimal_primary_background_color',
		'priority' => 1
    ) ) );
    
    /* secondary background color */
	$wp_customize->add_setting( 'tdminimal_secondary_background_color', array(
    	'default'        => '#ecf0f1'
	) );
	
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'tdminimal_secondary_background_color', array(
		'label' => __( 'Secondary Background Color', 'tdminimal' ),
		'section' => 'tdminimal_footer_section',
		'settings' => 'tdminimal_secondary_background_color',
		'priority' => 2
    ) ) );
    
    /* primary text color */
	$wp_customize->add_setting( 'tdminimal_primary_text_color', array(
    	'default'        => '#464242'
	) );
	
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'tdminimal_primary_text_color', array(
		'label' => __( 'Primary Text Color', 'tdminimal' ),
		'section' => 'tdminimal_footer_section',
		'settings' => 'tdminimal_primary_text_color',
		'priority' => 3
    ) ) );
    
    /* secondary text color */
	$wp_customize->add_setting( 'tdminimal_secondary_text_color', array(
    	'default'        => '#bdc3c7'
	) );
	
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'tdminimal_secondary_text_color', array(
		'label' => __( 'Secondary Text Color', 'tdminimal' ),
		'section' => 'tdminimal_footer_section',
		'settings' => 'tdminimal_secondary_text_color',
		'priority' => 4
    ) ) );
 }
 add_action( 'customize_register', 'tdminimal_customize_register');