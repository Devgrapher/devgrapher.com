<?php

class APSC_Data
{

	function init() {

		add_action( 'admin_init' , array( $this , 'dataUpdate' ) );
		add_action( 'wp_loaded' , array( $this , ( 'upgrade_data' ) ) );
		
	}

	function upgrade_data() {

		global $APSC;
		
		$version = get_option( $APSC->Record['db_version'] );
		
		if( $version != 1 ) {

			foreach( $APSC->Record as $rec => $record ) {
				
				$Data = array();

				if( $rec != 'donate' && $rec != 'db_version' ) {
					
					$GetData = $this->get_data( $rec );

					if( !empty( $GetData ) ) {
						
						$Data['default'] = $GetData;
						$Data['default']['use'] = 1;
						
						if( $rec == 'cat' ) {
							
							 $categories = get_categories();
							 foreach( $categories as $key => $category ) {
								$Data[$category->cat_ID] = $GetData;
								$Data[$category->cat_ID]['use'] = 0;
							 }
							 
						}
						
						update_option( $record , $Data );
						
					}
					
				}
	
			}

			update_option( $APSC->Record['db_version'] , 1 );

		}

	}

	function get_data( $record ) {

		global $APSC;

		$record = strip_tags( $record );
		$GetData = get_option( $APSC->Record[$record] );

		$Data = array();
		if( !empty( $GetData ) ) {
			$Data = $GetData;
		}
		
		return $Data;

	}

	function get_custom_taxonomy_data( $taxonomy_name ) {

		global $APSC;

		$taxonomy_name = strip_tags( $taxonomy_name );
		$GetData = get_option( $APSC->Record['ct_' . $taxonomy_name] );

		$Data = array();
		if( !empty( $GetData ) ) {
			$Data = $GetData;
		}
		
		return $Data;

	}

	function dataUpdate() {
		
		global $APSC;
		
		$RecordField = false;

		if( !empty( $_POST[$APSC->Nonces['field']] ) ) {
			
			if( check_admin_referer( $APSC->Nonces['value'] , $APSC->Nonces['field'] ) ) {

				if( !empty( $_POST['donate_key'] ) && !empty( $_POST['update'] ) ) {
					$this->DonatingCheck();
				}

				if( !empty( $_POST['record_field'] ) ) {
					$RecordField = strip_tags( $_POST['record_field'] );
				}
				
				if( !empty( $RecordField ) ) {
					if( !empty( $_POST['update'] ) ) {
						$this->update();
					} elseif( !empty( $_POST['reset'] ) ) {
						$this->update_reset();
					}
				}

			}

		}

	}

	function update_validate() {

		global $APSC;

		$Update = array();

		if( !empty( $_POST[$APSC->UPFN] ) ) {
			$UPFN = strip_tags( $_POST[$APSC->UPFN] );
			if( $UPFN == $APSC->UPFN ) {
				$Update['UPFN'] = strip_tags( $_POST[$APSC->UPFN] );
			}
		}

		return $Update;

	}

	function DonatingCheck() {
		
		global $APSC;

		$Update = $this->update_validate();

		if( !empty( $Update ) ) {

			if( !empty( $_POST['donate_key'] ) ) {

				$SubmitKey = md5( strip_tags( $_POST['donate_key'] ) );

				if( $APSC->DonateKey == $SubmitKey ) {

					update_option( $APSC->ltd . '_donated' , $SubmitKey );
					wp_redirect( add_query_arg( $APSC->MsgQ , 'donated' , remove_query_arg( $APSC->MsgQ ) ) );
					exit;

				}

			}

		}

	}

	function update() {
		
		global $APSC;

		$Update = $this->update_validate();
		
		if( !empty( $Update ) ) {
			
			if( !empty( $_POST['data'] ) ) {
				
				unset( $Update['UPFN'] );

				foreach( $_POST['data'] as $type => $setting ) {
					
					if( !empty( $setting['use'] ) ) {
						
						$use = 1;
						$posts_per_page = strip_tags( $setting['posts_per_page'] );
						$posts_per_page_num = intval( $setting['posts_per_page_num'] );
						$order = strip_tags( $setting['order'] );
						$orderby = strip_tags( $setting['orderby'] );
						$orderby_set = strip_tags( $setting['orderby_set'] );
						$ignore_words = array();
						
						if( !empty( $setting['ignore_words'] ) ) {
							
							foreach( $setting['ignore_words'] as $k => $word ) {
								
								$word = strip_tags( $word );

								if( !empty( $word ) )
									$ignore_words[] = $word;
								
							}
							
						}

						$Update[$type] = array( 'use' => $use , 'posts_per_page' => $posts_per_page , 'posts_per_page_num' => $posts_per_page_num , 'order' => $order , 'orderby' => $orderby , 'orderby_set' => $orderby_set , 'ignore_words' => $ignore_words );
						
					}
					
				}
				
				if( !empty( $Update ) ) {

					$RecordField = strip_tags( $_POST['record_field'] );
					$Record = $APSC->Record[$RecordField];
					
					update_option( $Record , $Update );
					wp_redirect( add_query_arg( $APSC->MsgQ , 'update' , remove_query_arg( $APSC->MsgQ ) ) );
					exit;

				}
				
			}

		}

	}

	function update_reset() {

		global $APSC;

		$Record = $APSC->Record[strip_tags( $_POST['record_field'] )];
		delete_option( $Record );
		wp_redirect( add_query_arg( $APSC->MsgQ , 'delete' , remove_query_arg( $APSC->MsgQ ) ) );
		exit;

	}

}
?>