<?php

// Post Contact Form 7 Values to a Custom MySQL Table
// 1. Create table in Wordpress

//     CREATE TABLE wp_cf7_test(
//     id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
//     name VARCHAR(50)
//     );

function contactform7_before_send_mail( $form_to_DB ) {
    global $wpdb;
    $form_to_DB = WPCF7_Submission::get_instance();
    if ( $form_to_DB )
        $formData = $form_to_DB->get_posted_data();
    $name = $formData['your-name']; // data from Contact Form 7

    $wpdb->insert( 'wp_cf7_test', array( 'name' => $name ), array( '%s' ) );  // send to db
}
remove_all_filters ('wpcf7_before_send_mail');
add_action( 'wpcf7_before_send_mail', 'contactform7_before_send_mail' );


// DISPLAY DATA FROM DB
function my_shortcode($args) {

	global $wpdb;
	$results = $wpdb->get_results( "SELECT * FROM wp_cf7_test");
	if(!empty($results)) {
		foreach ($results as $result) {
			echo '<h2>' . $result->name . '</h2>';
		}
	}	
}

function register_my_shortcode() {
	add_shortcode('my_sc', 'my_shortcode');
}
add_action('init', 'register_my_shortcode');

// ******************************************************************************

// Limit words in text
function limit_words($words, $limit, $append = ' &hellip;') {
       // Add 1 to the specified limit becuase arrays start at 0
       $limit = $limit+1;
       // Store each individual word as an array element
       // Up to the limit
       $words = explode(' ', $words, $limit);
       // Shorten the array by 1 because that final element will be the sum of all the words after the limit
       array_pop($words);
       // Implode the array for output, and append an ellipse
       $words = implode(' ', $words) . $append;
       // Return the result
       return $words;
}

// Usage - limit words to 15
$client_excerpt = limit_words($client_excerpt_long, 15, $append = ' <span class="expand-excerpt">[&hellip;]</span>');

// ********************************************************************************
