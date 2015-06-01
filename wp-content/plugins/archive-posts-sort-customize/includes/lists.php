<?php

class APSC_Lists
{

	function get_postbox( $args ) {
		
		global $APSC;

		$APSC_Data = new APSC_Data();
		
		$Default = array( 'use' => 0 , 'posts_per_page' => 'default' , 'posts_per_page_num' => false , 'orderby' => 'date' , 'orderby_set' => false , 'order' => 'desc' , 'ignore_words' => array() );

		$Settings = $Default;
		
		if( $args['archive'] != 'custom_taxonomy' ) {

			$Data = $APSC_Data->get_data( $args['archive'] );

		} else {

			$Data = $APSC_Data->get_custom_taxonomy_data( $args['taxonomy_name'] );

		}

		if( !empty( $Data ) ) {

			$field = 'default';
			if( !empty( $args['per_setting'] ) ) {

				$field = $args['term_id'];

			}
			
			if( !empty( $Data[$field] ) ) {
				
				foreach( $Data[$field] as $key => $val ) {
					$Settings[$key] = $val;
				}
				
			}
			
		}

		$this->show_postbox( $Settings , $args );

	}

	function show_postbox( $Settings , $args ) {
		
		global $APSC;

		$preview_info = $this->get_preview_info( $args );
		
		$class = '';
		if( !empty( $args['per_setting'] ) ) {
			$class = 'per_setting';
			if( empty( $Settings['use'] ) ) {
				$class .= ' closed';
			}
		}
?>
		<div class="postbox <?php echo $class; ?>">

			<?php if( !empty( $args['per_setting'] ) ) : ?>

				<div class="handlediv"><br /></div>

			<?php endif; ?>

			<h3>

				<?php if( !empty( $args['per_setting'] ) ) : ?>
				
					<?php if( !empty( $preview_info['url'] ) ) : ?>

						<?php _e( 'Sort settings' , $APSC->ltd ); ?>:
						<a href="<?php echo $preview_info['url']; ?>" target="_blank"><?php echo $preview_info['label']; ?></a>

					<?php endif; ?>

				<?php else: ?>

					<?php if( !empty( $preview_info['url'] ) ) : ?>

						<?php _e( 'Sort settings' , $APSC->ltd ); ?>:
						<a href="<?php echo $preview_info['url']; ?>" target="_blank"><?php echo $preview_info['label']; ?></a>

					<?php else: ?>
					
						<?php _e( 'Default' ); ?>
						<?php if( $args['archive'] == 'cat' ) : ?>
							<?php _e( 'Categories' ); ?>
						<?php endif; ?>
						<?php _e( 'Sort settings' , $APSC->ltd ); ?>

					<?php endif; ?>
				
				<?php endif; ?>

			</h3>

			<?php $style = ''; ?>
			<?php if( !empty( $args['per_setting'] ) && !empty( $Settings['use'] ) ) : ?>
				<?php $style = 'display: block;'; ?>
			<?php endif; ?>
			<div class="inside" style="<?php echo $style; ?>">

				<?php
				$field_name = 'data';
				if( !empty( $args['term_id'] ) ) {
					$field_name .= '[' . $args['term_id'] . ']';
				} else {
					$field_name .= '[default]';
				}
				$field_name .= '[use]';
				?>
				<?php if( empty( $args['per_setting'] ) ) : ?>

					<input type="hidden" name="<?php echo $field_name; ?>" class="use_val" value="1" />

				<?php else: ?>

					<label>
						<input type="checkbox" name="<?php echo $field_name; ?>" class="use_val" value="1" <?php checked( $Settings['use'] , 1 ); ?> />
						<?php if( $args['archive'] == 'cat' ) : ?>
							<?php $term = __( 'Categories' ); ?>
						<?php elseif( $args['archive'] == 'custom_taxonomy' ) : ?>
							<?php $term = __( 'Custom Taxonomies' , $APSC->ltd ); ?>
						<?php endif; ?>
						<?php printf( __( 'Another sort settings of only this %s.' , $APSC->ltd ) , $term ); ?>
					</label>

				<?php endif; ?>

				<?php $class = ''; ?>
				<?php if( !empty( $args['per_setting'] ) && empty( $Settings['use'] ) ) : ?>
					<?php $class = 'disabled'; ?>
				<?php endif; ?>
				<table class="form-table <?php echo $class; ?>">
					<tbody>
						<tr>
							<th>
								<?php _e( 'Number of posts per page' , $APSC->ltd ); ?>
							</th>
							<td>
								<p><?php printf( __( 'The default is <strong>%s</strong>.' , $APSC->ltd ) , __( 'Follow Reading Setting' , $APSC->ltd ) ); ?></p>
								<div class="posts_per_page_settings">
									<?php $this->get_fileds_post_per_page( $args , $Settings['posts_per_page'] , $Settings['posts_per_page_num'] ); ?>
								</div>
							</td>
						</tr>
						<tr>
							<th>
								<?php _e( 'Sort target' , $APSC->ltd ); ?>
							</th>
							<td>
								<p><?php printf( __( 'The default is <strong>%s</strong>.' , $APSC->ltd ) , __( 'Date' ) ); ?></p>
								<div class="orderby_settings">
									<?php $this->get_fileds_orderby( $args , $Settings['orderby'] ); ?>
								</div>
								<div class="orderby_customfields_settings">
									<?php $this->get_fileds_custom_fields( $args , $Settings['orderby_set'] ); ?>
								</div>
								<div class="orderby_ignore_words_settings">
									<?php $this->get_fileds_ignore_words( $args , $Settings['ignore_words'] ); ?>
								</div>
							</td>
						</tr>
						<tr>
							<th>
								<?php _e( 'Order' ); ?>
							</th>
							<td>
								<p><?php printf( __( 'The default is <strong>%s</strong>.' , $APSC->ltd ) , __( 'Descending' ) ); ?></p>
								<div class="order_settings">
									<?php $this->get_fileds_order( $args , $Settings['order'] ); ?>
								</div>
								<p><strong><?php _e( 'Descending' ); ?></strong> : <?php _e( 'From new to old' , $APSC->ltd ); ?> &amp; <?php _e( 'From many to small' , $APSC->ltd ); ?> &amp; Z to A</p>
								<p><strong><?php _e( 'Ascending' ); ?></strong> : <?php _e( 'From old to new' , $APSC->ltd ); ?> &amp; <?php _e( 'From small to many' , $APSC->ltd ); ?> &amp; A to Z</p>
							</td>
						</tr>
					</tbody>
				</table>

			</div>

		</div>
<?php

	}

