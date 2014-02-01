<?php
/*
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl.txt
Copyright 2012-2014 - Jean-Sebastien Morisset - http://surniaulula.com/
*/

if ( ! defined( 'ABSPATH' ) ) 
	die( 'These aren\'t the droids you\'re looking for...' );

if ( ! class_exists( 'NgfbAdminNetwork' ) && class_exists( 'NgfbAdmin' ) ) {

	class NgfbAdminNetwork extends NgfbAdmin {

		// executed by NgfbAdminAdvancedPro() as well
		public function __construct( &$plugin, $id, $name ) {
			$this->p =& $plugin;
			$this->p->debug->mark();
			$this->menu_id = $id;
			$this->menu_name = $name;
		}

		protected function set_form() {
			$def_site_opts = $this->p->opt->get_site_defaults();
			$this->form = new SucomForm( $this->p, NGFB_SITE_OPTIONS_NAME, $this->p->site_options, $def_site_opts );
		}

		protected function add_meta_boxes() {
			// add_meta_box( $id, $title, $callback, $post_type, $context, $priority, $callback_args );
			add_meta_box( $this->pagehook.'_network', 'Network-Wide Settings', array( &$this, 'show_metabox_network' ), $this->pagehook, 'normal' );
		}

		public function show_metabox_network() {
			echo '<table class="sucom-setting">';
			foreach ( $this->get_rows( 'network' ) as $row )
				echo '<tr>'.$row.'</tr>';
			echo '</table>';
		}

		protected function get_rows( $id ) {
			$ret = array();
			$use = array( 
				'default' => 'As Default Value', 
				'empty' => 'If Value is Empty', 
				'force' => 'Force This Value',
			);
			$use_msg = esc_attr( 'Individual sites / blogs may use this value as a default when the plugin is first activated, 
			if the current site / blog option value is blank, or force every site / blog to use this value (disabling editing of this field).' );

			switch ( $id ) {
				case 'network' :
					if ( $this->p->is_avail['aop'] )
						$pro_msg = 'After purchasing a Pro version license, an email will be sent to you with a unique Authentication ID 
						and installation instructions. Enter the Authentication ID here, to define a value for all sites within the network,
						or enter the Authentication ID(s) individually on each site\'s Advanced settings page. Note that the default site / blog 
						must be licensed to allow Pro version updates from the Network Admin interface.';
					else
						$pro_msg = 'After purchasing the Pro version, an email will be sent to you with a unique Authentication ID 
						and installation instructions. Enter the Authentication ID here, to define a value for all sites within the network,
						or enter the Authentication ID(s) individually on each site\'s Advanced settings page. Note that the default site / blog 
						must be licensed to allow Pro version updates from the Network Admin interface.
						Once the default site / blog is licensed, an update for '.$this->p->cf['full'].' will appear on the 
						<a href="'.get_admin_url( null, 'update-core.php' ).'">WordPress Updates</a> page. 
						Update the \''.$this->p->cf['full'].'\' plugin to download and activate the Pro version.';
		
					$ret[] = $this->p->util->th( 'Pro Version Authentication ID', 'highlight', null, $pro_msg ).
					'<td>'.$this->form->get_input( 'plugin_tid' ).'</td>'.
					'<td>All Sites Use <img src="'.NGFB_URLPATH.'images/question-mark.png" class="sucom_tooltip'.'" alt="'.
					$use_msg.'" /> '.$this->form->get_select( 'plugin_tid:use', $use ).'</td>';

					break;

			}
			return $ret;
		}
	}
}
?>
