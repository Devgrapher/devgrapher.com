<?php
/**
 * @package tdMinimal
 */
?>

<div class="hentry-container col-lg-12 col-md-12 post-box">
	<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
		
		<header class="entry-header">	
			<h2 class="entry-title"><a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a></h2>
			<?php if ( 'post' == get_post_type() ) : ?>
			<div class="entry-meta">
				<?php tdminimal_posted_on(); ?>
				<span class="posted-by"><i class="fa fa-user"></i> <?php echo tdminimal_get_post_author(); ?></span>
				<span class="posted-in"><i class="fa fa-folder-open-o"></i> <?php echo tdminimal_get_categories(); ?></span>
				<span class="sep">&mdash;</span>
				<?php echo tdminimal_get_comment_counts(); ?>
				<?php edit_post_link( __( 'Edit', 'tdminimal' ), ' / <span class="edit-link">', '</span>' ); ?>
			</div><!-- .entry-meta -->
			<?php endif; ?>	
		</header><!-- .entry-header -->

	</article><!-- #post-## -->
</div><!-- .hentry-container -->
