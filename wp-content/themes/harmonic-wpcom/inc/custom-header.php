<?php
/**
 * Sample implementation of the Custom Header feature
 * http://codex.wordpress.org/Custom_Headers
 *
 * You can add an optional custom header image to header.php like so ...

	<?php if ( get_header_image() ) : ?>
	<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
		<img src="<?php header_image(); ?>" width="<?php echo get_custom_header()->width; ?>" height="<?php echo get_custom_header()->height; ?>" alt="">
	</a>
	<?php endif; // End header image check. ?>

 *
 * @package harmonic
 */

/**
 * Setup the WordPress core custom header feature.
 *
 * In the case of harmonic the custom header can be a logo output and is a smaller size.
 *
 * @uses harmonic_headerharmonictyle()
 * @uses harmonic_admin_headerharmonictyle()
 * @uses harmonic_admin_header_image()
 *
 * @package harmonic
 */
function harmonic_custom_header_setup() {
	add_theme_support( 'custom-header', apply_filters( 'harmonic_custom_header_args', array(
		'default-image'          => '',
		'flex-width'   			 => false,
		'width'        			 => 1240,
		'flex-height'   		 => true,
		'height'       	 		 => 400,
		'header-text'            => false,
		'wp-head-callback'       => 'harmonic_header_style',
		'admin-head-callback'    => 'harmonic_admin_header_style',
		'admin-preview-callback' => 'harmonic_admin_header_image',
	) ) );
}
add_action( 'after_setup_theme', 'harmonic_custom_header_setup' );

if ( ! function_exists( 'harmonic_header_style' ) ) :
/**
 * Styles the header image and text displayed on the blog
 *
 * @see harmonic_custom_header_harmonic_setup().
 */
function harmonic_header_style() {
	$header_text_color = get_header_textcolor();

	// If no custom options for text are set, let's bail
	// get_header_textcolor() options: HEADER_TEXTCOLOR is default, hide text (returns 'blank') or any hex value
	if ( HEADER_TEXTCOLOR == $header_text_color ) {
		return;
	}

	// If we get this far, we have custom styles. Let's do this.
	?>
	<style type="text/css">
	<?php
		// Has the text been hidden?
		if ( 'blank' == $header_text_color ) :
	?>
		.site-title,
		.site-description {
			position: absolute;
			clip: rect(1px, 1px, 1px, 1px);
		}
	<?php
		// If the user has set a custom color for the text use that
		else :
	?>
		.site-title a,
		.site-description {
			color: #<?php echo $header_text_color; ?>;
		}
	<?php endif; ?>
	</style>
	<?php
}
endif;

if ( ! function_exists( 'harmonic_admin_header_style' ) ) :
/**
 * Styles the header image displayed on the Appearance > Header admin panel.
 *
 * @see harmonic_custom_header_harmonic_setup().
 */
function harmonic_admin_header_style() {
?>
	<style type="text/css">
		#headimg h1,
		#desc {
			display: none;
		}
	</style>
<?php
}
endif; // harmonic_admin_headerharmonictyle

if ( ! function_exists( 'harmonic_admin_header_image' ) ) :
/**
 * Custom header image markup displayed on the Appearance > Header admin panel.
 *
 * @see harmonic_custom_header_harmonic_setup().
 */
function harmonic_admin_header_image() {
	$style = sprintf( ' style="color:#%s;"', get_header_textcolor() );
?>
	<div id="headimg">
		<div id="headimg-details">
			<h1 class="displaying-header-text"><a id="name"<?php echo $style; ?> onclick="return false;" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php bloginfo( 'name' ); ?></a></h1>
			<h2 id="desc" class="displaying-header-text"<?php echo $style; ?>><?php bloginfo( 'description' ); ?></h2>
		</div>
		<?php if ( get_header_image() ) : ?>
			<img src="<?php header_image(); ?>" alt="">
		<?php endif; ?>
	</div>
<?php
}
endif; // harmonic_admin_header_image
