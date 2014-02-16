<?php
/**
 * Plugin Name: Admin Page Notes
 * Plugin URI: http://joshmallard.com
 * Description: Gives administrators the ability to add page notes to certain pages that will prominently display special instructions for all users editing those pages. 
 * Version: 1.1.0
 * Author: Josh Mallard
 * Author URI: http://joshmallard.com
 * Text Domain: gb-page-notes
 * License: GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Domain Path: /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/*----------------------------------------------------------------------------*
 * Dashboard and Administrative Functionality
 *----------------------------------------------------------------------------*/

if ( is_admin() && ( ! defined( 'DOING_AJAX' ) || ! DOING_AJAX ) ) {

	require_once( plugin_dir_path( __FILE__ ) . 'admin/page-notes-admin.php' );
	add_action( 'plugins_loaded', array( 'Page_Notes_Admin', 'get_instance' ) );

}