	function get_preview_info( $args ) {

		global $APSC;
		
		$preview_info = array( 'url' => ''  , 'label' => '' );

		if( $args['archive'] == 'home' ) {
			
			$preview_info['url'] = home_url( '/' );
			$preview_info['label'] = __( 'Home' );
			
		} elseif( $args['archive'] == 'cat' ) {
			
			if( !empty( $args['term_id'] ) ) {
				$preview_info['url'] = get_category_link( $args['term_id'] );
				$preview_info['label'] = $args['title'];
			}
			
		} elseif( $args['archive'] == 'tag' ) {
			
			$Tag = get_tags( array( 'number' => 1 , 'orderby' => 'ID' , 'hide_empty' => true ) );
			if( !empty( $Tag ) ) {
				$preview_info['url'] = get_tag_link( $Tag[0]->term_id );
				$preview_info['label'] = __( 'Tags' );
			}
			
		} elseif( $args['archive'] == 'custom_taxonomy' ) {
			
			if( !empty( $args['term_id'] ) ) {
				$preview_info['url'] = get_term_link( (int) $args['term_id'] , $args['taxonomy_name'] );
				$preview_info['label'] = $args['title'];
			}
			
		} elseif( $args['archive'] == 'search' ) {
			
			$preview_info['url'] = get_search_link( 'Hello' );
			$preview_info['label'] = __( 'Search' );
			
		} elseif( $args['archive'] == 'monthly' ) {
			
			$currentDate = array( "y" => gmdate( 'Y' , current_time( 'timestamp' ) ) , "m" => gmdate( 'm' , current_time('timestamp') ) );
			$preview_info['url'] = get_month_link( $currentDate["y"] , $currentDate["m"] );
			$preview_info['label'] = __( 'Monthly' , $APSC->ltd ) . __( 'Archives' );
			
		}
		
		return $preview_info;

	}

