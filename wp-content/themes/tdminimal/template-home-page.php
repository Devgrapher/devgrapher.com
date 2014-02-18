<?php
  /*
  Template Name: Home Page
  */
?>

<?php get_header(); ?>

<div class="container">
	<?php 
		global $data;
		
		if( isset( $data['tdminimal_home_page_manager']['enabled'] ) ) {
			$home_page_layout = $data['tdminimal_home_page_manager']['enabled'];
			
			if( $home_page_layout ) {
				foreach ( $home_page_layout as $key => $value ) { 
					 switch( $key ) { 
						case 'slider_block':
							tdminimal_slider_content();
							break;
						case 'recent_posts_block':
							tdminimal_recent_blog_posts();
							break;
						case 'custom_text_block':
							tdminimal_custom_text_section();
							break;
					 }
				}
			}
		}
	?>
</div><!-- .container -->
<?php get_footer(); ?>