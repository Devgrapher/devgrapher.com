<?php
/**
 * Runo functions and definitions
 *
 * 
 */

/**
 * Set the content width based on the theme's design and stylesheet.
 */
if ( ! isset( $content_width ) )
	$content_width = 640;

/**
 * Tell WordPress to run runo_setup() when the 'after_setup_theme' hook is run.
 */
add_action( 'after_setup_theme', 'runo_setup' );

if ( ! function_exists( 'runo_setup' ) ):
/**
 * Sets up theme defaults and registers support for various WordPress features.
 */
function runo_setup() {

	/* Make Runo available for translation.
	 * Translations can be added to the /languages/ directory.
	 */
	load_theme_textdomain( 'runo', get_template_directory() . '/languages' );

	// This theme styles the visual editor with editor-style.css to match the theme style.
	add_editor_style('runo-editor-style.css');

	// Load up our theme options page and related code.
	require( get_template_directory() . '/inc/theme-options.php' );

	// Add default posts and comments RSS feed links to <head>.
	add_theme_support( 'automatic-feed-links' );

	// This theme uses wp_nav_menu() in one location.
	register_nav_menu( 'primary', __( 'Primary Menu', 'runo' ) );

	// Add support for a variety of post formats
	add_theme_support( 'post-formats', array( 'gallery','quote', 'image' ) );
	
	// Add support for custom background
	$args = array(
        'default-color' => 'f1f1f1',
    );

    $args = apply_filters( 'runo_custom_background_args', $args );

    if ( function_exists( 'wp_get_theme' ) ) { // Compat: Versions of WordPress prior to 3.4.
        add_theme_support( 'custom-background', $args );
    } else {
        define( 'BACKGROUND_COLOR', $args['default-color'] );
        add_custom_background();
    }

}
endif; // runo_setup


/**
 * Hook for Runo styles and fonts
 */
function runo_theme_styles() { 
	$runo_language = get_bloginfo( 'language' ); 
	if ($runo_language == 'ru-RU') {
		wp_register_style( 'runo-EBGaramond-font', 'http://fonts.googleapis.com/css?family=EB+Garamond&subset=latin,cyrillic-ext');
		wp_register_style( 'runo-cyrillic-font', get_template_directory_uri() . '/fonts/cyrillic.css');
		wp_enqueue_style( 'runo-EBGaramond-font' );
		wp_enqueue_style( 'runo-cyrillic-font' );
	}
	else {
		wp_register_style( 'runo-vollkorn-font', 'http://fonts.googleapis.com/css?family=Vollkorn:400,400italic');
		wp_enqueue_style( 'runo-vollkorn-font' );
	}
}
add_action('wp_enqueue_scripts', 'runo_theme_styles');


/**
 * Hook for Runo scripts
 */

function runo_scripts() {
	wp_enqueue_script(
		'runo-script',
		get_template_directory_uri() . '/js/runo.js',
		array('jquery')
	);
	wp_enqueue_script( 
		'small-menu', 
		get_template_directory_uri() . '/js/small-menu.js',
		array( 'jquery' ),
		'20120206', 
		true 
	);
}
add_action('wp_enqueue_scripts', 'runo_scripts');


/**
 * Implement the Custom Header feature
 */
require( get_template_directory() . '/inc/custom-header.php' );


/**
 * Comments threading
 */


if ( is_singular() && get_option( 'thread_comments' ) )
		wp_enqueue_script( 'comment-reply' );


/** 
 * Enable more buttons in TinyMCE editor 
 */

function runo_enable_more_buttons($buttons) {
  	$buttons[] = 'fontselect';
	$buttons[] = 'fontsizeselect'; 
	$buttons[] = 'backcolor'; 
	$buttons[] = 'anchor'; 
	$buttons[] = 'hr';
	$buttons[] = 'wp_page';
	$buttons[] = 'cleanup'; 
  return $buttons;
}
add_filter("mce_buttons_3", "runo_enable_more_buttons");

