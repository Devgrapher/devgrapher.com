<?php
  /*
  Template Name: Guestbook Page
  */
?>

<?php get_header(); ?>
<?php $page_template = tdminimal_post_page_template(); ?>

<div class="container">
	<div class="row">	
		<div id="primary" class="content-area <?php echo $page_template['span_class']; ?>">
			<div id="main" class="site-main" role="main">
				<div class="container hentry-container">
					<?php get_template_part( 'content', 'page' ); ?>
					<?php
						// If comments are open or we have at least one comment, load up the comment template
						if ( comments_open() || '0' != get_comments_number() )
							comments_template();
					?>
				</div><!-- .hentry-container -->
			</div><!-- #main -->
		</div>
	</div>
</div><!-- .container -->
<?php get_footer(); ?>