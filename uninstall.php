<?php
/**
 * MV Simple Booking System - Uninstall Script
 * Runs only when the user clicks "Delete" on the WordPress Plugins dashboard.
 */

// १. सुरक्षा ढोका if wordpress didnt build this file itself then exit
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
    exit;
}

// enter data manager
global $wpdb;

// plugin activated table name
$table_name = $wpdb->prefix . 'mv_bookings';

// remove this table from the database forever
$wpdb->query( "DROP TABLE IF EXISTS $table_name" );

