<?php 
/**
 * Custom Widgets
 *
 * @package tdminimal
 */

/**
 *  Shows Recent Posts for Widgets
 *	@since tdminimal 1.0.3
 */
 function tdminimal_get_recent_posts_for_widget( $number, $img_layout, $category ) {
 	setlocale( LC_TIME, get_locale() );
 	
 	if( $category != '' ) { 
		$category_id = get_cat_ID( $category );
		$recent_posts_args = array(
			'post_type' => 'post',
			'posts_per_page' => $number,
			'ignore_sticky_posts' => 1,
			'cat' => $category_id
		);
	} else {
		$recent_posts_args = array(
			'post_type' => 'post',
			'posts_per_page' => $number,
			'ignore_sticky_posts' => 1
		);
	}
		
	$recent_posts_query = new WP_Query( $recent_posts_args );
	$image_width = $img_layout;
	
	if( $recent_posts_query->have_posts() ) { 
		$recent_posts_output = '<ul>';
		while( $recent_posts_query->have_posts() ) {
			$recent_posts_query->the_post();
		
			if( $image_width === 'full' ) {
				$recent_posts_output .= '<li class="full-width">';
			
				if( has_post_thumbnail() ) {
					$recent_posts_output .= '<div class="recent-post-entry-image post-thumb" style="float: none;">';
					$recent_posts_output .= get_the_post_thumbnail();
					$recent_posts_output .= '<a href="'.get_permalink().'" class="thumb-mask" rel="bookmark" title="'.get_the_title().'"></a>';
					$recent_posts_output .= '<a href="'.get_permalink().'" class="thumb-mask-link-icon" rel="bookmark" title="'.get_the_title().'"><i class="fa fa-link"></i></a>';
					$recent_posts_output .= '</div>';
				}
			
			} else if ( $image_width === 'thumb' ) {
				$recent_posts_output .= '<li class="thumb-width">';
				$recent_posts_output .= '<div class="recent-post-entry-image"><a href="'.get_permalink().'" rel="bookmark" title="'.get_the_title().'">'.get_the_post_thumbnail( $recent_posts_query->post->ID, 'thumbnail' ).'</a></div>';
			} else {
				$recent_posts_output .= '<li>';
			}
		
			$recent_posts_output .= '<h4 class="recent-post-entry-title"><a href="'.get_permalink().'" rel="bookmark" title="'.get_the_title().'">'.get_the_title().'</a></h4>';
			$recent_posts_output .= '<span class="recent-post-date">'.esc_html( strftime('%B %e, %Y', get_post_time('U', true)) ). '</span>';
			$recent_posts_output .= '</li>';
		}
		$recent_posts_output .= '</ul>';
	
		echo $recent_posts_output;
	}
	wp_reset_query();
 }

/**
 *  Shows Popular Posts for Widgets
 *	@since tdminimal 1.0.3
 */
 function tdminimal_get_popular_posts_for_widget( $number, $img_layout, $category ) {
  	setlocale( LC_TIME, get_locale() );
		
	if( $category != '' ) {
		$category_id = get_cat_ID( $category );
		$recent_posts_args = array(
			'post_type' => 'post',
			'posts_per_page' => $number,
			'ignore_sticky_posts' => 1,
			'orderby'=>'comment_count',
			'order' => 'DESC',
			'cat' => $category_id
		);
	} else {
		$recent_posts_args = array(
			'post_type' => 'post',
			'posts_per_page' => $number,
			'ignore_sticky_posts' => 1,
			'orderby'=>'comment_count',
			'order' => 'DESC'
		);
	}
		
	$recent_posts_query = new WP_Query( $recent_posts_args );
	$image_width = $img_layout;
	
	if( $recent_posts_query->have_posts() ) { 
		$recent_posts_output = '<ul>';
		while( $recent_posts_query->have_posts() ) {
			$recent_posts_query->the_post();
			
			if( $image_width === 'full' ) {
				$recent_posts_output .= '<li class="full-width">';
				
				if( has_post_thumbnail() ) {
					$recent_posts_output .= '<div class="popular-post-entry-image post-thumb" style="float: none;">';
					$recent_posts_output .= get_the_post_thumbnail();
					$recent_posts_output .= '<a href="'.get_permalink().'" class="thumb-mask" rel="bookmark" title="'.get_the_title().'"></a>';
					$recent_posts_output .= '<a href="'.get_permalink().'" class="thumb-mask-link-icon" rel="bookmark" title="'.get_the_title().'"><i class="fa fa-link"></i></a>';
					$recent_posts_output .= '</div>';
				}
				
			} else if ( $image_width === 'thumb' ) {
				$recent_posts_output .= '<li class="thumb-width">';
				$recent_posts_output .= '<div class="popular-post-entry-image"><a href="'.get_permalink().'" rel="bookmark" title="'.get_the_title().'">'.get_the_post_thumbnail( $recent_posts_query->post->ID, 'thumbnail' ).'</a></div>';
			} else {
				$recent_posts_output .= '<li>';
			}
			
			$recent_posts_output .= '<h4 class="popular-post-entry-title"><a href="'.get_permalink().'" rel="bookmark" title="'.get_the_title().'">'.get_the_title().'</a></h4>';
			$recent_posts_output .= '<span class="popular-post-date">'.esc_html( strftime('%B %e, %Y', get_post_time('U', true)) ). '</span>';
			$recent_posts_output .= '</li>';
		}
		$recent_posts_output .= '</ul>';
		
		echo $recent_posts_output;
	}
	wp_reset_query();
 }
 
/**
 *  Shows Random Posts for Widgets
 *	@since tdminimal 1.0.3
 */
 function tdminimal_get_random_posts_for_widget( $number, $img_layout, $category ) {
 	setlocale( LC_TIME, get_locale() );
 	
 	if( $category != '' ) {
		$category_id = get_cat_ID( $category );
		$random_posts_args = array(
			'post_type' => 'post',
			'posts_per_page' => $number,
			'ignore_sticky_posts' => 1,
			'orderby'=>'rand',
			'cat' => $category_id
		);
	} else {
		$random_posts_args = array(
			'post_type' => 'post',
			'posts_per_page' => $number,
			'ignore_sticky_posts' => 1,
			'orderby'=>'rand'
		);
	}
	
	$random_posts_query = new WP_Query( $random_posts_args );
	$image_width = $img_layout;
	
	if( $random_posts_query->have_posts() ) { 
		$random_posts_output = '<ul>';
		while( $random_posts_query->have_posts() ) {
			$random_posts_query->the_post();
			
			if( $image_width === 'full' ) {
				$random_posts_output .= '<li class="full-width">';
				
				if( has_post_thumbnail() ) {
					$random_posts_output .= '<div class="random-post-entry-image post-thumb" style="float: none;">';
					$random_posts_output .= get_the_post_thumbnail();
					$random_posts_output .= '<a href="'.get_permalink().'" class="thumb-mask" rel="bookmark" title="'.get_the_title().'"></a>';
					$random_posts_output .= '<a href="'.get_permalink().'" class="thumb-mask-link-icon" rel="bookmark" title="'.get_the_title().'"><i class="fa fa-link"></i></a>';
					$random_posts_output .= '</div>';
				}
				
			} else if ( $image_width === 'thumb' ) {
				$random_posts_output .= '<li class="thumb-width">';
				$random_posts_output .= '<div class="random-post-entry-image"><a href="'.get_permalink().'" rel="bookmark" title="'.get_the_title().'">'.get_the_post_thumbnail( $random_posts_query->post->ID, 'thumbnail' ).'</a></div>';
			} else {
				$random_posts_output .= '<li>';
			}
			
			$random_posts_output .= '<h4 class="random-post-entry-title"><a href="'.get_permalink().'" rel="bookmark" title="'.get_the_title().'">'.get_the_title().'</a></h4>';
			$random_posts_output .= '<span class="random-post-date">'.esc_html( strftime('%B %e, %Y', get_post_time('U', true)) ). '</span>';
			$random_posts_output .= '</li>';
		}
		$random_posts_output .= '</ul>';
		
		echo $random_posts_output;
	}

 }

