<?php
/**
 * Setup the WordPress core custom header feature.
 *
 * @uses syntax_header_style()
 * @uses syntax_admin_header_style()
 * @uses syntax_admin_header_image()
 *
 * @package Syntax
 */
function syntax_custom_header_setup() {
	add_theme_support( 'custom-header', apply_filters( 'syntax_custom_header_args', array(
		'default-image'          => '',
		'default-text-color'     => 'ffffff',
		'width'                  => 2500,
		'height'                 => 250,
		'flex-width'             => true,
		'flex-height'            => true,
		'wp-head-callback'       => 'syntax_header_style',
		'admin-head-callback'    => 'syntax_admin_header_style',
		'admin-preview-callback' => 'syntax_admin_header_image',
	) ) );
}
add_action( 'after_setup_theme', 'syntax_custom_header_setup' );

if ( ! function_exists( 'syntax_header_style' ) ) :
/**
 * Styles the header image and text displayed on the blog
 *
 * @see syntax_custom_header_setup().
 */
function syntax_header_style() {
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
			width: auto;
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
endif; // syntax_header_style

if ( ! function_exists( 'syntax_admin_header_style' ) ) :
/**
 * Styles the header image displayed on the Appearance > Header admin panel.
 *
 * @see syntax_custom_header_setup().
 */
function syntax_admin_header_style() {
?>
	<style type="text/css">
		.appearance_page_custom-header #headimg {
			background: #333;
			border: none;
		}
		#headimg h1 {
			font-family: Merriweather, Georgia, "Times New Roman", serif;
			font-size: 23px;
			font-weight: 300;
			line-height: 29.7px;
			padding: 7.425px 0 0 0;
		}
		.site-branding {
			margin: 14.85px 0 14.85px 29.7px;
			min-height: 37.9875px;
		}
		#headimg h1 a {
			text-decoration: none;
		}
		#headimg img {
			display: block;
			max-width: 100%;
			height: auto;
		}
	</style>
<?php
}
endif; // syntax_admin_header_style

if ( ! function_exists( 'syntax_admin_header_image' ) ) :
/**
 * Custom header image markup displayed on the Appearance > Header admin panel.
 *
 * @see syntax_custom_header_setup().
 */
function syntax_admin_header_image() {
	$style = sprintf( ' style="color:#%s;"', get_header_textcolor() );
?>
	<div id="headimg">
		<?php if ( get_header_image() ) : ?>
		<img src="<?php header_image(); ?>" alt="">
		<?php endif; ?>
		<div class="site-branding">
			<h1 class="displaying-header-text"><a id="name"<?php echo $style; ?> onclick="return false;" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php bloginfo( 'name' ); ?></a></h1>
		</div>
	</div>
<?php
}
endif; // syntax_admin_header_image
