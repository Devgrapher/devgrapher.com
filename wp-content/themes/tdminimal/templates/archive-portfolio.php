<?php
/**
 * The template for displaying Portfolio Archive posts/pages.
 *
 * @package tdminimal
 */

?>

<?php 
	$website_layout = tdminimal_is_portfolio_sidebar(); 
	$archive_layout = tdminimal_portfolio_archive_layout();
?>

<?php if( $archive_layout === "two-columns" || $archive_layout === "two-columns-alt"  ): ?>
<div id="portfolio-container" class="container">
<?php else: ?>
<div id="portfolio-container" class="container portfolio-one-column">
<?php endif; ?>
	<div class="row">
		<div id="primary" class="content-area <?php echo $website_layout['class']; ?>">
			<div id="main" class="site-main" role="main">
			
			<?php if ( have_posts() ) : ?>
				<header class="page-header">
					<h2 class="page-title"><?php single_cat_title(); ?></h2>
					<?php
						// Show an optional term description.
						$term_description = term_description();
						if ( ! empty( $term_description ) ) :
							printf( '<div class="taxonomy-description">%s</div>', $term_description );
						endif;
					?>
					
					<?php echo tdminimal_portfolio_archive_filter(); ?>
					
				</header><!-- .page-header -->
				
				<?php if( $archive_layout === "one-column-alt" ): ?>
					<div id="portfolio-one-column-alt" class="row portfolio-archive-container">
					<?php while ( have_posts() ) : the_post(); ?>
						<?php get_template_part( 'portfolio/content', 'one-column-alt' ); ?>
					<?php endwhile; ?>
					</div><!-- .portfolio-archive-container -->
				<?php elseif( $archive_layout === "two-columns" ): ?>
					<div id="portfolio-two-columns" class="row portfolio-archive-container">
					<?php while ( have_posts() ) : the_post(); ?>
						<?php get_template_part( 'portfolio/content', 'two-columns' ); ?>
					<?php endwhile; ?>
					</div><!-- .portfolio-archive-container -->
				<?php elseif( $archive_layout === "two-columns-alt" ): ?>
					<div id="portfolio-two-columns-alt" class="row portfolio-archive-container">
					<?php while ( have_posts() ) : the_post(); ?>
						<?php get_template_part( 'portfolio/content', 'two-columns-alt' ); ?>
					<?php endwhile; ?>
					</div><!-- .portfolio-archive-container -->
				<?php else: ?>
					<div id="portfolio-one-column" class="row portfolio-archive-container">
					<?php while ( have_posts() ) : the_post(); ?>
						<?php get_template_part( 'portfolio/content', 'one-column' ); ?>
					<?php endwhile; ?>
					</div><!-- .portfolio-archive-container -->
				<?php endif; ?>
				
				<div class="container nav-container">
					<?php tdminimal_content_nav( 'nav-below' ); ?>
				</div><!-- .nav-container -->
				
				
				<script type="text/javascript">
					jQuery(document).ready(function(){	
						jQuery('#portfolio-container').imagesLoaded(function(){
							jQuery('.portfolio-archive-container').isotope({
								itemSelector : '.portfolio-archive-item',
								<?php if( tdminimal_is_portfolio_masonry_layout() ): ?>
								layoutMode: 'sloppyMasonry'
								<?php else: ?>
								layoutMode: 'fitRows'
								<?php endif; ?>
							});
							
							jQuery(document).smartresize(function(){
								jQuery('#portfolio-container').imagesLoaded(function(){
									jQuery('.portfolio-archive-container').isotope({
										itemSelector : '.portfolio-archive-item',
										<?php if( tdminimal_is_portfolio_masonry_layout() ): ?>
										layoutMode: 'sloppyMasonry'
										<?php else: ?>
										layoutMode: 'fitRows'
										<?php endif; ?>
									});
								});
							});
							
							jQuery('.potfolio-filter-link').click(function(e) {
								e.preventDefault();
								jQuery('.potfolio-filter-link').parent().removeClass('active');
								jQuery(this).parent().addClass('active');
								var selector = jQuery(this).data('portfolio-filter');
								jQuery('.portfolio-archive-container').isotope({ filter: selector });
								
								jQuery('img.animated-image').each(function() {
									jQuery(this).animate({'opacity':"1", "left":"0"},150);
								}); 
							});
						});
					});
				</script>

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

