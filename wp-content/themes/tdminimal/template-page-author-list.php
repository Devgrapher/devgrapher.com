<?php
  /*
  Template Name: Authors List Page
  */

get_header(); ?>
<?php $page_template = tdminimal_post_page_template(); ?>

<div class="container">
	<div class="row">	
		<div id="primary" class="content-area <?php echo $page_template['span_class']; ?>">
			<div id="main" class="site-main" role="main">

				<?php while ( have_posts() ) : the_post(); ?>
					<div class="container hentry-container">
						<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
							<header class="entry-header">
								<?php if ( has_post_thumbnail() ): ?>
								<div class="post-thumb">
									<?php the_post_thumbnail(); ?>
								</div><!-- .post-thumb -->
								<?php endif; ?>
								<h2 class="entry-title">
									<span><?php the_title(); ?></span>
								</h2>
							</header><!-- .entry-header -->
							<div class="hentry-inner">
								<?php the_content(); ?>
								<?php tdminimal_get_authors_list(); ?>
							</div><!-- .hentry-inner -->
							<?php edit_post_link( __( 'Edit', 'tdminimal' ), '<footer class="entry-meta"><span class="edit-link">', '</span></footer>' ); ?>
						</article><!-- #post-## -->
					</div><!-- .hentry-container -->
				<?php endwhile; // end of the loop. ?>

			</div><!-- #main -->
		</div><!-- #primary -->

		<?php if( $page_template['is_sidebar'] && $page_template['right_sidebar'] ): ?>
		<div class="col-lg-4 col-md-4 sidebar-section">
			<?php get_sidebar(); ?>
		</div>
		<?php elseif( $page_template['is_sidebar'] && $page_template['left_sidebar'] ): ?>
		<div class="col-lg-4 col-md-4 left-sidebar sidebar-section">
			<?php get_sidebar(); ?>
		</div>
		<?php endif; ?>
	</div><!-- .row -->
</div><!-- .container -->

<?php get_footer(); ?>
