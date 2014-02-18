<?php
/**
 * The main template file.
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package tdMinimal
 */

get_header(); ?>
<?php $website_layout = tdminimal_is_sidebar(); ?>

<div class="container">
	<div class="row">
		<div id="primary" class="content-area <?php echo $website_layout['class']; ?>">
			<div id="main" class="site-main" role="main">

			<?php if ( have_posts() ) : ?>
				
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
					<div class="row content-grid">
						<?php
							while ( have_posts() ) : the_post();
								get_template_part( 'blog-alternative/content', get_post_format() );
							endwhile;
						?>
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

				<?php get_template_part( 'no-results', 'index' ); ?>

			<?php endif; ?>

			</div><!-- #main -->
		</div><!-- #primary -->

		<?php if( $website_layout['is_sidebar'] ): ?>
		<div class="col-lg-4 col-md-4">
			<?php get_sidebar(); ?>
		</div>
		<?php endif; ?>
		
	</div><!-- .row -->
</div><!-- .container -->
<?php get_footer(); ?>