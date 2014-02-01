<?php
/*
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl.txt
Copyright 2012-2014 - Jean-Sebastien Morisset - http://surniaulula.com/
*/

if ( ! defined( 'ABSPATH' ) ) 
	die( 'These aren\'t the droids you\'re looking for...' );

if ( ! class_exists( 'NgfbAdminSocialPinterest' ) && class_exists( 'NgfbAdminSocial' ) ) {

	class NgfbAdminSocialPinterest extends NgfbAdminSocial {

		public function __construct( &$plugin ) {
			$this->p =& $plugin;
			$this->p->debug->mark();
		}

		public function get_rows() {
			return array(
				$this->p->util->th( 'Show Button in', 'short highlight', null,
				'The Pinterest "Pin It" button will only appear on Posts and Pages with a <em>custom image ID</em>, 
				a <em>featured</em> image, or an <em>attached</em> image, with an image dimension that is equal to 
				or larger than the \'Image Size to Share\' you have selected.' ).'<td>'.
				( $this->show_on_checkboxes( 'pin', $this->p->cf['social']['show_on'] ) ).'</td>',

				$this->p->util->th( 'Preferred Order', 'short' ).'<td>'.
				$this->form->get_select( 'pin_order', range( 1, count( $this->p->admin->submenu['social']->website ) ), 'short' ).'</td>',

				$this->p->util->th( 'JavaScript in', 'short' ).'<td>'.
				$this->form->get_select( 'pin_js_loc', $this->js_locations ).'</td>',

				$this->p->util->th( 'Pin Count Layout', 'short' ).'<td>'.
				$this->form->get_select( 'pin_count_layout', 
					array( 
						'none' => '',
						'horizontal' => 'Horizontal',
						'vertical' => 'Vertical',
					)
				).'</td>',

				$this->p->util->th( 'Pin Button Image', 'short' ).'<td>'.
				$this->form->get_input( 'pin_img_url' ),

				$this->p->util->th( 'Image Size to Share', 'short' ).'<td>'.
				$this->form->get_select_img_size( 'pin_img_size', '/^'.$this->p->cf['lca'].'-/' ).'</td>',

				$this->p->util->th( 'Image Caption Text', 'short' ).'<td>'.
				$this->form->get_select( 'pin_caption', $this->captions ).'</td>',

				$this->p->util->th( 'Caption Length', 'short' ).'<td>'.
				$this->form->get_input( 'pin_cap_len', 'short' ).' Characters or less</td>',

			);
		}
	}
}

if ( ! class_exists( 'NgfbSocialPinterest' ) && class_exists( 'NgfbSocial' ) ) {

	class NgfbSocialPinterest {

		protected $p;

		public function __construct( &$plugin ) {
			$this->p =& $plugin;
			$this->p->debug->mark();
		}

		public function get_html( $atts = array(), $opts = array() ) {
			if ( empty( $opts ) ) 
				$opts =& $this->p->options;
			global $post; 
			$prot = empty( $_SERVER['HTTPS'] ) ? 'http:' : 'https:';
			$use_post = empty( $atts['is_widget'] ) || is_singular() || is_admin() ? true : false;
			$source_id = $this->p->util->get_source_id( 'pinterest', $atts );
			$atts['add_page'] = array_key_exists( 'add_page', $atts ) ? $atts['add_page'] : true;
			$atts['url'] = empty( $atts['url'] ) ? 
				$this->p->util->get_sharing_url( $use_post, $atts['add_page'], $source_id ) : 
				apply_filters( $this->p->cf['lca'].'_sharing_url', $atts['url'], 
					$use_post, $atts['add_page'], $source_id );
			if ( empty( $atts['size'] ) ) $atts['size'] = $opts['pin_img_size'];
			if ( empty( $atts['photo'] ) ) {
				if ( empty( $atts['pid'] ) ) {
					// allow on index pages only if in content (not a widget)
					if ( ! empty( $post ) && $use_post == true ) {
						$pid = $this->p->meta->get_options( $post->ID, 'og_img_id' );
						$pre = $this->p->meta->get_options( $post->ID, 'og_img_id_pre' );
						if ( ! empty( $pid ) ) 
							$atts['pid'] = $pre == 'ngg' ? 'ngg-'.$pid : $pid;
						elseif ( $this->p->is_avail['postthumb'] == true && has_post_thumbnail( $post->ID ) )
							$atts['pid'] = get_post_thumbnail_id( $post->ID );
						else $atts['pid'] = $this->p->media->get_first_attached_image_id( $post->ID );
					}
				}
				if ( ! empty( $atts['pid'] ) )
					list( $atts['photo'], $atts['width'], $atts['height'],
						$atts['cropped'] ) = $this->p->media->get_attachment_image_src( $atts['pid'], $atts['size'], false );
			}
			if ( empty( $atts['photo'] ) ) 
				return;

			if ( empty( $atts['pin_count_layout'] ) ) 
				$atts['pin_count_layout'] = $opts['pin_count_layout'];

			if ( empty( $atts['caption'] ) && ! empty( $post ) && $use_post == true ) 
				$atts['caption'] = $this->p->meta->get_options( $post->ID, 'pin_desc' );

			if ( empty( $atts['caption'] ) ) 
				$atts['caption'] = $this->p->webpage->get_caption( $opts['pin_caption'], 
					$opts['pin_cap_len'], $use_post );

			$query = 'url='.urlencode( $atts['url'] );
			$query .= '&amp;media='.urlencode( $atts['photo'] );
			$query .= '&amp;description='.urlencode( $atts['caption'] );

			if ( empty( $this->p->options['pin_img_url'] ) )
				$img = $prot.'//assets.pinterest.com/images/PinExt.png';
			elseif ( preg_match( '/^https?:(\/\/.*)/', $this->p->options['pin_img_url'], $match ) )
				$img = $prot.$match[1];
			else $img = $this->p->options['pin_img_url'];
			$img = $this->p->util->get_cache_url( $img );

			$html = '<!-- Pinterest Button --><div '.$this->p->social->get_css( 'pinterest', $atts ).'><a href="'.$prot.'//pinterest.com/pin/create/button/?'.$query.'" class="pin-it-button" count-layout="'.$atts['pin_count_layout'].'" title="Share on Pinterest"><img border="0" alt="Pin It" src="'.$img.'" /></a></div>';
			$this->p->debug->log( 'returning html ('.strlen( $html ).' chars)' );
			return $html;
		}

		public function get_js( $pos = 'id' ) {
			$this->p->debug->mark();
			$prot = empty( $_SERVER['HTTPS'] ) ? 'http:' : 'https:';
			$js_url = $this->p->util->get_cache_url( $prot.'//assets.pinterest.com/js/pinit.js' );

			return '<script type="text/javascript" id="pinterest-script-'.$pos.'">'.$this->p->cf['lca'].'_insert_js( "pinterest-script-'.$pos.'", "'.$js_url.'" );</script>';
		}
	}
}
?>
