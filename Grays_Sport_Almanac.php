<?php
/**
 * Plugin Name: Grays Sport Almanac
 * Plugin URI: https://tecnomancia.com
 * Description: Show Future Sports Events Through WordPress. The almanac of the future... NOW!
 * Author: misterge
 * Author URI: https://misterge.tecnomancia.com
 * Version: 0.3
 * Text Domain: gsa
 * @package GSA
 * @category Core
 * @author misterge
 * @version 0.3
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // disable direct access
}

if ( ! class_exists( 'GraysSportAlmanac' ) ) :

/**
 * Register the plugin.
 */
class GraysSportAlmanac
{
	/**
	 * @var string
	 */
	public $version = '0.3';

	/**
	 * Init
	 */
	public static function init() {
		$almanac = new self();
	}

	/**
	 * Constructor
	 */
	public function __construct() {
		$this->setup_constants();
		$this->includes();
		$this->setup_actions();
		$this->setup_shortcode();

		register_activation_hook(__FILE__, 'gsa_install');
	}


	/**
	 * Setup plugin constants
	 *
	 * @access private
	 * @return void
	 */
	function setup_constants()
	{

		// Plugin Folder Path
		if (!defined('GSA_PLUGIN_DIR')) {
			define('GSA_PLUGIN_DIR', plugin_dir_path(__FILE__));
		}

		// Plugin Folder URL
		if (!defined('GSA_PLUGIN_URL')) {
			define('GSA_PLUGIN_URL', plugin_dir_url(__FILE__));
		}

		// Plugin Root File
		if (!defined('GSA_PLUGIN_FILE')) {
			define('GSA_PLUGIN_FILE', __FILE__);
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
		require_once GSA_PLUGIN_DIR . 'includes/template-functions.php';
	}

	/**
	 * Hook Grays Sport Almanac into WordPress
	 */
	private function setup_actions() {

		add_action( 'init', array( $this, 'setup_post_types' ) );
		add_action( 'init', array( $this, 'setup_taxonomies' ) );
		add_action( 'init', array( $this, 'setup_post_type_statuses' ) );

	}


	/**
	 * Registers and sets up the Downloads custom post type
	 *
	 * @return void
	 */
	function setup_post_types() {

		/** Matches Post Type */
		$match_labels = array(
			'name' 				=> _x( 'Matches', 'post type general name', 'gsa' ),
			'singular_name' 	=> _x( 'Match', 'post type singular name', 'gsa' ),
			'menu_name'         => __( 'Grays Sport Almanac' ),
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



	/**
	 * Registers the custom taxonomies for the matches custom post type
	 *
	 * @return void
	 */
	function setup_taxonomies() {
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

	/**
	 * Registers Custom Post Statuses
	 *
	 * @return void
	 */
	function setup_post_type_statuses() {
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

	/**
	 * Get Default Labels
	 *
	 * @since 0.2
	 * @return array $defaults Default labels
	 */
	function get_default_labels() {
		$defaults = array(
			'singular' => __( 'Match', 'gsa' ),
			'plural'   => __( 'Matches', 'gsa')
		);
		return apply_filters( 'default_matches_name', $defaults );
	}


	/**
	 * Get Plural Label
	 *
	 * @since 0.2
	 * @return string $defaults['plural'] Plural label
	 */
	function get_label_plural( $lowercase = false ) {
		$defaults = $this->get_default_labels();
		return ( $lowercase ) ? strtolower( $defaults['plural'] ) : $defaults['plural'];
	}

	/**
	 * Register the [almanac] shortcode.
	 */
	private function setup_shortcode() {

		add_shortcode( 'almanac', array( $this, 'register_shortcode' ) );

	}

	/**
	 * Shortcode used to display almanac
	 *
	 * @return string HTML output of the shortcode
	 */
	public function register_shortcode( $atts ) {
		return $this->gsa_matches_query($atts);
	}

	/**
	 * Matches Shortcode
	 *
	 * This shortcodes uses the WordPress Query API to get matches with the
	 * arguments specified when using the shortcode. A list of the arguments
	 * can be found from the GSA Dccumentation. The shortcode will take all the
	 * parameters and display the matches queried in a valid HTML <div> competitors.
	 *
	 * @since 0.2
	 * @internal Incomplete shortcode
	 * @param array $atts Shortcode attributes
	 * @param string $content
	 * @return string $display Output generated from the matches queried
	 */
	function gsa_matches_query( $atts, $content = null ) {
		$atts = shortcode_atts( array(
			'sport'         => '',
			'exclude_sport' => '',
			'competitors'             => '',
			'exclude_competitors'     => '',
			'relation'         => 'OR',
			'number'           => 9,
			'excerpt'          => 'yes',
			'full_content'     => 'no',
			'columns'          => 3,
			'thumbnails'       => 'true',
			'orderby'          => 'post_date',
			'order'            => 'DESC',
			'ids'              => ''
		), $atts, 'matches' );

		$query = array(
			'post_type'      => 'gsa_match',
			'posts_per_page' => (int) $atts['number'],
			'orderby'        => $atts['orderby'],
			'order'          => $atts['order']
		);

		if ( $query['posts_per_page'] < -1 ) {
			$query['posts_per_page'] = abs( $query['posts_per_page'] );
		}

		switch ( $atts['orderby'] ) {
			case 'price':
				$atts['orderby']   = 'meta_value';
				$query['meta_key'] = 'gsa_price';
				$query['orderby']  = 'meta_value_num';
				break;

			case 'title':
				$query['orderby'] = 'title';
				break;

			case 'id':
				$query['orderby'] = 'ID';
				break;

			case 'random':
				$query['orderby'] = 'rand';
				break;

			default:
				$query['orderby'] = 'post_date';
				break;
		}

		if ( $atts['competitors'] || $atts['sport'] || $atts['exclude_sport'] || $atts['exclude_competitors'] ) {

			$query['tax_query'] = array(
				'relation' => $atts['relation']
			);

			if ( $atts['competitors'] ) {

				$competitor_list = explode( ',', $atts['competitors'] );

				foreach( $competitor_list as $competitor ) {

					if( is_numeric( $competitor ) ) {

						$term_id = $competitor;

					} else {

						$term = get_term_by( 'slug', $competitor, 'gsa_competitor' );

						if( ! $term ) {
							continue;
						}

						$term_id = $term->term_id;
					}

					$query['tax_query'][] = array(
						'taxonomy' => 'gsa_competitor',
						'field'    => 'term_id',
						'terms'    => $term_id
					);
				}

			}

			if ( $atts['sport'] ) {

				$categories = explode( ',', $atts['sport'] );

				foreach( $categories as $sport ) {

					if( is_numeric( $sport ) ) {

						$term_id = $sport;

					} else {

						$term = get_term_by( 'slug', $sport, 'gsa_sport' );

						if( ! $term ) {
							continue;
						}

						$term_id = $term->term_id;

					}

					$query['tax_query'][] = array(
						'taxonomy' => 'gsa_sport',
						'field'    => 'term_id',
						'terms'    => $term_id,
					);

				}

			}

			if ( $atts['exclude_sport'] ) {

				$categories = explode( ',', $atts['exclude_sport'] );

				foreach( $categories as $sport ) {

					if( is_numeric( $sport ) ) {

						$term_id = $sport;

					} else {

						$term = get_term_by( 'slug', $sport, 'gsa_sport' );

						if( ! $term ) {
							continue;
						}

						$term_id = $term->term_id;
					}

					$query['tax_query'][] = array(
						'taxonomy' => 'gsa_sport',
						'field'    => 'term_id',
						'terms'    => $term_id,
						'operator' => 'NOT IN'
					);
				}

			}

			if ( $atts['exclude_competitors'] ) {

				$competitor_list = explode( ',', $atts['exclude_competitors'] );

				foreach( $competitor_list as $competitor ) {

					if( is_numeric( $competitor ) ) {

						$term_id = $competitor;

					} else {

						$term = get_term_by( 'slug', $competitor, 'gsa_competitor' );

						if( ! $term ) {
							continue;
						}

						$term_id = $term->term_id;
					}

					$query['tax_query'][] = array(
						'taxonomy' => 'gsa_competitor',
						'field'    => 'term_id',
						'terms'    => $term_id,
						'operator' => 'NOT IN'
					);

				}

			}
		}

		if ( $atts['exclude_competitors'] || $atts['exclude_sport'] ) {
			$query['tax_query']['relation'] = 'AND';
		}

		if( ! empty( $atts['ids'] ) )
			$query['post__in'] = explode( ',', $atts['ids'] );

		if ( get_query_var( 'paged' ) )
			$query['paged'] = get_query_var('paged');
		else if ( get_query_var( 'page' ) )
			$query['paged'] = get_query_var( 'page' );
		else
			$query['paged'] = 1;

		switch( intval( $atts['columns'] ) ) :
			case 0:
				$column_width = 'inherit'; break;
			case 1:
				$column_width = '100%'; break;
			case 2:
				$column_width = '50%'; break;
			case 3:
				$column_width = '33%'; break;
			case 4:
				$column_width = '25%'; break;
			case 5:
				$column_width = '20%'; break;
			case 6:
				$column_width = '16.6%'; break;
			default:
				$column_width = '33%'; break;
		endswitch;

		// Allow the query to be manipulated by other plugins
		$query = apply_filters( 'gsa_matches_query', $query, $atts );

		$matches = new WP_Query( $query );
		if ( $matches->have_posts() ) :
			$i = 1;
			$wrapper_class = 'gsa_match_columns_' . $atts['columns'];
			ob_start(); ?>
			<div class="gsa_matches_list <?php echo apply_filters( 'gsa_matches_list_wrapper_class', $wrapper_class, $atts ); ?>">
				<?php while ( $matches->have_posts() ) : $matches->the_post(); ?>
					<div class="<?php echo apply_filters( 'gsa_match_class', 'gsa_match', get_the_ID(), $atts, $i ); ?>" id="gsa_match_<?php echo get_the_ID(); ?>" style="width: <?php echo $column_width; ?>; float: left;">
						<div class="gsa_match_inner">
							<?php

							do_action( 'gsa_match_before' );

							if ( 'false' != $atts['thumbnails'] ) :
								gsa_get_template_part( 'shortcode', 'content-image' );
								do_action( 'gsa_match_after_thumbnail' );
							endif;

							gsa_get_template_part( 'shortcode', 'content-title' );
							do_action( 'gsa_match_after_title' );

							if ( $atts['excerpt'] == 'yes' && $atts['full_content'] != 'yes' ) {
								gsa_get_template_part( 'shortcode', 'content-excerpt' );
								do_action( 'gsa_match_after_content' );
							} else if ( $atts['full_content'] == 'yes' ) {
								gsa_get_template_part( 'shortcode', 'content-full' );
								do_action( 'gsa_match_after_content' );
							}

							do_action( 'gsa_match_after' );

							?>
						</div>
					</div>
					<?php if ( $atts['columns'] != 0 && $i % $atts['columns'] == 0 ) { ?><div style="clear:both;"></div><?php } ?>
					<?php $i++; endwhile; ?>

				<div style="clear:both;"></div>

				<?php wp_reset_postdata(); ?>

				<?php
				$pagination = false;

				if ( is_single() ) {
					$pagination = paginate_links( apply_filters( 'gsa_match_pagination_args', array(
						'base'    => get_permalink() . '%#%',
						'format'  => '?paged=%#%',
						'current' => max( 1, $query['paged'] ),
						'total'   => $matches->max_num_pages
					), $atts, $matches, $query ) );
				} else {
					$big = 999999;
					$search_for   = array( $big, '#038;' );
					$replace_with = array( '%#%', '&' );
					$pagination = paginate_links( apply_filters( 'gsa_match_pagination_args', array(
						'base'    => str_replace( $search_for, $replace_with, get_pagenum_link( $big ) ),
						'format'  => '?paged=%#%',
						'current' => max( 1, $query['paged'] ),
						'total'   => $matches->max_num_pages
					), $atts, $matches, $query ) );
				}
				?>

				<?php if ( ! empty( $pagination ) ) : ?>
					<div id="gsa_match_pagination" class="navigation">
						<?php echo $pagination; ?>
					</div>
				<?php endif; ?>

			</div>
			<?php
			$display = ob_get_clean();
		else:
			$display = sprintf( _x( 'No %s found', 'match post type name', 'gsa' ), gsa_get_label_plural() );
		endif;

		return apply_filters( 'matches_shortcode', $display, $atts, $atts['buy_button'], $atts['columns'], $column_width, $matches, $atts['excerpt'], 		$atts['full_content'], $atts['price'], $atts['thumbnails'], $query );
	}
}

endif;

add_action( 'plugins_loaded', array( 'GraysSportAlmanac', 'init' ), 10 );