<?php
/**
 * harmonic Theme Customizer
 *
 * @package harmonic
 */

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function harmonic_customize_register( $wp_customize ) {
	$wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
	$wp_customize->get_setting( 'header_textcolor' )->transport = 'postMessage';
	$wp_customize->get_setting( 'background_image' )->transport = 'postMessage';

	$wp_customize->add_panel( 'harmonic_panel', array(
    	'priority'       => 130,
    	'capability'     => 'edit_theme_options',
    	'theme_supports' => '',
    	'title'          => __( 'Theme', 'harmonic' ),
    	'description'    => __( 'Harmonic Theme Options', 'harmonic' ),
	) );

	$wp_customize->add_section( 'harmonic_theme_options', array(
		'title'    => __( 'Portfolio', 'harmonic' ),
		'priority' => 1,
		'panel'  => 'harmonic_panel',
	) );

	$wp_customize->add_setting( 'harmonic_portfolio_thumbnail', array(
		'default'           => 'landscape',
		'sanitize_callback' => 'harmonic_sanitize_ratio',
	) );

	$wp_customize->add_control( 'harmonic_portfolio_thumbnail', array(
		'label'   => __( 'Portfolio Thumbnail Aspect Ratio', 'harmonic' ),
		'section' => 'harmonic_theme_options',
		'type'    => 'select',
		'priority'          => 1,
		'choices' => array(
			'landscape' => __( 'Landscape (4:3)', 'harmonic' ),
			'portrait'  => __( 'Portrait (3:4)', 'harmonic' ),
			'square'    => __( 'Square (1:1)', 'harmonic' ),
		),
	) );

	$wp_customize->add_section( 'harmonic_visibility_options', array(
		'title'    => __( 'Visibility', 'harmonic' ),
		'priority' => 2,
		'panel'  => 'harmonic_panel',
	) );

	$wp_customize->add_setting( 'harmonic_front_title', array(
		'default'           => '',
		'sanitize_callback' => 'harmonic_sanitize_checkbox',
	) );

	$wp_customize->add_control( 'harmonic_front_title', array(
		'label'             => __( 'Hide Title Section', 'harmonic' ),
		'section'           => 'harmonic_visibility_options',
		'priority'          => 2,
		'type'              => 'checkbox',
	) );
	$wp_customize->add_setting( 'harmonic_front_news', array(
		'default'           => '',
		'sanitize_callback' => 'harmonic_sanitize_checkbox',
	) );
	$wp_customize->add_control( 'harmonic_front_news', array(
		'label'             => __( 'Hide News Section', 'harmonic' ),
		'section'           => 'harmonic_visibility_options',
		'priority'          => 3,
		'type'              => 'checkbox',
	) );

	$wp_customize->add_setting( 'harmonic_front_page', array(
		'default'           => '',
		'sanitize_callback' => 'harmonic_sanitize_checkbox',
	) );

	$wp_customize->add_control( 'harmonic_front_page', array(
		'label'             => __( 'Hide Page Section', 'harmonic' ),
		'section'           => 'harmonic_visibility_options',
		'priority'          => 4,
		'type'              => 'checkbox',
	) );

	$wp_customize->add_setting( 'harmonic_front_widgets', array(
		'default'           => '',
		'sanitize_callback' => 'harmonic_sanitize_checkbox',
	) );

	$wp_customize->add_control( 'harmonic_front_widgets', array(
		'label'             => __( 'Hide Widgets Section', 'harmonic' ),
		'section'           => 'harmonic_visibility_options',
		'priority'          => 5,
		'type'              => 'checkbox',
	) );

	$wp_customize->add_setting( 'harmonic_front_portfolio', array(
		'default'           => '',
		'sanitize_callback' => 'harmonic_sanitize_checkbox',
	) );

	$wp_customize->add_control( 'harmonic_front_portfolio', array(
		'label'             => __( 'Hide Front Portfolio Section', 'harmonic' ),
		'section'           => 'harmonic_visibility_options',
		'priority'          => 6,
		'type'              => 'checkbox',
	) );

	$section_one = get_theme_mod( 'harmonic_front_title' );
	if ( 1 != $section_one ) :

		$wp_customize->add_section( 'harmonic_title_options', array(
			'title'    => __( 'Title', 'harmonic' ),
			'priority' => 3,
			'panel'  => 'harmonic_panel',
		) );

		$wp_customize->add_setting('harmonic_front_titleimage', array(
			'transport'	=> 'refresh',
			'sanitize_callback' => 'esc_url_raw',
			)
		);
		$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize,
			'harmonic_front_titleimage',
			array(
				'label'			=> __( 'Background', 'harmonic' ),
				'section'		=> 'harmonic_title_options',
				'priority'		=> 1,
			)
		) );

		$wp_customize->add_setting( 'harmonic_front_titlelayer', array(
			'default'           => '',
			'sanitize_callback' => 'harmonic_sanitize_checkbox',
		) );
		$wp_customize->add_control( 'harmonic_front_titlelayer', array(
			'label'             => __( 'Add background shade', 'harmonic' ),
			'section'           => 'harmonic_title_options',
			'priority'          => 2,
			'type'              => 'checkbox',
		) );

		$wp_customize->add_setting( 'harmonic_hide_description', array(
			'default'           => '',
			'sanitize_callback' => 'harmonic_sanitize_checkbox',
		) );

		$wp_customize->add_control( 'harmonic_hide_description', array(
			'label'             => __( 'Hide Tagline', 'harmonic' ),
			'section'           => 'harmonic_title_options',
			'priority'          => 3,
			'type'              => 'checkbox',
		) );
	endif;

	$section_two = get_theme_mod( 'harmonic_front_news' );
	if ( 1 != $section_two ) :

		$wp_customize->add_section( 'harmonic_news_options', array(
			'title'    => __( 'News', 'harmonic' ),
			'priority' => 4,
			'panel'  => 'harmonic_panel',
		) );

		$wp_customize->add_setting('harmonic_front_newsimage', array(
			'transport'	=> 'refresh',
			'sanitize_callback' => 'esc_url_raw',
			)
		);
		$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize,
			'harmonic_front_newsimage',
			array(
				'label'			=> __( 'Background', 'harmonic' ),
				'section'		=> 'harmonic_news_options',
				'priority'		=> 1,
			)
		) );

		$wp_customize->add_setting( 'harmonic_front_newslayer', array(
			'default'           => '',
			'sanitize_callback' => 'harmonic_sanitize_checkbox',
		) );
		$wp_customize->add_control( 'harmonic_front_newslayer', array(
			'label'             => __( 'Add background shade', 'harmonic' ),
			'section'           => 'harmonic_news_options',
			'priority'          => 2,
			'type'              => 'checkbox',
		) );

		$wp_customize->add_setting( 'harmonic_front_newstitle', array(
			'default'           => 'News',
			'sanitize_callback' => 'harmonic_sanitize_text',
		) );
		$wp_customize->add_control( 'harmonic_front_newstitle', array(
			'label'             => __( 'Footer Link Text', 'harmonic' ),
			'section'           => 'harmonic_news_options',
			'priority'          => 3,
			'type'              => 'text',
		) );

	endif;

	$section_three = get_theme_mod( 'harmonic_front_page' );
	if ( 1 != $section_three ) :

		$wp_customize->add_section( 'harmonic_page_options', array(
			'title'    => __( 'Page', 'harmonic' ),
			'priority' => 5,
			'panel'  => 'harmonic_panel',
		) );

		$wp_customize->add_setting('harmonic_front_pageimage', array(
			'transport'	=> 'refresh',
			'sanitize_callback' => 'esc_url_raw',
			)
		);
		$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize,
			'harmonic_front_pageimage',
			array(
				'label'			=> __( 'Background', 'harmonic' ),
				'section'		=> 'harmonic_page_options',
				'priority'		=> 1,
			)
		) );

		$wp_customize->add_setting( 'harmonic_front_pagelayer', array(
			'default'           => '',
			'sanitize_callback' => 'harmonic_sanitize_checkbox',
		) );
		$wp_customize->add_control( 'harmonic_front_pagelayer', array(
			'label'             => __( 'Add background shade', 'harmonic' ),
			'section'           => 'harmonic_page_options',
			'priority'          => 2,
			'type'              => 'checkbox',
		) );

		$wp_customize->add_setting( 'harmonic_front_pagetitle', array(
			'default'           => 'Page',
			'sanitize_callback' => 'harmonic_sanitize_text',
		) );
		$wp_customize->add_control( 'harmonic_front_pagetitle', array(
			'label'             => __( 'Footer Link Text', 'harmonic' ),
			'section'           => 'harmonic_page_options',
			'priority'          => 3,
			'type'              => 'text',
		) );

	endif;

	$section_four = get_theme_mod( 'harmonic_front_widget' );
	if ( 1 != $section_four ) :

		$wp_customize->add_section( 'harmonic_widget_options', array(
			'title'    => __( 'Widgets', 'harmonic' ),
			'priority' => 6,
			'panel'  => 'harmonic_panel',
		) );

		$wp_customize->add_setting('harmonic_front_widgetimage', array(
			'transport'	=> 'refresh',
			'sanitize_callback' => 'esc_url_raw',
			)
		);
		$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize,
			'harmonic_front_widgetimage',
			array(
				'label'			=> __( 'Background', 'harmonic' ),
				'section'		=> 'harmonic_widget_options',
				'priority'		=> 1,
			)
		) );

		$wp_customize->add_setting( 'harmonic_front_widgetlayer', array(
			'default'           => '',
			'sanitize_callback' => 'harmonic_sanitize_checkbox',
		) );
		$wp_customize->add_control( 'harmonic_front_widgetlayer', array(
			'label'             => __( 'Add background shade', 'harmonic' ),
			'section'           => 'harmonic_widget_options',
			'priority'          => 2,
			'type'              => 'checkbox',
		) );

		$wp_customize->add_setting( 'harmonic_front_widgettitle', array(
			'default'           => 'Widgets',
			'sanitize_callback' => 'harmonic_sanitize_text',
		) );
		$wp_customize->add_control( 'harmonic_front_widgettitle', array(
			'label'             => __( 'Footer Link Text', 'harmonic' ),
			'section'           => 'harmonic_widget_options',
			'priority'          => 3,
			'type'              => 'text',
		) );
	endif;

	$section_five = get_theme_mod( 'harmonic_front_portfolio' );
	if ( 1 != $section_five ) :

		$wp_customize->add_section( 'harmonic_portfolio_options', array(
			'title'    => __( 'Portfolio front', 'harmonic' ),
			'priority' => 7,
			'panel'  => 'harmonic_panel',
		) );

		$wp_customize->add_setting('harmonic_front_portfolioimage', array(
			'transport'	=> 'refresh',
			'sanitize_callback' => 'esc_url_raw',
			)
		);
		$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize,
			'harmonic_front_portfolioimage',
			array(
				'label'			=> __( 'Background', 'harmonic' ),
				'section'		=> 'harmonic_portfolio_options',
				'priority'		=> 1,
			)
		) );

		$wp_customize->add_setting( 'harmonic_front_portfoliolayer', array(
			'default'           => '',
			'sanitize_callback' => 'harmonic_sanitize_checkbox',
		) );
		$wp_customize->add_control( 'harmonic_front_portfoliolayer', array(
			'label'             => __( 'Add background shade', 'harmonic' ),
			'section'           => 'harmonic_portfolio_options',
			'priority'          => 2,
			'type'              => 'checkbox',
		) );

		$wp_customize->add_setting( 'harmonic_front_portfoliotitle', array(
			'default'           => 'Photos',
			'sanitize_callback' => 'harmonic_sanitize_text',
		) );
		$wp_customize->add_control( 'harmonic_front_portfoliotitle', array(
			'label'             => __( 'Footer Link Text', 'harmonic' ),
			'section'           => 'harmonic_portfolio_options',
			'priority'          => 3,
			'type'              => 'text',
		) );
	endif;

}
add_action( 'customize_register', 'harmonic_customize_register' );

