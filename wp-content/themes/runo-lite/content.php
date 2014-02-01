<?php
/**
 * The default template for displaying content
 *
 */
?>


	<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
		<header class="entry-header">
		
			<div class="entry-date"><time><?php the_date(); ?></time></div>
			
			<h1><a href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__( 'Permalink to %s', 'runo' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark"><?php the_title(); ?></a></h1>
			
			<div class="entry-meta">
			
				<span class="by-author">
					<span class="author vcard"><?php _e( 'By', 'runo' ); ?> <?php the_author_link(); ?></span>
					<span class="sep"> &para; </span>
				</span> 
			
				<?php $show_sep = false; ?>
			<?php if ( 'post' == get_post_type() ) : // Hide category and tag text for pages on Search ?>
			<?php
				/* translators: used between list items, there is a space after the comma */
				$categories_list = get_the_category_list( __( ', ', 'runo' ) );
				if ( $categories_list ):
			?>
			<span class="cat-links">
				<?php printf( __( '<span class="%1$s">Posted in</span> %2$s', 'runo' ), 'entry-utility-prep entry-utility-prep-cat-links', $categories_list );
				$show_sep = true; ?>
			</span>
			<?php endif; // End if categories ?>
			
			<?php
				/* translators: used between list items, there is a space after the comma */
				$tags_list = get_the_tag_list( '', __( ', ', 'runo' ) );
				if ( $tags_list ):
				if ( $show_sep ) : ?>
			<span class="sep"> &para; </span>
				<?php endif; // End if $show_sep ?>
			<span class="tag-links">
				<?php printf( __( '<span class="%1$s">Tagged</span> %2$s', 'runo' ), 'entry-utility-prep entry-utility-prep-tag-links', $tags_list );
				$show_sep = true; ?>
			</span>
			<?php endif; // End if $tags_list ?>
			<?php endif; // End if 'post' == get_post_type() ?>

			<?php if ( comments_open() ) : ?>
			<?php if ( $show_sep ) : ?>
			<span class="sep"> &para; </span>
			<?php endif; // End if $show_sep ?>
			<span class="comments-link"><?php comments_popup_link( '<span class="leave-reply">' . __( 'Leave a reply', 'runo' ) . '</span>', __( '<b>1</b> Reply', 'runo' ), __( '<b>%</b> Replies', 'runo' ) ); ?></span>
			<?php endif; // End if comments_open() ?>

			<?php edit_post_link( __( 'Edit', 'runo' ), '<span class="sep"> &para; </span><span class="edit-link">', '</span>' ); ?>
			
			</div><!-- .entry-meta -->
			

		</header><!-- .entry-header -->

		<?php if ( is_search() ) : // Only display Excerpts for Search ?>
		<div class="entry-summary">
			<?php the_excerpt(); ?>
		</div><!-- .entry-summary -->
		<?php else : ?>
		
		<div class="entry-content">
		
			<?php the_content( __( 'Continue reading <span class="meta-nav">&rarr;</span>', 'runo' ) ); ?>
			<?php wp_link_pages( array( 'before' => '<div class="page-link"><span>' . __( 'Pages:', 'runo' ) . '</span>', 'after' => '</div>' ) ); ?>
		
		</div><!-- .entry-content -->
		
		
		<?php endif; ?>

		
	</article><!-- #post-<?php the_ID(); ?> -->
