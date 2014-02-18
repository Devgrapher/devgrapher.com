<?php
/**
 * Standard Blog Layout
 * @package tdMinimal
 */
?>

<div class="hentry-container col-lg-12 col-md-12 post-box">
	<article id="post-<?php the_ID(); ?>" <?php post_class('clearfix'); ?>>
		<?php if ( has_post_thumbnail() ): ?>
			<a href="<?php the_permalink(); ?>" class="alignleft post-thumb" alt="<?php the_title(); ?>">
			<?php the_post_thumbnail( 'thumbnail' ); ?>
			</a>
		<?php endif; ?>
			
		<header class="entry-header">
			<h2 class="entry-title"><a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a></h2>
		</header><!-- .entry-header -->
		
		<div class="hentry-inner">
			<?php if ( is_search() ) :?>
			<div class="entry-summary">
				<?php the_excerpt(); ?>
			</div><!-- .entry-summary -->
			<?php else : ?>
			<div class="entry-content">
				<?php if( tdminimal_is_auto_summary() ): ?>
					<?php the_excerpt(); ?>
				<?php else: ?>
					<?php the_content( __( 'Continue Reading', 'tdminimal' ) ); ?>
				<?php endif; ?>
				<?php
					wp_link_pages( array(
						'before'      => '<div class="page-links"><span class="page-links-title">' . __( 'Pages:', 'tdminimal') . '</span>',
						'after'       => '</div>',
						'link_before' => '<span>',
						'link_after'  => '</span>'
					) );
				?>
			</div><!-- .entry-content -->
			<?php endif; ?>
		</div><!-- .hentry-inner -->
		<footer class="entry-meta clearfix">
			<?php if ( 'post' == get_post_type() ) : ?>
				<div class="pull-left">
					<?php tdminimal_posted_on(); ?>
					<span class="posted-by"><i class="fa fa-user"></i> <?php echo tdminimal_get_post_author(); ?></span>
					<span class="posted-in"><i class="fa fa-folder-open-o"></i> <?php echo tdminimal_get_categories(); ?></span>
					<?php edit_post_link( __( 'Edit', 'tdminimal' ), ' / <span class="edit-link">', '</span>' ); ?>
				</div><!-- .pull-left -->
				<div class="pull-right">
					<a href="<?php the_permalink(); ?>"><?php _e( 'Continue Reading', 'tdminimal' ); ?></a>
				</div><!-- .pull-right -->
			<?php endif; ?>
		</footer><!-- .entry-meta -->
	</article><!-- #post-## -->
</div><!-- .hentry-container -->
