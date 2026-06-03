<?php
/**
 * Custom Post Types Registration Class
 * Handles 'mv-staff' and 'mv-service' registration.
 */

// Suraksha Guard: Yo file bahira bata direct access garna नमिल्ने banako
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( ! class_exists( 'MV_Booking_CPT' ) ) {

    class MV_Booking_CPT {

        public function __construct() {
            // WordPress shuru hune bittikai CPT register garna hook (Hook) lagako
            add_action( 'init', array( $this, 'register_custom_post_types' ) );
        }

        public function register_custom_post_types() {
            
            // 1. STAFF ko lagi CPT code
            register_post_type( 'mv-staff', array(
                'labels' => array(
                    'name'          => 'Staff',
                    'singular_name' => 'Staff Member',
                    'add_new_item'  => 'Add New Staff Member',
                    'edit_item'     => 'Edit Staff Member',
                ),
                'public'       => true,
                'show_in_menu' => true, // Dashboard ko sidebar ma menu dekhauna ko lagi
                'menu_icon'    => 'dashicons-businessman', // Staff ko icon (Manche ko logo)
                'supports'     => array( 'title', 'thumbnail' ), // Name ra Photo rakhna dina ko lagi
                'has_archive'  => false,
            ) );

            // 2. SERVICE (Sewa) ko lagi CPT code
            register_post_type( 'mv-service', array(
                'labels' => array(
                    'name'          => 'Services',
                    'singular_name' => 'Service',
                    'add_new_item'  => 'Add New Service',
                    'edit_item'     => 'Edit Service',
                ),
                'public'       => true,
                'show_in_menu' => true, // Dashboard ko sidebar ma menu dekhauna ko lagi
                'menu_icon'    => 'dashicons-admin-tools', // Service ko icon (Aujaar ko logo)
                'supports'     => array( 'title', 'editor' ), // Name ra Description (Bhibaran) rakhna dina ko lagi
                'has_archive'  => false,
            ) );
        }
    }
}