/**
 * Sets the post excerpt length to 40 words.
 *
 */
function runo_excerpt_length( $length ) {
	return 40;
}
add_filter( 'excerpt_length', 'runo_excerpt_length' );

/**
 * Returns a "Continue Reading" link for excerpts
 */
function runo_continue_reading_link() {
	return ' <a href="'. esc_url( get_permalink() ) . '">' . __( 'Continue reading <span class="meta-nav">&rarr;</span>', 'runo' ) . '</a>';
}

/**
 * Replaces "[...]" (appended to automatically generated excerpts) with an ellipsis and runo_continue_reading_link().
 *
 */
function runo_auto_excerpt_more( $more ) {
	return ' &hellip;' . runo_continue_reading_link();
}
add_filter( 'excerpt_more', 'runo_auto_excerpt_more' );

/**
 * Adds a pretty "Continue Reading" link to custom post excerpts.
 *
 * To override this link in a child theme, remove the filter and add your own
 * function tied to the get_the_excerpt filter hook.
 */
function runo_custom_excerpt_more( $output ) {
	if ( has_excerpt() && ! is_attachment() ) {
		$output .= runo_continue_reading_link();
	}
	return $output;
}
add_filter( 'get_the_excerpt', 'runo_custom_excerpt_more' );

/**
 * Get our wp_nav_menu() fallback, wp_page_menu(), to show a home link.
 */
function runo_page_menu_args( $args ) {
	$args['show_home'] = true;
	return $args;
}
add_filter( 'wp_page_menu_args', 'runo_page_menu_args' );


/**
 * Register our sidebars and widgetized areas.
 *
 */
function runo_widgets_init() {

	register_sidebar( array(
		'name' => __( 'Main Sidebar', 'runo' ),
		'id' => 'sidebar-1',
		'before_widget' => '<div class="sidebarbox" id="%1$s">',
		'after_widget' => "</div>",
		'before_title' => '<h3>',
		'after_title' => '</h3>',
	) );

	register_sidebar( array(
		'name' => __( 'Footer Area One', 'runo' ),
		'id' => 'sidebar-3',
		'description' => __( 'An optional widget area for your site footer', 'runo' ),
		'before_widget' => '<div class="widget">',
		'after_widget' => '</div>',
		'before_title' => '<h3>',
		'after_title' => '</h3>',
	) );

	register_sidebar( array(
		'name' => __( 'Footer Area Two', 'runo' ),
		'id' => 'sidebar-4',
		'description' => __( 'An optional widget area for your site footer', 'runo' ),
		'before_widget' => '<div class="widget">',
		'after_widget' => '</div>',
		'before_title' => '<h3>',
		'after_title' => '</h3>',
	) );

	register_sidebar( array(
		'name' => __( 'Footer Area Three', 'runo' ),
		'id' => 'sidebar-5',
		'description' => __( 'An optional widget area for your site footer', 'runo' ),
		'before_widget' => '<div class="widget">',
		'after_widget' => '</div>',
		'before_title' => '<h3>',
		'after_title' => '</h3>',
	) );
}
add_action( 'widgets_init', 'runo_widgets_init' );

if ( ! function_exists( 'runo_content_nav' ) ) :
/**
 * Display navigation to next/previous pages when applicable
 */
function runo_content_nav( $nav_id ) {
	global $wp_query;

	if ( $wp_query->max_num_pages > 1 ) : ?>
		<nav id="<?php echo $nav_id; ?>">
			<h3 class="assistive-text"><?php _e( 'Post navigation', 'runo' ); ?></h3>
			<div class="nav-previous"><?php next_posts_link( __( '<span class="meta-nav">&larr;</span> Older posts', 'runo' ) ); ?></div>
			<div class="nav-next"><?php previous_posts_link( __( 'Newer posts <span class="meta-nav">&rarr;</span>', 'runo' ) ); ?></div>
		</nav><!-- #nav-above -->
	<?php endif;
}
endif; // runo_content_nav

