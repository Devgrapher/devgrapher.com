<?php
/*
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl.txt
Copyright 2012-2014 - Jean-Sebastien Morisset - http://surniaulula.com/
*/

if ( ! defined( 'ABSPATH' ) ) 
	die( 'These aren\'t the droids you\'re looking for...' );

if ( ! class_exists( 'NgfbAdmin' ) ) {

	class NgfbAdmin {
	
		protected $js_locations = array(
			'header' => 'Header',
			'footer' => 'Footer',
		);

		protected $captions = array(
			'none' => '',
			'title' => 'Title Only',
			'excerpt' => 'Excerpt Only',
			'both' => 'Title and Excerpt',
		);

		protected $p;
		protected $menu_id;
		protected $menu_name;
		protected $pagehook;
		protected $readme;

		public $form;
		public $lang = array();
		public $submenu = array();

		public function __construct( &$plugin ) {
			$this->p =& $plugin;
			$this->p->debug->mark();
			$this->p->check->conflict_warnings();

			$this->set_objects();

			add_action( 'admin_init', array( &$this, 'register_setting' ) );
			add_action( 'admin_menu', array( &$this, 'add_admin_menus' ), -20 );
			add_action( 'admin_menu', array( &$this, 'add_admin_settings' ), -10 );
			add_filter( 'plugin_action_links', array( &$this, 'add_plugin_action_links' ), 10, 2 );

			if ( is_multisite() ) {
				add_action( 'network_admin_menu', array( &$this, 'add_network_admin_menus' ), -20 );
				add_action( 'network_admin_edit_'.NGFB_SITE_OPTIONS_NAME, array( &$this, 'save_site_options' ) );
				add_filter( 'network_admin_plugin_action_links', array( &$this, 'add_plugin_action_links' ), 10, 2 );
			}
		}

		// load all submenu classes into the $this->submenu array
		private function set_objects() {
			$libs = array( 'setting', 'submenu' );
			if ( is_multisite() )
				$libs[] = 'site_submenu';
			foreach ( $libs as $sub ) {
				foreach ( $this->p->cf['lib'][$sub] as $id => $name ) {
					do_action( $this->p->cf['lca'].'_load_lib', $sub, $id );
					$classname = __CLASS__.ucfirst( $id );
					if ( class_exists( $classname ) )
						$this->submenu[$id] = new $classname( $this->p, $id, $name );
				}
			}
		}

		protected function set_form() {
			$def_opts = $this->p->opt->get_defaults();
			$this->form = new SucomForm( $this->p, NGFB_OPTIONS_NAME, $this->p->options, $def_opts );
		}

		protected function &get_form_ref() {	// return reference
			return $this->form;
		}

		public function register_setting() {
			register_setting( $this->p->cf['lca'].'_setting', NGFB_OPTIONS_NAME, array( &$this, 'sanitize_options' ) );
		} 

		public function set_readme( $expire_secs = 0 ) {
			if ( empty( $this->readme ) )
				$this->readme = $this->p->util->parse_readme( $expire_secs );
		}

		public function add_admin_settings() {
			foreach ( $this->p->cf['lib']['setting'] as $id => $name ) {
				if ( array_key_exists( $id, $this->submenu ) ) {
					$parent_slug = 'options-general.php';
					$this->submenu[$id]->add_submenu_page( $parent_slug );
				}
			}
		}

		public function add_network_admin_menus() {
			$this->add_admin_menus( $this->p->cf['lib']['site_submenu'] );
		}

		public function add_admin_menus( $libs = array() ) {
			if ( empty( $libs ) ) 
				$libs = $this->p->cf['lib']['submenu'];
			$this->menu_id = key( $libs );
			$this->menu_name = $libs[$this->menu_id];
			if ( array_key_exists( $this->menu_id, $this->submenu ) ) {
				$menu_slug = $this->p->cf['lca'].'-'.$this->menu_id;
				$this->submenu[$this->menu_id]->add_menu_page( $menu_slug );
			}
			foreach ( $libs as $id => $name ) {
				if ( array_key_exists( $id, $this->submenu ) ) {
					$parent_slug = $this->p->cf['lca'].'-'.$this->menu_id;
					$this->submenu[$id]->add_submenu_page( $parent_slug );
				}
			}
		}

		protected function add_menu_page( $menu_slug ) {
			global $wp_version;
			// add_menu_page( $page_title, $menu_title, $capability, $menu_slug, $function, $icon_url, $position );
			$this->pagehook = add_menu_page( 
				$this->p->cf['full'].' : '.$this->menu_name, 
				$this->p->cf['menu'], 
				'manage_options', 
				$menu_slug, 
				array( &$this, 'show_page' ), 
				( version_compare( $wp_version, 3.8, '<' ) ? null : 'dashicons-share' ),
				NGFB_MENU_PRIORITY
			);
			add_action( 'load-'.$this->pagehook, array( &$this, 'load_page' ) );
		}

		protected function add_submenu_page( $parent_slug ) {
			// add_submenu_page( $parent_slug, $page_title, $menu_title, $capability, $menu_slug, $function );
			$this->pagehook = add_submenu_page( 
				$parent_slug, 
				$this->p->cf['full'].' : '.$this->menu_name, 
				$this->menu_name, 
				'manage_options', 
				$this->p->cf['lca'].'-'.$this->menu_id, 
				array( &$this, 'show_page' ) 
			);
			add_action( 'load-'.$this->pagehook, array( &$this, 'load_page' ) );
		}

		// display a settings link on the main plugins page
		public function add_plugin_action_links( $links, $file ) {
			// only add links when filter is called for this plugin
			if ( $file == NGFB_PLUGINBASE ) {
				// remove the Edit link
				foreach ( $links as $num => $val ) {
					if ( preg_match( '/>Edit</', $val ) )
						unset ( $links[$num] );
				}
				if ( $this->p->is_avail['aop'] ) {
					array_push( $links, '<a href="'.$this->p->cf['url']['pro_codex'].'">'.__( 'Codex', NGFB_TEXTDOM ).'</a>' );
					array_push( $links, '<a href="'.$this->p->cf['url']['pro_support'].'">'.__( 'Support', NGFB_TEXTDOM ).'</a>' );
					if ( ! $this->p->check->is_aop() ) 
						array_push( $links, '<a href="'.$this->p->cf['url']['purchase'].'">'.__( 'Purchase License', NGFB_TEXTDOM ).'</a>' );
				} else {
					array_push( $links, '<a href="'.$this->p->cf['url']['faq'].'">'.__( 'FAQ', NGFB_TEXTDOM ).'</a>' );
					array_push( $links, '<a href="'.$this->p->cf['url']['notes'].'">'.__( 'Notes', NGFB_TEXTDOM ).'</a>' );
					array_push( $links, '<a href="'.$this->p->cf['url']['support'].'">'.__( 'Forum', NGFB_TEXTDOM ).'</a>' );
					array_push( $links, '<a href="'.$this->p->cf['url']['purchase'].'">'.__( 'Purchase Pro', NGFB_TEXTDOM ).'</a>' );
				}

			}
			return $links;
		}

		// this method receives only a partial options array, so re-create a full one
		// wordpress handles the actual saving of the options
		public function sanitize_options( $opts ) {
			if ( ! is_array( $opts ) ) {
				add_settings_error( NGFB_OPTIONS_NAME, 'notarray', '<b>'.$this->p->cf['uca'].' Error</b> : '.
					__( 'Submitted settings are not an array.', NGFB_TEXTDOM ), 'error' );
				return $opts;
			}
			// get default values, including css from default stylesheets
			$def_opts = $this->p->opt->get_defaults();
			$opts = SucomUtil::restore_checkboxes( $opts );
			$opts = array_merge( $this->p->options, $opts );
			$opts = $this->p->opt->sanitize( $opts, $def_opts );	// cleanup excess options and sanitize
			if ( $this->p->is_avail['ssb'] ) 
				$this->p->style->update_social( $opts );
			$opts = apply_filters( $this->p->cf['lca'].'_save_options', $opts );
			$this->p->notice->inf( __( 'Plugin settings have been updated.', NGFB_TEXTDOM ).' '.
				sprintf( __( 'Wait %d seconds for cache objects to expire (default) or use the \'Clear All Cache\' button.', NGFB_TEXTDOM ), 
					$this->p->options['plugin_object_cache_exp'] ), true );
			return $opts;
		}

		public function save_site_options() {
			$page = empty( $_POST['page'] ) ? 
				key( $this->p->cf['lib']['site_submenu'] ) : $_POST['page'];

			if ( empty( $_POST[ NGFB_NONCE ] ) ) {
				$this->p->debug->log( 'Nonce token validation post field missing.' );
				wp_redirect( $this->p->util->get_admin_url( $page ) );
				exit;
			} elseif ( ! wp_verify_nonce( $_POST[ NGFB_NONCE ], $this->get_nonce() ) ) {
				$this->p->notice->err( __( 'Nonce token validation failed for network options (update ignored).', NGFB_TEXTDOM ), true );
				wp_redirect( $this->p->util->get_admin_url( $page ) );
				exit;
			} elseif ( ! current_user_can( 'manage_network_options' ) ) {
				$this->p->notice->err( __( 'Insufficient privileges to modify network options.', NGFB_TEXTDOM ), true );
				wp_redirect( $this->p->util->get_admin_url( $page ) );
				exit;
			}

			$def_opts = $this->p->opt->get_site_defaults();
			$opts = empty( $_POST[NGFB_SITE_OPTIONS_NAME] ) ?  $def_opts : 
				SucomUtil::restore_checkboxes( $_POST[NGFB_SITE_OPTIONS_NAME] );
			$opts = array_merge( $this->p->site_options, $opts );
			$opts = $this->p->opt->sanitize( $opts, $def_opts );	// cleanup excess options and sanitize

			$opts = apply_filters( $this->p->cf['lca'].'_save_site_options', $opts );
			update_site_option( NGFB_SITE_OPTIONS_NAME, $opts );

			// store message in user options table
			$this->p->notice->inf( __( 'Plugin settings have been updated.', NGFB_TEXTDOM ), true );
			wp_redirect( $this->p->util->get_admin_url( $page ).'&settings-updated=true' );
			exit;
		}

		public function load_page() {
			wp_enqueue_script( 'postbox' );
			$upload_dir = wp_upload_dir();	// returns assoc array with path info
			$user_opts = $this->p->user->get_options();

			if ( ! empty( $_GET['action'] ) ) {

				if ( empty( $_GET[ NGFB_NONCE ] ) )
					$this->p->debug->log( 'Nonce token validation query field missing.' );
				elseif ( ! wp_verify_nonce( $_GET[ NGFB_NONCE ], $this->get_nonce() ) )
					$this->p->notice->err( __( 'Nonce token validation failed for plugin action (action ignored).', NGFB_TEXTDOM ) );
				else {
					switch ( $_GET['action'] ) {
						case 'check_for_updates' : 
							if ( ! empty( $this->p->options['plugin_tid'] ) ) {
								$this->p->admin->set_readme( 0 );
								$this->p->update->check_for_updates();
								$this->p->notice->inf( __( 'Plugin update information has been checked and updated.', NGFB_TEXTDOM ) );
							}
							break;
						case 'clear_all_cache' : 
							$deleted_cache = $this->p->util->delete_expired_file_cache( true );
							$deleted_transient = $this->p->util->delete_expired_transients( true );
							wp_cache_flush();
							if ( function_exists('w3tc_pgcache_flush') ) 
								w3tc_pgcache_flush();
							elseif ( function_exists('wp_cache_clear_cache') ) 
								wp_cache_clear_cache();
							$this->p->notice->inf( __( 'Cached files, WP object cache, transient cache, and any additional caches, like APC, Memcache, Xcache, W3TC, Super Cache, etc. have all been cleared.', NGFB_TEXTDOM ) );
							break;
						case 'clear_metabox_prefs' : 
							NgfbUser::delete_metabox_prefs( get_current_user_id() );
							break;
					}
				}
			}

			// the plugin information metabox on all settings pages needs this
			$this->p->admin->set_readme( $this->p->cf['update_hours'] * 3600 );

			// add child metaboxes first, since they contain the default reset_metabox_prefs()
			$this->p->admin->submenu[$this->menu_id]->add_meta_boxes();

			if ( empty( $this->p->options['plugin_tid'] ) || ! $this->p->check->is_aop() ) {
				add_meta_box( $this->pagehook.'_purchase', __( 'Pro Version', NGFB_TEXTDOM ), array( &$this, 'show_metabox_purchase' ), $this->pagehook, 'side' );
				add_filter( 'postbox_classes_'.$this->pagehook.'_'.$this->pagehook.'_purchase', array( &$this, 'add_class_postbox_highlight_side' ) );
				$this->p->user->reset_metabox_prefs( $this->pagehook, array( 'purchase' ), null, 'side', true );
			}
			add_meta_box( $this->pagehook.'_info', __( 'Version Information', NGFB_TEXTDOM ), array( &$this, 'show_metabox_info' ), $this->pagehook, 'side' );
			add_meta_box( $this->pagehook.'_status', __( 'Plugin Features', NGFB_TEXTDOM ), array( &$this, 'show_metabox_status' ), $this->pagehook, 'side' );
			add_meta_box( $this->pagehook.'_help', __( 'Help and Support', NGFB_TEXTDOM ), array( &$this, 'show_metabox_help' ), $this->pagehook, 'side' );

			if ( $this->p->check->is_aop() )
				add_meta_box( $this->pagehook.'_thankyou', __( 'Thank You', NGFB_TEXTDOM ), array( &$this, 'show_metabox_thankyou' ), $this->pagehook, 'side' );
		}

		public function show_page() {
			if ( $this->menu_id !== 'contact' )		// the "settings" page displays its own error messages
				settings_errors( NGFB_OPTIONS_NAME );	// display "error" and "updated" messages
			$this->set_form();				// define form for side boxes and show_form()
			if ( $this->p->debug->is_on() ) {
				$this->p->debug->show_html( print_r( $this->p->is_avail, true ), 'available features' );
				$this->p->debug->show_html( print_r( $this->p->check->get_active(), true ), 'active plugins' );
				$this->p->debug->show_html( null, 'debug log' );
			}
			?>
			<div class="wrap" id="<?php echo $this->pagehook; ?>">
				<?php $this->show_follow_icons(); ?>
				<h2>
					<!-- <span class="dashicons dashicons-share" 
						style="font-size:1.4em;vertical-align:abs-middle;margin-right:0.6em;"></span> -->
					<?php echo $this->p->cf['full'].' : '.$this->menu_name; ?>
				</h2>
				<div id="poststuff" class="metabox-holder <?php echo 'has-right-sidebar'; ?>">
					<div id="side-info-column" class="inner-sidebar">
						<?php do_meta_boxes( $this->pagehook, 'side', null ); ?>
					</div><!-- .inner-sidebar -->
					<div id="post-body" class="has-sidebar">
						<div id="post-body-content" class="has-sidebar-content">
							<?php $this->show_form(); ?>
						</div><!-- .has-sidebar-content -->
					</div><!-- .has-sidebar -->
				</div><!-- .metabox-holder -->
			</div><!-- .wrap -->
			<script type="text/javascript">
				//<![CDATA[
					jQuery(document).ready( 
						function($) {
							// close postboxes that should be closed
							$('.if-js-closed').removeClass('if-js-closed').addClass('closed');
							// postboxes setup
							postboxes.add_postbox_toggles('<?php echo $this->pagehook; ?>');
						}
					);
				//]]>
			</script>
			<?php
		}

		public function add_class_postbox_highlight_side( $classes ) {
			array_push( $classes, 'postbox_highlight_side' );
			return $classes;
		}

		protected function show_form() {
			if ( ! empty( $this->p->cf['lib']['submenu'][$this->menu_id] ) ) {
				echo '<form name="ngfb" id="setting" method="post" action="options.php">';
				echo $this->form->get_hidden( 'options_version', $this->p->cf['opt']['version'] );
				echo $this->form->get_hidden( 'plugin_version', $this->p->cf['version'] );
				settings_fields( $this->p->cf['lca'].'_setting' ); 

			} elseif ( ! empty( $this->p->cf['lib']['site_submenu'][$this->menu_id] ) ) {
				echo '<form name="ngfb" id="setting" method="post" action="edit.php?action='.NGFB_SITE_OPTIONS_NAME.'">';
				echo '<input type="hidden" name="page" value="'.$this->menu_id.'">';
				echo $this->form->get_hidden( 'options_version', $this->p->cf['opt']['version'] );
				echo $this->form->get_hidden( 'plugin_version', $this->p->cf['version'] );
			}
			wp_nonce_field( $this->get_nonce(), NGFB_NONCE );
			wp_nonce_field( 'closedpostboxes', 'closedpostboxesnonce', false );
			wp_nonce_field( 'meta-box-order', 'meta-box-order-nonce', false );

			do_meta_boxes( $this->pagehook, 'normal', null ); 

			// if we're displaying the "social" page, then do the social website metaboxes
			if ( $this->menu_id == 'social' ) {
				foreach ( range( 1, ceil( count( $this->p->admin->submenu[$this->menu_id]->website ) / 2 ) ) as $row ) {
					echo '<div class="website-row">', "\n";
					foreach ( range( 1, 2 ) as $col ) {
						$pos_id = 'website-row-'.$row.'-col-'.$col;
						echo '<div class="website-col-', $col, '" id="', $pos_id, '" >';
						do_meta_boxes( $this->pagehook, $pos_id, null ); 
						echo '</div>', "\n";
					}
					echo '</div>', "\n";
				}
				echo '<div style="clear:both;"></div>';
			}

			//do_meta_boxes( $this->pagehook, 'bottom', null ); 

			if ( $this->menu_id != 'about' )
				echo $this->get_submit_button();

			echo '</form>', "\n";
		}

		public function feed_cache_expire( $seconds ) {
			return $this->p->cf['update_hours'] * 3600;
		}

		public function show_metabox_info() {
			$stable_tag = __( 'N/A', NGFB_TEXTDOM );
			$latest_version = __( 'N/A', NGFB_TEXTDOM );
			$latest_notice = '';
			if ( ! empty( $this->p->admin->readme['stable_tag'] ) ) {
				$stable_tag = $this->p->admin->readme['stable_tag'];
				$upgrade_notice = $this->p->admin->readme['upgrade_notice'];
				if ( is_array( $upgrade_notice ) ) {
					reset( $upgrade_notice );
					$latest_version = key( $upgrade_notice );
					$latest_notice = $upgrade_notice[$latest_version];
				}
			}
			echo '<table class="sucom-setting">';
			echo '<tr><th class="side">'.__( 'Installed', NGFB_TEXTDOM ).':</th>';
			echo '<td colspan="2">'.$this->p->cf['version'].' (';
			if ( $this->p->is_avail['aop'] ) 
				echo __( 'Pro', NGFB_TEXTDOM );
			else echo __( 'GPL', NGFB_TEXTDOM );
			echo ')</td></tr>';
			echo '<tr><th class="side">'.__( 'Stable', NGFB_TEXTDOM ).':</th><td colspan="2">'.$stable_tag.'</td></tr>';
			echo '<tr><th class="side">'.__( 'Latest', NGFB_TEXTDOM ).':</th><td colspan="2">'.$latest_version.'</td></tr>';
			echo '<tr><td colspan="3" id="latest_notice"><p>'.$latest_notice.'</p>';
			echo '<p><a href="'.$this->p->cf['url']['changelog'].'" target="_blank">'.__( 'See the Changelog for additional details...', NGFB_TEXTDOM ).'</a></p>';
			echo '</td></tr>';
			echo '</table>';
		}

		public function show_metabox_status() {
			echo '<table class="sucom-setting">';
			/*
			 * GNU version features
			 */
			$cca = $this->p->cf['cca'];
			$features = array(
				'Debug Messages' => array( 'class' => 'SucomDebug' ),
				'Non-Persistant Cache' => array( 'status' => $this->p->is_avail['cache']['object'] ? 'on' : 'rec' ),
				'Open Graph / Rich Pin' => array( 'status' => class_exists( $cca.'Opengraph' ) ? 'on' : 'rec' ),
				'Pro Update Check' => array( 'class' => 'SucomUpdate' ),
				'Social Sharing Buttons' => array( 'class' => $cca.'Social' ),
				'Social Sharing Shortcode' => array( 'class' => $cca.'ShortcodeNgfb' ),
				'Social Sharing Widget' => array( 'class' => $cca.'WidgetSocialSharing' ),
				'Transient Cache' => array( 'status' => $this->p->is_avail['cache']['transient'] ? 'on' : 'rec' ),
			);
			echo '<tr><td><h4 style="margin-top:0;">Standard</h4></td></tr>';
			$this->show_plugin_status( $features );

			/*
			 * Pro version features
			 */
			$features = array(
				'Social File Cache' => array( 'status' => $this->p->is_avail['cache']['file'] ? 'on' : 'off' ),
				'Custom Post Meta' => array( 'status' => class_exists( $cca.'PostMetaPro' ) ? 'on' : 'rec' ),
				'WP Locale Language' => array( 'status' => class_exists( $cca.'Language' ) ? 'on' : 'rec' ),
				'Twitter Cards' => array( 'status' => class_exists( $cca.'Opengraph' ) && 
					class_exists( $cca.'TwitterCard' ) ? 'on' : 'rec' ),
				'URL Rewriter' => array( 'status' => class_exists( $cca.'RewritePro' ) ? 'on' : 
					( empty( $this->p->options['plugin_cdn_urls'] ) ? 'off' : 'rec' ) ),
				'URL Shortener' => array( 'status' => class_exists( $cca.'ShortenPro' ) ? 'on' : 
					( empty( $this->p->is_avail['ssb'] ) ||
						empty( $this->p->options['twitter_shortener'] ) ? 'off' : 'rec' ) ),
			);
			foreach ( $this->p->cf['lib']['pro'] as $sub => $libs ) {
				foreach ( $libs as $id => $name ) {
					$features[$name] = array( 
						'status' => class_exists( $cca.ucfirst( $sub ).ucfirst( $id ) ) ? 'on' : 
							( $this->p->is_avail[$sub][$id] ? 'rec' : 'off' ) );

					$features[$name]['tooltip'] = 'If the '.$name.' plugin is detected, '.
						$this->p->cf['full_pro'].' will load a specific integration addon
						for '.$name.' to improve the accuracy of Open Graph, Rich Pin, 
						and Twitter Card meta tag values.';

					switch ( $id ) {
						case 'bbpress':
						case 'buddypress':
							$features[$name]['tooltip'] .= ' '.$name.' support also provides social sharing buttons 
							that can be enabled from the Open Graph+ '.$this->p->util->get_admin_url( 'social',
							'Social Sharing settings' ).' page.';
							break;
					}
				}
			}
			echo '<tr><td><h4>Pro Addons</h4></td></tr>';
			$this->show_plugin_status( $features, ( $this->p->check->is_aop() ? '' : 'blank' ) );

			$action_buttons = '';
			if ( ! empty( $this->p->options['plugin_tid'] ) )
				$action_buttons .= $this->form->get_button( __( 'Check for Updates', NGFB_TEXTDOM ), 
					'button-secondary', null, wp_nonce_url( $this->p->util->get_admin_url( '?action=check_for_updates' ), 
						$this->get_nonce(), NGFB_NONCE ) ).' ';

			// don't offer the 'Clear All Cache' and 'Reset Metaboxes' buttons on network admin pages
			if ( empty( $this->p->cf['lib']['site_submenu'][$this->menu_id] ) ) {
				$action_buttons .= $this->form->get_button( __( 'Clear All Cache', NGFB_TEXTDOM ), 
					'button-secondary', null, wp_nonce_url( $this->p->util->get_admin_url( '?action=clear_all_cache' ),
						$this->get_nonce(), NGFB_NONCE ) ).' ';

				$action_buttons .= $this->form->get_button( __( 'Reset Metaboxes', NGFB_TEXTDOM ), 
					'button-secondary', null, wp_nonce_url( $this->p->util->get_admin_url( '?action=clear_metabox_prefs' ),
						$this->get_nonce(), NGFB_NONCE ) ).' ';
			}

			if ( ! empty( $action_buttons ) )
				echo '<tr><td colspan="2" class="actions">'.$action_buttons.'</td></tr>';
			echo '</table>';
		}

		private function show_plugin_status( $feature = array(), $class = '' ) {
			$status_images = array( 
				'on' => 'green-circle.png',
				'off' => 'gray-circle.png',
				'rec' => 'red-circle.png',
			);
			foreach ( $status_images as $status => $img )
				$status_images[$status] = '<td style="min-width:0;text-align:center;"'.
					( empty( $class ) ? '' : ' class="'.$class.'"' ).'><img src="'.NGFB_URLPATH.
					'images/'.$img.'" width="12" height="12" /></td>';

			ksort( $feature );
			$first = key( $feature );
			foreach ( $feature as $name => $arr ) {
				if ( array_key_exists( 'class', $arr ) )
					$status = class_exists( $arr['class'] ) ? 'on' : 'off';
				elseif ( array_key_exists( 'status', $arr ) )
					$status = $arr['status'];
				if ( ! empty( $status ) ) {
					$tooltip_text = empty( $arr['tooltip'] ) ? '' : $arr['tooltip'];
					$tooltip_text = $this->p->msgs->get( 'tooltip-side-'.$name, $tooltip_text, 'sucom_tooltip_side' );
					echo '<tr><td class="side'.( empty( $class ) ? '' : ' '.$class ).'">'.$tooltip_text.
						( $status == 'rec' ? '<strong>'.$name.'</strong>' : $name ).'</td>'.$status_images[$status].'</tr>';
				}
			}
		}

		public function show_metabox_purchase() {
			echo '<table class="sucom-setting"><tr><td>';
			echo $this->p->msgs->get( 'side-purchase' );
			echo '<p>Thank you,</p>';
			echo '<p class="sig">js.</p>';
			echo '<p class="centered">';
			echo $this->form->get_button( 
				( $this->p->is_avail['aop'] ? 
					__( 'Purchase a Pro License', NGFB_TEXTDOM ) :
					__( 'Purchase the Pro Version', NGFB_TEXTDOM ) ), 
				'button-primary', null, $this->p->cf['url']['purchase'], true );
			echo '</p></td></tr></table>';
		}

		public function show_metabox_thankyou() {
			echo '<table class="sucom-setting"><tr><td>';
			echo $this->p->msgs->get( 'side-thankyou' );
			echo '<p class="sig">js.</p>';
			echo '</td></tr></table>';
		}

		public function show_metabox_help() {
			echo '<table class="sucom-setting"><tr><td>';
			echo $this->p->msgs->get( 'side-help' );
			echo '</td></tr></table>';
		}

		protected function show_follow_icons() {
			echo '<div class="follow_icons">';
			$img_size = $this->p->cf['follow']['size'];
			foreach ( $this->p->cf['follow']['src'] as $img => $url )
				echo '<a href="'.$url.'" target="_blank"><img src="'.NGFB_URLPATH.'images/'.$img.'" 
					width="'.$img_size.'" height="'.$img_size.'" /></a> ';
			echo '</div>';
		}

		protected function get_submit_button( $submit_text = '', $class = 'save-all-button' ) {
			if ( empty( $submit_text ) ) 
				$submit_text = __( 'Save All Changes', NGFB_TEXTDOM );
			return '<div class="'.$class.'"><input type="submit" class="button-primary" value="'.$submit_text.'" /></div>'."\n";
		}

		protected function get_nonce() {
			return plugin_basename( __FILE__ );
		}
	}
}
?>
