<?php

function fullcalander_enqueue_styles() {

    wp_enqueue_style( 'fullcalendar-min', 'https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.9.0/fullcalendar.min.css' );
    
    wp_enqueue_script( 'jquery_min', 'https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js' );
    wp_enqueue_script( 'moment-min', 'https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.20.1/moment.min.js' );
    wp_enqueue_script( 'fullcalendar-min', 'https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.9.0/fullcalendar.min.js' );
    // wp_enqueue_style( 'bootstrap-min', 'https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css' );
    wp_enqueue_script( 'bootstrap-min', 'https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js' );
}

add_action( 'wp_enqueue_scripts', 'fullcalander_enqueue_styles', 99 );

function get_events_data() {
    // Implement your code to fetch events from your data source (e.g., custom database table) here.
    // Query your custom table or external API and return the events as JSON.

    // Example data:
    /*
    $events = array(
        array(
            'event_id' => 1,
            'title' => 'Event 1',
            'start' => '2023-07-20',
            'end' => '2023-07-22',
            'color' => '#FF0000',
            'url' => 'https://example.com/event-1'
        ),
        array(
            'event_id' => 2,
            'title' => 'Event 2',
            'start' => '2023-07-25',
            'end' => '2023-07-27',
            'color' => '#00FF00',
            'url' => 'https://example.com/event-2'
        ),
        // Add more events here...
    );

    */
    // echo get_option('event_date');
    // echo '<pre>'; print_r(get_option('event_date')); echo '<pre>';
    wp_send_json_success(array(json_decode(get_option('event_date'),true)));
    
}
add_action('wp_ajax_get_events', 'get_events_data');
add_action('wp_ajax_nopriv_get_events', 'get_events_data'); // For non-logged-in users

/*
function save_events_data() {
    
    $data_id = update_option('event_date',json_encode($_POST));
    
    if($data_id) {
        $data = array(
                'status' => true,
                'msg' => 'Event added successfully!'
            );
    }else{
        $data = array(
                'status' => false,
                'msg' => 'Sorry, Event not added.'				
            );
    }
    
    echo json_encode($data);
    
    die;
    
}

add_action('wp_ajax_save_events', 'save_events_data');
add_action('wp_ajax_nopriv_save_events', 'save_events_data'); // For non-logged-in users
*/

function save_events_data() {
    $event_name = isset($_POST['event_name']) ? sanitize_text_field($_POST['event_name']) : '';
    $event_start_date = isset($_POST['event_start_date']) ? sanitize_text_field($_POST['event_start_date']) : '';
    $event_end_date = isset($_POST['event_end_date']) ? sanitize_text_field($_POST['event_end_date']) : '';
    
    if (empty($event_name) || empty($event_start_date) || empty($event_end_date)) {
        wp_send_json_error(array('msg' => 'Please enter all required details.'));
    }
    
    // Get the existing events data from the options table
    $events_data = get_option('events_data', array());
    
    // Add the new event to the events data array
    $event_data = array(
        'event_name' => $event_name,
        'event_start_date' => $event_start_date,
        'event_end_date' => $event_end_date,
    );
    
    $events_data[] = $event_data;
    
    // Save the updated events data in the options table
    $data_id = update_option('events_data', $events_data);
    
    if($data_id) {
        $data = array(
                'status' => true,
                'msg' => 'Event added successfully!'
            );
    }else{
        $data = array(
                'status' => false,
                'msg' => 'Sorry, Event not added.'				
            );
    }
    
    echo json_encode($data);
    die;
}
add_action('wp_ajax_save_events_data', 'save_events_data');
add_action('wp_ajax_nopriv_save_events_data', 'save_events_data'); // For non-logged-in users


// add_action('init', function() {
//     $data = get_option('events_data', array());
//     echo '<pre>'; print_r($data); echo '<pre>';
// });


add_action('wp_ajax_delete_events_data', 'delete_events_data');
add_action('wp_ajax_nopriv_delete_events_data', 'delete_events_data');
function delete_events_data() {
    $events_data = get_option('events_data', array());
    
    
    unset($events_data[$_POST['event_id']]);
    
    $data_id = update_option('events_data', $events_data);
    
    if($data_id) {
        $data = array(
                'status' => true,
                'msg' => 'Event deleted successfully!'
            );
    }else{
        $data = array(
                'status' => false,
                'msg' => 'Sorry, Event not deleted.'				
            );
    }
    
    echo json_encode($data);
    die;
}


// add_action('init', function() {
//     $events_data = json_encode(get_option('events_data', array()));
//     echo '<pre>'; print_r($events_data); echo '<pre>';
// });