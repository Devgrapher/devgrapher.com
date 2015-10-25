<?php
/**
 * @package harmonic
 */
$format = get_post_format();
$formats = get_theme_support( 'post-formats' );
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<footer class="entry-meta">
		<?php if ( 'post' == get_post_type() && $format && in_array( $format, $formats[0] ) ): ?>
			<a href="<?php echo esc_url( get_post_format_link( $format ) ); ?>" title="<?php echo esc_attr( sprintf( __( 'All %s posts', 'harmonic' ), get_post_format_string( $format ) ) ); ?>">
				<span class="screen-reader-text"><?php echo get_post_format_string( $format ); ?></span>
				<span class="entry-format icon-block"></span>
			</a>
		<?php else : ?>
			<a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>">
				<span class="entry-format icon-block"></span>
			</a>
		<?php endif; ?>

		<?php harmonic_posted_on(); ?>

		<?php if ( 'post' == get_post_type() ) : // Hide category and tag text for pages on Search ?>
		<?php
			/* translators: used between list items, there is a space after the comma */
			$category_list = get_the_category_list( __( ', ', 'harmonic' ) );
			if ( 'post' == get_post_type() && $category_list && harmonic_categorized_blog() ) :
		?>
				<span class="cat-links"><?php echo $category_list; ?></span>
			<?php endif; ?>

		<?php endif; // End if 'post' == get_post_type() ?>


		<?php if ( ! post_password_required() && ( comments_open() || '0' != get_comments_number() ) ) : ?>
			<span class="comments-link"><?php comments_popup_link( __( 'Leave a comment', 'harmonic' ), __( '1 Comment', 'harmonic' ), __( '% Comments', 'harmonic' ) ); ?></span>
		<?php endif; ?>

		<?php if ( 'post' == get_post_type() ) : // Hide category and tag text for pages on Search ?>

		<?php
		$tags_list = get_the_tag_list( '', ' ' );
		if ( $tags_list ) :
		?>
			<span class="tags-links">
				<?php echo $tags_list; ?>
			</span>
		<?php endif; // End if $tags_list ?>

		<?php edit_post_link( __( 'Edit', 'harmonic' ), '<span class="edit-link">', '</span>' ); ?>

		<?php endif; // End if 'post' == get_post_type() ?>
	</footer><!-- .entry-meta -->

	<div class="entry-main">

		<?php if ( '' != get_the_post_thumbnail() && '' == $format ) : ?>
			<div class="entry-thumbnail">
				<a href="<?php the_permalink(); ?>" title="<?php echo esc_attr( sprintf( __( 'Permalink to %s', 'harmonic' ), the_title_attribute( 'echo=0' ) ) ); ?>" rel="<?php the_ID(); ?>">
					<?php the_post_thumbnail( 'harmonic-featured-image' ); ?>
				</a>
			</div><!-- .entry-thumbnail .fullwidth-block -->
		<?php endif; ?>

		<header class="entry-header">
			<h1 class="entry-title">
				<a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a>
			</h1>
		</header><!-- .entry-header -->

		<?php if ( is_search() ) : // Only display Excerpts for Search ?>
			<div class="entry-summary">
				<?php the_excerpt(); ?>
			</div><!-- .entry-summary -->
		<?php else : ?>
			<div class="entry-content">
				<?php the_content( __( 'Continue reading <span class="meta-nav">&rarr;</span>', 'harmonic' ) ); ?>
				<?php
					wp_link_pages( array(
						'before' => '<div class="page-links">' . __( 'Pages:', 'harmonic' ),
						'after'  => '</div>',
					) );
				?>
			</div><!-- .entry-content -->
		<?php endif; ?>

	</div><!-- .entry-main -->

</article><!-- #post-## -->
