<?php

class APSC_Filter
{

	function __construct() {

		if( !is_admin() ) {

			$this->init();

		}

	}

	function init() {
		
		add_action( 'pre_get_posts' , array( $this , 'archive_sort' ) );
		add_filter( 'posts_orderby' , array( $this , 'posts_orderby' ) , 10 , 2 );

	}
	
	function posts_orderby( $orderby_statement , $query ) {
		
		global $wpdb;
		global $APSC;
		
		if( $query->is_main_query() && !empty( $query->query_vars[$APSC->ltd . '_title_order'] ) ) {
			
			$Data = $this->get_data( $query );
			
			if( !empty( $Data['ignore_words'] ) ) {
				
				$ignore_words = $Data['ignore_words'];
				
				$new_orderby = "$wpdb->posts.post_title";
				
				$trim_sqls = array();
				$trim_sql = false;
				
				foreach( $ignore_words as $word ) {
					
					$word = strip_tags( $word );
					$trim_sqls[] = $wpdb->prepare( "TRIM( LEADING '%s' FROM `post_title` )" , $word );
					
				}
				
				$trim_sqls_count = count( $trim_sqls );
				
				$trim_sql = $trim_sqls[0];
				unset( $trim_sqls[0] );
				
				if( !empty( $trim_sqls ) ) {
					
					foreach( $trim_sqls as $trim_word ) {
						
						$explode = explode( '`post_title`' , $trim_word );
						$trim_sql = $explode[0] . $trim_sql . $explode[1];
						
					}
					
				}
				
				$new_orderby = $trim_sql;
				$new_orderby .= ' ' . strtoupper( $query->query_vars[$APSC->ltd . '_title_order'] );
	
				$orderby_statement = $new_orderby;
	
			}

		}
		
		return $orderby_statement;
	}

	function archive_sort( $query ) {
		
		global $wpdb;
		global $APSC;
		
		if( $query->is_main_query() ) {

			$Data = $this->get_data( $query );
			
			if( !empty( $Data ) && !empty( $Data['posts_per_page'] ) ) {
				
				if( $Data['posts_per_page'] == 'all' ) {

					$query->set( 'posts_per_page' , -1 );

				} elseif( $Data['posts_per_page'] == 'set' && !empty( $Data['posts_per_page_num'] ) ) {

					$query->set( 'posts_per_page' , intval( $Data['posts_per_page_num'] ) );

				}
				
				if( !empty( $Data["orderby"] ) ) {

					if( $Data['orderby'] != 'date' ) {

						if( $Data["orderby"] == 'custom_fields' && !empty( $Data["orderby_set"] ) ) {
	
							$custom_fields = $wpdb->get_col( "SELECT DISTINCT meta_value FROM $wpdb->postmeta WHERE `meta_key` LIKE '%" . strip_tags( $Data['orderby_set'] ) . "%' ORDER BY meta_value" );
							
							$numeric = true;

							foreach( $custom_fields as $cf ) {

								if( !is_numeric( $cf ) ) {

									$numeric = false;
									break;

								}

							}

							if( $numeric ) {

								$query->set( 'orderby' , 'meta_value_num' );

							} else {

								$query->set( 'orderby' , 'meta_value' );

							}

							$query->set( 'meta_key' , strip_tags( $Data['orderby_set'] ) );

						} else {
							
							$query->set( 'orderby' , strip_tags( $Data['orderby'] ) );
							
							if( $Data['orderby'] == 'title' )
								$query->set( $APSC->ltd . '_title_order' , $Data['order'] );
							
						}

					}

				}

				if( !empty( $Data['order'] ) ) {

					if( $Data["order"] != 'desc' ) {

						$query->set( 'order' , 'ASC' );

					} else {

						$query->set( 'order' , 'DESC' );

					}

				}

			}
			
		}
		
	}
	
	function get_data( $query ) {
		
		$Data = array();
		$APSC_Data = new APSC_Data();

		if( $query->is_home() ) {

			$GetData = $APSC_Data->get_data( 'home' );

		} elseif( $query->is_category() ) {

			$CatData = $APSC_Data->get_data( 'cat' );
			$current_cat_id = $query->get_queried_object()->cat_ID;

			if( !empty( $CatData[$current_cat_id] ) && !empty( $CatData[$current_cat_id]['use'] ) ) {

				$GetData['default'] = $CatData[$current_cat_id];

			} elseif( !empty( $CatData['default'] ) ) {

				$GetData['default'] = $CatData['default'];
				
			} else {

				$GetData = '';
				
			}

		} elseif( $query->is_tag() ) {

			$GetData = $APSC_Data->get_data( 'tag' );

		} elseif( $query->is_tax() ) {

			$tax = $query->get_queried_object();
			if( !empty( $tax ) ) {
				$current_taxonomy = $query->get_queried_object()->taxonomy;
				$current_term_id = $query->get_queried_object()->term_id;
				$TaxData = $APSC_Data->get_custom_taxonomy_data( $current_taxonomy );
	
				if( !empty( $TaxData[$current_term_id] ) && !empty( $TaxData[$current_term_id]['use'] ) ) {
	
					$GetData['default'] = $TaxData[$current_term_id];
	
				} elseif( !empty( $TaxData['default'] ) ) {
	
					$GetData['default'] = $TaxData['default'];
					
				} else {
	
					$GetData = '';
					
				}
			}
			
		} elseif( $query->is_search() ) {

			$GetData = $APSC_Data->get_data( 'search' );

		} elseif( $query->is_archive() && $query->is_month() ) {

			$GetData = $APSC_Data->get_data( 'monthly' );

		}

		if( !empty( $GetData ) && !empty( $GetData['default'] ) ) {

			$Data = $GetData['default'];

		}
		
		return $Data;
		
	}
	
}
?>