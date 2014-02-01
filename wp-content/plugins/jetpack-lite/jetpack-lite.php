<?php
/*
 * Plugin Name: Jetpack Lite
 * Plugin URI: http://wordpress.org/extend/plugins/jetpack-lite/
 * Description: Disables all Jetpack modules except for Stats and WP.me Shortlinks modules. Jetpack is required!
 * Author: Samuel Aguilera
 * Version: 3.0.2
 * Author URI: http://www.samuelaguilera.com
 * License: GPL2+
 */

/*
This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License version 2 as published by
the Free Software Foundation.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/

function Jetpack_Lite_Init() {

	if (class_exists('Jetpack', false )) {

			function Leave_only_JetpackLite_modules ( $modules ) {
			    $return = array();
			    $return['stats'] = $modules['stats'];
			    $return['shortlinks'] = $modules['shortlinks'];
			    return $return;
			}

			add_filter( 'jetpack_get_available_modules', 'Leave_only_JetpackLite_modules' );

			function Activate_only_JetpackLite_modules() {
			    return array( 'stats', 'shortlinks' );
			}

			add_filter( 'jetpack_get_default_modules', 'Activate_only_JetpackLite_modules' );

	} else {


			function No_Jetpack_Found() { //TODO: i8n support for this little warning?
			    ?>
			    <div class="error">
			        <p><?php _e( '<b>This version of Jetpack Lite requires <a href="http://wordpress.org/plugins/jetpack/" title="Jetpack">Jetpack</a> to work!</b> Please <b>install and activate Jetpack</b> to keep using WordPress.com stats.', 'jetpack-lite' ); ?></p>
			        <p><?php _e( 'Your previous settings will be used by Jetpack. Simply install and activate Jetpack and Jetpack Lite will automatically trim down modules to leave only stats and shorlinks.', 'jetpack-lite' ); ?></p>
			    </div>
			    <?php
			}
			add_action( 'admin_notices', 'No_Jetpack_Found' );

	}

}

add_action( 'plugins_loaded', 'Jetpack_Lite_init' );

?>