/**
 *  	Newsletter Widget
 *	@since tdminimal 1.0
 */
class tdminimal_newsletter_widget extends WP_Widget {
	function __construct() {
		parent::__construct(false, $name = 'tdMinimal Newsletter Widget', array( 'description' => __( 'This widget allows you to add your own newsletter form.', 'tdminimal' ) ) );
	}
	
	function form( $instance ) {
		$tdminimal_widget_title = isset( $instance['tdminimal_widget_newsletter_widget_title'] ) ? esc_attr( $instance['tdminimal_widget_newsletter_widget_title'] ) : '';
		$tdminimal_widget_layout = isset( $instance['tdminimal_widget_newsletter_layout'] ) ? esc_attr( $instance['tdminimal_widget_newsletter_layout'] ) : '';

		?>
			<p>
				<label for="<?php echo $this->get_field_id( 'tdminimal_widget_newsletter_widget_title' ); ?>"><?php _e( 'Title', 'tdminimal' ); ?></label>
				<input id="<?php echo $this->get_field_id( 'tdminimal_widget_newsletter_widget_title' ); ?>" class="widefat" name="<?php echo $this->get_field_name( 'tdminimal_widget_newsletter_widget_title' ); ?>" type="text" value="<?php echo $tdminimal_widget_title; ?>" />
			</p>
			<p>
				<label for="<?php echo $this->get_field_id( 'tdminimal_widget_newsletter_layout' ); ?>"><?php _e( 'Style:', 'tdminimal' ); ?></label>
				<select id="<?php echo $this->get_field_id( 'tdminimal_widget_newsletter_layout' ); ?>" class="widefat" name="<?php echo $this->get_field_name( 'tdminimal_widget_newsletter_layout' ); ?>">
					<option value="" <?php selected($tdminimal_widget_layout, '', true); ?>><?php _e( 'Default', 'tdminimal' ); ?></option>
				  	<option value="theme" <?php selected($tdminimal_widget_layout, 'theme', true); ?>><?php _e( 'tdMinimal', 'tdminimal' ); ?></option>
				</select>
			</p>
		<?php
		
	}
	
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;  
		$instance['tdminimal_widget_newsletter_widget_title' ] = strip_tags( $new_instance['tdminimal_widget_newsletter_widget_title'] );  
		$instance['tdminimal_widget_newsletter_layout' ] = strip_tags( $new_instance['tdminimal_widget_newsletter_layout'] );  

    	return $instance;  
	}

	function widget( $args, $instance ) {
		$newsletter_layout = $instance['tdminimal_widget_newsletter_layout' ];
		
		if( $newsletter_layout === 'theme' ) {
			$newsletter_layout_class = 'widget-tdminimal-style'; 
		} else {
			$newsletter_layout_class = 'widget-default-style';
		}
		
		echo $args['before_widget'];
		
		if( $instance['tdminimal_widget_newsletter_widget_title'] != "" && $newsletter_layout != 'theme' ) {
			echo '<h4 class="widget-title">'.__( $instance['tdminimal_widget_newsletter_widget_title'] ).'</h4>';
		}
		
		?>
		
		<?php if( tdminimal_newsletter_form() ): ?>
			<div id="newsletter-container" class="<?php echo $newsletter_layout_class; ?>">
				<?php echo tdminimal_newsletter_image(); ?>
				
				<?php if( $newsletter_layout === 'theme' ): ?>
				<h4 class="widget-title"><?php echo $instance['tdminimal_widget_newsletter_widget_title'] ?></h4>
				<?php endif; ?>
				
				<div id="newsletter">
					<?php 
						$tdminimal_newsletter = tdminimal_newsletter_form();
						echo $tdminimal_newsletter['newsletter-code']; 
					?>
				</div><!-- #newsletter -->
			</div><!-- #newsletter-container -->
		<?php endif; ?>
		
		<?php
		echo $args['after_widget'];
	}

}
add_action( 'widgets_init', create_function( '', 'return register_widget( "tdminimal_newsletter_widget" );' ) );

/**
*  	Recent Comments Widget
*	@since tdminimal 1.0
*/
class tdminimal_recent_comments_widget extends WP_Widget {
	function __construct() {
		parent::__construct(false, $name = 'tdMinimal Recent Commenst Widget', array( 'description' => __( 'This widget shows recent comments.', 'tdminimal' ) ) );
	}
	
	function form( $instance ) {
		$tdminimal_widget_title = isset( $instance['tdminimal_widget_recent_comments_widget_title'] ) ? esc_attr( $instance['tdminimal_widget_recent_comments_widget_title'] ) : '';
		$tdminimal_widget_number = isset( $instance['tdminimal_widget_recent_comments_widget_number'] ) ? esc_attr( $instance['tdminimal_widget_recent_comments_widget_number'] ) : '5';
		?>
			<p>
				<label for="<?php echo $this->get_field_id( 'tdminimal_widget_recent_comments_widget_title' ); ?>"><?php _e( 'Title', 'tdminimal' ); ?></label>
				<input id="<?php echo $this->get_field_id( 'tdminimal_widget_recent_comments_widget_title' ); ?>" class="widefat" name="<?php echo $this->get_field_name( 'tdminimal_widget_recent_comments_widget_title' ); ?>" type="text" value="<?php echo $tdminimal_widget_title; ?>" />
			</p>
			<p>
				<label for="<?php echo $this->get_field_id( 'tdminimal_widget_recent_comments_widget_number' ); ?>"><?php _e( 'Number of comments to show:', 'tdminimal' ); ?></label>
				<input id="<?php echo $this->get_field_id( 'tdminimal_widget_recent_comments_widget_number' ); ?>" class="widefat" name="<?php echo $this->get_field_name( 'tdminimal_widget_recent_comments_widget_number' ); ?>" type="text" value="<?php echo $tdminimal_widget_number; ?>" />
			</p>
		<?php
		
	}
	
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;  
		$instance['tdminimal_widget_recent_comments_widget_title' ] = strip_tags( $new_instance['tdminimal_widget_recent_comments_widget_title'] );  
  		$instance['tdminimal_widget_recent_comments_widget_number' ] = intval(strip_tags( $new_instance['tdminimal_widget_recent_comments_widget_number'] )); 
  		
