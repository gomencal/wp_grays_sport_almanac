<?php
/**
 * Post Type Functions
 *
 * @package     GSA
 * @subpackage  Functions
 * @copyright   Copyright (c) 2016, misterge
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 */


/**
 * Registers and sets up the Downloads custom post type
 *
 * @return void
 */
function gsa_setup_gsa_post_types() {

	/** Matches Post Type */
	$match_labels = array(
		'name' 				=> _x( 'Matches', 'post type general name', 'gsa' ),
		'singular_name' 	=> _x( 'Match', 'post type singular name', 'gsa' ),
		'menu_name'         => __( 'Matches' ),
		'name_admin_bar'    => __( 'Match' ),
		'add_new' 			=> __( 'Add New', 'gsa' ),
		'add_new_item' 		=> __( 'Add New Match', 'gsa' ),
		'edit_item' 		=> __( 'Edit Match', 'gsa' ),
		'new_item' 			=> __( 'New Match', 'gsa' ),
		'all_items' 		=> __( 'All Matches', 'gsa' ),
		'view_item' 		=> __( 'View Match', 'gsa' ),
		'search_items' 		=> __( 'Search Matches', 'gsa' ),
		'not_found' 		=> __( 'No Matches found', 'gsa' ),
		'not_found_in_trash'=> __( 'No Matches found in Trash', 'gsa' ),
		'parent_item_colon' => '',
	);

	$match_args = array(
		'labels' 				=> $match_labels ,
		'public'              	=> true,
		'exclude_from_search' 	=> false,
		'publicly_queryable'  	=> true,
		'show_ui'             	=> true,
		'show_in_nav_menus'  	=> true,
		'show_in_menu'        	=> true,
		'show_in_admin_bar'  	=> true,
		'supports' 				=> array( 'title' , 'editor', 'post-formats' , 'custom-fields' ),
		'can_export'			=> true,
		'menu_position'    		=> 5,
		'menu_icon'     	    => 'dashicons-editor-indent',
		'hierarchical'      	=> false,
		'has_archive'      		=> true,
		'rewrite'           	=> array( 'slug' => 'matches' ),
	);
	register_post_type( 'gsa_match', $match_args );
}
add_action( 'init', 'gsa_setup_gsa_post_types', 1 );



/**
 * Registers the custom taxonomies for the matches custom post type
 *
 * @return void
*/
function gsa_setup_taxonomies() {
	// Add a taxonomy like categories
	// Sports, they can be categorized (football -> sub21...)
	$labels = array(
		'name'              => 'Sports',
		'singular_name'     => 'Sport',
		'search_items'      => 'Search Sports',
		'all_items'         => 'All Sports',
		'parent_item'       => 'Parent Sport',
		'parent_item_colon' => 'Parent Sport:',
		'edit_item'         => 'Edit Sport',
		'update_item'       => 'Update Sport',
		'add_new_item'      => 'Add New Sport',
		'new_item_name'     => 'New Sport Name',
		'menu_name'         => 'Sports',
	);

	$args = array(
		'hierarchical'      => true,
		'labels'            => $labels,
		'show_ui'           => true,
		'show_admin_column' => true,
		'query_var'         => true,
		'rewrite'           => array( 'slug' => 'sport' ),
	);

	register_taxonomy('gsa_sport',array('gsa_match'),$args);

	// Add a taxonomy like tags. Teams/Competitors
	$labels = array(
		'name'                       => 'Competitors',
		'singular_name'              => 'Competitor',
		'search_items'               => 'Competitors',
		'popular_items'              => 'Popular Competitors',
		'all_items'                  => 'All Competitors',
		'parent_item'                => null,
		'parent_item_colon'          => null,
		'edit_item'                  => 'Edit Competitor',
		'update_item'                => 'Update Competitor',
		'add_new_item'               => 'Add New Competitor',
		'new_item_name'              => 'New Competitor Name',
		'separate_items_with_commas' => 'Separate Competitors with commas',
		'add_or_remove_items'        => 'Add or remove Competitors',
		'choose_from_most_used'      => 'Choose from most used Competitors',
		'not_found'                  => 'No Competitors found',
		'menu_name'                  => 'Competitors',
	);

	$args = array(
		'hierarchical'          => false,
		'labels'                => $labels,
		'show_ui'               => true,
		'show_admin_column'     => true,
		'update_count_callback' => '_update_post_term_count',
		'query_var'             => true,
		'rewrite'               => array( 'slug' => 'attribute' ),
	);

	register_taxonomy('gsa_competitor','gsa_match',$args);

}
add_action( 'init', 'gsa_setup_taxonomies', 0 );

/**
 * Registers Custom Post Statuses
 *
 * @return void
 */
function gsa_register_post_type_statuses() {
	// Match Statuses
	register_post_status( 'future', array(
		'label'                     => _x( 'Future', 'Still not played status', 'gsa' ),
		'public'                    => true,
		'exclude_from_search'       => false,
		'show_in_admin_all_list'    => true,
		'show_in_admin_status_list' => true,
		'label_count'               => _n_noop( '<span class="count">(%s)</span> to be played', '<span class="count">(%s)</span> to be played', 'gsa' )
	) );
	register_post_status( 'past', array(
		'label'                     => _x( 'Played', 'Played status', 'gsa' ),
		'public'                    => true,
		'exclude_from_search'       => false,
		'show_in_admin_all_list'    => true,
		'show_in_admin_status_list' => true,
		'label_count'               => _n_noop( '<span class="count">(%s)</span> played', '<span class="count">(%s)</span> played', 'gsa' )
	)  );
	register_post_status( 'present', array(
		'label'                     => _x( 'Playing', 'Playing payment status', 'gsa' ),
		'public'                    => true,
		'exclude_from_search'       => false,
		'show_in_admin_all_list'    => true,
		'show_in_admin_status_list' => true,
		'label_count'               => _n_noop( '<span class="count">(%s)</span> playing just now!', '<span class="count">(%s)</span> playing just now!', 'gsa' )
	)  );

}
add_action( 'init', 'gsa_register_post_type_statuses' );
