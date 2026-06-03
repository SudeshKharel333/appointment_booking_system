<?php
/**
 * Plugin Name: MV Simple Booking System
 * Description: A clean, modular appointment booking system with static slot checking.
 * Version: 1.0.0
 * Author: Sudesh Kharel
 * Text Domain: mv-simple-booking
 */

// Security Gate: Prevent direct file access from malicious outside scripts
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Define Constants for clean file path resolution across the plugin
define( 'MV_BOOKING_PATH', plugin_dir_path( __FILE__ ) );//path of plugin in computer
define( 'MV_BOOKING_URL', plugin_dir_url( __FILE__ ) );//url link of plugin

/**
 * 1. THE ACTIVATION HOOK (Database Table Creation)
 * Runs automatically the exact moment the user clicks "Activate Plugin".
 */
register_activation_hook( __FILE__, 'mv_booking_activate_plugin' );

function mv_booking_activate_plugin() {
    //This line opens a window inside your function and brings 
    //WordPress’s global database manager ($wpdb) into your function. 
    global $wpdb;
    
    // Dynamically prefixes table name (e.g., 'wp_mv_bookings')
    $table_name = $wpdb->prefix . 'mv_bookings'; 
    $charset_collate = $wpdb->get_charset_collate();

    // The optimized SQL schema containing columns for Staff, Service, Date, and Time
    $sql = "CREATE TABLE $table_name (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        customer_name varchar(100) NOT NULL,
        customer_email varchar(100) NOT NULL,
        staff_id bigint(20) NOT NULL,
        service_id bigint(20) NOT NULL,
        booking_date date NOT NULL,
        booking_time varchar(20) NOT NULL,
        status varchar(20) DEFAULT 'confirmed' NOT NULL,
        PRIMARY KEY  (id)
    ) $charset_collate;";

    // Load native WordPress core upgrade file to safely execute dbDelta
    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    //checks if there is fault in the sql query and creates the table if there is no error
    dbDelta( $sql );
}

/**
 * 2. THE MASTER CLASS DEFINITION
 * Wakes up and manages files cleanly using an OOP constructor pattern.
 */
if ( ! class_exists( 'MV_Simple_Booking' ) ) {

    class MV_Simple_Booking {

        public function __construct() {
// WordPress le "Sabai plugin load bhaye" bhanda yo function chalchha            
            add_action( 'plugins_loaded', array( $this, 'initialize_plugin' ) );
        // Front-end ma form dekhauna shortcode register gareko
            add_shortcode( 'mv_simple_booking_form', array( $this, 'render_booking_form' ) );
            }

       public function initialize_plugin() {
            // CPT file load gareko
            require_once MV_BOOKING_PATH . 'includes/class-booking-cpt.php';
            if ( class_exists( 'MV_Booking_CPT' ) ) {
                new MV_Booking_CPT();
            }
            // 2. Naya Form Handler file lai bhitrayako (Load gareko)
            require_once MV_BOOKING_PATH . 'includes/class-booking-handler.php';
            if ( class_exists( 'MV_Booking_Handler' ) ) {
                new MV_Booking_Handler();
            }

            // 3. Naya Admin Booking List file lai bhitrayako (Load gareko)
            require_once MV_BOOKING_PATH . 'includes/class_booking_admin.php';
            if ( class_exists( 'MV_Booking_Admin' ) ) {
                new MV_Booking_Admin();
            }
        }
        // Shortcode chalda yo function le HTML view file load garchha
        public function render_booking_form() {
            // Buffer shuru gareko ta ki HTML direct print nahos, shortcode bhitrai bashi rakhos
            ob_start();
            
            // View template file lai link gareko
            include MV_BOOKING_PATH . 'public/views/booking_form_view.php';
            
            // Buffer close गरेर HTML return gareko
            return ob_get_clean();
        }
        }
    

    // Fire up the engine!
    $mv_simple_booking = new MV_Simple_Booking();
}