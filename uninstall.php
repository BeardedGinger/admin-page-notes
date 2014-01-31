<?php
/**
 * Uninstall
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Delete all page notes from the database when plugin is uninstalled
 */

delete_post_meta_by_key( 'gb_admin_note' );