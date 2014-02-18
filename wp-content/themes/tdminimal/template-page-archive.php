<?php
  /*
  Template Name: Archive Page
  */
?>

<?php get_header(); ?>

	<div id="primary" class="content-area">
		<div id="content" class="site-content" role="main">
		
		<div class="container">
		<div class="hentry-container">
		<?php while ( have_posts() ) : the_post(); ?>
		
			<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	
				<div class="entry-body">
					<header class="entry-header">
						<h2 class="entry-title"><?php the_title(); ?></h2>
						<div class="entry-meta">
							<?php edit_post_link( __( 'Edit', 'tdminimal' ), ' <span class="edit-link">', '</span>' ); ?>
						</div><!-- .entry-meta -->
					</header><!-- .entry-header -->
		
					<div class="entry-content">
						<?php the_content(); ?>
					</div><!-- .entry-content -->
					<div class="row custom-archive-container">
						<div class="col-lg-4 col-md-4 col-sm-4 custom-archive-item">
							<h3><?php _e( 'Latest Posts', 'tdminimal' ); ?></h3>
				
							<ul>
								<?php
									$args = array( 'numberposts' => '15', 'post_status' => 'publish' );
									$recent_posts = wp_get_recent_posts( $args );
									foreach( $recent_posts as $recent ){
										echo '<li><a href="' . get_permalink( $recent["ID"] ) . '" title="'.esc_attr( $recent["post_title"] ).'" >' . $recent["post_title"].'</a> </li> ';
									}
								?>
							</ul>
						</div>
						
						<div class="col-lg-4 col-md-4 col-sm-4 custom-archive-item">
							<h3><?php _e( 'Archives by Month', 'tdminimal' ); ?></h3>
							<ul>
							<?php wp_get_archives( 'type=monthly' ); ?>
							</ul>
						</div>
						
						<div class="col-lg-4 col-md-4 col-sm-4 custom-archive-item">
							<h3><?php _e( 'Archive By Year', 'tdminimal' ); ?></h3>
							<ul>
							<?php wp_get_archives( 'type=yearly' ); ?>
							</ul>
						</div>

						<div class="col-lg-4 col-md-4 col-sm-4 custom-archive-item">
							<h3><?php _e( 'Pages', 'tdminimal' ); ?></h3>
				
							<ul>
				
							 <?php 
								$pages = get_pages(); 
								foreach ( $pages as $page ) {
									echo '<li><a href="'.get_page_link( $page->ID ).'">'.$page->post_title.'</a></li>';
								  }
							 ?>
				
							</ul>
						</div>
					
						<div class="col-lg-4 col-md-4 col-sm-4 custom-archive-item">
							<h3><?php _e( 'Categories', 'tdminimal' ); ?></h3>
				
							<ul>
							<?php
								$args = array(
									'orderby' => 'name',
									'order' => 'ASC'
								);
					
								$categories = get_categories( $args );
					
								foreach( $categories as $category ) {  
									echo '<li><a href="' . get_category_link( $category->term_id ) . '" title="' . sprintf( __( "View all posts in %s", "tdminimal" ), $category->name ) . '" ' . '>' . $category->name.'</a> ('.$category->count.') </li>';
								} 
							?>
							</ul>
						</div>
						
						<div class="col-lg-4 col-md-4 col-sm-4 custom-archive-item">
							<h3><?php _e( 'Contributors', 'tdminimal' ); ?></h3>
							<ul>
							<?php 
								$args = array(
									'show_fullname' => true,
									'optioncount' => true,
									'orderby' => 'post_count',
									'order' => 'DESC'
								);
							
								wp_list_authors($args); 
							?>
							</ul>
						</div>
					</div><!-- .custom-archive-container -->
				</div><!-- .entry-body -->
				
			</article><!-- #post-<?php the_ID(); ?> -->
		
		<?php endwhile; // end of the loop. ?>
		</div> <!-- .container -->
		</div>
		
		<script type="text/javascript">
		jQuery(document).ready(function(){	
			jQuery('.custom-archive-container').isotope({
				itemSelector : '.custom-archive-item',
				transformsEnabled: false,
				layoutMode: 'sloppyMasonry'
			});
		
			jQuery(document).smartresize(function(){
				jQuery('.custom-archive-container').isotope({
					itemSelector : '.custom-archive-item',
					transformsEnabled: false,
					layoutMode: 'sloppyMasonry'
				});
			});
        });
		</script>
		
		</div><!-- #content .site-content -->
	</div><!-- #primary .content-area -->

<?php get_footer(); ?>