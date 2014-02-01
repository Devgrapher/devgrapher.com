<?php
/*
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl.txt
Copyright 2012-2014 - Jean-Sebastien Morisset - http://surniaulula.com/
*/

if ( ! defined( 'ABSPATH' ) ) 
	die( 'These aren\'t the droids you\'re looking for...' );

if ( ! class_exists( 'NgfbAdminSocialManagewp' ) && class_exists( 'NgfbAdminSocial' ) ) {

	class NgfbAdminSocialManagewp extends NgfbAdminSocial {

		public function __construct( &$plugin ) {
			$this->p =& $plugin;
			$this->p->debug->mark();
		}

		public function get_rows() {
			return array(
				$this->p->util->th( 'Show Button in', 'short' ) . '<td>' . 
				( $this->show_on_checkboxes( 'managewp', $this->p->cf['social']['show_on'] ) ).'</td>',

				$this->p->util->th( 'Preferred Order', 'short' ) . '<td>' . 
				$this->form->get_select( 'managewp_order', range( 1, count( $this->p->admin->submenu['social']->website ) ), 'short' ) . '</td>',

				$this->p->util->th( 'JavaScript in', 'short' ) . '<td>' . 
				$this->form->get_select( 'managewp_js_loc', $this->js_locations ) . '</td>',

				$this->p->util->th( 'Button Type', 'short' ) . '<td>' . 
				$this->form->get_select( 'managewp_counter', 
					array( 
						'small' => 'Small',
						'big' => 'Big',
					)
				) . '</td>',
			);
		}
	}
}

if ( ! class_exists( 'NgfbSocialManagewp' ) && class_exists( 'NgfbSocial' ) ) {

	class NgfbSocialManagewp {

		protected $p;

		public function __construct( &$plugin ) {
			$this->p =& $plugin;
			$this->p->debug->mark();
		}

		public function get_html( $atts = array(), $opts = array() ) {
			if ( empty( $opts ) ) 
				$opts =& $this->p->options;
			$use_post = empty( $atts['is_widget'] ) || is_singular() || is_admin() ? true : false;
			$source_id = $this->p->util->get_source_id( 'managewp', $atts );
			$atts['add_page'] = array_key_exists( 'add_page', $atts ) ? $atts['add_page'] : true;
			$atts['url'] = empty( $atts['url'] ) ? 
				$this->p->util->get_sharing_url( $use_post, $atts['add_page'], $source_id ) : 
				apply_filters( $this->p->cf['lca'].'_sharing_url', $atts['url'], 
					$use_post, $atts['add_page'], $source_id );
			$js_url = $this->p->util->get_cache_url( 'http://managewp.org/share.js' ).'#http://managewp.org/share';

			if ( empty( $atts['title'] ) ) 
				$atts['title'] = $this->p->webpage->get_title( null, null, $use_post);

			$html = '<!-- ManageWP Button --><div '.$this->p->social->get_css( 'managewp', $atts ).'>';
			$html .= '<script src="'.$js_url.'"';
			$html .= ' data-url="'.$atts['url'].'"';
			$html .= ' data-title="'.$atts['title'].'"';

			if ( ! empty( $opts['managewp_type'] ) ) 
				$html .= ' data-type="'.$opts['managewp_type'].'"';

			$html .= '></script></div>';
			$this->p->debug->log( 'returning html ('.strlen( $html ).' chars)' );
			return $html;
		}
	}
}
?>
