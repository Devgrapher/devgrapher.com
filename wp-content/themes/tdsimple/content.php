<?php
/**
 * @package tdsimple
 * @since tdsimple 1.0
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class('post-index'); ?>>
	<header class="entry-header">
		<h2 class="entry-title"><a href="<?php the_permalink(); ?>" title="<?php echo esc_attr( sprintf( __( 'Permalink to %s', 'tdsimple' ), the_title_attribute( 'echo=0' ) ) ); ?>" rel="bookmark"><?php the_title(); ?></a></h2>

		<?php if ( 'post' == get_post_type() ) : ?>
		<div class="entry-meta top">
			<?php tdsimple_posted_on(); ?>
		</div><!-- .entry-meta -->
		<?php endif; ?>
	</header><!-- .entry-header -->

	<?php if ( is_search() ) : // Only display Excerpts for Search ?>
	<div class="entry-summary">
		<?php the_excerpt(); ?>
	</div><!-- .entry-summary -->
	<?php else : ?>
	<div class="entry-content">
		<div class="post-thumb">
			<?php  if ( has_post_thumbnail() ) { the_post_thumbnail(); } ?>
		</div>
		<?php the_content( __( 'Continue reading <span class="meta-nav">&rarr;</span>', 'tdsimple' ) ); ?>
		<?php wp_link_pages( array( 'before' => '<div class="page-links">' . __( 'Pages:', 'tdsimple' ), 'after' => '</div>' ) ); ?>
	</div><!-- .entry-content -->
	<?php endif; ?>

	<footer class="entry-meta bottom">
		<?php if ( 'post' == get_post_type() ) : // Hide category and tag text for pages on Search ?>
			<?php
				/* translators: used between list items, there is a space after the comma */
				$categories_list = get_the_category_list( __( ', ', 'tdsimple' ) );
				if ( $categories_list && tdsimple_categorized_blog() ) :
			?>
			<span class="cat-links">
				<?php printf( __( 'Posted in %1$s', 'tdsimple' ), $categories_list ); ?>
			</span>
			<?php endif; // End if categories ?>

			<?php
				/* translators: used between list items, there is a space after the comma */
				$tags_list = get_the_tag_list( '', __( ', ', 'tdsimple' ) );
				if ( $tags_list ) :
			?>
			<span class="sep"> / </span>
			<span class="tags-links">
				<?php printf( __( 'Tagged %1$s', 'tdsimple' ), $tags_list ); ?>
			</span>
			<?php endif; // End if $tags_list ?>
			
			<?php 
				//check if post title empty
				$title = get_the_title();
				if( empty($title) ) {
					echo "/ <a href=".get_permalink().">". __( 'Read More', 'tdsimple' )."</a>";
				}
			?> 
			
		<?php endif; // End if 'post' == get_post_type() ?>

		<?php if ( ! post_password_required() && ( comments_open() || '0' != get_comments_number() ) ) : ?>
			
			<?php if( get_post_type() != "page" ): ?>
				<span class="sep"> / </span>
			<?php endif; ?>
			
		<span class="comments-link"><?php comments_popup_link( __( 'Leave a comment', 'tdsimple' ), __( '1 Comment', 'tdsimple' ), __( '% Comments', 'tdsimple' ) ); ?></span>
		<?php endif; ?>

		<?php edit_post_link( __( 'Edit', 'tdsimple' ), '<span class="sep"> / </span> <span class="edit-link">', '</span>' ); ?>
	</footer><!-- .entry-meta -->
	
	<div class="post-seperator"></div>
	
</article><!-- #post-<?php the_ID(); ?> -->