/**
 * Return the URL for the first link found in the post content.
 *
 */
function runo_url_grabber() {
	if ( ! preg_match( '/<a\s[^>]*?href=[\'"](.+?)[\'"]/is', get_the_content(), $matches ) )
		return false;

	return esc_url_raw( $matches[1] );
}

/**
 * Count the number of footer sidebars to enable dynamic classes for the footer
 */
function runo_footer_sidebar_class() {
	$count = 0;

	if ( is_active_sidebar( 'sidebar-3' ) )
		$count++;

	if ( is_active_sidebar( 'sidebar-4' ) )
		$count++;

	if ( is_active_sidebar( 'sidebar-5' ) )
		$count++;

	$class = '';

	switch ( $count ) {
		case '1':
			$class = 'one';
			break;
		case '2':
			$class = 'two';
			break;
		case '3':
			$class = 'three';
			break;
	}

	if ( $class )
		echo 'class="' . $class . '"';
}

if ( ! function_exists( 'runo_comment' ) ) :
/**
 * Template for comments and pingbacks.
 *
 */
function runo_comment( $comment, $args, $depth ) {
	$GLOBALS['comment'] = $comment;
	switch ( $comment->comment_type ) :
		case 'pingback' :
		case 'trackback' :
	?>
	<li class="post pingback">
		<p><?php _e( 'Pingback:', 'runo' ); ?> <?php comment_author_link(); ?> <?php edit_comment_link( __( 'Edit', 'runo' ), '<span class="edit-link">', '</span>' ); ?></p>
	<?php
			break;
		default :
	?>
	<li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
		<article id="comment-<?php comment_ID(); ?>" class="comment">
			<footer class="comment-meta">
				<div class="comment-author vcard">
					<?php
						$avatar_size = 68;
						if ( '0' != $comment->comment_parent )
							$avatar_size = 39;

						echo get_avatar( $comment, $avatar_size );

						/* translators: 1: comment author, 2: date and time */
						printf( __( '%1$s on %2$s <span class="says">said:</span>', 'runo' ),
							sprintf( '<span class="fn">%s</span>', get_comment_author_link() ),
							sprintf( '<a href="%1$s"><time pubdate datetime="%2$s">%3$s</time></a>',
								esc_url( get_comment_link( $comment->comment_ID ) ),
								get_comment_time( 'c' ),
								/* translators: 1: date, 2: time */
								sprintf( __( '%1$s at %2$s', 'runo' ), get_comment_date(), get_comment_time() )
							)
						);
					?>

					<?php edit_comment_link( __( 'Edit', 'runo' ), '<span class="edit-link">', '</span>' ); ?>
				</div><!-- .comment-author .vcard -->

				<?php if ( $comment->comment_approved == '0' ) : ?>
					<em class="comment-awaiting-moderation"><?php _e( 'Your comment is awaiting moderation.', 'runo' ); ?></em>
					<br />
				<?php endif; ?>

			</footer>

			<div class="comment-content"><?php comment_text(); ?></div>

			<div class="reply">
				<?php comment_reply_link( array_merge( $args, array( 'reply_text' => __( 'Reply <span>&darr;</span>', 'runo' ), 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
			</div><!-- .reply -->
		</article><!-- #comment-## -->

	<?php
			break;
	endswitch;
}
endif; // ends check for runo_comment() 

/** 
 * Adds two classes to the array of body classes.
 * The first is if the site has only had one author with published posts.
 * The second is if a singular post being displayed
 *
 */
function runo_body_classes( $classes ) {

	if ( function_exists( 'is_multi_author' ) && ! is_multi_author() )
		$classes[] = 'single-author';

	if ( is_singular() && ! is_home() )
		$classes[] = 'singular';

	return $classes;
}
add_filter( 'body_class', 'runo_body_classes' ); 

/**
 * Adds footnotes 
 */

class runo_footnotes {
        var $footnotes = array();
        function runo_footnotes() {
                add_shortcode( 'ref', array( &$this, 'shortcode' ) );
   				add_filter( 'wp_link_pages_args', array( &$this, 'wp_link_pages_args' ) );
                add_filter( 'the_content', array( &$this, 'the_content' ), 12 );     
        }

        function shortcode( $atts, $content = null ) {
                global $id;
                if ( null === $content )
                        return;
                if ( ! isset( $this->footnotes[$id] ) )
                        $this->footnotes[$id] = array( 0 => false );
                $this->footnotes[$id][] = $content;
                $note = count( $this->footnotes[$id] ) - 1;
                return ' <a class="footnote" title="' . esc_attr( wp_strip_all_tags( $content ) ) . '" id="return-note-' . $id . '-' . $note . '" href="#note-' . $id . '-' . $note . '"><sup>' . $note . '</sup></a>';
        }
        function the_content( $content ) {
                if ( ! $GLOBALS['multipage'] )
                        return $this->footnotes( $content );
                return $content;
        }
        function wp_link_pages_args( $args ) {
                // if wp_link_pages appears both before and after the content,
                // $this->footnotes[$id] will be empty the first time through,
                // so it works, simple as that.
                $args['after'] = $this->footnotes( $args['after'] );
                return $args;
        }
        function footnotes( $content ) {
                global $id;
                if ( empty( $this->footnotes[$id] ) )
                        return $content;
                $content .= '<div class="footnotes"><h2 class="notes">'. __( 'Notes:', 'runo' ) .'</h2><ol>';
                foreach ( array_filter( $this->footnotes[$id] ) as $num => $note )
                        $content .= '<li id="note-' . $id . '-' . $num . '">' . do_shortcode( $note ) . ' <a href="#return-note-' . $id . '-' . $num . '">&#8617;</a></li>';
                $content .= '</ol></div>';
                return $content;
        }
}

new runo_footnotes();

/**
** Add more-class to posts which contain more shortcode
*/

function runo_add_more_to_post_class( $classes ) {
    global $post;
    if ( ( is_archive() || is_home() ) && false !== strpos( $post->post_content, '<!--more-->' ) && ! in_array( 'more', $classes ) ) {
        $classes[] = 'more';
    }
    return $classes;
}
add_filter( 'post_class', 'runo_add_more_to_post_class' );


/**
 * Where the post has no post title, but must still display a link to the single-page post view.
 */
add_filter('the_title', 'runo_title');

function runo_title($title) {
    if ($title == '') {
        return _e( 'Untitled', 'runo' ) ;
    } else {
        return $title;
    }
}

/**
** Filter function for wp_title
*/
function runo_filter_wp_title( $old_title, $sep, $sep_location ){
  
	// determine sep_location
	if( $sep_location != 'right' ) $sep_location = 'left';
	  
	// get the page number we're on (index)
	if( get_query_var( 'paged' ) )
		$page_num = get_query_var( 'paged' );
	  
	// get the page number we're on (multipage post)
	elseif( get_query_var( 'page' ) )
		$page_num = get_query_var( 'page' );
	  
	// else
	else $page_num = NULL;
	 
	// if sep_location is right
	if( $sep_location == 'right' ) {
		if( !empty( $page_num) ) $page_num = $page_num . ' ' . $sep . ' ';
		return $page_num . $old_title . get_bloginfo( 'name' );
	}
	 
	// if sep_location is left
	else {
		if( !empty( $page_num) ) $page_num = ' ' . $sep . ' ' . $page_num;
		return get_bloginfo( 'name' ) . $old_title . $page_num;
	}
}

// call our custom wp_title filter, with normal (10) priority, and 3 args
add_filter( 'wp_title', 'runo_filter_wp_title', 10, 3 );