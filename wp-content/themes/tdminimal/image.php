<?php
/**
 * The template for displaying image attachments.
 *
 * @package tdMinimal
 */

get_header();
?>

<div class="container">
	<div id="primary" class="content-area image-attachment">
		<div id="main" class="site-main" role="main">

		<?php while ( have_posts() ) : the_post(); ?>
			
			<div class="hentry-container">
				<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
					<header class="entry-header">
						<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>

						<div class="entry-meta">
							<?php
								$metadata = wp_get_attachment_metadata();
								printf( __( 'Published <span class="entry-date"><time class="entry-date" datetime="%1$s">%2$s</time></span> at <a href="%3$s" title="Link to full-size image">%4$s &times; %5$s</a> in <a href="%6$s" title="Return to %7$s" rel="gallery">%8$s</a>', 'tdminimal' ),
									esc_attr( get_the_date( 'c' ) ),
									esc_html( get_the_date() ),
									esc_url( wp_get_attachment_url() ),
									$metadata['width'],
									$metadata['height'],
									esc_url( get_permalink( $post->post_parent ) ),
									esc_attr( strip_tags( get_the_title( $post->post_parent ) ) ),
									get_the_title( $post->post_parent )
								);

								edit_post_link( __( 'Edit', 'tdminimal' ), ' / <span class="edit-link">', '</span>' );
							?>
						</div><!-- .entry-meta -->

						<nav role="navigation" id="image-navigation" class="image-navigation">
							<div class="nav-previous"><?php previous_image_link( false, __( '<span class="meta-nav">&larr;</span> Previous', 'tdminimal' ) ); ?></div>
							<div class="nav-next"><?php next_image_link( false, __( 'Next <span class="meta-nav">&rarr;</span>', 'tdminimal' ) ); ?></div>
						</nav><!-- #image-navigation -->
					</header><!-- .entry-header -->
				
					<div class="hentry-inner">
						<div class="entry-content">
							<div class="entry-attachment">
								<div class="attachment">
									<?php tdminimal_the_attached_image(); ?>
								</div><!-- .attachment -->

								<?php if ( has_excerpt() ) : ?>
								<div class="entry-caption">
									<?php the_excerpt(); ?>
								</div><!-- .entry-caption -->
								<?php endif; ?>
							</div><!-- .entry-attachment -->

							<?php
								the_content();
								wp_link_pages( array(
									'before' => '<div class="page-links">' . __( 'Pages:', 'tdminimal' ),
									'after'  => '</div>',
								) );
							?>
						</div><!-- .entry-content -->
					</div>
				</article><!-- #post-## -->
			
				<?php
					// If comments are open or we have at least one comment, load up the comment template
					if ( comments_open() || '0' != get_comments_number() )
						comments_template();
				?>
			</div>

		<?php endwhile; // end of the loop. ?>

		</div><!-- #main -->
	</div><!-- #primary -->
</div>
<?php get_footer(); ?>