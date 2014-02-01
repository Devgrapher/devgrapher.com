<?php
/**
 * Runo Theme Options
 *
 */

/**
 * Properly enqueue styles and scripts for our theme options page.
 *
 * This function is attached to the admin_enqueue_scripts action hook.
 *
 *
 */
function runo_admin_enqueue_scripts( $hook_suffix ) {
	wp_enqueue_style( 'runo-theme-options', get_template_directory_uri() . '/inc/theme-options.css', false, '2011-04-28' );
}
add_action( 'admin_print_styles-appearance_page_theme_options', 'runo_admin_enqueue_scripts' );

/**
 * Register the form setting for our runo_options array.
 *
 * This function is attached to the admin_init action hook.
 *
 * This call to register_setting() registers a validation callback, runo_theme_options_validate(),
 * which is used when the option is saved, to ensure that our option values are complete, properly
 * formatted, and safe.
 *
 */
function runo_theme_options_init() {

	register_setting(
		'runo_options',       // Options group, see settings_fields() call in runo_theme_options_render_page()
		'runo_theme_options', // Database option, see runo_get_theme_options()
		'runo_theme_options_validate' // The sanitization callback, see runo_theme_options_validate()
	);

	// Register our settings field group
	add_settings_section(
		'general', // Unique identifier for the settings section
		'', // Section title (we don't want one)
		'__return_false', // Section callback (we don't want anything)
		'theme_options' // Menu slug, used to uniquely identify the page; see runo_theme_options_add_page()
	);

	add_settings_field( 'layout',     __( 'Default Layout', 'runo' ), 'runo_settings_field_layout',     'theme_options', 'general' );
}
add_action( 'admin_init', 'runo_theme_options_init' );

/**
 * Change the capability required to save the 'runo_options' options group.
 *
 * @see runo_theme_options_init() First parameter to register_setting() is the name of the options group.
 * @see runo_theme_options_add_page() The edit_theme_options capability is used for viewing the page.
 *
 * By default, the options groups for all registered settings require the manage_options capability.
 * This filter is required to change our theme options page to edit_theme_options instead.
 * By default, only administrators have either of these capabilities, but the desire here is
 * to allow for finer-grained control for roles and users.
 *
 * @param string $capability The capability used for the page, which is manage_options by default.
 * @return string The capability to actually use.
 */
function runo_option_page_capability( $capability ) {
	return 'edit_theme_options';
}
add_filter( 'option_page_capability_runo_options', 'runo_option_page_capability' );

/**
 * Add our theme options page to the admin menu, including some help documentation.
 *
 * This function is attached to the admin_menu action hook.
 *
 */
function runo_theme_options_add_page() {
	$theme_page = add_theme_page(
		__( 'Theme Options', 'runo' ),   // Name of page
		__( 'Theme Options', 'runo' ),   // Label in menu
		'edit_theme_options',                    // Capability required
		'theme_options',                         // Menu slug, used to uniquely identify the page
		'runo_theme_options_render_page' // Function that renders the options page
	);

	if ( ! $theme_page )
		return;

	add_action( "load-$theme_page", 'runo_theme_options_help' );
}
add_action( 'admin_menu', 'runo_theme_options_add_page' );

function runo_theme_options_help() {

	$help = '<p>' . __( 'Some themes provide customization options that are grouped together on a Theme Options screen. If you change themes, options may change or disappear, as they are theme-specific. Your current theme, Runo Lite, provides the following Theme Options:', 'runo' ) . '</p>' .
			'<ol>' .
				'<li>' . __( '<strong>Default Layout</strong>: You can choose if you want your site&#8217;s default layout to have a sidebar on the left, the right, or not at all.', 'runo' ) . '</li>' .
			'</ol>' .
			'<p>' . __( 'Remember to click "Save Changes" to save any changes you have made to the theme options.', 'runo' ) . '</p>';

	$sidebar = '<p><strong>' . __( 'For more information:', 'runo' ) . '</strong></p>' .
		'<p>' . __( '<a href="http://codex.wordpress.org/Appearance_Theme_Options_Screen" target="_blank">Documentation on Theme Options</a>', 'runo' ) . '</p>' .
		'<p>' . __( '<a href="http://wordpress.org/support/" target="_blank">Support Forums</a>', 'runo' ) . '</p>';

	$screen = get_current_screen();

	if ( method_exists( $screen, 'add_help_tab' ) ) {
		// WordPress 3.3
		$screen->add_help_tab( array(
			'title' => __( 'Overview', 'runo' ),
			'id' => 'theme-options-help',
			'content' => $help,
			)
		);

		$screen->set_help_sidebar( $sidebar );
	}
}

/**
 * Returns an array of layout options registered for Runo.
 *

 */
function runo_layouts() {
	$layout_options = array(
		'content' => array(
			'value' => 'content',
			'label' => __( 'One-column, no sidebar', 'runo' ),
			'thumbnail' => get_template_directory_uri() . '/inc/images/content.png',
		),
		'content-sidebar' => array(
			'value' => 'content-sidebar',
			'label' => __( 'Content on left', 'runo' ),
			'thumbnail' => get_template_directory_uri() . '/inc/images/content-sidebar.png',
		),
		'sidebar-content' => array(
			'value' => 'sidebar-content',
			'label' => __( 'Content on right', 'runo' ),
			'thumbnail' => get_template_directory_uri() . '/inc/images/sidebar-content.png',
		),
	);

	return apply_filters( 'runo_layouts', $layout_options );
}

