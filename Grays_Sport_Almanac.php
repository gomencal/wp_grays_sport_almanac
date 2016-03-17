<?php
/**
 * Plugin Name: Grays Sport Almanac
 * Plugin URI: https://tecnomancia.com
 * Description: Show Future Sports Events Through WordPress. The almanac of the future... NOW!
 * Author: misterge
 * Author URI: https://misterge.tecnomancia.com
 * Version: 0.0.1
 * Text Domain: gsa
 * @package GSA
 * @category Core
 * @author misterge
 * @version 0.0.1
 */


setup_constants();
includes();

register_activation_hook(__FILE__, 'gsa_install');


/**
 * Setup plugin constants
 *
 * @access private
 * @return void
 */
function setup_constants() {

	// Plugin Folder Path
	if ( ! defined( 'GSA_PLUGIN_DIR' ) ) {
		define( 'GSA_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
	}

	// Plugin Folder URL
	if ( ! defined( 'GSA_PLUGIN_URL' ) ) {
		define( 'GSA_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
	}

	// Plugin Root File
	if ( ! defined( 'GSA_PLUGIN_FILE' ) ) {
		define( 'GSA_PLUGIN_FILE', __FILE__ );
	}

}

/**
 * Include required files
 *
 * @access private
 * @return void
 */
function includes()
{

	require_once GSA_PLUGIN_DIR . 'includes/post-types.php';
	require_once GSA_PLUGIN_DIR . 'includes/install.php';



}