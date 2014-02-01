<?php
/*
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl.txt
Copyright 2012-2014 - Jean-Sebastien Morisset - http://surniaulula.com/
*/

if ( ! defined( 'ABSPATH' ) ) 
	die( 'These aren\'t the droids you\'re looking for...' );

if ( ! class_exists( 'NgfbAdminGeneral' ) && class_exists( 'NgfbAdmin' ) ) {

	class NgfbAdminGeneral extends NgfbAdmin {

		public function __construct( &$plugin, $id, $name ) {
			$this->p =& $plugin;
			$this->p->debug->mark();
			$this->menu_id = $id;
			$this->menu_name = $name;
		}

		protected function add_meta_boxes() {
			// add_meta_box( $id, $title, $callback, $post_type, $context, $priority, $callback_args );
			add_meta_box( $this->pagehook.'_opengraph', 'Open Graph Settings', array( &$this, 'show_metabox_opengraph' ), $this->pagehook, 'normal' );
			add_meta_box( $this->pagehook.'_publishers', 'Publisher Settings', array( &$this, 'show_metabox_publishers' ), $this->pagehook, 'normal' );
		}

		public function show_metabox_opengraph() {
			$show_tabs = array( 
				'media' => 'Image and Video',
				'general' => 'Title and Description',
				'author' => 'Authorship',
			);
			$tab_rows = array();
			foreach ( $show_tabs as $key => $title )
				$tab_rows[$key] = $this->get_rows( $key );
			$this->p->util->do_tabs( 'og', $show_tabs, $tab_rows );
		}

		public function show_metabox_publishers() {
			$show_tabs = array( 
				'google' => 'Google',
				'facebook' => 'Facebook',
				'twitter' => 'Twitter',
			);
			$tab_rows = array();
			foreach ( $show_tabs as $key => $title )
				$tab_rows[$key] = $this->get_rows( $key );
			$this->p->util->do_tabs( 'pub', $show_tabs, $tab_rows );
		}

		protected function get_rows( $id ) {
			$ret = array();
			$user_ids = array();
			foreach ( get_users() as $user ) 
				$user_ids[$user->ID] = $user->display_name;
			$user_ids[0] = 'none';
			switch ( $id ) {

				case 'media' :

					$ret[] = $this->p->util->th( 'Image Dimensions', 'highlight', 'og_img_dimensions' ).
					'<td>Width '.$this->form->get_input( 'og_img_width', 'short' ).' x '.
					'Height '.$this->form->get_input( 'og_img_height', 'short' ).' &nbsp; '.
					'Cropped '.$this->form->get_checkbox( 'og_img_crop' ).' &nbsp; '.
					 'Auto-Resize Images'.$this->p->msgs->get( 'tooltip-og_img_resize' ).
					 $this->form->get_checkbox( 'og_img_resize' ).'</td>';
	
					$id_pre = array( 'wp' => 'Media Library' );
					if ( $this->p->is_avail['media']['ngg'] == true ) 
						$id_pre['ngg'] = 'NextGEN Gallery';
					$ret[] = $this->p->util->th( 'Default Image ID', 'highlight', 'og_def_img_id' ).
					'<td>'.$this->form->get_input( 'og_def_img_id', 'short' ).' in the '.
					$this->form->get_select( 'og_def_img_id_pre', $id_pre ).'</td>';
	
					$ret[] = $this->p->util->th( 'Default Image URL', null, 'og_def_img_url' ).
					'<td>'.$this->form->get_input( 'og_def_img_url', 'wide' ).'</td>';
	
					$ret[] = $this->p->util->th( 'Default Image on Indexes', null, 'og_def_img_on_index' ).
					'<td>'.$this->form->get_checkbox( 'og_def_img_on_index' ).'</td>';
	
					$ret[] = $this->p->util->th( 'Default Image on Search Results', null, 'og_def_img_on_search' ).
					'<td>'.$this->form->get_checkbox( 'og_def_img_on_search' ).'</td>';
	
					if ( $this->p->is_avail['media']['ngg'] == true ) {
						$ret[] = $this->p->util->th( 'Add Featured Image Tags', null, 'og_ngg_tags' ).
						'<td>'.$this->form->get_checkbox( 'og_ngg_tags' ).'</td>';
					} else $ret[] = $this->form->get_hidden( 'og_ngg_tags' );
	
					$ret[] = $this->p->util->th( 'Maximum Images', 'highlight', 'og_img_max' ).
					'<td>'.$this->form->get_select( 'og_img_max', 
						range( 0, $this->p->cf['form']['max_media_items'] ), 'short', null, true ).'</td>';
	
					$ret[] = $this->p->util->th( 'Maximum Videos', 'highlight', 'og_vid_max' ).
					'<td>'.$this->form->get_select( 'og_vid_max', 
						range( 0, $this->p->cf['form']['max_media_items'] ), 'short', null, true ).'</td>';
	
					$ret[] = $this->p->util->th( 'Use HTTPS for Video APIs', null, 'og_vid_https' ).
					'<td>'.$this->form->get_checkbox( 'og_vid_https' ).'</td>';
	
					break;

				case 'general' :

					$ret[] = $this->p->util->th( 'Website Topic', 'highlight', 'og_art_section' ).
					'<td>'.$this->form->get_select( 'og_art_section', $this->p->util->get_topics() ).'</td>';

					$ret[] = $this->p->util->th( 'Site Name', 'highlight', 'og_site_name' ).
					'<td>'.$this->form->get_input( 'og_site_name', null, null, null, get_bloginfo( 'name', 'display' ) ).'</td>';

					$ret[] = $this->p->util->th( 'Site Description', 'highlight', 'og_site_description' ).
					'<td>'.$this->form->get_input( 'og_site_description', 'wide', null, null, get_bloginfo( 'description', 'display' ) ).'</td>';

					$ret[] = $this->p->util->th( 'Title Separator', 'highlight', 'og_title_sep' ).
					'<td>'.$this->form->get_input( 'og_title_sep', 'short' ).'</td>';

					$ret[] = $this->p->util->th( 'Title Length', null, 'og_title_len' ).
					'<td>'.$this->form->get_input( 'og_title_len', 'short' ).' characters or less</td>';

					$ret[] = $this->p->util->th( 'Description Length', null, 'og_desc_len' ).
					'<td>'.$this->form->get_input( 'og_desc_len', 'short' ).' characters or less</td>';

					$ret[] = $this->p->util->th( 'Add Page Title in Tags', null, 'og_page_title_tag' ).
					'<td>'.$this->form->get_checkbox( 'og_page_title_tag' ).'</td>';
	
					$ret[] = $this->p->util->th( 'Add Page Ancestor Tags', null, 'og_page_parent_tags' ).
					'<td>'.$this->form->get_checkbox( 'og_page_parent_tags' ).'</td>';
	
					$ret[] = $this->p->util->th( 'Number of Hashtags to Include', 'highlight', 'og_desc_hashtags' ).
					'<td>'.$this->form->get_select( 'og_desc_hashtags', 
						range( 0, $this->p->cf['form']['max_desc_hashtags'] ), 'short', null, true ).' tag names</td>';
	
					$ret[] = $this->p->util->th( 'Content Begins at First Paragraph', null, 'og_desc_strip' ).
					'<td>'.$this->form->get_checkbox( 'og_desc_strip' ).'</td>';

					break;

				case 'author' :

					$ret[] = $this->p->util->th( 'Author Profile URL', null, 'og_author_field' ).
					'<td>'.$this->form->get_select( 'og_author_field', $this->author_fields() ).'</td>';

					$ret[] = $this->p->util->th( 'Fallback to Author Index', null, 'og_author_fallback' ).
					'<td>'.$this->form->get_checkbox( 'og_author_fallback' ).'</td>';
	
					$ret[] = $this->p->util->th( 'Default Author', null, 'og_def_author_id' ).
					'<td>'.$this->form->get_select( 'og_def_author_id', $user_ids, null, null, true ).'</td>';
	
					$ret[] = $this->p->util->th( 'Default Author on Indexes', null, 'og_def_author_on_index' ).
					'<td>'.$this->form->get_checkbox( 'og_def_author_on_index' ).' defines index webpages as articles</td>';
	
					$ret[] = $this->p->util->th( 'Default Author on Search Results', null, 'og_def_author_on_search' ).
					'<td>'.$this->form->get_checkbox( 'og_def_author_on_search' ).' defines search webpages as articles</td>';

					$ret[] = $this->p->util->th( 'Article Publisher Page URL', 'highlight', 'og_publisher_url' ).
					'<td>'.$this->form->get_input( 'og_publisher_url', 'wide' ).'</td>';

					break;

				case 'facebook' :

					$ret[] = $this->p->util->th( 'Facebook Admin(s)', 'highlight', 'fb_admins' ).
					'<td>'.$this->form->get_input( 'fb_admins' ).'</td>';

					$ret[] = $this->p->util->th( 'Facebook Application ID', null, 'fb_app_id' ).
					'<td>'.$this->form->get_input( 'fb_app_id' ).'</td>';

					$ret[] = $this->p->util->th( 'Default Language', null, 'fb_lang' ).
					'<td>'.$this->form->get_select( 'fb_lang', SucomUtil::get_lang( 'facebook' ) ).'</td>';

					break;

				case 'google' :
			
					$ret[] = $this->p->util->th( 'Description Length', null, 'google_desc_len' ).
					'<td>'.$this->form->get_input( 'meta_desc_len', 'short' ).' characters or less</td>';

					$ret[] = $this->p->util->th( 'Author Link URL', null, 'google_author_field' ).
					'<td>'.$this->form->get_select( 'link_author_field', $this->author_fields() ).'</td>';

					$ret[] = $this->p->util->th( 'Default Author', null, 'google_def_author_id' ).
					'<td>'.$this->form->get_select( 'link_def_author_id', $user_ids, null, null, true ).'</td>';

					$ret[] = $this->p->util->th( 'Default Author on Indexes', null, 'google_def_author_on_index' ).
					'<td>'.$this->form->get_checkbox( 'link_def_author_on_index' ).'</td>';

					$ret[] = $this->p->util->th( 'Default Author on Search Results', null, 'google_def_author_on_search' ).
					'<td>'.$this->form->get_checkbox( 'link_def_author_on_search' ).'</td>';
			
					$ret[] = $this->p->util->th( 'Publisher Link URL', 'highlight', 'google_publisher_url' ).
					'<td>'.$this->form->get_input( 'link_publisher_url', 'wide' ).'</td>';

					break;

				case 'twitter' :

					$ret = $this->get_rows_twitter();

					break;

			}
			return $ret;
		}

		protected function get_rows_twitter() {
			return array(
				'<td colspan="2" align="center">'.$this->p->msgs->get( 'pro-feature-msg' ).'</td>',

				$this->p->util->th( 'Enable Twitter Cards', 'highlight', 'tc_enable' ).
				'<td class="blank">'.$this->form->get_fake_checkbox( 'tc_enable' ).'</td>',

				$this->p->util->th( 'Maximum Description Length', null, 'tc_desc_len' ).
				'<td class="blank">'.$this->form->get_hidden( 'tc_desc_len' ).
					$this->p->options['tc_desc_len'].' characters or less</td>',

				$this->p->util->th( 'Website @username to Follow', 'highlight', 'tc_site' ).
				'<td class="blank">'.$this->form->get_hidden( 'tc_site' ).
					$this->p->options['tc_site'].'</td>',

				$this->p->util->th( '<em>Summary</em> Card Image Size', null, 'tc_sum_size' ).
				'<td class="blank">'.$this->form->get_hidden( 'tc_sum_size' ).
					$this->p->options['tc_sum_size'].'</td>',

				$this->p->util->th( '<em>Large Image Summary</em> Card Image Size', null, 'tc_large_size' ).
				'<td class="blank">'.$this->form->get_hidden( 'tc_large_size' ).
					$this->p->options['tc_large_size'].'</td>',

				$this->p->util->th( '<em>Photo</em> Card Image Size', 'highlight', 'tc_photo_size' ).
				'<td class="blank">'.$this->form->get_hidden( 'tc_photo_size' ).
					$this->p->options['tc_photo_size'].'</td>',

				$this->p->util->th( '<em>Gallery</em> Card Image Size', null, 'tc_gal_size' ).
				'<td class="blank">'.$this->form->get_hidden( 'tc_gal_size' ).
					$this->p->options['tc_gal_size'].'</td>',

				$this->p->util->th( '<em>Gallery</em> Card Minimum Images', null, 'tc_gal_min' ).
				'<td class="blank">'.$this->form->get_hidden( 'tc_gal_min' ).
					$this->p->options['tc_gal_min'].'</td>',

				$this->p->util->th( '<em>Product</em> Card Image Size', null, 'tc_prod_size' ).
				'<td class="blank">'.$this->form->get_hidden( 'tc_prod_size' ).
					$this->p->options['tc_prod_size'].'</td>',

				$this->p->util->th( '<em>Product</em> Card Default 2nd Attribute', null, 'tc_prod_def' ).
				'<td class="blank">'.
				$this->form->get_hidden( 'tc_prod_def_l2' ).'Label: '.$this->p->options['tc_prod_def_l2'].' &nbsp; '.
				$this->form->get_hidden( 'tc_prod_def_d2' ).'Value: '.$this->p->options['tc_prod_def_d2'].
				'</td>',

			);
		}

		private function author_fields() {
			return $this->p->user->add_contact_methods( 
				array( 'none' => '', 'author' => 'Author Index', 'url' => 'Website' ) 
			);
		}
	}
}

?>