/**
 * Returns the default options for Runo.
 *

 */
function runo_get_default_theme_options() {
	$default_theme_options = array(
		'theme_layout' => 'content',
	);

	if ( is_rtl() )
 		$default_theme_options['theme_layout'] = 'content';

	return apply_filters( 'runo_default_theme_options', $default_theme_options );
}

/**
 * Returns the options array for Runo.
 *

 */
function runo_get_theme_options() {
	return get_option( 'runo_theme_options', runo_get_default_theme_options() );
}


/**
 * Renders the Layout setting field.
 *
 */
function runo_settings_field_layout() {
	$options = runo_get_theme_options();
	foreach ( runo_layouts() as $layout ) {
		?>
		<div class="layout image-radio-option theme-layout">
		<label class="description">
			<input type="radio" name="runo_theme_options[theme_layout]" value="<?php echo esc_attr( $layout['value'] ); ?>" <?php checked( $options['theme_layout'], $layout['value'] ); ?> />
			<span>
				<img src="<?php echo esc_url( $layout['thumbnail'] ); ?>" width="136" height="122" alt="" />
				<?php echo $layout['label']; ?>
			</span>
		</label>
		</div>
		<?php
	}
}

/**
 * Returns the options array for Runo.
 *
 */
function runo_theme_options_render_page() {
	?>
	<div class="wrap">
		<?php screen_icon(); ?>
		<?php $theme_name = function_exists( 'wp_get_theme' ) ? wp_get_theme() : get_current_theme(); ?>
		<h2><?php printf( __( '%s Theme Options', 'runo' ), $theme_name ); ?></h2>
		<?php settings_errors(); ?>

		<form method="post" action="options.php">
			<?php
				settings_fields( 'runo_options' );
				do_settings_sections( 'theme_options' );
				submit_button();
			?>
		</form>
		
		<?php echo __( '<h3>Missing some options?</h3><p>Only in Premium version: modify the whole layout by replacing the background with your own image and color; select fonts and font size. Some examples:</p>', 'runo' ) ?>
		<p><img alt="Premium theme" src="<?php echo get_template_directory_uri(); ?>/inc/images/premium-screenshots.png" /></p>
		<p><strong><a href="http://runo.lala.fi/features/"> &raquo; <?php echo __( 'Take a look', 'runo' ) ?></a></strong></p>
	</div>
	<?php
}

/**
 * Sanitize and validate form input. Accepts an array, return a sanitized array.
 *
 * @see runo_theme_options_init()
 *

 */
function runo_theme_options_validate( $input ) {
	$output = $defaults = runo_get_default_theme_options();

	// Theme layout must be in our array of theme layout options
	if ( isset( $input['theme_layout'] ) && array_key_exists( $input['theme_layout'], runo_layouts() ) )
		$output['theme_layout'] = $input['theme_layout'];

	return apply_filters( 'runo_theme_options_validate', $output, $input, $defaults );
}


/**
 * Adds Runo layout classes to the array of body classes.
 *
 */
function runo_layout_classes( $existing_classes ) {
	$options = runo_get_theme_options();
	$current_layout = $options['theme_layout'];

	if ( in_array( $current_layout, array( 'content-sidebar', 'sidebar-content' ) ) )
		$classes = array( 'two-column' );
	else
		$classes = array( 'one-column' );

	if ( 'content-sidebar' == $current_layout )
		$classes[] = 'right-sidebar';
	elseif ( 'sidebar-content' == $current_layout )
		$classes[] = 'left-sidebar';
	else
		$classes[] = $current_layout;

	$classes = apply_filters( 'runo_layout_classes', $classes, $current_layout );

	return array_merge( $existing_classes, $classes );
}
add_filter( 'body_class', 'runo_layout_classes' );

/**
 * Implements Runo theme options into Theme Customizer
 *
 * @param $wp_customize Theme Customizer object
 * @return void
 *
 */
function runo_customize_register( $wp_customize ) {
	$wp_customize->get_setting( 'blogname' )->transport = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport = 'postMessage';

	$options  = runo_get_theme_options();
	$defaults = runo_get_default_theme_options();

	// Default Layout
	$wp_customize->add_section( 'runo_layout', array(
		'title'    => __( 'Layout', 'runo' ),
		'priority' => 80,
	) );

	$wp_customize->add_setting( 'runo_theme_options[theme_layout]', array(
		'type'              => 'option',
		'default'           => $defaults['theme_layout'],
		'sanitize_callback' => 'sanitize_key',
	) );

	$layouts = runo_layouts();
	$choices = array();
	foreach ( $layouts as $layout ) {
		$choices[$layout['value']] = $layout['label'];
	}

	$wp_customize->add_control( 'runo_theme_options[theme_layout]', array(
		'section'    => 'runo_layout',
		'type'       => 'radio',
		'choices'    => $choices,
	) );

}
add_action( 'customize_register', 'runo_customize_register' );

/**
 * Bind JS handlers to make Theme Customizer preview reload changes asynchronously.
 * Used with blogname and blogdescription.
 *
 */
function runo_customize_preview_js() {
	wp_enqueue_script( 'runo-customizer', get_template_directory_uri() . '/inc/theme-customizer.js', array( 'customize-preview' ), '20120906', true );
}
add_action( 'customize_preview_init', 'runo_customize_preview_js' );