    	return $instance;  
	}

	function widget( $args, $instance ) {
		$recent_comments = get_comments( array(
			'number'    => $instance['tdminimal_widget_recent_comments_widget_number'],
			'status'    => 'approve'
		) );
		
		echo $args['before_widget'];
		echo '<h4 class="widget-title">'.__( $instance['tdminimal_widget_recent_comments_widget_title'] ).'</h4>';
		
		if ( $recent_comments ) { 
			echo '<ul>';
			foreach ($recent_comments as $comment) {
				$comment_output = '<li>';
				$comment_output .= '<div class="recent-comment-meta">';
				$comment_output .= '<div class="avatar-container">'.get_avatar( $comment->comment_author_email, 92).'</div>';
				$comment_output .= '<div class="recent-comment-info"><h4 class="author-name">'.$comment->comment_author.'</h4> <div class="recent-comment-title"><a class="comment-title" href="'. get_permalink($comment->comment_post_ID).'#comment-'.$comment->comment_ID .'" title="'.$comment->comment_author .' | '.get_the_title($comment->comment_post_ID).'">'.get_the_title($comment->comment_post_ID).'</a></div></div>';
				$comment_output .= '</div>';
				$comment_output .= '<div class="recent-comment-excerpt">'.get_comment_excerpt($comment->comment_ID).'</div>';
				$comment_output .= '</li>';
				
				echo $comment_output;
			}
			echo '</ul>';
		}
		
		echo $args['after_widget'];
	}

}
add_action( 'widgets_init', create_function( '', 'return register_widget( "tdminimal_recent_comments_widget" );' ) );

/**
*  	Recent Posts Widget
*	@since tdminimal 1.0
*/
class tdminimal_recent_posts_widget extends WP_Widget {
	function __construct() {
		parent::__construct(false, $name = 'tdMinimal Recent Posts Widget', array( 'description' => __( 'This widget shows recent posts.', 'tdminimal' ) ) );
	}
	
	function form( $instance ) {
		$tdminimal_widget_title = isset( $instance['tdminimal_widget_recent_posts_widget_title'] ) ? esc_attr( $instance['tdminimal_widget_recent_posts_widget_title'] ) : '';
		$tdminimal_widget_number = isset( $instance['tdminimal_widget_recent_posts_widget_number'] ) ? esc_attr( $instance['tdminimal_widget_recent_posts_widget_number'] ) : '5';
		$tdminimal_widget_layout = isset( $instance['tdminimal_widget_recent_posts_widget_layout'] ) ? esc_attr( $instance['tdminimal_widget_recent_posts_widget_layout'] ) : 'thumb';
		$tdminimal_widget_category = isset( $instance['tdminimal_widget_recent_posts_widget_category'] ) ? esc_attr( $instance['tdminimal_widget_recent_posts_widget_category'] ) : '';

		?>
			<p>
				<label for="<?php echo $this->get_field_id( 'tdminimal_widget_recent_posts_widget_title' ); ?>"><?php _e( 'Title', 'tdminimal' ); ?></label>
				<input id="<?php echo $this->get_field_id( 'tdminimal_widget_recent_posts_widget_title' ); ?>" class="widefat" name="<?php echo $this->get_field_name( 'tdminimal_widget_recent_posts_widget_title' ); ?>" type="text" value="<?php echo $tdminimal_widget_title; ?>" />
			</p>
			<p>
				<label for="<?php echo $this->get_field_id( 'tdminimal_widget_recent_posts_widget_number' ); ?>"><?php _e( 'Number of posts to show:', 'tdminimal' ); ?></label>
				<input id="<?php echo $this->get_field_id( 'tdminimal_widget_recent_posts_widget_number' ); ?>" class="widefat" name="<?php echo $this->get_field_name( 'tdminimal_widget_recent_posts_widget_number' ); ?>" type="text" value="<?php echo $tdminimal_widget_number; ?>" />
			</p>
			<p>
				<label for="<?php echo $this->get_field_id( 'tdminimal_widget_recent_posts_widget_category' ); ?>"><?php _e( 'Category Name (Optional):', 'tdminimal' ); ?></label>
				<input id="<?php echo $this->get_field_id( 'tdminimal_widget_recent_posts_widget_category' ); ?>" class="widefat" name="<?php echo $this->get_field_name( 'tdminimal_widget_recent_posts_widget_category' ); ?>" type="text" value="<?php echo $tdminimal_widget_category; ?>" />
			</p>
			<p>
				<label for="<?php echo $this->get_field_id( 'tdminimal_widget_recent_posts_widget_layout' ); ?>"><?php _e( 'Image Width:', 'tdminimal' ); ?></label>
				<select id="<?php echo $this->get_field_id( 'tdminimal_widget_recent_posts_widget_layout' ); ?>" class="widefat" name="<?php echo $this->get_field_name( 'tdminimal_widget_recent_posts_widget_layout' ); ?>">
					<option value="full" <?php selected($tdminimal_widget_layout, 'full', true); ?>><?php _e( 'Full Size', 'tdminimal' ); ?></option>
				  	<option value="thumb" <?php selected($tdminimal_widget_layout, 'thumb', true); ?>><?php _e( 'Thumbnail', 'tdminimal' ); ?></option>
				  	<option value="none" <?php selected($tdminimal_widget_layout, 'none', true); ?>><?php _e( 'None', 'tdminimal' ); ?></option>
				</select>
			</p>
		<?php
		
	}
	
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;  
		$instance['tdminimal_widget_recent_posts_widget_title'] = strip_tags( $new_instance['tdminimal_widget_recent_posts_widget_title'] );  
  		$instance['tdminimal_widget_recent_posts_widget_number'] = intval(strip_tags( $new_instance['tdminimal_widget_recent_posts_widget_number'] )); 
  		$instance['tdminimal_widget_recent_posts_widget_layout'] = strip_tags( $new_instance['tdminimal_widget_recent_posts_widget_layout'] );
  		$instance['tdminimal_widget_recent_posts_widget_category'] = strip_tags( $new_instance['tdminimal_widget_recent_posts_widget_category'] ); 
  		  		
    	return $instance;  
	}

	function widget( $args, $instance ) {
		setlocale( LC_TIME, get_locale() );
		
		$number_of_posts = $instance['tdminimal_widget_recent_posts_widget_number'];
		$image_layout = $instance['tdminimal_widget_recent_posts_widget_layout'];
		$selected_category = $instance['tdminimal_widget_recent_posts_widget_category'];
		
		echo $args['before_widget'];
		echo '<h4 class="widget-title">'.__( $instance['tdminimal_widget_recent_posts_widget_title'] ).'</h4>';
		
		tdminimal_get_recent_posts_for_widget( $number_of_posts, $image_layout, $selected_category );
	
		echo $args['after_widget'];
	}

}
add_action( 'widgets_init', create_function( '', 'return register_widget( "tdminimal_recent_posts_widget" );' ) );

