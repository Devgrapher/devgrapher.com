<?php
/**
 * The template for displaying Archive pages.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package Hexa
 */

get_header(); ?>

	<section id="primary" class="content-area">
		<main id="main" class="site-main" role="main">

		<?php if ( have_posts() ) : ?>

			<header class="page-header">
				<h1 class="page-title">
					<?php
						if ( is_category() ) :
							single_cat_title();

						elseif ( is_tag() ) :
							single_tag_title();

						elseif ( is_author() ) :
							printf( __( 'Author: %s', 'hexa' ), '<span class="vcard">' . get_the_author() . '</span>' );

						elseif ( is_day() ) :
							printf( __( 'Day: %s', 'hexa' ), '<span>' . get_the_date() . '</span>' );

						elseif ( is_month() ) :
							printf( __( 'Month: %s', 'hexa' ), '<span>' . get_the_date( _x( 'F Y', 'monthly archives date format', 'hexa' ) ) . '</span>' );

						elseif ( is_year() ) :
							printf( __( 'Year: %s', 'hexa' ), '<span>' . get_the_date( _x( 'Y', 'yearly archives date format', 'hexa' ) ) . '</span>' );

						elseif ( is_tax( 'post_format', 'post-format-aside' ) ) :
							_e( 'Asides', 'hexa' );

						elseif ( is_tax( 'post_format', 'post-format-gallery' ) ) :
							_e( 'Galleries', 'hexa');

						elseif ( is_tax( 'post_format', 'post-format-image' ) ) :
							_e( 'Images', 'hexa');

						elseif ( is_tax( 'post_format', 'post-format-video' ) ) :
							_e( 'Videos', 'hexa' );

						elseif ( is_tax( 'post_format', 'post-format-quote' ) ) :
							_e( 'Quotes', 'hexa' );

						elseif ( is_tax( 'post_format', 'post-format-link' ) ) :
							_e( 'Links', 'hexa' );

						elseif ( is_tax( 'post_format', 'post-format-status' ) ) :
							_e( 'Statuses', 'hexa' );

						elseif ( is_tax( 'post_format', 'post-format-audio' ) ) :
							_e( 'Audios', 'hexa' );

						elseif ( is_tax( 'post_format', 'post-format-chat' ) ) :
							_e( 'Chats', 'hexa' );

						else :
							_e( 'Archives', 'hexa' );

						endif;
					?>
				</h1>
				<?php if ( is_author() ) : ?>
					<div class="author-archives-header">
						<div class="author-info">
							<span class="author-archives-name"><span class="vcard"><a class="url fn n" href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( "ID" ) ) ); ?>" rel="me"><?php echo get_the_author(); ?></a></span></span>
							<span class="author-archives-url"><a href="<?php echo esc_url( get_the_author_meta( 'user_url', get_the_author_meta( 'ID' ) ) ); ?>"><?php echo get_the_author_meta( 'user_url', get_the_author_meta( 'ID' ) ); ?></a></span>
							<span class="author-archives-bio"><?php echo get_the_author_meta( 'user_description', get_the_author_meta( 'ID' ) ); ?></span>
						</div>
						<span class="author-archives-img"><?php echo get_avatar( get_the_author_meta( 'ID' ), '60' ); ?></span>
					</div>

				<?php endif; ?>
				<?php
					// Show an optional term description.
					$term_description = term_description();
					if ( ! empty( $term_description ) ) :
						printf( '<div class="taxonomy-description">%s</div>', $term_description );
					endif;
				?>
			</header><!-- .page-header -->

			<?php /* Start the Loop */ ?>
			<?php while ( have_posts() ) : the_post(); ?>

				<?php
					/* Include the Post-Format-specific template for the content.
					 * If you want to override this in a child theme, then include a file
					 * called content-___.php (where ___ is the Post Format name) and that will be used instead.
					 */
					get_template_part( 'content', get_post_format() );
				?>

			<?php endwhile; ?>

			<?php hexa_paging_nav(); ?>

		<?php else : ?>

			<?php get_template_part( 'content', 'none' ); ?>

		<?php endif; ?>

		</main><!-- #main -->
	</section><!-- #primary -->

<?php get_footer(); ?>
