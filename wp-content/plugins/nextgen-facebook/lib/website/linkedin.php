<?php
/*
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl.txt
Copyright 2012-2014 - Jean-Sebastien Morisset - http://surniaulula.com/
*/

if ( ! defined( 'ABSPATH' ) ) 
	die( 'These aren\'t the droids you\'re looking for...' );

if ( ! class_exists( 'NgfbAdminSocialLinkedin' ) && class_exists( 'NgfbAdminSocial' ) ) {

	class NgfbAdminSocialLinkedin extends NgfbAdminSocial {

		public function __construct( &$plugin ) {
			$this->p =& $plugin;
			$this->p->debug->mark();
		}

		public function get_rows() {
			return array(
				$this->p->util->th( 'Show Button in', 'short' ) . '<td>' . 
				( $this->show_on_checkboxes( 'linkedin', $this->p->cf['social']['show_on'] ) ).'</td>',

				$this->p->util->th( 'Preferred Order', 'short' ) . '<td>' . 
				$this->form->get_select( 'linkedin_order', range( 1, count( $this->p->admin->submenu['social']->website ) ), 'short' ) . '</td>',

				$this->p->util->th( 'JavaScript in', 'short' ) . '<td>' . 
				$this->form->get_select( 'linkedin_js_loc', $this->js_locations ) . '</td>',

				$this->p->util->th( 'Counter Mode', 'short' ) . '<td>' . 
				$this->form->get_select( 'linkedin_counter', 
					array( 
						'none' => '',
						'right' => 'Horizontal',
						'top' => 'Vertical',
					)
				) . '</td>',

				$this->p->util->th( 'Zero in Counter', 'short' ) . '<td>' . 
				$this->form->get_checkbox( 'linkedin_showzero' ) . '</td>',
			);
		}
	}
}

if ( ! class_exists( 'NgfbSocialLinkedin' ) && class_exists( 'NgfbSocial' ) ) {

	class NgfbSocialLinkedin {

		protected $p;

		public function __construct( &$plugin ) {
			$this->p =& $plugin;
			$this->p->debug->mark();
		}

		public function get_html( $atts = array(), $opts = array() ) {
			if ( empty( $opts ) ) 
				$opts =& $this->p->options;
			$use_post = empty( $atts['is_widget'] ) || is_singular() || is_admin() ? true : false;
			$source_id = $this->p->util->get_source_id( 'linkedin', $atts );
			$atts['add_page'] = array_key_exists( 'add_page', $atts ) ? $atts['add_page'] : true;
			$atts['url'] = empty( $atts['url'] ) ? 
				$this->p->util->get_sharing_url( $use_post, $atts['add_page'], $source_id ) : 
				apply_filters( $this->p->cf['lca'].'_sharing_url', $atts['url'],
					$use_post, $atts['add_page'], $source_id );
			$html = '<!-- LinkedIn Button --><div '.$this->p->social->get_css( 'linkedin', $atts ).'><script type="IN/Share" data-url="'.$atts['url'].'"';

			if ( ! empty( $opts['linkedin_counter'] ) ) 
				$html .= ' data-counter="'.$opts['linkedin_counter'].'"';

			if ( ! empty( $opts['linkedin_showzero'] ) ) 
				$html .= ' data-showzero="true"';

			$html .= '></script></div>';
			$this->p->debug->log( 'returning html ('.strlen( $html ).' chars)' );
			return $html;
		}
		
		public function get_js( $pos = 'id' ) {
			$this->p->debug->mark();
			$prot = empty( $_SERVER['HTTPS'] ) ? 'http:' : 'https:';
			$js_url = $this->p->util->get_cache_url( $prot.'//platform.linkedin.com/in.js' );

			return  '<script type="text/javascript" id="linkedin-script-'.$pos.'">'.$this->p->cf['lca'].'_insert_js( "linkedin-script-'.$pos.'", "'.$js_url.'" );</script>';
		}
	}
}
?>