/**
 *  	Popular Posts Widget
 *	@since tdminimal 1.0
 */
class tdminimal_popular_posts_widget extends WP_Widget {
	function __construct() {
		parent::__construct(false, $name = 'tdMinimal Popular Posts Widget', array( 'description' => __('This widget shows popular posts.', 'tdminimal' ) ) );
	}
	
	function form( $instance ) {
		$tdminimal_widget_title = isset( $instance['tdminimal_widget_popular_posts_widget_title'] ) ? esc_attr( $instance['tdminimal_widget_popular_posts_widget_title'] ) : '';
		$tdminimal_widget_number = isset( $instance['tdminimal_widget_popular_posts_widget_number'] ) ? esc_attr( $instance['tdminimal_widget_popular_posts_widget_number'] ) : '5';
		$tdminimal_widget_layout = isset( $instance['tdminimal_widget_popular_posts_widget_layout'] ) ? esc_attr( $instance['tdminimal_widget_popular_posts_widget_layout'] ) : 'thumb';
		$tdminimal_widget_category = isset( $instance['tdminimal_widget_popular_posts_widget_category'] ) ? esc_attr( $instance['tdminimal_widget_popular_posts_widget_category'] ) : '';

		?>
			<p>
				<label for="<?php echo $this->get_field_id( 'tdminimal_widget_popular_posts_widget_title' ); ?>"><?php _e( 'Title', 'tdminimal' ); ?></label>
				<input id="<?php echo $this->get_field_id( 'tdminimal_widget_popular_posts_widget_title' ); ?>" class="widefat" name="<?php echo $this->get_field_name( 'tdminimal_widget_popular_posts_widget_title' ); ?>" type="text" value="<?php echo $tdminimal_widget_title; ?>" />
			</p>
			<p>
				<label for="<?php echo $this->get_field_id( 'tdminimal_widget_popular_posts_widget_number' ); ?>"><?php _e( 'Number of posts to show:', 'tdminimal' ); ?></label>
				<input id="<?php echo $this->get_field_id( 'tdminimal_widget_popular_posts_widget_number' ); ?>" class="widefat" name="<?php echo $this->get_field_name( 'tdminimal_widget_popular_posts_widget_number' ); ?>" type="text" value="<?php echo $tdminimal_widget_number; ?>" />
			</p>
			<p>
				<label for="<?php echo $this->get_field_id( 'tdminimal_widget_popular_posts_widget_category' ); ?>"><?php _e( 'Category Name (Optional):', 'tdminimal' ); ?></label>
				<input id="<?php echo $this->get_field_id( 'tdminimal_widget_popular_posts_widget_category' ); ?>" class="widefat" name="<?php echo $this->get_field_name( 'tdminimal_widget_popular_posts_widget_category' ); ?>" type="text" value="<?php echo $tdminimal_widget_category; ?>" />
			</p>
			<p>
				<label for="<?php echo $this->get_field_id( 'tdminimal_widget_popular_posts_widget_layout' ); ?>"><?php _e( 'Image Width:', 'tdminimal' ); ?></label>
				<select id="<?php echo $this->get_field_id( 'tdminimal_widget_popular_posts_widget_layout' ); ?>" class="widefat" name="<?php echo $this->get_field_name( 'tdminimal_widget_popular_posts_widget_layout' ); ?>">
					<option value="full" <?php selected($tdminimal_widget_layout, 'full', true); ?>><?php _e( 'Full Size', 'tdminimal' ); ?></option>
				  	<option value="thumb" <?php selected($tdminimal_widget_layout, 'thumb', true); ?>><?php _e( 'Thumbnail', 'tdminimal' ); ?></option>
				  	<option value="none" <?php selected($tdminimal_widget_layout, 'none', true); ?>><?php _e( 'None', 'tdminimal' ); ?></option>
				</select>
			</p>
		<?php
		
	}
	
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;  
		$instance['tdminimal_widget_popular_posts_widget_title'] = strip_tags( $new_instance['tdminimal_widget_popular_posts_widget_title'] );  
  		$instance['tdminimal_widget_popular_posts_widget_number'] = intval(strip_tags( $new_instance['tdminimal_widget_popular_posts_widget_number'] )); 
  		$instance['tdminimal_widget_popular_posts_widget_layout'] = strip_tags( $new_instance['tdminimal_widget_popular_posts_widget_layout'] );
  		$instance['tdminimal_widget_popular_posts_widget_category'] = strip_tags( $new_instance['tdminimal_widget_popular_posts_widget_category'] );

    	return $instance;  
	}

	function widget( $args, $instance ) {
		
		$number_of_posts = $instance['tdminimal_widget_popular_posts_widget_number'];
		$image_layout = $instance['tdminimal_widget_popular_posts_widget_layout'];
		$selected_category = $instance['tdminimal_widget_popular_posts_widget_category'];
		
		echo $args['before_widget'];
		echo '<h4 class="widget-title">'.$instance['tdminimal_widget_popular_posts_widget_title'].'</h4>';
		
		tdminimal_get_popular_posts_for_widget( $number_of_posts, $image_layout, $selected_category );
		
		echo $args['after_widget'];
	}

}
add_action( 'widgets_init', create_function( '', 'return register_widget( "tdminimal_popular_posts_widget" );' ) );

/**
*  	Authors Widget
*	@since tdminimal 1.0
*/
class tdminimal_author_widget extends WP_Widget {
	function __construct() {
		parent::__construct(false, $name = 'tdMinimal Author Widget', array( 'description' => __( 'This widget shows recent/random/popular authors.', 'tdminimal' ) ) );
	}
	
