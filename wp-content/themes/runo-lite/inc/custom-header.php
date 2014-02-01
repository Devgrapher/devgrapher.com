<?php /**
 * Setup the WordPress core custom header feature.
 *
 */

function runo_custom_header_setup() {
	$args = array(
		'default-image'          => '',
		'default-text-color'     => '333333',
		'width'                  => 980,
		'height'                 => 250,
		'flex-width'             => true,
		'flex-height'            => true,
		'wp-head-callback'       => 'runo_header_style',
		'admin-head-callback'    => 'runo_admin_header_style',
		'admin-preview-callback' => 'runo_admin_header_image',
	);

	$args = apply_filters( 'runo_custom_header_args', $args );

	if ( function_exists( 'wp_get_theme' ) ) {
		add_theme_support( 'custom-header', $args );
	} else {
		// Compat: Versions of WordPress prior to 3.4.
		define( 'HEADER_TEXTCOLOR',    $args['default-text-color'] );
		define( 'HEADER_IMAGE',        $args['default-image'] );
		define( 'HEADER_IMAGE_WIDTH',  $args['width'] );
		define( 'HEADER_IMAGE_HEIGHT', $args['height'] );
		add_custom_image_header( $args['wp-head-callback'], $args['admin-head-callback'], $args['admin-preview-callback'] );
	}
}
add_action( 'after_setup_theme', 'runo_custom_header_setup' );

/**
 * Shiv for get_custom_header().
 *
 * get_custom_header() was introduced to WordPress
 * in version 3.4. To provide backward compatibility
 * with previous versions, we will define our own version
 * of this function.
 *
 * @todo Remove this function when WordPress 3.6 is released.
 * @return stdClass All properties represent attributes of the curent header image.
 *
 */

if ( ! function_exists( 'get_custom_header' ) ) {
	function get_custom_header() {
		return (object) array(
			'url'           => get_header_image(),
			'thumbnail_url' => get_header_image(),
			'width'         => HEADER_IMAGE_WIDTH,
			'height'        => HEADER_IMAGE_HEIGHT,
		);
	}
}

if ( ! function_exists( 'runo_header_style' ) ) :
/**
 * Styles the header image and text displayed on the blog
 *
 * @see runo_custom_header_setup().
 *
 */
function runo_header_style() {

	// If no custom options for text are set, let's bail
	// get_header_textcolor() options: HEADER_TEXTCOLOR is default, hide text (returns 'blank') or any hex value
	if ( HEADER_TEXTCOLOR == get_header_textcolor() )
		return;
	// If we get this far, we have custom styles. Let's do this.
	?>
	<style type="text/css">
	<?php
		// Has the text been hidden?
		if ( 'blank' == get_header_textcolor() ) :
	?>
		#site-title,
		#site-description {
			position: absolute !important;
			clip: rect(1px 1px 1px 1px); /* IE6, IE7 */
			clip: rect(1px, 1px, 1px, 1px);
		}
	<?php
		// If the user has set a custom color for the text use that
		else :
	?>
		#site-title a,
		#site-description,
		#site-title:before {
			color: #<?php echo get_header_textcolor(); ?> !important;
		}
	<?php endif; ?>
	</style>
	<?php
}
endif; // runo_header_style

if ( ! function_exists( 'runo_admin_header_style' ) ) :
/**
 * Styles the header image displayed on the Appearance > Header admin panel.
 *
 * @see runo_custom_header_setup().
 *
 */
function runo_admin_header_style() {
?>
	<style type="text/css">
	
	.appearance_page_custom-header #headimg {
		border: none;
		text-align: center;
	}
	#headimg h1 {
		font-family: 'Vollkorn', 'Times New Roman', serif;
		font-size: 40px;
		font-weight: 400;
		line-height: 40px;
	}
	#headimg h1 a:before {
		content: "\00B6  ";
	}
	#headimg h1 a {
		line-height: 1.2em;
		text-decoration: none;
	}
	#desc {
		font-family: 'Vollkorn', 'Times New Roman', serif;
		font-size: 18px;
	}
	
	<?php $runo_language = get_bloginfo( 'language' ); 
	if ($runo_language == 'ru-RU') { ?>
	
	#headimg h1, #desc {
		font-family: 'EB Garamond', 'Times New Roman', serif;
	}
	<?php } ?>
	
	</style>
<?php
}
endif; // runo_admin_header_style

if ( ! function_exists( 'runo_admin_header_image' ) ) :
/**
 * Custom header image markup displayed on the Appearance > Header admin panel.
 *
 * @see runo_custom_header_setup().
 *
 */
function runo_admin_header_image() { ?>
	<div id="headimg">
		<?php
		if ( 'blank' == get_header_textcolor() || '' == get_header_textcolor() )
			$style = ' style="display:none;"';
		else
			$style = ' style="color:#' . get_header_textcolor() . ';"';
		?>
		<?php $header_image = get_header_image();
		if ( ! empty( $header_image ) ) : ?>
			<img src="<?php echo esc_url( $header_image ); ?>" alt="" />
		<?php endif; ?>
		<h1><a id="name"<?php echo $style; ?> onclick="return false;" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php bloginfo( 'name' ); ?></a></h1>
		<div id="desc"<?php echo $style; ?>><?php bloginfo( 'description' ); ?></div>
	</div>
<?php }
endif; // runo_admin_header_image

/**
 * Enqueue custom header fonts for admin panel
 */
 
function runo_custom_header_fonts() {
	$runo_language = get_bloginfo( 'language' ); 
	if ($runo_language == 'ru-RU') {
		wp_register_style( 'runo-EBGaramond-font', 'http://fonts.googleapis.com/css?family=EB+Garamond&subset=latin,cyrillic-ext');
		wp_enqueue_style( 'runo-EBGaramond-font' );
	}
	else {
		wp_register_style( 'runo-vollkorn-font', 'http://fonts.googleapis.com/css?family=Vollkorn:400,400italic');
		wp_enqueue_style( 'runo-vollkorn-font' );
	}

}
add_action( 'admin_enqueue_scripts', 'runo_custom_header_fonts' );
