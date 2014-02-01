<?php
/*
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl.txt
Copyright 2012-2014 - Jean-Sebastien Morisset - http://surniaulula.com/
*/

if ( ! defined( 'ABSPATH' ) )
	die( 'These aren\'t the droids you\'re looking for...' );

if ( ! class_exists( 'NgfbAdminSocialFacebook' ) && class_exists( 'NgfbAdminSocial' ) ) {

	class NgfbAdminSocialFacebook extends NgfbAdminSocial {

		public function __construct( &$plugin ) {
			$this->p =& $plugin;
			$this->p->debug->mark();
		}

		public function show_metabox_website() {
			$show_tabs = array( 
				'all' => 'All Buttons',
				'like' => 'Like and Send',
				'share' => 'Share (Deprecated)',
			);
			$tab_rows = array();
			foreach ( $show_tabs as $key => $title )
				$tab_rows[$key] = $this->get_rows( $key );
			$this->p->util->do_tabs( 'fb', $show_tabs, $tab_rows );
		}

		public function get_rows( $id ) {
			$ret = array();
			switch ( $id ) {

				case 'all' :

					$ret[] = $this->p->util->th( 'Show Button in', 'short' ).'<td>'.
					( $this->show_on_checkboxes( 'fb', $this->p->cf['social']['show_on'] ) ).'</td>';

					$ret[] = $this->p->util->th( 'Preferred Order', 'short' ).'<td>'.
					$this->form->get_select( 'fb_order', range( 1, count( $this->p->admin->submenu['social']->website ) ), 'short' ).'</td>';
	
					$ret[] = $this->p->util->th( 'JavaScript in', 'short' ).'<td>'.
					$this->form->get_select( 'fb_js_loc', $this->js_locations ).'</td>';
	
					$ret[] = $this->p->util->th( 'Default Language', 'short' ).'<td>'.
					$this->form->get_select( 'fb_lang', SucomUtil::get_lang( 'facebook' ) ).'</td>';
	
					$ret[] = $this->p->util->th( 'Button Type', 'short highlight', null,
					'The Share button has been deprecated and replaced by the Facebook Like and Send buttons. 
					It is still available and functional, but no longer supported. The Share button offers the 
					additional option of posting to a Facebook Page.' ).
					'<td>'.$this->form->get_select( 'fb_button', 
						array(
							'like' => 'Like and Send',
							'share' => 'Share (deprecated)',
						) 
					).'</td>';

					break;

				case 'like' :

					$ret[] = $this->p->util->th( 'Markup Language', 'short' ).
					'<td>'.$this->form->get_select( 'fb_markup', 
						array( 
							'html5' => 'HTML5', 
							'xfbml' => 'XFBML',
						) 
					).'</td>';
	
					$ret[] = $this->p->util->th( 'Include Send', 'short', null, 
					'The Send button is only available in combination with the XFBML <em>Markup Language</em>.' ).
					'<td>'.$this->form->get_checkbox( 'fb_send' ).'</td>';
	
					$ret[] = $this->p->util->th( 'Layout', 'short', null, 
					'The Standard layout displays social text to the right of the button, and friends\' 
					profile photos below (if <em>Show Faces</em> is also checked). The Button Count layout 
					displays the total number of likes to the right of the button, and the Box Count layout 
					displays the total number of likes above the button.' ).
					'<td>'.$this->form->get_select( 'fb_layout', 
						array(
							'standard' => 'Standard',
							'button_count' => 'Button Count',
							'box_count' => 'Box Count',
						) 
					).'</td>';
	
					$ret[] = $this->p->util->th( 'Show Faces', 'short', null, 
					'Show profile photos below the Standard button (Standard button <em>Layout</em> only).' ).
					'<td>'.$this->form->get_checkbox( 'fb_show_faces' ).'</td>';
	
					$ret[] = $this->p->util->th( 'Font', 'short' ).'<td>'.
					$this->form->get_select( 'fb_font', 
						array( 
							'arial' => 'Arial',
							'lucida grande' => 'Lucida Grande',
							'segoe ui' => 'Segoe UI',
							'tahoma' => 'Tahoma',
							'trebuchet ms' => 'Trebuchet MS',
							'verdana' => 'Verdana',
						) 
					).'</td>';
	
					$ret[] = $this->p->util->th( 'Color Scheme', 'short' ).'<td>'.
					$this->form->get_select( 'fb_colorscheme', 
						array( 
							'light' => 'Light',
							'dark' => 'Dark',
						)
					).'</td>';
	
					$ret[] = $this->p->util->th( 'Action Name', 'short' ).'<td>'.
					$this->form->get_select( 'fb_action', 
						array( 
							'like' => 'Like',
							'recommend' => 'Recommend',
						)
					).'</td>';
	
					break;
	
				case 'share' :

					$ret[] = $this->p->util->th( 'Layout', 'short' ).'<td>'.
					$this->form->get_select( 'fb_type', 
						array(
							'button' => 'Button',
							'button_count' => 'Button Count',
							'box_count' => 'Box Count',
							'icon' => 'Small Icon',
							'link' => 'Text Link',
						) 
					).'</td>';

					break;

			}
			return $ret;
		}
	}
}

if ( ! class_exists( 'NgfbSocialFacebook' ) && class_exists( 'NgfbSocial' ) ) {

	class NgfbSocialFacebook {

		protected $p;

		public function __construct( &$plugin ) {
			$this->p =& $plugin;
			$this->p->debug->mark();
		}

		public function get_html( $atts = array(), $opts = array() ) {
			if ( empty( $opts ) ) 
				$opts =& $this->p->options;
			$html = '';
			$use_post = empty( $atts['is_widget'] ) || is_singular() || is_admin() ? true : false;
			$lang = empty( $opts['fb_lang'] ) ? 'en_US' : $opts['fb_lang'];
			$lang = apply_filters( $this->p->cf['lca'].'_lang', $lang, SucomUtil::get_lang( 'facebook' ) );
			$send = $opts['fb_send'] ? 'true' : 'false';
			$show_faces = $opts['fb_show_faces'] ? 'true' : 'false';

			$source_id = 'facebook';
			switch ( $opts['fb_button'] ) {
				case 'like' : $source_id = $this->p->util->get_source_id( 'facebook', $atts ); break;
				case 'share' : $source_id = $this->p->util->get_source_id( 'fb-share', $atts ); break;
			}
			$atts['add_page'] = array_key_exists( 'add_page', $atts ) ? $atts['add_page'] : true;
			$atts['url'] = empty( $atts['url'] ) ? 
				$this->p->util->get_sharing_url( $use_post, $atts['add_page'], $source_id ) : 
				apply_filters( $this->p->cf['lca'].'_sharing_url', $atts['url'], 
					$use_post, $atts['add_page'], $source_id );

			switch ( $opts['fb_button'] ) {
				case 'like' :
					switch ( $opts['fb_markup'] ) {
						case 'xfbml' :
							// XFBML
							$html .= '<!-- Facebook Like / Send Button(s) --><div '.
							$this->p->social->get_css( 'facebook', $atts, 'fb-like' ).'><fb:like href="'.
							$atts['url'].'" send="'.$send.'" layout="'.$opts['fb_layout'].'" show_faces="'.
							$show_faces.'" font="'.$opts['fb_font'].'" action="'.
							$opts['fb_action'].'" colorscheme="'.$opts['fb_colorscheme'].'"></fb:like></div>';
							break;
						case 'html5' :
							// HTML5
							$html .= '<!-- Facebook Like / Send Button(s) --><div '.
							$this->p->social->get_css( 'facebook', $atts, 'fb-like' ).' data-href="'.
							$atts['url'].'" data-send="'.$send.'" data-layout="'.
							$opts['fb_layout'].'" data-show-faces="'.$show_faces.'" data-font="'.
							$opts['fb_font'].'" data-action="'.$opts['fb_action'].'" data-colorscheme="'.
							$opts['fb_colorscheme'].'"></div>';
							break;
					}
					break;
				case 'share' :
					$html .= '<!-- Facebook Share Button --><div '.
					$this->p->social->get_css( 'fb-share', $atts, 'fb-share' ).'><fb:share-button href="'.
					$atts['url'].'" font="'.$opts['fb_font'].'" type="'.$opts['fb_type'].'"></fb:share-button></div>';
					break;
			}
			$this->p->debug->log( 'returning html ('.strlen( $html ).' chars)' );
			return $html;
		}
		
		public function get_js( $pos = 'id' ) {
			$this->p->debug->mark();
			$prot = empty( $_SERVER['HTTPS'] ) ? 'http:' : 'https:';
			$lang = empty( $this->p->options['fb_lang'] ) ? 'en_US' : $this->p->options['fb_lang'];
			$lang = apply_filters( $this->p->cf['lca'].'_lang', $lang, SucomUtil::get_lang( 'facebook' ) );
			$app_id = empty( $this->p->options['fb_app_id'] ) ? '' : $this->p->options['fb_app_id'];
			$js_url = $this->p->util->get_cache_url( $prot.'//connect.facebook.net/'.$lang.'/all.js#xfbml=1&appId='.$app_id );

			$html = '<script type="text/javascript" id="fb-script-'.$pos.'">'.
			$this->p->cf['lca'].'_insert_js( "fb-script-'.$pos.'", "'.$js_url.'" );</script>';
			return $html;
		}
	}
}
?>