	function form( $instance ) {
		$tdminimal_widget_title = isset( $instance['tdminimal_widget_author_widget_title'] ) ? esc_attr( $instance['tdminimal_widget_author_widget_title'] ) : '';
		$tdminimal_widget_number = isset( $instance['tdminimal_widget_author_widget_number'] ) ? esc_attr( $instance['tdminimal_widget_author_widget_number'] ) : '5';
		$tdminimal_widget_sort = isset( $instance['tdminimal_widget_author_widget_sort'] ) ? esc_attr( $instance['tdminimal_widget_author_widget_sort'] ) : '';
		?>
			<p>
				<label for="<?php echo $this->get_field_id( 'tdminimal_widget_author_widget_title' ); ?>"><?php _e( 'Title', 'tdminimal' ); ?></label>
				<input id="<?php echo $this->get_field_id( 'tdminimal_widget_author_widget_title' ); ?>" class="widefat" name="<?php echo $this->get_field_name( 'tdminimal_widget_author_widget_title' ); ?>" type="text" value="<?php echo $tdminimal_widget_title; ?>" />
			</p>
			<p>
				<label for="<?php echo $this->get_field_id( 'tdminimal_widget_author_widget_number' ); ?>"><?php _e( 'Number of posts to show:', 'tdminimal' ); ?></label>
				<input id="<?php echo $this->get_field_id( 'tdminimal_widget_author_widget_number' ); ?>" class="widefat" name="<?php echo $this->get_field_name( 'tdminimal_widget_author_widget_number' ); ?>" type="text" value="<?php echo $tdminimal_widget_number; ?>" />
			</p>
			<p>
				<label for="<?php echo $this->get_field_id( 'tdminimal_widget_author_widget_sort' ); ?>"><?php _e( 'Sort by:', 'tdminimal' ); ?></label>
				<select id="<?php echo $this->get_field_id( 'tdminimal_widget_author_widget_sort' ); ?>" class="widefat" name="<?php echo $this->get_field_name( 'tdminimal_widget_author_widget_sort' ); ?>">
					<option value="recent" <?php selected($tdminimal_widget_sort, 'recent', true); ?>><?php _e( 'Recent', 'tdminimal' ); ?></option>
				  	<option value="popular" <?php selected($tdminimal_widget_sort, 'popular', true); ?>><?php _e( 'Popular', 'tdminimal' ); ?></option>
				  	<option value="name" <?php selected($tdminimal_widget_sort, 'name', true); ?>><?php _e( 'Name', 'tdminimal' ); ?></option>
				</select>
			</p>
		<?php
		
	}
	
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;  
		$instance['tdminimal_widget_author_widget_title'] = strip_tags( $new_instance['tdminimal_widget_author_widget_title'] );  
  		$instance['tdminimal_widget_author_widget_number'] = intval(strip_tags( $new_instance['tdminimal_widget_author_widget_number'] )); 
  		$instance['tdminimal_widget_author_widget_sort'] = strip_tags( $new_instance['tdminimal_widget_author_widget_sort'] );
  		
    	return $instance;  
	}

	function widget( $args, $instance ) {
		
		$authors_order = $instance['tdminimal_widget_author_widget_sort'];
		
		if ( $authors_order === 'recent' ) {
			$order_authors_by = 'registered';
			$author_order = 'DESC';
		} else if ( $authors_order === 'popular' ) {
			$order_authors_by = 'post_count';
			$author_order = 'DESC';
		} else if ( $authors_order === 'name' ) {
			$order_authors_by = 'display_name';
			$author_order = 'ASC';
		}
	
		$tdauthors = new WP_User_Query( array(
			'orderby' => $order_authors_by,
			'number' => $instance['tdminimal_widget_author_widget_number'],
			'order' => $author_order,
			'who' => 'authors'
		));
		
		$authors = $tdauthors->get_results();
		
		echo $args['before_widget'];
		echo '<h4 class="widget-title">'.__( $instance['tdminimal_widget_author_widget_title'] ).'</h4>';
		
		if ( !empty( $authors ) ) { 
			$output = '<ul>';
			foreach( $authors as $author ){
				$output .= '<li>';
				$output .= '<div class="avatar-container"><a href="'.get_author_posts_url( $author->ID ).'">'.get_avatar( $author->ID, 92 ).'</a></div>';
				$output .= '<h4 class="author-widget-name">'.$author->display_name.'</h4>';
    			$output .= '<div class="author-link"><a href="'.get_author_posts_url( $author->ID ).'">'. __( 'View all posts by', 'tdminimal' ) . ' '. $author->display_name . '</a></div>';
				$output .= '</li>';
			}
			$output .= '</ul>';
			echo $output;
		}
		
		wp_reset_query();
		echo $args['after_widget'];
	}

}
add_action( 'widgets_init', create_function( '', 'return register_widget( "tdminimal_author_widget" );' ) );

/**
 *  Gallery Widget
 *	@since tdminimal 1.0
 */
class tdminimal_gallery_widget extends WP_Widget {
	function __construct() {
		parent::__construct(false, $name = 'tdMinimal Gallery Widget', array( 'description' => __( 'This widget shows galleries from Gallery Post Format.', 'tdminimal' ) ) );
	}
	
	function form( $instance ) {
		$tdminimal_widget_title = isset( $instance['tdminimal_widget_gallery_widget_title'] ) ? esc_attr( $instance['tdminimal_widget_gallery_widget_title'] ) : '';
		$tdminimal_widget_post_name = isset( $instance['tdminimal_widget_galler_widget_post_title'] ) ? esc_attr( $instance['tdminimal_widget_galler_widget_post_title'] ) : '';
		?>
			<p>
				<label for="<?php echo $this->get_field_id( 'tdminimal_widget_gallery_widget_title' ); ?>"><?php _e( 'Title', 'tdminimal' ); ?></label>
				<input id="<?php echo $this->get_field_id( 'tdminimal_widget_gallery_widget_title' ); ?>" class="widefat" name="<?php echo $this->get_field_name( 'tdminimal_widget_gallery_widget_title' ); ?>" type="text" value="<?php echo $tdminimal_widget_title; ?>" />
			</p>
			<p>
				<label for="<?php echo $this->get_field_id( 'tdminimal_widget_galler_widget_post_title' ); ?>"><?php _e( 'Gallery Post Title:', 'tdminimal' ); ?></label>
				<input id="<?php echo $this->get_field_id( 'tdminimal_widget_galler_widget_post_title' ); ?>" class="widefat" name="<?php echo $this->get_field_name( 'tdminimal_widget_galler_widget_post_title' ); ?>" type="text" value="<?php echo $tdminimal_widget_post_name; ?>" />
			</p>
		<?php
		
	}
	
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;  
		$instance['tdminimal_widget_gallery_widget_title'] = strip_tags( $new_instance['tdminimal_widget_gallery_widget_title'] );  
  		$instance['tdminimal_widget_galler_widget_post_title'] = strip_tags( $new_instance['tdminimal_widget_galler_widget_post_title'] );
  		
