<?php
/**
 * The template for displaying Archive pages.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package tdminimal
 */

get_header(); ?>
<?php $website_layout = tdminimal_is_sidebar(); ?>
<?php $curauth = ( isset( $_GET['author_name'] ) ) ? get_user_by( 'slug', $author_name ) : get_userdata( intval( $author ) ); ?>

<div class="container">
	<div class="row">
		<section id="primary" class="content-area author-archive">
			<div id="main" class="site-main <?php echo $website_layout['class']; ?>" role="main">
			<header class="page-header">
				<h2 class="author-name"><?php echo $curauth->display_name; ?></h2>
				<div class="author-archive-container clearfix">
					<div class="avatar-container pull-left">
						<?php echo get_avatar( $curauth->ID, 96 ); ?>
					</div><!-- .avatar-container -->
					<div class="author-info">
						<?php echo $curauth->user_description; ?>				
					</div><!-- .author-info -->
					<div class="author-archive-social clearfix">
						<?php echo tdminimal_get_author_social_links( $curauth->ID ); ?>
					</div><!-- .author-archive-social -->
				</div><!-- .author-archive-container -->
			</header><!-- .page-header -->
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

				<?php get_template_part( 'no-results', 'archive' ); ?>

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
