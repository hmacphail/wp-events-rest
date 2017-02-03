<?php
/**
 * Plugin Name:     Events REST API
 * Plugin URI:      https://github.com/hmacphail/wp-events-rest
 * Description:     Extends the WordPress REST API to add three custom content type endpoints for the Events Manager plugin.
 * Author:          Heather MacPhail
 * Text Domain:     wp-events-rest
 * Domain Path:     /languages
 * Version:         0.1.0
 *
 * @package         Wp_Events_Rest
 */


/**
 * Register the routes for the objects of the controller.
 */
add_action( 'rest_api_init', function () {
  register_rest_route( 'wp/v2', '/events', array(
    'methods' => 'GET',
    'callback' => 'get_all_events',
  ) );

  register_rest_route( 'wp/v2', '/event/(?P<id>\d+)', array(
    'methods' => 'GET',
    'callback' => 'get_event',
  ) );

  register_rest_route( 'wp/v2', '/locations', array(
    'methods' => 'GET',
    'callback' => 'get_all_locations',
  ) );

  register_rest_route( 'wp/v2', '/location/(?P<id>\d+)', array(
    'methods' => 'GET',
    'callback' => 'get_location',
  ) );

  register_rest_route( 'wp/v2', '/recurring-events', array(
    'methods' => 'GET',
    'callback' => 'get_all_recurring_events',
  ) );

  register_rest_route( 'wp/v2', '/recurring-event/(?P<id>\d+)', array(
    'methods' => 'GET',
    'callback' => 'get_recurring_event',
  ) );
});

/**
 * Get all events
 *
 * @return WP_Error|WP_REST_Response
 */
function get_all_events() {
  $events = EM_Events::get();

  if ( empty( $events ) ) {
    return new WP_Error( 'events_rest_no_events', 'No events found', array( 'status' => 404 ) );
  }

  return new WP_REST_Response( $events, 200 );
}

/**
 * Get one event from the collection
 *
 * @param array $data Options for the function.
 * @return WP_Error|WP_REST_Response
 */
function get_event( $data ) {
  $event = em_get_event($data['id']);

  if ( empty( $event ) || empty( $event->event_id ) ) {
    return new WP_Error( 'events_rest_no_event', 'No event with specified id found', array( 'status' => 404 ) );
  }

  return new WP_REST_Response( $event, 200 );
}

/**
 * Get all locations
 *
 * @return WP_Error|WP_REST_Response
 */
function get_all_locations() {
  $locations = EM_Locations::get();

  if ( empty( $locations ) ) {
    return new WP_Error( 'locations_rest_no_locations', 'No locations found', array( 'status' => 404 ) );
  }

  return new WP_REST_Response( $locations, 200 );
}

/**
 * Get one location from the collection
 *
 * @param array $data Options for the function.
 * @return WP_Error|WP_REST_Response
 */
function get_location( $data ) {
  $location = em_get_location($data['id']);

  if ( empty( $location ) || empty ( $location->location_id) ) {
    return new WP_Error( 'locations_rest_no_location', 'No location with specified id found', array( 'status' => 404 ) );
  }

  return new WP_REST_Response( $location, 200 );
}

/**
 * Get all recurring events
 *
 * @param array $data Options for the function
 * @return WP_Error|WP_REST_Response
 */
function get_all_recurring_events( $data ) {
  $events = EM_Events::get( 'recurrence_id' ); // builds SQL query, so 'recurrence_id is not null'

  if ( empty( $events ) ) {
    return new WP_Error( 'events_rest_no_recurrences', 'No recurring events found', array( 'status' => 404 ) );
  }

  return new WP_REST_Response( $events, 200 );
}

/**
 * Get one recurring event from the collection
 *
 * @param array $data Options for the function.
 * @return WP_Error|WP_REST_Response
 */
function get_recurring_event( $data ) {
  $event = em_get_event($data['id'], 'recurrence');

  if ( empty( $event ) || empty( $event->recurrence_id ) ) {
    return new WP_Error( 'events_rest_no_recurrence', 'No recurring event with specified id found', array( 'status' => 404 ) );
  }

  return new WP_REST_Response( $event, 200 );
}