	function get_fileds_post_per_page( $args , $posts_per_page , $post_per_page_num ) {
		
		global $APSC;

		$Sort = array(
			'all' => __( 'View all posts' , $APSC->ltd ),
			'default' => __( 'Follow Reading Setting' , $APSC->ltd ),
			'set' => __( 'Set the number of posts' , $APSC->ltd )
		);

		foreach( $Sort as $type => $type_label ) {
			
			echo '<div class="posts_per_page_fields">';
			
			echo '<label>';

			$field_name = 'data';
			if( !empty( $args['term_id'] ) ) {
				$field_name .= '[' . $args['term_id'] . ']';
			} else {
				$field_name .= '[default]';
			}
			$field_name .= '[posts_per_page]';

			echo '<input type="radio" name="' . $field_name . '" value="' . $type . '" ' . checked( $posts_per_page , $type , 0 ) . ' /> ' . $type_label;
			echo '</label>';

			if( $type == 'default' ) {
				echo sprintf( ' <span class="description">%1$s</span> <strong>%2$s</strong>%3$s' , __( 'Current number of Reading Setting' , $APSC->ltd ) , get_option( 'posts_per_page' ) , __( 'posts' ) );
			}

			if( $type == 'set' ) {
				echo '<span class="posts_per_page_num">';
				$val = 0;
				if( $posts_per_page == 'set' && !empty( $post_per_page_num ) ) {
					$val = intval( $post_per_page_num );
				}

				$field_name = 'data';
				if( !empty( $args['term_id'] ) ) {
					$field_name .= '[' . $args['term_id'] . ']';
				} else {
					$field_name .= '[default]';
				}
				$field_name .= '[posts_per_page_num]';

				echo '<input type="number" class="small-text posts_per_page_num" name="' . $field_name . '" value="' . $val . '" /> ' . __( 'posts' );
				echo '</span>';
			}

			echo '</div>';

		}

	}

	function get_fileds_orderby( $args , $orderby ) {
		
		global $APSC;

		$Sort = array(
			'date' => __( 'Date' ),
			'title' => __( 'Title' ),
			'author' => __( 'Author' ),
			'comment_count' => __( 'Comments Count' , $APSC->ltd ),
			'id' => 'ID',
			'modified' => __( 'Last Modified' ),
			'menu_order' => sprintf( '%1$s (%2$s)' , __( 'Order' ) , __( 'Page Attributes' ) ),
			'custom_fields' => __( 'Custom Fields' )
		);

		echo '<div class="orderby_fields">';
		
		$field_name = 'data';
		if( !empty( $args['term_id'] ) ) {
			$field_name .= '[' . $args['term_id'] . ']';
		} else {
			$field_name .= '[default]';
		}
		$field_name .= '[orderby]';

		echo '<select name="' . $field_name . '">';

		foreach( $Sort as $type => $type_label ) {
			
			echo '<option value="' . $type . '" ' . selected( $orderby , $type , 0 ) . '> ' . $type_label . '</option>';

		}

		echo '</select>';

		echo '</div>';

	}

	function get_fileds_custom_fields( $args , $custom_field ) {
		
		global $APSC;

		echo '<div class="orderby_custom_fields">';
		
		echo '<p class="description">' . __( 'Please enter the name of the custom field you want to sort.' , $APSC->ltd ) . '</p>';
		echo '<p><label>' . __( 'Custom Fields Name' , $APSC->ltd ) . ' : ';
		
		$field_name = 'data';
		if( !empty( $args['term_id'] ) ) {
			$field_name .= '[' . $args['term_id'] . ']';
		} else {
			$field_name .= '[default]';
		}
		$field_name .= '[orderby_set]';
		
		echo '<input type="text" class="regular-text orderby_set" name="' . $field_name . '" value="' . $custom_field . '" />';

		echo '</label></p>';

		echo '<p><a href="javascript:void(0);" class="all_custom_fields">' . __( 'Click here, please look for the required custom fields if you do not know the name of the custom field.' , $APSC->ltd ) . '</a></p>';

		$custom_fields = $this->get_custom_fields();
		if( !empty( $custom_fields ) ) {

			echo '<ul class="custom_fields_lists">';
			foreach( $custom_fields as $field_name ) {
				echo '<li><a href="javascript:void(0);" class="custom_fields_target_click button">' . $field_name . '</a></li>';
			}
			echo '</ul>';

		}

		echo '</div>';

	}

