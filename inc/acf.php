<?php
/**
 * ACF specific functions
 *
 * @package WP-Bootstrap-Navwalker
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;


//timeline acf constructor 
function treehouse_timeline(){
	if(get_field('timeline_events')){
		echo '<div class="timeline">';
		$events = get_field('timeline_events');
		//var_dump($events);
		foreach ($events as $key => $event) {
			$title = $event['title'] ? "<h2>".get_field('title')."</h2>" : '';
			$content = $event['content'];
			$align = ($key % 2 == 0) ? 'right' : 'left';
			$date = $event['date'];
			echo "
				 <div class='timeline-container {$align}'>
				    <div class='date'>{$date}</div>
				    <i class='icon fa fa-grimace'></i>
				    <div class='content'>
				      {$content}
				    </div>
				  </div>
			";
		}
		echo '</div>';

	}
}


	//save acf json
		add_filter('acf/settings/save_json', 'treehouse_json_save_point');
		 
		function treehouse_json_save_point( $path ) {
		    
		    // update path
		    $path = get_stylesheet_directory() . '/acf-json'; //replace w get_stylesheet_directory() for theme
		    
		    
		    // return
		    return $path;
		    
		}


		// load acf json
		add_filter('acf/settings/load_json', 'treehouse_json_load_point');

		function treehouse_json_load_point( $paths ) {
		    
		    // remove original path (optional)
		    unset($paths[0]);
		    
		    
		    // append path
		    $paths[] = get_stylesheet_directory()  . '/acf-json';//replace w get_stylesheet_directory() for theme
		    
		    
		    // return
		    return $paths;
		    
		}