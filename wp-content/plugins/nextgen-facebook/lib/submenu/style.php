<?php
/*
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl.txt
Copyright 2012-2014 - Jean-Sebastien Morisset - http://surniaulula.com/
*/

if ( ! defined( 'ABSPATH' ) ) 
	die( 'These aren\'t the droids you\'re looking for...' );

if ( ! class_exists( 'NgfbAdminStyle' ) && class_exists( 'NgfbAdmin' ) ) {

	class NgfbAdminStyle extends NgfbAdmin {

		public function __construct( &$plugin, $id, $name ) {
			$this->p =& $plugin;
			$this->p->debug->mark();
			$this->menu_id = $id;
			$this->menu_name = $name;
		}

		protected function add_meta_boxes() {
			// add_meta_box( $id, $title, $callback, $post_type, $context, $priority, $callback_args );
			add_meta_box( $this->pagehook.'_style', 'Social Styles', array( &$this, 'show_metabox_style' ), $this->pagehook, 'normal' );
		}

		public function show_metabox_style() {
			echo '<table class="sucom-setting"><tr>';
			echo $this->p->util->th( 'Use the Social Styles', 'highlight', 'buttons_link_css' );
			echo '<td>', $this->form->get_checkbox( 'buttons_link_css' ), '</td>';
			echo '</tr></table>';

			$tab_rows = array();
			$style_tabs = apply_filters( $this->p->cf['lca'].'_style_tabs', $this->p->cf['css'] );
			foreach ( $style_tabs as $id => $name )
				$tab_rows[$id] = apply_filters( $this->p->cf['lca'].'_style_rows', $this->get_rows( $id ), $id, $this->form );

			$this->p->util->do_tabs( 'css', $style_tabs, $tab_rows );
		}

		public function get_rows( $id ) {
			$ret = array();
			switch ( $id ) {
				case 'social' :
					$ret[] = '<td class="textinfo">
					<p>'.$this->p->cf['full'].' uses the \'ngfb-buttons\' class to wrap all its 
					social buttons, and each button has it\'s own individual class name as well. 
					Refer to the <a href="http://wordpress.org/extend/plugins/nextgen-facebook/other_notes/" 
					target="_blank">Other Notes</a> webpage for additional stylesheet information, 
					including how to hide the social buttons for specific Posts, Pages, categories, tags, etc.</p></td>'.
					'<td>'.$this->form->get_textarea( 'buttons_css_social', 'large css' ).'</td>';
					break;
				case 'excerpt' :
					$ret[] = '<td class="textinfo">
					<p>Social sharing buttons, enabled / added to the excerpt text from the '.
					$this->p->util->get_admin_url( 'social', 'Social Sharing settings page' ).
					', are assigned the \'ngfb-excerpt-buttons\' class, which itself contains the 
					\'ngfb-buttons\' class -- a common class for all the social buttons 
					(see the Buttons Style tab).</p> 
					<p>Example:</p><pre>
.ngfb-excerpt-buttons 
    .ngfb-buttons
        .facebook-button { }</pre></td><td>'.
					$this->form->get_textarea( 'buttons_css_excerpt', 'large css' ).'</td>';
					break;
				case 'content' :
					$ret[] = '<td class="textinfo">
					<p>Social sharing buttons, enabled / added to the content text from the '.
					$this->p->util->get_admin_url( 'social', 'Social Sharing settings page' ).
					', are assigned the \'ngfb-content-buttons\' class, which itself contains the 
					\'ngfb-buttons\' class -- a common class for all the social buttons 
					(see the Buttons Style tab).</p> 
					<p>Example:</p><pre>
.ngfb-content-buttons 
    .ngfb-buttons
        .facebook-button { }</pre></td><td>'.
					$this->form->get_textarea( 'buttons_css_content', 'large css' ).'</td>';
					break;
				case 'shortcode' :
					$ret[] = '<td class="textinfo">
					<p>Social sharing buttons added from a shortcode are assigned the 
					\'ngfb-shortcode-buttons\' class, which itself contains the 
					\'ngfb-buttons\' class -- a common class for all the social buttons 
					(see the Buttons Style tab).</p> 
					<p>Example:</p><pre>
.ngfb-shortcode-buttons 
    .ngfb-buttons
        .facebook-button { }</pre></td><td>'.
					$this->form->get_textarea( 'buttons_css_shortcode', 'large css' ).'</td>';
					break;
				case 'widget' :
					$ret[] = '<td class="textinfo">
					<p>Social sharing buttons within the '.$this->p->cf['menu'].' Social Sharing
					widget are assigned the \'ngfb-widget-buttons\' class, which itself contains the 
					\'ngfb-buttons\' class -- a common class for all the social buttons 
					(see the Buttons Style tab).</p> 
					<p>Example:</p><pre>
.ngfb-widget-buttons 
    .ngfb-buttons
        .facebook-button { }</pre>
					<p>The '.$this->p->cf['menu'].' Social Sharing widget also has an id of 
					\'ngfb-widget-buttons-<em>#</em>\', and the buttons have an id of 
					\'<em>name</em>-ngfb-widget-buttons-<em>#</em>\'.</p>
					<p>Example:</p><pre>
#ngfb-widget-buttons-2
    .ngfb-buttons
        #facebook-ngfb-widget-buttons-2 { }</pre></td><td>'.
					$this->form->get_textarea( 'buttons_css_widget', 'large css' ).'</td>';
					break;
			}
			return $ret;
		}
	}
}

?>
