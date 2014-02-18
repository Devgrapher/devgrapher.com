<?php
/**
 * The template for displaying Archive pages.
 *
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

				<header class="page-header">
					<h2 class="page-title">
						<?php
							if ( is_category() ) :
								single_cat_title();

							elseif ( is_tag() ) :
								single_tag_title();

							elseif ( is_author() ) :
								/* Queue the first post, that way we know
								 * what author we're dealing with (if that is the case).
								*/
								the_post();
								printf( __( 'Author: %s', 'tdminimal' ), '<span class="vcard">' . get_the_author() . '</span>' );
								/* Since we called the_post() above, we need to
								 * rewind the loop back to the beginning that way
								 * we can run the loop properly, in full.
								 */
								rewind_posts();

							elseif ( is_day() ) :
								printf( __( 'Day: %s', 'tdminimal' ), '<span>' . get_the_date() . '</span>' );

							elseif ( is_month() ) :
								printf( __( 'Month: %s', 'tdminimal' ), '<span>' . get_the_date( 'F Y' ) . '</span>' );

							elseif ( is_year() ) :
								printf( __( 'Year: %s', 'tdminimal' ), '<span>' . get_the_date( 'Y' ) . '</span>' );

							elseif ( is_tax( 'post_format', 'post-format-aside' ) ) :
								_e( 'Asides', 'tdminimal' );

							elseif ( is_tax( 'post_format', 'post-format-image' ) ) :
								_e( 'Images', 'tdminimal');

							elseif ( is_tax( 'post_format', 'post-format-video' ) ) :
								_e( 'Videos', 'tdminimal' );

							elseif ( is_tax( 'post_format', 'post-format-quote' ) ) :
								_e( 'Quotes', 'tdminimal' );

							elseif ( is_tax( 'post_format', 'post-format-link' ) ) :
								_e( 'Links', 'tdminimal' );

							else :
								_e( 'Archives', 'tdminimal' );

							endif;
						?>
					</h2>
					<?php
						// Show an optional term description.
						$term_description = term_description();
						if ( ! empty( $term_description ) ) :
							printf( '<div class="taxonomy-description">%s</div>', $term_description );
						endif;
					?>
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
			
				<?php get_template_part( 'no-results', 'archive' ); ?>

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
