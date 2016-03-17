<?php
/**
 * Install Function
 *
 * @package     gsa
 * @subpackage  Functions/Install
 * @copyright   Copyright (c) 2016, misterge
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
*/

/**
 * Install
 *
 * Runs on plugin install by setting up the post types, custom taxonomies,etc
 *
 * @return void
 */
function gsa_install() {
	
	// Setup the Custom Post Types
	gsa_setup_gsa_post_types();

	// Setup the Taxonomies
	gsa_setup_taxonomies();

	// Setup the post type status
	gsa_register_post_type_statuses();

	// Clear the permalinks
	flush_rewrite_rules( false );

}



