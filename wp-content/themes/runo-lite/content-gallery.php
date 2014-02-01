<?php
/**
 * The template for displaying posts in the Gallery Post Format on index and archive pages
 *
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header">
		<hgroup>
			<div class="entry-date"><time><?php the_date(); ?></time></div>
			<div class="entry-format"><?php _e( 'Gallery', 'runo' ); ?></div>
			<h1 class="entry-title"><a href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__( 'Permalink to %s', 'runo' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark"><?php the_title(); ?></a></h1>
		</hgroup>

	</header><!-- .entry-header -->

	<?php if ( is_search() ) : // Only display Excerpts for search pages ?>
		<div class="entry-summary">
			<?php the_excerpt(); ?>
		</div><!-- .entry-summary -->
		<?php else : ?>
		<div class="entry-content">
			<?php if ( post_password_required() ) : ?>
				<?php the_content( __( 'Continue reading <span class="meta-nav">&rarr;</span>', 'runo' ) ); ?>

			<?php else : ?>
			
				<?php
				global $post;
				$post_subtitrare = get_post( $post->ID );
				$content = $post_subtitrare->post_content;
				$pattern = get_shortcode_regex();
				preg_match( "/$pattern/s", $content, $match );
				if( isset( $match[2] ) && ( "gallery" == $match[2] ) ) {
					$atts = shortcode_parse_atts( $match[3] );
					$images = isset( $atts['ids'] ) ? explode( ',', $atts['ids'] ) : get_children( 'post_type=attachment&post_mime_type=image&post_parent=' . $post->ID .'&order=ASC&orderby=menu_order ID' );
				}
				if ( $images ) :
					$total_images = count( $images );
					$image = array_shift( $images );
					if (is_object($image)) /* Let's check if we are dealing with old style gallery */
						$image_id = $image->ID;
					else /* WP 3.5 Gallery */
						$image_id = $image;
						$image_img_tag = wp_get_attachment_image( $image_id, 'thumbnail' );		
				?>

				<figure class="gallery-thumb alignleft">
					<a href="<?php the_permalink(); ?>"><?php echo $image_img_tag; ?></a>
				</figure><!-- .gallery-thumb -->

				<p><em><?php printf( _n( 'This gallery contains <a %1$s>%2$s photo</a>.', 'This gallery contains <a %1$s>%2$s photos</a>.', $total_images, 'runo' ),
						'href="' . esc_url( get_permalink() ) . '" title="' . sprintf( esc_attr__( 'Permalink to %s', 'runo' ), the_title_attribute( 'echo=0' ) ) . '" rel="bookmark"',
						number_format_i18n( $total_images )
					); ?></em></p>
			<?php endif; ?>
			<?php the_excerpt(); ?>
		<?php endif; ?>
		<?php wp_link_pages( array( 'before' => '<div class="page-link"><span>' . __( 'Pages:', 'runo' ) . '</span>', 'after' => '</div>' ) ); ?>
	</div><!-- .entry-content -->
	<?php endif; ?>

	<footer class="entry-meta">
		<?php $show_sep = false; ?>
		<?php
			/* translators: used between list items, there is a space after the comma */
			$categories_list = get_the_category_list( __( ', ', 'runo' ) );
			if ( $categories_list ):
		?>
		<span class="cat-links">
			<?php printf( __( '<span class="%1$s">Posted in</span> %2$s', 'runo' ), 'entry-utility-prep entry-utility-prep-cat-links', $categories_list );
			$show_sep = true; ?>
		</span>
		<?php endif; // End if categories ?>
		<?php
			/* translators: used between list items, there is a space after the comma */
			$tags_list = get_the_tag_list( '', __( ', ', 'runo' ) );
			if ( $tags_list ):
			if ( $show_sep ) : ?>
		<span class="sep"> &para; </span>
			<?php endif; // End if $show_sep ?>
		<span class="tag-links">
			<?php printf( __( '<span class="%1$s">Tagged</span> %2$s', 'runo' ), 'entry-utility-prep entry-utility-prep-tag-links', $tags_list );
			$show_sep = true; ?>
		</span>
		<?php endif; // End if $tags_list ?>

		<?php if ( comments_open() ) : ?>
		<?php if ( $show_sep ) : ?>
		<span class="sep"> &para; </span>
		<?php endif; // End if $show_sep ?>
		<span class="comments-link"><?php comments_popup_link( '<span class="leave-reply">' . __( 'Leave a reply', 'runo' ) . '</span>', __( '<b>1</b> Reply', 'runo' ), __( '<b>%</b> Replies', 'runo' ) ); ?></span>
		<?php endif; // End if comments_open() ?>

		<?php edit_post_link( __( 'Edit', 'runo' ), '<span class="sep"> &para; </span><span class="edit-link">', '</span>' ); ?>
	</footer><!-- #entry-meta -->
</article><!-- #post-<?php the_ID(); ?> -->
