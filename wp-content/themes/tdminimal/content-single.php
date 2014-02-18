<?php
/**
 * @package tdMinimal
 */
?>

	<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
		<header class="entry-header">
		<?php if(  has_post_format( 'video' ) ): ?>
	
			<?php if ( get_post_meta(get_the_ID(), 'td-video', true) ) : ?>
			<div class="featured-video">
				<?php echo get_post_meta(get_the_ID(), 'td-video', true) ?>
			</div>
			<?php endif; ?>
			
		<?php elseif( has_post_format( 'gallery' ) ): ?>
		
			<?php if( tdminimal_post_slideshow() ): ?>
			<div class="post-slideshow">
			<?php echo tdminimal_post_slideshow(); ?>
			</div>
			<?php endif; ?>
		
		<?php elseif( has_post_format( 'audio' ) ): ?>
			
			<?php if ( has_post_thumbnail() ): ?>
			<div class="post-thumb">
				<?php the_post_thumbnail(); ?>
			</div><!-- .post-thumb -->
			<?php endif; ?>
		
			<?php if( tdminimal_post_audio_player() ): ?>
				<div class="featured-audio">
					<?php echo tdminimal_post_audio_player(); ?>
				</div>
			<?php endif; ?>
			
		<?php else: ?>
		
			<?php if ( has_post_thumbnail() ): ?>
			<div class="post-thumb">
				<?php the_post_thumbnail(); ?>
			</div>
			<?php endif; ?>
		
		<?php endif; ?>
			<h2 class="entry-title"><?php the_title(); ?></h2>

			<div class="entry-meta">
				<?php tdminimal_posted_on(); ?>
				<?php edit_post_link( __( 'Edit', 'tdminimal' ), ' / <span class="edit-link">', '</span>' ); ?>
				<div class="entry-categories">
					<?php echo tdminimal_get_categories(); ?>
				</div><!-- .entry-categories -->
			</div><!-- .entry-meta -->
		</header><!-- .entry-header -->
		
		<div class="hentry-inner">
			<div class="entry-content">
				<?php the_content(); ?>
				<?php
					wp_link_pages( array(
						'before'      => '<div class="page-links"><span class="page-links-title">' . __( 'Pages:', 'tdminimal') . '</span>',
						'after'       => '</div>',
						'link_before' => '<span>',
						'link_after'  => '</span>'
					) );
				?>
			</div><!-- .entry-content -->

			<footer class="entry-meta bottom">
				<?php
					/* translators: used between list items, there is a space after the comma */
					$category_list = get_the_category_list( __( ', ', 'tdminimal' ) );

					/* translators: used between list items, there is a space after the comma */
					$tag_list = get_the_tag_list( '', __( ', ', 'tdminimal' ) );

					if ( ! tdminimal_categorized_blog() ) {
						// This blog only has 1 category so we just need to worry about tags in the meta text
						if ( '' != $tag_list ) {
							$meta_text = __( '<span class="entry-tags">This entry was tagged %2$s by %3$s.<span class="entry-tags">', 'tdminimal' );
						} else {
							$meta_text = __( 'This entry was posted by %3$s.', 'tdminimal' );
						}

					} else {
						// But this blog has loads of categories so we should probably display them here
						if ( '' != $tag_list ) {
							$meta_text = __( 'This entry was posted in %1$s <span class="entry-tags">and tagged %2$s</span> by %3$s', 'tdminimal' );
						} else {
							$meta_text = __( 'This entry was posted in %1$s by %3$s.', 'tdminimal' );
						}

					} // end check for categories on this blog

					printf(
						$meta_text,
						$category_list,
						$tag_list,
						tdminimal_get_post_author()
					);
				?>
			</footer><!-- .entry-meta -->
			
			<?php if( tdminimal_is_bottom_share_buttons() ): ?>
			<div class="share-buttons-bottom">
				<?php tdminimal_get_share_buttons(); ?>
			</div>
			<?php endif; ?>
			
			<?php if( tdminimal_is_newsletter_section() ): ?>
			<div id="newsletter-container" class="single-post">
				<div class="newsletter-inner">
					<?php echo tdminimal_newsletter_image(); ?>
					<div id="newsletter">
						<?php 
							$tdminimal_newsletter = tdminimal_newsletter_form();
							echo $tdminimal_newsletter['newsletter-code']; 
						?>
					</div><!-- #newsletter -->
				</div><!-- .newsletter-inner -->
			</div><!-- #newsletter-container -->
			<?php endif; ?>
			
			<?php tdminimal_author_section(); ?>
			
			<?php if( tdminimal_related_posts() ): ?>
				<?php echo tdminimal_related_posts(); ?>
			<?php endif; ?>
		</div><!-- .hentry-inner -->
	</article><!-- #post-## -->
