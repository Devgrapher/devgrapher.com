<?php
/*
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl.txt
Copyright 2012-2014 - Jean-Sebastien Morisset - http://surniaulula.com/
*/

if ( ! defined( 'ABSPATH' ) ) 
	die( 'These aren\'t the droids you\'re looking for...' );

if ( ! class_exists( 'NgfbAdminAdvanced' ) && class_exists( 'NgfbAdmin' ) ) {

	class NgfbAdminAdvanced extends NgfbAdmin {

		// executed by NgfbAdminAdvancedPro() as well
		public function __construct( &$plugin, $id, $name ) {
			$this->p =& $plugin;
			$this->p->debug->mark();
			$this->menu_id = $id;
			$this->menu_name = $name;
		}

		protected function add_meta_boxes() {
			// add_meta_box( $id, $title, $callback, $post_type, $context, $priority, $callback_args );
			add_meta_box( $this->pagehook.'_plugin', 'Plugin Settings', array( &$this, 'show_metabox_plugin' ), $this->pagehook, 'normal' );
			add_meta_box( $this->pagehook.'_contact', 'Profile Contact Methods', array( &$this, 'show_metabox_contact' ), $this->pagehook, 'normal' );
			add_meta_box( $this->pagehook.'_taglist', 'Meta Tag List', array( &$this, 'show_metabox_taglist' ), $this->pagehook, 'normal' );
		}

		public function show_metabox_plugin() {
			$show_tabs = array( 
				'activation' => 'Activate and Update',
				'content' => 'Content and Filters',
				'cache' => 'File and Object Cache',
				'rewrite' => 'URL Rewrite',
				'apikeys' => 'API Keys',
			);

			// show only if the social sharing button features are enabled
			if ( empty( $this->p->is_avail['ssb'] ) ) {
				unset( $show_tabs['rewrite'] );
				unset( $show_tabs['apikeys'] );
			}

			$tab_rows = array();
			foreach ( $show_tabs as $key => $title )
				$tab_rows[$key] = $this->get_rows( $key );
			$this->p->util->do_tabs( 'plugin', $show_tabs, $tab_rows );
		}

		public function show_metabox_contact() {
			echo '<table class="sucom-setting" style="padding-bottom:0"><tr><td>'.
			$this->p->msgs->get( 'contact-info' ).'</td></tr></table>';
			$show_tabs = array( 
				'custom' => 'Custom Contacts',
				'builtin' => 'Built-In Contacts',
			);
			$tab_rows = array();
			foreach ( $show_tabs as $key => $title )
				$tab_rows[$key] = $this->get_rows( $key );
			$this->p->util->do_tabs( 'cm', $show_tabs, $tab_rows );
		}

		public function show_metabox_taglist() {
			echo '<table class="sucom-setting" style="padding-bottom:0;"><tr><td>'.
			$this->p->msgs->get( 'taglist-info' ).'</td></tr></table>';

			echo '<table class="sucom-setting" style="padding-bottom:0;">';
			foreach ( $this->get_more_taglist() as $num => $row ) 
				echo '<tr>', $row, '</tr>';
			unset( $num, $row );
			echo '</table>';

			echo '<table class="sucom-setting"><tr>';
			echo $this->p->util->th( 'Include Empty og:* Meta Tags', null, 'og_empty_tags' );
			echo '<td'.( $this->p->check->is_aop() ? '>'.$this->form->get_checkbox( 'og_empty_tags' ) :
			' class="blank checkbox">'.$this->form->get_fake_checkbox( 'og_empty_tags' ) ).'</td>';
			echo '<td width="100%"></td></tr></table>';

		}

		protected function get_rows( $id ) {
			$ret = array();
			switch ( $id ) {

				case 'custom' :

					if ( ! $this->p->check->is_aop() )
						$ret[] = '<td colspan="4" align="center">'.$this->p->msgs->get( 'pro-feature-msg' ).'</td>';

					$ret[] = '<td></td>'.
					$this->p->util->th( 'Show', 'left checkbox' ).
					$this->p->util->th( 'Contact Field Name', 'left medium', 'custom-cm-field-name' ).
					$this->p->util->th( 'Profile Contact Label', 'left wide' );

					$sorted_opt_pre = $this->p->cf['opt']['pre'];
					ksort( $sorted_opt_pre );
					foreach ( $sorted_opt_pre as $id => $pre ) {
						$cm_opt = 'plugin_cm_'.$pre.'_';

						// check for the lib website classname for a nice 'display name'
						$name = empty( $this->p->cf['lib']['website'][$id] ) ? 
							ucfirst( $id ) : $this->p->cf['lib']['website'][$id];
						$name = $name == 'GooglePlus' ? 'Google+' : $name;

						// not all social websites have a contact method field
						if ( array_key_exists( $cm_opt.'enabled', $this->p->options ) ) {
							if ( $this->p->check->is_aop() ) {
								$ret[] = $this->p->util->th( $name, 'medium' ).
								'<td class="checkbox">'.$this->form->get_checkbox( $cm_opt.'enabled' ).'</td>'.
								'<td>'.$this->form->get_input( $cm_opt.'name', 'medium' ).'</td>'.
								'<td>'.$this->form->get_input( $cm_opt.'label' ).'</td>';
							} else {
								$ret[] = $this->p->util->th( $name, 'medium' ).
								'<td class="blank checkbox">'.$this->form->get_fake_checkbox( $cm_opt.'enabled' ).'</td>'.
								'<td class="blank medium">'.$this->form->get_hidden( $cm_opt.'name' ).
								$this->p->options[$cm_opt.'name'].'</td>'.
								'<td class="blank">'.$this->form->get_hidden( $cm_opt.'label' ).
								$this->p->options[$cm_opt.'label'].'</td>';
							}
						}
					
					}
					break;

				case 'builtin' :

					if ( ! $this->p->check->is_aop() )
						$ret[] = '<td colspan="4" align="center">'.$this->p->msgs->get( 'pro-feature-msg' ).'</td>';

					$ret[] = '<td></td>'.
					$this->p->util->th( 'Show', 'left checkbox' ).
					$this->p->util->th( 'Contact Field Name', 'left medium', 'wp-cm-field-name' ).
					$this->p->util->th( 'Profile Contact Label', 'left wide' );

					$sorted_wp_contact = $this->p->cf['wp']['cm'];
					ksort( $sorted_wp_contact );
					foreach ( $sorted_wp_contact as $id => $name ) {
						$cm_opt = 'wp_cm_'.$id.'_';
						if ( array_key_exists( $cm_opt.'enabled', $this->p->options ) ) {
							if ( $this->p->check->is_aop() ) {
								$ret[] = $this->p->util->th( $name, 'medium' ).
								'<td class="checkbox">'.$this->form->get_checkbox( $cm_opt.'enabled' ).'</td>'.
								'<td>'.$this->form->get_fake_input( $cm_opt.'name', 'medium' ).'</td>'.
								'<td>'.$this->form->get_input( $cm_opt.'label' ).'</td>';
							} else {
								$ret[] = $this->p->util->th( $name, 'medium' ).
								'<td class="blank checkbox">'.$this->form->get_hidden( $cm_opt.'enabled' ).
									$this->form->get_fake_checkbox( $cm_opt.'enabled' ).'</td>'.
								'<td>'.$this->form->get_fake_input( $cm_opt.'name', 'medium' ).'</td>'.
								'<td class="blank">'.$this->form->get_hidden( $cm_opt.'label' ).
									$this->p->options[$cm_opt.'label'].'</td>';
							}
						}
					}
					break;

				case 'activation':

					if ( is_multisite() && ! empty( $this->p->site_options['plugin_tid:use'] ) && $this->p->site_options['plugin_tid:use'] == 'force' )
						$input = $this->form->get_fake_input( 'plugin_tid', 'mono' );
					else $input = $this->form->get_input( 'plugin_tid', 'mono' );

					$ret[] = $this->p->util->th( 'Pro Version Authentication ID', 'highlight', 'plugin_tid' ).'<td>'.$input.'</td>';

					if ( $this->p->is_avail['aop'] )
						$ret[] = '<th></th><td>'.$this->p->msgs->get( 'auth-id-info' ).'</td>';

					$ret[] = $this->p->util->th( 'Preserve Settings on Uninstall', 'highlight', 'plugin_preserve' ).
					'<td>'.$this->form->get_checkbox( 'plugin_preserve' ).'</td>';

					$ret[] = $this->p->util->th( 'Add Hidden Debug Info', null, 'plugin_debug' ).
					'<td>'.$this->form->get_checkbox( 'plugin_debug' ).'</td>';

					break;

				case 'content':

					$ret[] = $this->p->util->th( 'Apply Content Filters', null, 'plugin_filter_content' ).
					'<td>'.$this->form->get_checkbox( 'plugin_filter_content' ).'</td>';

					$ret[] = $this->p->util->th( 'Apply Excerpt Filters', null, 'plugin_filter_excerpt' ).
					'<td>'.$this->form->get_checkbox( 'plugin_filter_excerpt' ).'</td>';

					if ( $this->p->is_avail['ssb'] )
						$ret[] = $this->p->util->th( 'Enable Shortcode(s)', 'highlight', 'plugin_shortcode_ngfb' ).
						'<td>'.$this->form->get_checkbox( 'plugin_shortcode_ngfb' ).'</td>';

					$ret[] =  $this->p->util->th( 'Ignore Small Images', null, 'plugin_ignore_small_img' ).
					'<td>'.$this->form->get_checkbox( 'plugin_ignore_small_img' ).'</td>';

					$ret = array_merge( $ret, $this->get_more_content() );

					break;

				case 'cache':

					$ret[] = $this->p->util->th( 'Object Cache Expiry', null, 'plugin_object_cache_exp' ).
					'<td nowrap>'.$this->form->get_input( 'plugin_object_cache_exp', 'short' ).' seconds</td>';

					if ( $this->p->is_avail['ssb'] )
						$ret = array_merge( $ret, $this->get_more_cache() );

					break;

				case 'apikeys':

					$ret = array_merge( $ret, $this->get_more_apikeys() );

					break;

				case 'rewrite':

					$ret = array_merge( $ret, $this->get_more_rewrite() );

					break;
			}
			return $ret;
		}

		protected function get_more_content() {
			$add_to_checkboxes = '';
			foreach ( $this->p->util->get_post_types( 'plugin' ) as $post_type )
				$add_to_checkboxes .= '<p>'.$this->form->get_fake_checkbox( 'plugin_add_to_'.$post_type->name ).' '.
					$post_type->label.' '.( empty( $post_type->description ) ? '' : '('.$post_type->description.')' ).'</p>';

			return array(
				'<td colspan="2" align="center">'.$this->p->msgs->get( 'pro-feature-msg' ).'</td>',

				$this->p->util->th( 'Check for Wistia Videos', null, 'plugin_wistia_api' ).
				'<td class="blank">'.$this->form->get_fake_checkbox( 'plugin_wistia_api' ).'</td>',

				$this->p->util->th( 'Show Custom Settings on', null, 'plugin_add_to' ).
				'<td class="blank">'.$add_to_checkboxes.'</td>',
			);
		}

		protected function get_more_taglist() {
			$og_cols = 4;
			$cells = array();
			$rows = array();
			foreach ( $this->p->opt->get_defaults() as $opt => $val ) {
				if ( preg_match( '/^inc_(.*)$/', $opt, $match ) ) {
					$cells[] = '<td class="taglist blank checkbox">'.
					$this->form->get_fake_checkbox( $opt ).'</td>'.
					'<th class="taglist">'.$match[1].'</th>'."\n";
				}
			}
			$per_col = ceil( count( $cells ) / $og_cols );
			foreach ( $cells as $num => $cell ) {
				if ( empty( $rows[ $num % $per_col ] ) )
					$rows[ $num % $per_col ] = '';	// initialize the array
				$rows[ $num % $per_col ] .= $cell;	// create the html for each row
			}
			return array_merge( array( '<td colspan="'.($og_cols * 2).'" align="center">'.$this->p->msgs->get( 'pro-feature-msg' ).'</td>' ), $rows );
		}

		protected function get_more_cache() {
			return array(
				'<td colspan="2" align="center">'.$this->p->msgs->get( 'pro-feature-msg' ).'</td>',

				$this->p->util->th( 'Social File Cache Expiry', 'highlight', 'plugin_file_cache_hrs' ).
				'<td class="blank">'.$this->form->get_hidden( 'plugin_file_cache_hrs' ). 
				$this->p->options['plugin_file_cache_hrs'].' hours</td>',

				$this->p->util->th( 'Verify SSL Certificates', null, 'plugin_verify_certs' ).
				'<td class="blank">'.$this->form->get_fake_checkbox( 'plugin_verify_certs' ).'</td>',
			);
		}

		protected function get_more_apikeys() {
			return array(
				'<td colspan="2" align="center">'.$this->p->msgs->get( 'pro-feature-msg' ).'</td>',

				$this->p->util->th( 'Bit.ly Username', null, 'plugin_bitly_login' ).
				'<td class="blank mono">'.$this->form->get_hidden( 'plugin_bitly_login' ).
				$this->p->options['plugin_bitly_login'].'</td>',

				$this->p->util->th( 'Bit.ly API Key', null, 'plugin_bitly_api_key' ).
				'<td class="blank mono">'.$this->form->get_hidden( 'plugin_bitly_api_key' ).
				$this->p->options['plugin_bitly_api_key'].'</td>',

				$this->p->util->th( 'Google Project Application BrowserKey', null, 'plugin_google_api_key' ).
				'<td class="blank mono">'.$this->form->get_hidden( 'plugin_google_api_key' ).
				$this->p->options['plugin_google_api_key'].'</td>',

				$this->p->util->th( 'Google URL Shortener API is ON', null, 'plugin_google_shorten' ).
				'<td class="blank">'.$this->form->get_fake_radio( 'plugin_google_shorten', 
					array( '1' => 'Yes', '0' => 'No' ), null, null, true ).'</td>',
			);
		}

		protected function get_more_rewrite() {
			return array(
				'<td colspan="2" align="center">'.$this->p->msgs->get( 'pro-feature-msg' ).'</td>',

				$this->p->util->th( 'URL Length to Shorten', null, 'plugin_min_shorten' ). 
				'<td class="blank">'.$this->form->get_hidden( 'plugin_min_shorten' ).
					$this->p->options['plugin_min_shorten'].' characters</td>',

				$this->p->util->th( 'Static Content URL(s)', 'highlight', 'plugin_cdn_urls' ). 
				'<td class="blank">'.$this->form->get_hidden( 'plugin_cdn_urls' ). 
					$this->p->options['plugin_cdn_urls'].'</td>',

				$this->p->util->th( 'Include Folders', null, null, 'plugin_cdn_folders' ).
				'<td class="blank">'.$this->form->get_hidden( 'plugin_cdn_folders' ). 
					$this->p->options['plugin_cdn_folders'].'</td>',

				$this->p->util->th( 'Exclude Patterns', null, 'plugin_cdn_excl' ).
				'<td class="blank">'.$this->form->get_hidden( 'plugin_cdn_excl' ).
					$this->p->options['plugin_cdn_excl'].'</td>',

				$this->p->util->th( 'Not when Using HTTPS', null, 'plugin_cdn_not_https' ).
				'<td class="blank">'.$this->form->get_fake_checkbox( 'plugin_cdn_not_https' ).'</td>',

				$this->p->util->th( 'www is Optional', null, 'plugin_cdn_www_opt' ). 
				'<td class="blank">'.$this->form->get_fake_checkbox( 'plugin_cdn_www_opt' ).'</td>',
			);
		}
	}
}

?>
