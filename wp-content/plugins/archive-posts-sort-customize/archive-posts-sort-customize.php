<?php
/*
Plugin Name: Archive Posts Sort Customize
Description: Customize the posts order of the list of Archive Posts.
Plugin URI: http://wordpress.org/extend/plugins/archive-posts-sort-customize/
Version: 1.5
Author: gqevu6bsiz
Author URI: http://gqevu6bsiz.chicappa.jp/?utm_source=use_plugin&utm_medium=list&utm_content=apsc&utm_campaign=1_5
Text Domain: apsc
Domain Path: /languages
*/

/*  Copyright 2012 gqevu6bsiz (email : gqevu6bsiz@gmail.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as
	published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/





if ( !class_exists( 'APSC' ) ) :

class APSC
{

	var $Ver = '';
	var $Name = '';
	var $Dir = '';
	var $Url = '';
	var $Path = array();
	var $ltd = '';
	var $Record = array();
	var $PageSlug = '';
	var $PluginSlug = '';
	var $Nonces = array();
	var $Schema = '';
	var $UPFN = '';
	var $DonateKey = '';
	var $MsgQ = '';


	function __construct() {

		$this->Ver = '1.5';
		$this->Name = 'Archive Post Sort Customize';
		$this->Dir = plugin_dir_path( __FILE__ );
		$this->Url = plugin_dir_url( __FILE__ );
		$this->Path = array( 'inc' => 'includes' , 'view' => 'views' , 'asset' => 'assets' );
		$this->ltd = 'apsc';
		$this->Record = array(
			'home' => $this->ltd . '_home',
			'tag' => $this->ltd . '_tag',
			'cat' => $this->ltd . '_cat',
			'search' => $this->ltd . '_search',
			'monthly' => $this->ltd . '_monthly',
			'db_version' => $this->ltd . '_db_ver',
			'donate' => $this->ltd . '_donated',
		);
		$this->PageSlug = 'archive_posts_sort_customize';
		$this->PluginSlug = dirname( plugin_basename( __FILE__ ) );
		$this->Nonces = array( "field" => $this->ltd . '_field' , "value" => $this->ltd . '_value' );
		$this->Schema = is_ssl() ? 'https://' : 'http://';

		$this->UPFN = 'Y';
		$this->DonateKey = 'd77aec9bc89d445fd54b4c988d090f03';
		$this->MsgQ = $this->ltd . '_msg';
		
		$this->init();

	}

	function init() {

		// load text domain
		load_plugin_textdomain( $this->ltd , false , $this->PluginSlug . '/languages' );
		
		require_once $this->Dir . trailingslashit( $this->Path['inc'] ) . 'data.php';
		require_once $this->Dir . trailingslashit( $this->Path['inc'] ) . 'lists.php';
		require_once $this->Dir . trailingslashit( $this->Path['inc'] ) . 'manage.php';
		require_once $this->Dir . trailingslashit( $this->Path['inc'] ) . 'filter.php';

		add_action( 'plugins_loaded' , array( $this , ( 'setup' ) ) );
		add_action( 'init' , array( $this , ( 'record_add' ) ) );

	}

	function setup() {
		
		$APSC_Data = new APSC_Data();
		$APSC_Manage = new APSC_Manage();
		$APSC_Filter = new APSC_Filter();
		
		$APSC_Data->init();
		
	}
	
	function record_add() {

		$APSC_Lists = new APSC_Lists();
		
		$taxonomies = $APSC_Lists->get_custom_taxonomies();
		if( !empty( $taxonomies ) ) {
			foreach( $taxonomies as $tax_name => $tanoxomy ) {
				$this->Record['ct_' . $tax_name] = $this->ltd . '_ct_' . $tax_name;
			}
		}

	}
	

}

$APSC = new APSC();

endif;
