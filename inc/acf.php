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
			$title = $event['title'] ? "<h2>{$event['title']}</h2>" : '';
			$content = $event['content'];
			$align = ($key % 2 == 0) ? 'right' : 'left';
			$date = $event['date'];
			echo "
				 <div class='timeline-container {$align}'>
				    <div class='date'>{$date}</div>
				    <i class='icon fa fa-grimace'></i>
				    <div class='content'>
				    	{$title}
				      	{$content}				    
				    </div>
				  </div>
			";
		}
		echo '</div>';

	}
}

//PROJECT FUNCTIONS
function treehouse_project_description(){
	if(get_field('project_description')){
		$desc = get_field('project_description');
		echo "
			<div class='project-block'>
			{$desc}
			</div>
		";
	}
}

function treehouse_project_students(){
	$html = '';
	$students = get_field('students');
	$plural = count(get_field('students')) ? 's' : '';
	if( have_rows('students') ):
		echo "
			<div class='project-sidebar'>
				<h2>Student{$plural}</h2>
		";
	    // Loop through rows.
	    while( have_rows('students') ) : the_row();

	        // Load sub field value.
	        $name = get_sub_field('student_name');
	        $class = get_sub_field('class_of');
	        // Do something...
	        echo "
	        	<div class='project-student-block'>
	        		{$name} - class of {$class}
	        	</div>
	        ";
	    // End loop.
	    endwhile;	
	    echo "</div>";    
		else :
		    // Do something...
		endif;
	}



//change title for ACF flexible layout in collapsed mode

add_filter('acf/fields/flexible_content/layout_title/name=content', 'dlinq_acf_fields_flexible_content_layout_title', 10, 4);
function dlinq_acf_fields_flexible_content_layout_title( $title, $field, $layout, $i ) {

    if( get_sub_field('sub_topic_title') ) {
        $title .= ' - ' . get_sub_field('sub_topic_title');     
    }
	if( get_sub_field('title') ) {
        $title .= ' - ' . get_sub_field('title');     
    }
	 if( get_sub_field('accordion_title') ) {
        $title .= ' - ' . get_sub_field('accordion_title');     
    }

    return $title;
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