	function get_fileds_ignore_words( $args , $ignore_words ) {
		
		global $APSC;
		
		echo '<div class="orderby_ignore_words">';
		
		printf( '<p class="description">%s</p>' , __( 'If you want to ignore some words of beginning, please enter the word.' , $APSC->ltd ) );

		echo '<p><input type="button" class="button button-primary" id="add_ignore_word" value="' . __( 'Add New Word' , $APSC->ltd ) . '" /></p>';

		echo '<p>';
		
		$field_name = 'data';
		if( !empty( $args['term_id'] ) ) {
			$field_name .= '[' . $args['term_id'] . ']';
		} else {
			$field_name .= '[default]';
		}

		$field_name .= '[ignore_words][]';

		if( !empty( $ignore_words ) ) {
			
			foreach( $ignore_words as $word ) {

				echo '<p>';
				echo '<input type="text" class="regular-text" name="' . $field_name . '" placeholder="The (space)" value="' . $word . '" />';
				echo '<input type="button" class="button button-secondary remove_ignore_word" value="' . __( 'Remove Word' , $APSC->ltd ) . '" />';
				echo '</p>';

			}
			
		}
		echo '</p>';
		
		echo '<div id="add_ignore_field" style="display: none;">';
		echo '<p>';
		echo '<input type="text" class="regular-text" name="' . $field_name . '" placeholder="The (space)" value="" />';
		echo '<input type="button" class="button button-secondary remove_ignore_word" value="' . __( 'Remove Word' , $APSC->ltd ) . '" />';
		echo '</p>';
		echo '</div>';

		echo '</div>';

	}

	function get_fileds_order( $args , $order ) {
		
		global $APSC;

		$Sort = array(
			'desc' => __( 'Descending' ),
			'asc' => __( 'Ascending' )
		);

		echo '<div class="order_fields">';

		$field_name = 'data';
		if( !empty( $args['term_id'] ) ) {
			$field_name .= '[' . $args['term_id'] . ']';
		} else {
			$field_name .= '[default]';
		}
		$field_name .= '[order]';

		echo '<select name="' . $field_name . '">';
		foreach( $Sort as $type => $type_label ) {
			
			echo '<option value="' . $type . '" ' . selected( $order , $type ) . '> ' . $type_label . '</option>';

		}
		echo '</select>';

		echo '</div>';

	}

	function get_custom_fields() {

		global $wpdb;

		$Acfk = $wpdb->get_col( "SELECT meta_key FROM $wpdb->postmeta GROUP BY meta_key HAVING meta_key NOT LIKE '\_%' ORDER BY meta_key" );
		if( !empty( $Acfk) ) {
			natcasesort( $Acfk );
		}
		
		return $Acfk;
		
	}

	function get_custom_taxonomies() {
		
		$Terms = array();
		
		$get_taxonomies = get_taxonomies();
		$remove_terms = array( 'category' , 'post_tag' , 'nav_menu' , 'link_category' , 'post_format' );
		
		if( !empty( $get_taxonomies ) ) {

			foreach( $get_taxonomies as $term_name => $val ) {
				if( in_array( $term_name , $remove_terms ) ) {

					unset( $get_taxonomies[$term_name] );

				}
			}

		}
		
		if( !empty( $get_taxonomies ) ) {
			
			foreach( $get_taxonomies as $term_name => $val ) {

				$Terms[$term_name] = get_taxonomy( $term_name );

			}

		}

		return $Terms;

	}

}
?>