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

// ******************************************************************************
