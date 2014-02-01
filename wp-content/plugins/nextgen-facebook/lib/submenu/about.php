<?php
/*
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl.txt
Copyright 2012-2014 - Jean-Sebastien Morisset - http://surniaulula.com/
*/

if ( ! defined( 'ABSPATH' ) ) 
	die( 'These aren\'t the droids you\'re looking for...' );

if ( ! class_exists( 'NgfbAdminAbout' ) && class_exists( 'NgfbAdmin' ) ) {

	class NgfbAdminAbout extends NgfbAdmin {

		public function __construct( &$plugin, $id, $name ) {
			$this->p =& $plugin;
			$this->p->debug->mark();
			$this->menu_id = $id;
			$this->menu_name = $name;
		}

		protected function add_meta_boxes() {
			// add_meta_box( $id, $title, $callback, $post_type, $context, $priority, $callback_args );
			add_meta_box( $this->pagehook.'_description', 'Description', array( &$this, 'show_metabox_description' ), $this->pagehook, 'normal' );
			add_meta_box( $this->pagehook.'_faq', 'FAQ', array( &$this, 'show_metabox_faq' ), $this->pagehook, 'normal' );
			add_meta_box( $this->pagehook.'_remaining', 'Other Notes', array( &$this, 'show_metabox_remaining' ), $this->pagehook, 'normal' );
			add_meta_box( $this->pagehook.'_changelog', 'Changelog', array( &$this, 'show_metabox_changelog' ), $this->pagehook, 'normal' );

			// these metabox ids should be closed by default
			$this->p->user->reset_metabox_prefs( $this->pagehook, array( 'description', 'remaining', 'changelog' ), 'closed' );
		}

		public function show_metabox_description() {
			echo '<table class="sucom-setting about-metabox"><tr><td>';
			echo empty( $this->p->admin->readme['sections']['description'] ) ? 
				'Content not Available' : $this->p->admin->readme['sections']['description'];
			echo '</td></tr></table>';
		}
		
		public function show_metabox_faq() {
			echo '<table class="sucom-setting about-metabox"><tr><td>';
			echo empty( $this->p->admin->readme['sections']['frequently_asked_questions'] ) ?
				'Content not Available' : $this->p->admin->readme['sections']['frequently_asked_questions'];
			echo '</td></tr></table>';
		}

		public function show_metabox_remaining() {
			echo '<table class="sucom-setting about-metabox"><tr><td>';
			echo empty( $this->p->admin->readme['remaining_content'] ) ?
				'Content not Available' : $this->p->admin->readme['remaining_content'];
			echo '</td></tr></table>';
		}

		public function show_metabox_changelog() {
			echo '<table class="sucom-setting about-metabox"><tr><td>';
			echo empty( $this->p->admin->readme['sections']['changelog'] ) ?
				'Content not Available' : $this->p->admin->readme['sections']['changelog'];
			echo '</td></tr></table>';
		}
	}
}

?>