    	return $instance;  
	}
	
	function widget( $args, $instance ) {
		$selected_post = get_page_by_title( $instance['tdminimal_widget_galler_widget_post_title'], 'OBJECT', 'post' );
		
		if( $selected_post && get_post_format( $selected_post->ID ) === 'gallery' ) {
			$gallery_link =  get_permalink( $selected_post->ID );
			$gallery_args = array(
				'post_type' => 'attachment',
				'numberposts' => -1,
				'post_status' => null,
				'post_parent' => $selected_post->ID
			);
			
			$attachments = get_posts( $gallery_args );
 
			if( !empty( $attachments ) ) {
				$output = '<ul class="bxslider">';
				foreach ( $attachments as $attachment ) { 
					$output .= '<li><img src="'.wp_get_attachment_url( $attachment->ID ).'" alt="'.$attachment->post_title.'"></li>';
				}
				$output .= '</ul>';
				$output .= '<div class="gallery-meta"><a href="'.$gallery_link.'" title="'.$attachment->post_title.'">'.__( 'Read More', 'tdminimal' ).' <i class="icon-chevron-sign-right"></i></a></div>';
				wp_reset_postdata();
			} 
	
		} else {
			$gallery_args = array(
				'posts_per_page' => 1,
				'orderby' => 'rand',
				'tax_query' => array(
					array(                
						'taxonomy' => 'post_format',
						'field' => 'slug',
						'terms' => array( 'post-format-gallery' )
					)
				)
			);
			
			$gallery_posts = new WP_Query( $gallery_args );
			
			if( $gallery_posts->have_posts() ) {
				while( $gallery_posts->have_posts() ) {
					$gallery_posts->the_post(); 
					$selected_post_id = $gallery_posts->post->ID;	
				} 
				
				wp_reset_postdata();
				
				$gallery_args = array(
					'post_type' => 'attachment',
					'numberposts' => -1,
					'post_status' => null,
					'post_parent' => $selected_post_id
				);
				
				$attachments = get_posts( $gallery_args );
 
				if( !empty( $attachments ) ) {
					$output = '<div class="post-slideshow"><ul class="bxslider">';
					foreach ( $attachments as $attachment ) { 
						$output .= '<li><img src="'.wp_get_attachment_url( $attachment->ID ).'" alt="'.$attachment->post_title.'"></li>';
					}
					$output .= '</ul></div>';
					wp_reset_postdata();
					$output .= '<div class="gallery-meta"><a class="button" href="'.get_permalink( $gallery_posts->post->ID ).'" title="'.$gallery_posts->post->post_title.'">'.__( 'Read More', 'tdminimal' ).'</a></div>';
				} 
				
			} else {
				$output = '';
			}
		}
		
		echo $args['before_widget'];
		echo '<h4 class="widget-title">'.__( $instance['tdminimal_widget_gallery_widget_title'] ).'</h4>';
		echo $output;
		echo $args['after_widget'];
	}
}
add_action( 'widgets_init', create_function( '', 'return register_widget( "tdminimal_gallery_widget" );' ) );

/**
*  	Random Posts Widget
*	@since tdminimal 1.0.3
*/
class tdminimal_random_posts_widget extends WP_Widget {
	function __construct() {
		parent::__construct(false, $name = 'tdMinimal Random Posts Widget', array( 'description' => __('This widget shows random posts.', 'tdminimal' ) ) );
	}
	
	function form( $instance ) {
		$tdminimal_widget_title = isset( $instance['tdminimal_widget_random_posts_widget_title'] ) ? esc_attr( $instance['tdminimal_widget_random_posts_widget_title'] ) : '';
		$tdminimal_widget_number = isset( $instance['tdminimal_widget_random_posts_widget_number'] ) ? esc_attr( $instance['tdminimal_widget_random_posts_widget_number'] ) : '5';
		$tdminimal_widget_layout = isset( $instance['tdminimal_widget_random_posts_widget_layout'] ) ? esc_attr( $instance['tdminimal_widget_random_posts_widget_layout'] ) : 'thumb';
		$tdminimal_widget_category = isset( $instance['tdminimal_widget_random_posts_widget_category'] ) ? esc_attr( $instance['tdminimal_widget_random_posts_widget_category'] ) : '';

		?>
			<p>
				<label for="<?php echo $this->get_field_id( 'tdminimal_widget_random_posts_widget_title' ); ?>"><?php _e( 'Title', 'tdminimal' ); ?></label>
				<input id="<?php echo $this->get_field_id( 'tdminimal_widget_random_posts_widget_title' ); ?>" class="widefat" name="<?php echo $this->get_field_name( 'tdminimal_widget_random_posts_widget_title' ); ?>" type="text" value="<?php echo $tdminimal_widget_title; ?>" />
			</p>
			<p>
				<label for="<?php echo $this->get_field_id( 'tdminimal_widget_random_posts_widget_number' ); ?>"><?php _e( 'Number of posts to show:', 'tdminimal' ); ?></label>
				<input id="<?php echo $this->get_field_id( 'tdminimal_widget_random_posts_widget_number' ); ?>" class="widefat" name="<?php echo $this->get_field_name( 'tdminimal_widget_random_posts_widget_number' ); ?>" type="text" value="<?php echo $tdminimal_widget_number; ?>" />
			</p>
			<p>
				<label for="<?php echo $this->get_field_id( 'tdminimal_widget_random_posts_widget_category' ); ?>"><?php _e( 'Category Name (Optional):', 'tdminimal' ); ?></label>
				<input id="<?php echo $this->get_field_id( 'tdminimal_widget_random_posts_widget_category' ); ?>" class="widefat" name="<?php echo $this->get_field_name( 'tdminimal_widget_random_posts_widget_category' ); ?>" type="text" value="<?php echo $tdminimal_widget_category; ?>" />
			</p>
			<p>
				<label for="<?php echo $this->get_field_id( 'tdminimal_widget_random_posts_widget_layout' ); ?>"><?php _e( 'Image Width:', 'tdminimal' ); ?></label>
				<select id="<?php echo $this->get_field_id( 'tdminimal_widget_random_posts_widget_layout' ); ?>" class="widefat" name="<?php echo $this->get_field_name( 'tdminimal_widget_random_posts_widget_layout' ); ?>">
					<option value="full" <?php selected($tdminimal_widget_layout, 'full', true); ?>><?php _e( 'Full Size', 'tdminimal' ); ?></option>
				  	<option value="thumb" <?php selected($tdminimal_widget_layout, 'thumb', true); ?>><?php _e( 'Thumbnail', 'tdminimal' ); ?></option>
				  	<option value="none" <?php selected($tdminimal_widget_layout, 'none', true); ?>><?php _e( 'None', 'tdminimal' ); ?></option>
				</select>
			</p>
		<?php
		
	}
	
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;  
		$instance['tdminimal_widget_random_posts_widget_title'] = strip_tags( $new_instance['tdminimal_widget_random_posts_widget_title'] );  
  		$instance['tdminimal_widget_random_posts_widget_number'] = intval(strip_tags( $new_instance['tdminimal_widget_random_posts_widget_number'] )); 
  		$instance['tdminimal_widget_random_posts_widget_layout'] = strip_tags( $new_instance['tdminimal_widget_random_posts_widget_layout'] );
  		$instance['tdminimal_widget_random_posts_widget_category'] = strip_tags( $new_instance['tdminimal_widget_random_posts_widget_category'] );

    	return $instance;  
	}

	function widget( $args, $instance ) {
		
		$number_of_posts = $instance['tdminimal_widget_random_posts_widget_number'];
		$image_layout = $instance['tdminimal_widget_random_posts_widget_layout'];
		$selected_category = $instance['tdminimal_widget_random_posts_widget_category'];
		
		echo $args['before_widget'];
		echo '<h4 class="widget-title">'.__( $instance['tdminimal_widget_random_posts_widget_title'] ).'</h4>';
		
		tdminimal_get_random_posts_for_widget( $number_of_posts, $image_layout, $selected_category );
		
		echo $args['after_widget'];
	}

}
add_action( 'widgets_init', create_function( '', 'return register_widget( "tdminimal_random_posts_widget" );' ) );

/**
 *  Grouped Widget
 *	@since tdminimal 1.0.3
 */
class tdminimal_grouped_posts_widget extends WP_Widget {
	function __construct() {
		parent::__construct(false, $name = 'tdMinimal Grouped Posts Widget', array( 'description' => __( 'This widget shows Popular, Recent and Random Posts.', 'tdminimal' ) ) );
	}
	
