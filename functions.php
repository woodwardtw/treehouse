<?php
/**
 * UnderStrap functions and definitions
 *
 * @package Understrap
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

// UnderStrap's includes directory.
$understrap_inc_dir = 'inc';

// Array of files to include.
$understrap_includes = array(
	'/theme-settings.php',                  // Initialize theme default settings.
	'/setup.php',                           // Theme setup and custom theme supports.
	'/widgets.php',                         // Register widget area.
	'/enqueue.php',                         // Enqueue scripts and styles.
	'/template-tags.php',                   // Custom template tags for this theme.
	'/pagination.php',                      // Custom pagination for this theme.
	'/hooks.php',                           // Custom hooks.
	'/extras.php',                          // Custom functions that act independently of the theme templates.
	'/customizer.php',                      // Customizer additions.
	'/custom-comments.php',                 // Custom Comments file.
	'/class-wp-bootstrap-navwalker.php',    // Load custom WordPress nav walker. Trying to get deeper navigation? Check out: https://github.com/understrap/understrap/issues/567.
	'/editor.php',                          // Load Editor functions.
	'/block-editor.php',                    // Load Block Editor functions.
	'/acf.php',                          // Load ACF functions.
	'/deprecated.php',                      // Load deprecated functions.
);

// Load WooCommerce functions if WooCommerce is activated.
if ( class_exists( 'WooCommerce' ) ) {
	$understrap_includes[] = '/woocommerce.php';
}

// Load Jetpack compatibility file if Jetpack is activiated.
if ( class_exists( 'Jetpack' ) ) {
	$understrap_includes[] = '/jetpack.php';
}

// Include files.
foreach ( $understrap_includes as $file ) {
	require_once get_theme_file_path( $understrap_inc_dir . $file );
}

//Take over format dropdown in TinyMCE classic editor 
//from https://gist.github.com/psorensen/ab45d9408be658b6f90dfeabf1c9f4e6
function mce_formats( $init ) {

	$formats = array(
		'p'          => __( 'Paragraph', 'text-domain' ),
		'h1'         => __( 'Heading 1', 'text-domain' ),
		'h2'         => __( 'Heading 2', 'text-domain' ),
		'h3'         => __( 'Heading 2', 'text-domain' ),
		'h4'         => __( 'Heading 2', 'text-domain' ),
		'h5'         => __( 'Heading 2', 'text-domain' ),
		'h6'         => __( 'Heading 2', 'text-domain' ),
		'pre'        => __( 'Preformatted', 'text-domain' ),
		'blockquote' => __( 'Blockquote', 'text-domain' ),		
	);
    
    // concat array elements to string
	array_walk( $formats, function ( $key, $val ) use ( &$block_formats ) {
		$block_formats .= esc_attr( $key ) . '=' . esc_attr( $val ) . ';';
	}, $block_formats = '' );

	$init['block_formats'] = $block_formats;

	return $init;
}
add_filter( 'tiny_mce_before_init', __NAMESPACE__ . '\\mce_formats' );


//sets the publication year to match the oldest award year for projects
function treehouse_reset_year($new_status, $old_status, $post){
	if($post->post_type == 'project'){
			remove_action( 'transition_post_status', 'treehouse_reset_year', 10 );
			$new_year = get_the_terms($post->ID, 'award-year')[0]->name;//gets oldest year from award terms
			$og_date = strval(get_the_date('Y-m-d',$post->ID));		
			$new_date = $new_year . substr($og_date, -6);	
			$args = array(
				'ID'            => $post->ID,
				'post_date'     => $new_date,
			);
		    wp_update_post( $args );
		    add_action( 'transition_post_status', 'treehouse_reset_year', 10, 3 );	
		    // 
		}
	
	}
add_action( 'transition_post_status', 'treehouse_reset_year', 10, 3 );

//change default page template to full width
function treehouse_default_page_template() {
    global $post;
    if ( 'page' == $post->post_type 
        && 0 != count( get_page_templates( $post ) ) 
        && get_option( 'page_for_posts' ) != $post->ID // Not the page for listing posts
        && '' == $post->page_template // Only when page_template is not set
    ) {
        $post->page_template = "page-templates/fullwidthpage.php";
    }
}
add_action('add_meta_boxes', 'treehouse_default_page_template', 1);


add_action( 'init', 'create_award_year_taxonomies', 0 );
function create_award_year_taxonomies()
{
  // Add new taxonomy, NOT hierarchical (like tags)
  $labels = array(
    'name' => _x( 'Award years', 'taxonomy general name' ),
    'singular_name' => _x( 'Award year', 'taxonomy singular name' ),
    'search_items' =>  __( 'Search Award years' ),
    'popular_items' => __( 'Popular Award years' ),
    'all_items' => __( 'All Award_years' ),
    'parent_item' => null,
    'parent_item_colon' => null,
    'edit_item' => __( 'Edit Award years' ),
    'update_item' => __( 'Update award year' ),
    'add_new_item' => __( 'Add New award_year' ),
    'new_item_name' => __( 'New award year' ),
    'add_or_remove_items' => __( 'Add or remove award years' ),
    'choose_from_most_used' => __( 'Choose from the most used Award years' ),
    'menu_name' => __( 'Award year' ),
  );

//registers taxonomy specific post types - default is just post
  register_taxonomy('award-year', array('project'), array(
    'hierarchical' => false,
    'labels' => $labels,
    'show_ui' => false,
    'update_count_callback' => '_update_post_term_count',
    'query_var' => true,
    'rewrite' => array( 'slug' => 'award-year' ),
    'show_in_rest'          => true,
    'rest_base'             => 'award-year',
    'rest_controller_class' => 'WP_REST_Terms_Controller',
    'show_in_nav_menus' => true,
    'show_in_quick_edit' => true,  
    'show_admin_column' => true  
  ));
}


// WP QUERY LOOP
function treehouse_memories_query(){
	$html = '';
	 $args = array(
	      'posts_per_page' => 50,
	      'post_type'   => 'post', 
	      'post_status' => 'publish', 
	      'category_name' => 'Tree House Memory',
	      'nopaging' => false,
	   
	  );
	  $memory_query = new WP_Query( $args );
        if( $memory_query->have_posts() ): 
          while ( $memory_query->have_posts() ) : $memory_query->the_post();
           //DO YOUR THING
            $title = get_the_title();
            $content = get_the_content();
            $html .= "<div class='memory'>
            			<h2>{$title}</h2>
            			{$content}
            		</div>
            ";

             endwhile;
      endif;
	    wp_reset_query();  // Restore global post data stomped by the_post().
	   return $html;
}                    

add_shortcode( 'memories', 'treehouse_memories_query' );






//LOGGER -- like frogger but more useful

if ( ! function_exists('write_log')) {
   function write_log ( $log )  {
      if ( is_array( $log ) || is_object( $log ) ) {
         error_log( print_r( $log, true ) );
      } else {
         error_log( $log );
      }
   }
}