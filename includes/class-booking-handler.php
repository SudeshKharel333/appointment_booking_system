<?php
/**
 * Frontend Form Submission Handler Class
 * Handles form validation, security checks, and database insertion.
 */

// Suraksha Guard: Yo file bahira bata direct access garna namilne banako
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( ! class_exists( 'MV_Booking_Handler' ) ) {

    class MV_Booking_Handler {

        public function __construct() {
            // WordPress shuru hune bittikai form submission check garna hook lagako
            add_action( 'init', array( $this, 'handle_booking_submission' ) );
        }

        public function handle_booking_submission() {
            // 1. Check garne: User le 'Book Appointment' button thicheko ho कि hoina?
            if ( ! isset( $_POST['mv_submit_booking'] ) ) {
                return;
            }

            // 2. Security Check (Nonce Verification): Hacker le bahira bata data pathauna napaos
            if ( ! isset( $_POST['mv_booking_nonce'] ) || ! wp_verify_nonce( $_POST['mv_booking_nonce'], 'mv_secure_booking_action' ) ) {
                wp_die( 'Security check failed!' );
            }

            // 3. Global database manager ($wpdb) lai bhitrayako
            global $wpdb;
            $table_name = $wpdb->prefix . 'mv_bookings';

            // 4. Form bata aako input data lai sanitize (Safaa) gareko, malicious code hatauna
            $customer_name  = sanitize_text_field( $_POST['customer_name'] );
            $customer_email = sanitize_email( $_POST['customer_email'] );
            $service_id     = intval( $_POST['service_id'] );
            $staff_id       = intval( $_POST['staff_id'] );
            $booking_date   = sanitize_text_field( $_POST['booking_date'] );
            $booking_time   = sanitize_text_field( $_POST['booking_time'] );


// 5. DOUBLE-BOOKING CHECK (SABNAY BHANDA IMPORTANT PART)
            // SQL Query le check garchha: Yo Staff, Yo Date ra Yo Time vako row paila dekhinai database ma chha ki chhaina?
            $existing_booking = $wpdb->get_var( $wpdb->prepare(
                "SELECT COUNT(*) FROM $table_name WHERE staff_id = %d AND booking_date = %s AND booking_time = %s",
                $staff_id,
                $booking_date,
                $booking_time
            ) );

            // Jaba $existing_booking ko value 0 bhanda dheri (bhaneko 1) hunchha, tesko matlab slot fill bhaysakyo
            if ( $existing_booking > 0 ) {
                // Page refresh garera url ko pachhadi error parameter thapne (User lai slot paxna rokna)
                $redirect_url = add_query_arg( 'booking', 'already_booked', wp_get_referer() );
                wp_safe_redirect( $redirect_url );
                exit;
            }




            // 5. Data tayar gareko database ma pathauna ko lagi
            $booking_data = array(
                'customer_name'  => $customer_name,
                'customer_email' => $customer_email,
                'service_id'     => $service_id,
                'staff_id'       => $staff_id,
                'booking_date'   => $booking_date,
                'booking_time'   => $booking_time,
            );

            // 6. Database ko table ma data insert (Save) gareko
            $inserted = $wpdb->insert( $table_name, $booking_data );

            // 7. Success bhayo bhane user lai euta message dekhauuna query parameter thapeko
            if ( $inserted ) {
                // Page lai refresh garera success message deko (Form re-submission rokna)
                $redirect_url = add_query_arg( 'booking', 'success', wp_get_referer() );
                wp_safe_redirect( $redirect_url );
                exit;
            }
        }
    }
}