	function form( $instance ) {
		$tdminimal_widget_number = isset( $instance['tdminimal_widget_grouped_posts_widget_number'] ) ? esc_attr( $instance['tdminimal_widget_grouped_posts_widget_number'] ) : '5';
		$tdminimal_widget_layout = isset( $instance['tdminimal_widget_grouped_posts_widget_layout'] ) ? esc_attr( $instance['tdminimal_widget_grouped_posts_widget_layout'] ) : 'thumb';

		?>
			<p>
				<label for="<?php echo $this->get_field_id( 'tdminimal_widget_grouped_posts_widget_number' ); ?>"><?php _e( 'Number of posts to show:', 'tdminimal' ); ?></label>
				<input id="<?php echo $this->get_field_id( 'tdminimal_widget_grouped_posts_widget_number' ); ?>" class="widefat" name="<?php echo $this->get_field_name( 'tdminimal_widget_grouped_posts_widget_number' ); ?>" type="text" value="<?php echo $tdminimal_widget_number; ?>" />
			</p>
			<p>
				<label for="<?php echo $this->get_field_id( 'tdminimal_widget_grouped_posts_widget_layout' ); ?>"><?php _e( 'Image Width:', 'tdminimal' ); ?></label>
				<select id="<?php echo $this->get_field_id( 'tdminimal_widget_grouped_posts_widget_layout' ); ?>" class="widefat" name="<?php echo $this->get_field_name( 'tdminimal_widget_grouped_posts_widget_layout' ); ?>">
					<option value="full" <?php selected($tdminimal_widget_layout, 'full', true); ?>><?php _e( 'Full Size', 'tdminimal' ); ?></option>
				  	<option value="thumb" <?php selected($tdminimal_widget_layout, 'thumb', true); ?>><?php _e( 'Thumbnail', 'tdminimal' ); ?></option>
				  	<option value="none" <?php selected($tdminimal_widget_layout, 'none', true); ?>><?php _e( 'None', 'tdminimal' ); ?></option>
				</select>
			</p>
		<?php
		
	}
	
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;  
  		$instance['tdminimal_widget_grouped_posts_widget_number'] = intval(strip_tags( $new_instance['tdminimal_widget_grouped_posts_widget_number'] )); 
  		$instance['tdminimal_widget_grouped_posts_widget_layout'] = strip_tags( $new_instance['tdminimal_widget_grouped_posts_widget_layout'] );

    	return $instance;  
	}
	
	function widget( $args, $instance ) {
		
		$posts_number = $instance['tdminimal_widget_grouped_posts_widget_number'];
		$image_layout = $instance['tdminimal_widget_grouped_posts_widget_layout'];
		$selected_category = '';
		
		echo $args['before_widget'];
		echo '<div class="widget-title">';
		echo '<h4><a href="#grouped-popular-posts-widget" class="active">'.__( 'Popular', 'tdminimal' ).'</a></h4><h4><a href="#grouped-recent-posts-widget">'.__( 'Recent', 'tdminimal' ).'</a></h4><h4><a href="#grouped-random-posts-widget">'.__( 'Random', 'tdminimal' ).'</a></h4>';
		echo '</div>';
		
		echo '<div id="grouped-popular-posts-widget" class="active grouped-posts">';
		tdminimal_get_popular_posts_for_widget( $posts_number, $image_layout, $selected_category );
		echo '</div>';
		
		echo '<div id="grouped-recent-posts-widget" class="grouped-posts">';
		tdminimal_get_recent_posts_for_widget( $posts_number, $image_layout, $selected_category );
		echo '</div>';
		
		echo '<div id="grouped-random-posts-widget" class="grouped-posts">';
		tdminimal_get_random_posts_for_widget( $posts_number, $image_layout, $selected_category );
		echo '</div>';
	
		echo $args['after_widget'];
	}
}
add_action( 'widgets_init', create_function( '', 'return register_widget( "tdminimal_grouped_posts_widget" );' ) );

/**
 *  Social Widget
 *	@since tdminimal 1.0.6
 */
 class tdminimal_social_widget extends WP_Widget {
 	function __construct() {
		parent::__construct(false, $name = 'tdMinimal Social Widget', array( 'description' => __( 'This widget shows your social links.', 'tdminimal' ) ) );
	}
	
	function form( $instance ) {
		$tdminimal_widget_title = isset( $instance['tdminimal_widget_social_title'] ) ? esc_attr( $instance['tdminimal_widget_social_title'] ) : '';
	?>
		<p>
			<label for="<?php echo $this->get_field_id( 'tdminimal_widget_social_title' ); ?>"><?php _e( 'Title', 'tdminimal' ); ?></label>
			<input id="<?php echo $this->get_field_id( 'tdminimal_widget_social_title' ); ?>" class="widefat" name="<?php echo $this->get_field_name( 'tdminimal_widget_social_title' ); ?>" type="text" value="<?php echo $tdminimal_widget_title; ?>" />
		</p>
	<?php
	}
	
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;  
		$instance['tdminimal_widget_social_title'] = strip_tags( $new_instance['tdminimal_widget_social_title'] );  

    	return $instance;  
	}
	
	function widget( $args, $instance ) { 
		echo $args['before_widget'];
		if( $instance['tdminimal_widget_social_title'] != '' ) {
			echo '<h4 class="widget-title">'.__( $instance['tdminimal_widget_social_title'] ).'</h4>';
		} 
		echo '<ul class="widget-social-list">'.tdminimal_social_links().'</ul>';
		echo $args['after_widget'];
	}
 }
 add_action( 'widgets_init', create_function( '', 'return register_widget( "tdminimal_social_widget" );' ) );
 