/**
 * Sanitize the checkbox.
 *
 * @param boolean $input.
 * @return boolean (true|false).
 */
function harmonic_sanitize_checkbox( $input ) {
	if ( 1 == $input ) {
		return true;
	} else {
		return false;
	}
}

/**
 * Sanitize the dropdown pages.
 *
 * @param interger $input.
 * @return interger.
 */
function harmonic_sanitize_dropdown_pages( $input ) {
	if ( is_numeric( $input ) ) {
		return intval( $input );
	}
}

/**
 * Sanitize text
 */
function harmonic_sanitize_text( $input ) {
    return wp_kses_post( force_balance_tags( $input ) );
}

/**
 * Sanitize the Portfolio Thumbnail Aspect Ratio value.
 *
 * @param string $ratio Aspect ratio.
 * @return string Filtered ratio (landscape|portrait|square).
 */
function harmonic_sanitize_ratio( $ratio ) {
	if ( ! in_array( $ratio, array( 'landscape', 'portrait', 'square' ) ) ) {
		$ratio = 'landscape';
	}

	return $ratio;
}

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function harmonic_customize_preview_js() {
	wp_enqueue_script( 'harmonic_customizer', get_template_directory_uri() . '/js/customizer.js', array( 'customize-preview' ), '20130508', true );
}
add_action( 'customize_preview_init', 'harmonic_customize_preview_js' );
