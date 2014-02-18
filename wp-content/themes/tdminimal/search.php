<?php
/**
 * The template for displaying Search Results pages.
 *
 * @package tdMinimal
 */

get_header(); ?>

<?php $website_layout = tdminimal_is_sidebar(); ?>

<div class="container">
	<div class="row">
		<section id="primary" class="content-area <?php echo $website_layout['class']; ?>">
			<div id="main" class="site-main" role="main">

			<?php if ( have_posts() ) : ?>

				<header class="page-header">
					<h2 class="page-title"><?php printf( __( 'Search Results for: %s', 'tdminimal' ), '<span>' . get_search_query() . '</span>' ); ?></h2>
				</header><!-- .page-header -->

				<?php if( tdminimal_is_one_column_alt_layout() ): ?>
					<div class="row content-grid">
					<?php
						while ( have_posts() ) : the_post();
							get_template_part( 'blog-alternative/content', get_post_format() );
						endwhile;
					?>
					</div><!-- .row -->
				<?php elseif( tdminimal_is_two_columns_layout() ): ?>
					<div class="row content-grid">
						<?php
							while ( have_posts() ) : the_post();
								get_template_part( 'content', get_post_format() );
							endwhile;
						?>
					</div><!-- .row -->
				<?php elseif( tdminimal_is_two_columns_alt_layout() ): ?>
					<div class="row">
						<div class="content-grid">
						<?php
							while ( have_posts() ) : the_post();
								get_template_part( 'blog-alternative/content', get_post_format() );
							endwhile;
						?>
						</div><!-- .content-grid -->
					</div><!-- .row -->
				<?php elseif( tdminimal_is_three_columns_layout() ): ?>
					<div id="three-columns-blog-layout" class="row content-grid">
						<?php
							while ( have_posts() ) : the_post();
								get_template_part( 'content', get_post_format() );
							endwhile;
						?>
					</div><!-- .row -->
				<?php elseif( tdminimal_is_three_columns_alt_layout() ): ?>
					<div id="three-columns-blog-layout" class="row content-grid">
						<?php
							while ( have_posts() ) : the_post();
								get_template_part( 'blog-alternative/content', get_post_format() );
							endwhile;
						?>
					</div><!-- .row -->
				<?php elseif( tdminimal_is_lines_layout() ): ?>
					<div id="lines-blog-layout" class="row content-grid">
						<?php
							while ( have_posts() ) : the_post();
								get_template_part( 'blog-lines/content', get_post_format() );
							endwhile;
						?>
					</div><!-- .row -->
				<?php elseif( tdminimal_is_standard_layout() ): ?>
					<div id="standard-blog-layout" class="row content-grid">
						<?php
							while ( have_posts() ) : the_post();
								get_template_part( 'blog-standard/content', get_post_format() );
							endwhile;
						?>
					</div><!-- .row -->
				<?php else: ?>
					<div class="row content-grid">
					<?php
						while ( have_posts() ) : the_post();
							get_template_part( 'content', get_post_format() );
						endwhile;
					?>
					</div><!-- .row -->
				<?php endif; ?>

				<div class="container nav-container">
					<?php tdminimal_content_nav( 'nav-below' ); ?>
				</div>

			<?php else : ?>

				<?php get_template_part( 'no-results', 'search' ); ?>

			<?php endif; ?>

			</div><!-- #main -->
		</section><!-- #primary -->

		<?php if( $website_layout['is_sidebar'] ): ?>
		<div class="col-lg-4 col-md-4">
			<?php get_sidebar(); ?>
		</div>
		<?php endif; ?>
		
	</div><!-- .row -->
</div><!-- .container -->
<?php get_footer(); ?>