/**
 *  Contact Info Widget
 *	@since tdminimal 1.0.6
 */
 class tdminimal_contact_info_widget extends WP_Widget {
 	function __construct() {
		parent::__construct(false, $name = 'tdMinimal Contact Info Widget', array( 'description' => __( 'This widget shows your contact informaion.', 'tdminimal' ) ) );
	}
	
	function form( $instance ) {
		$tdminimal_widget_title = isset( $instance['tdminimal_widget_contact_info_title'] ) ? esc_attr( $instance['tdminimal_widget_contact_info_title'] ) : '';
		$tdminimal_widget_address = isset( $instance['tdminimal_widget_contact_info_address'] ) ? esc_attr( $instance['tdminimal_widget_contact_info_address'] ) : '';
		$tdminimal_widget_phone = isset( $instance['tdminimal_widget_contact_info_phone'] ) ? esc_attr( $instance['tdminimal_widget_contact_info_phone'] ) : '';
		$tdminimal_widget_fax = isset( $instance['tdminimal_widget_contact_info_fax'] ) ? esc_attr( $instance['tdminimal_widget_contact_info_fax'] ) : '';
		$tdminimal_widget_email = isset( $instance['tdminimal_widget_contact_info_email'] ) ? esc_attr( $instance['tdminimal_widget_contact_info_email'] ) : '';
		$tdminimal_widget_website = isset( $instance['tdminimal_widget_contact_info_website'] ) ? esc_attr( $instance['tdminimal_widget_contact_info_website'] ) : '';
		$tdminimal_widget_custom_text = isset( $instance['tdminimal_widget_contact_info_custom_text'] ) ? esc_attr( $instance['tdminimal_widget_contact_info_custom_text'] ) : '';
	?>
		<div class="widget-content">
			<p>
				<label for="<?php echo $this->get_field_id( 'tdminimal_widget_contact_info_title' ); ?>"><?php _e( 'Title', 'tdminimal' ); ?></label>
				<input id="<?php echo $this->get_field_id( 'tdminimal_widget_contact_info_title' ); ?>" class="widefat" name="<?php echo $this->get_field_name( 'tdminimal_widget_contact_info_title' ); ?>" type="text" value="<?php echo $tdminimal_widget_title; ?>" />
			</p>
		
			<p>
				<label for="<?php echo $this->get_field_id( 'tdminimal_widget_contact_info_address' ); ?>"><?php _e( 'Address', 'tdminimal' ); ?></label>
				<textarea rows="4" col="10" id="<?php echo $this->get_field_id( 'tdminimal_widget_contact_info_address' ); ?>" class="widefat" name="<?php echo $this->get_field_name( 'tdminimal_widget_contact_info_address' ); ?>"><?php echo $tdminimal_widget_address; ?></textarea>
			</p>
			
			<p>
				<label for="<?php echo $this->get_field_id( 'tdminimal_widget_contact_info_phone' ); ?>"><?php _e( 'Phone', 'tdminimal' ); ?></label>
				<input id="<?php echo $this->get_field_id( 'tdminimal_widget_contact_info_phone' ); ?>" class="widefat" name="<?php echo $this->get_field_name( 'tdminimal_widget_contact_info_phone' ); ?>" type="text" value="<?php echo $tdminimal_widget_phone; ?>" />
			</p>
			
			<p>
				<label for="<?php echo $this->get_field_id( 'tdminimal_widget_contact_info_fax' ); ?>"><?php _e( 'Fax', 'tdminimal' ); ?></label>
				<input id="<?php echo $this->get_field_id( 'tdminimal_widget_contact_info_fax' ); ?>" class="widefat" name="<?php echo $this->get_field_name( 'tdminimal_widget_contact_info_fax' ); ?>" type="text" value="<?php echo $tdminimal_widget_fax; ?>" />
			</p>
			
			<p>
				<label for="<?php echo $this->get_field_id( 'tdminimal_widget_contact_info_email' ); ?>"><?php _e( 'Email', 'tdminimal' ); ?></label>
				<input id="<?php echo $this->get_field_id( 'tdminimal_widget_contact_info_email' ); ?>" class="widefat" name="<?php echo $this->get_field_name( 'tdminimal_widget_contact_info_email' ); ?>" type="text" value="<?php echo $tdminimal_widget_email; ?>" />
			</p>
			
			<p>
				<label for="<?php echo $this->get_field_id( 'tdminimal_widget_contact_info_website' ); ?>"><?php _e( 'Website', 'tdminimal' ); ?></label>
				<input id="<?php echo $this->get_field_id( 'tdminimal_widget_contact_info_website' ); ?>" class="widefat" name="<?php echo $this->get_field_name( 'tdminimal_widget_contact_info_website' ); ?>" type="text" value="<?php echo $tdminimal_widget_website; ?>" />
			</p>
			
			<p>
				<label for="<?php echo $this->get_field_id( 'tdminimal_widget_contact_info_custom_text' ); ?>"><?php _e( 'Custom Text', 'tdminimal' ); ?></label>
				<textarea rows="10" col="10" id="<?php echo $this->get_field_id( 'tdminimal_widget_contact_info_custom_text' ); ?>" class="widefat" name="<?php echo $this->get_field_name( 'tdminimal_widget_contact_info_custom_text' ); ?>"><?php echo $tdminimal_widget_custom_text; ?></textarea>
			</p>
			<p class="description"><?php _e( 'This textarea supports Contact Form 7 plugin. So, you can insert contact form shortcode here if you want :)' ,'tdminimal' ); ?></p>
		</div>
	<?php
	}
	
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;  
		$instance['tdminimal_widget_contact_info_title'] = strip_tags( $new_instance['tdminimal_widget_contact_info_title'] );  
		$instance['tdminimal_widget_contact_info_address'] = $new_instance['tdminimal_widget_contact_info_address'];  
		$instance['tdminimal_widget_contact_info_phone'] = $new_instance['tdminimal_widget_contact_info_phone']; 
		$instance['tdminimal_widget_contact_info_fax'] = $new_instance['tdminimal_widget_contact_info_fax'];  
		$instance['tdminimal_widget_contact_info_email'] = $new_instance['tdminimal_widget_contact_info_email'];  
		$instance['tdminimal_widget_contact_info_website'] = $new_instance['tdminimal_widget_contact_info_website'];
		$instance['tdminimal_widget_contact_info_custom_text'] = $new_instance['tdminimal_widget_contact_info_custom_text'];
		
    	return $instance;  
	}
	
	function widget( $args, $instance ) { 
		echo $args['before_widget'];
		if( $instance['tdminimal_widget_contact_info_title'] != '' ) {
			echo '<h4 class="widget-title">'.__( $instance['tdminimal_widget_contact_info_title'] ).'</h4>';
		} 
		$output = '<ul class="widget-contact-info">';
		
		if( $instance['tdminimal_widget_contact_info_address'] != '' ) {
			$output .= '<li><i class="fa fa-map-marker"></i>'.$instance['tdminimal_widget_contact_info_address'].'</li>';
		}
		
		if( $instance['tdminimal_widget_contact_info_phone'] != '' ) {
			$output .= '<li><i class="fa fa-phone"></i>'.$instance['tdminimal_widget_contact_info_phone'].'</li>';
		}
		
		if( $instance['tdminimal_widget_contact_info_fax'] != '' ) {
			$output .= '<li><i class="fa fa-print"></i>'.$instance['tdminimal_widget_contact_info_fax'].'</li>';
		}
		
		if( $instance['tdminimal_widget_contact_info_email'] != '' ) {
			$output .= '<li><i class="fa fa-envelope-o"></i>'.$instance['tdminimal_widget_contact_info_email'].'</li>';
		}
		
		if( $instance['tdminimal_widget_contact_info_website'] != '' ) {
			$output .= '<li><i class="fa fa-desktop"></i>'.$instance['tdminimal_widget_contact_info_website'].'</li>';
		}
		
		if( $instance['tdminimal_widget_contact_info_custom_text'] != '' ) {
			$output .= '<li>'.do_shortcode( $instance['tdminimal_widget_contact_info_custom_text'] ).'</li>';
		}
		
		$output .= '</ul>';
		echo $output;
		echo $args['after_widget'];
	}
 }
 add_action( 'widgets_init', create_function( '', 'return register_widget( "tdminimal_contact_info_widget" );' ) );
?>