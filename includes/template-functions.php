<?php
/**
 * Template Functions
 *
 * @package     GSA
 * @subpackage  Functions/Templates
 * @copyright   Copyright (c) 2016, misterge
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       0.2
 */


/**
 * Returns the path to the GSA templates directory
 *
 * @since 0.2
 * @return string
 */
function gsa_get_templates_dir() {
	return GSA_PLUGIN_DIR . 'templates';
}

/**
 * Returns the URL to the GSA templates directory
 *
 * @since 0.2
 * @return string
 */
function gsa_get_templates_url() {
	return GSA_PLUGIN_URL . 'templates';
}


/**
 * Retrieve the name of the highest priority template file that exists.
 *
 * Searches in the STYLESHEETPATH before TEMPLATEPATH so that themes which
 * inherit from a parent theme can just overload one file. If the template is
 * not found in either of those, it looks in the theme-compat folder last.
 *
 * Taken from bbPress
 *
 * @since 0.2
 *
 * @param string|array $template_names Template file(s) to search for, in order.
 * @param bool $load If true the template file will be loaded if it is found.
 * @param bool $require_once Whether to require_once or require. Default true.
 *   Has no effect if $load is false.
 * @return string The template filename if one is located.
 */
function gsa_locate_template( $template_names, $load = false, $require_once = true ) {
	// No file found yet
	$located = false;

	// Try to find a template file
	foreach ( (array) $template_names as $template_name ) {

		// Continue if template is empty
		if ( empty( $template_name ) )
			continue;

		// Trim off any slashes from the template name
		$template_name = ltrim( $template_name, '/' );

		// try locating this template file by looping through the template paths
		foreach( gsa_get_theme_template_paths() as $template_path ) {

			if( file_exists( $template_path . $template_name ) ) {
				$located = $template_path . $template_name;
				break;
			}
		}

		if( $located ) {
			break;
		}
	}

	if ( ( true == $load ) && ! empty( $located ) )
		load_template( $located, $require_once );

	return $located;
}



/**
 * Returns a list of paths to check for template locations
 *
 * @since 0.2
 * @return mixed|void
 */
function gsa_get_theme_template_paths() {

	$template_dir = gsa_get_theme_template_dir_name();

	$file_paths = array(
		1 => trailingslashit( get_stylesheet_directory() ) . $template_dir,
		10 => trailingslashit( get_template_directory() ) . $template_dir,
		100 => gsa_get_templates_dir()
	);

	$file_paths = apply_filters( 'gsa_template_paths', $file_paths );

	// sort the file paths based on priority
	ksort( $file_paths, SORT_NUMERIC );

	return array_map( 'trailingslashit', $file_paths );
}



/**
 * Returns the template directory name.
 *
 * Themes can filter this by using the gsa_templates_dir filter.
 *
 * @since 0.2
 * @return string
 */
function gsa_get_theme_template_dir_name() {
	return trailingslashit( apply_filters( 'gsa_templates_dir', 'gsa_templates' ) );
}

/**
 * Should we add schema.org microdata?
 *
 * @since 0.2
 * @return bool
 */
function gsa_add_schema_microdata() {
	// Don't modify anything until after wp_head() is called
	$ret = (bool)did_action( 'wp_head' );
	return apply_filters( 'gsa_add_schema_microdata', $ret );
}




/**
* Retrieves a template part
*
* @since 0.2
*
* @param string $slug
* @param string $name Optional. Default null
* @param bool   $load
*
* @return string
*
* @uses gsa_locate_template()
* @uses load_template()
* @uses get_template_part()
*/
function gsa_get_template_part( $slug, $name = null, $load = true ) {
	// Execute code for this part
	do_action( 'get_template_part_' . $slug, $slug, $name );
	
	// Setup possible parts
	$templates = array();
	if ( isset( $name ) )
	$templates[] = $slug . '-' . $name . '.php';
	$templates[] = $slug . '.php';
	
	// Allow template parts to be filtered
	$templates = apply_filters( 'gsa_get_template_part', $templates, $slug, $name );
	
	// Return the part that is found
	return gsa_locate_template( $templates, $load, false );
}