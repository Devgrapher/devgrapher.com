<?php
/**
 * @package Hexa
 */

/**
 * Setup the WordPress core custom header feature.
 *
 * @uses hexa_header_style()
 * @uses hexa_admin_header_style()
 * @uses hexa_admin_header_image()
 *
 * @package Hexa
 */
function hexa_custom_header_setup() {
	add_theme_support( 'custom-header', apply_filters( 'hexa_custom_header_args', array(
		'default-image'          => '',
		'default-text-color'     => 'd25349',
		'width'                  => 2000,
		'height'                 => 200,
		'flex-height'            => true,
		'flex-width'             => true,
		'wp-head-callback'       => 'hexa_header_style',
		'admin-head-callback'    => 'hexa_admin_header_style',
		'admin-preview-callback' => 'hexa_admin_header_image',
	) ) );
}
add_action( 'after_setup_theme', 'hexa_custom_header_setup' );

if ( ! function_exists( 'hexa_header_style' ) ) :
/**
 * Styles the header image and text displayed on the blog
 *
 * @see hexa_custom_header_setup().
 */
function hexa_header_style() {
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
		.site-title a {
			color: #<?php echo $header_text_color; ?>;
		}
	<?php endif; ?>
	</style>
	<?php
}
endif; // hexa_header_style

if ( ! function_exists( 'hexa_admin_header_style' ) ) :
/**
 * Styles the header image displayed on the Appearance > Header admin panel.
 *
 * @see hexa_custom_header_setup().
 */
function hexa_admin_header_style() {
?>
	<style type="text/css">
		.appearance_page_custom-header #headimg {
			background: #e9e7e3;
			border: 0
		}
		#headimg h1,
		#desc {
		}
		#headimg h1 {
			font-family: "Source Sans Pro", Helvetica, Arial, sans-serif;
			font-size: 26px;
			line-height: 1;
			margin: 0;
		}
		#headimg h1 a {
			text-decoration: none;
		}
		#desc {
			font-family: "Source Sans Pro", Helvetica, Arial, sans-serif;
			color: #bcb6aa;
			font-size: 18px;
			font-weight: bold;
			margin: 7.2px 0;
			text-transform: uppercase;
		}
		#headimg img {
			display: block;
			margin: 0 auto;
			max-width: 100%;
		}
		.site-branding {
			border-left: 8px solid #d25349;
			box-sizing: border-box;
			max-width: 448px;
			padding: 57.6px 0 28.8px 57.6px;
		}
	</style>
<?php
}
endif; // hexa_admin_header_style

if ( ! function_exists( 'hexa_admin_header_image' ) ) :
/**
 * Custom header image markup displayed on the Appearance > Header admin panel.
 *
 * @see hexa_custom_header_setup().
 */
function hexa_admin_header_image() {
	$style = sprintf( ' style="color:#%s;"', get_header_textcolor() );
?>
	<div id="headimg">
		<?php if ( get_header_image() ) : ?>
			<img src="<?php header_image(); ?>" alt="">
		<?php endif; ?>
		<div class="site-branding">
			<h1 class="displaying-header-text"><a id="name"<?php echo $style; ?> onclick="return false;" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php bloginfo( 'name' ); ?></a></h1>
			<div class="displaying-header-text" id="desc"><?php bloginfo( 'description' ); ?></div>
		</div>
	</div>
<?php
}
endif; // hexa_admin_header_image
