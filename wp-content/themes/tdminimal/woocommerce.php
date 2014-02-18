<?php
/**
 * @package tdminimal
 */

get_header(); ?>

<div class="container">
	<div class="row">
		<div id="primary" class="content-area col-lg-12 col-md-12">
			<div id="main" class="site-main" role="main">
				<div class="hentry-container">
					<header class="entry-header">
						<h2 class="entry-title">
							<?php woocommerce_page_title(); ?>
						</h2>
					</header>
					<?php woocommerce_content(); ?>
				</div><!-- .hentry-container -->
			</div><!-- #main -->
		</div><!-- #primary -->
	</div><!-- .row -->
</div><!-- .container -->

<script type='text/javascript'>
jQuery(function() {
		jQuery(document).imagesLoaded(function(){
			var productContainer = jQuery('ul.products');
			productContainer.isotope({
				itemSelector : 'li.product',
				layoutMode : 'fitRows'
			});
	
			 jQuery(window).smartresize(function(){
				jQuery('ul.products').isotope({
					itemSelector : 'li.product',
					layoutMode : 'fitRows'
				});
			});
		});
	});
</script>

<?php get_footer(); ?>
 