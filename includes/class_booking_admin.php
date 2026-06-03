<?php
/**
 * Admin Dashboard Booking List Class
 * Creates an admin menu and displays the booking records in a clean table.
 */

// Suraksha Guard: Yo file bahira bata direct access garna namilne banako
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( ! class_exists( 'MV_Booking_Admin' ) ) {

    class MV_Booking_Admin {

        public function __construct() {
            // WordPress Admin Menu tayar garna hook lagako
            add_action( 'admin_menu', array( $this, 'create_admin_menu' ) );
        }

        public function create_admin_menu() {
            // Dashboard sidebar ma "Bookings" menu thपेको
            add_menu_page(
                'All Bookings',          // Page Title
                'Bookings',              // Menu Title (Sidebar ma dekhine)
                'manage_options',        // Capability (Admin le matra herna milne)
                'mv-simple-bookings',    // Menu Slug (Unique ID)
                array( $this, 'render_admin_booking_list' ), // HTML dekhauune function
                'dashicons-calendar-alt',// Menu Icon (Calendar ko logo)
                26                       // Menu ko position (Sidebar ma kata basne)
            );
        }

        public function render_admin_booking_list() {
            global $wpdb;
            $table_name = $wpdb->prefix . 'mv_bookings';
            $bookings = $wpdb->get_results( "SELECT * FROM $table_name ORDER BY id DESC" );
            ?>
            
           






            




            <div class="md-admin-wrap">
                <h1 class="md-header-title">Customer Bookings Manager</h1>

                <div class="md-table-container">
                    <table class="md-table">
                        <thead>
                            <tr>
                                <th style="width: 70px;">ID</th>
                                <th>Customer Details</th>
                                <th>Assigned Service</th>
                                <th>Assigned Staff</th>
                                <th>Booking Date</th>
                                <th>Time Slot</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ( ! empty( $bookings ) ) : ?>
                                <?php foreach ( $bookings as $booking ) : 
                                    $service_name = get_the_title( $booking->service_id );
                                    $staff_name = get_the_title( $booking->staff_id );
                                ?>
                                    <tr>
                                        <td><span class="md-badge-id">#<?php echo esc_html( $booking->id ); ?></span></td>
                                        <td>
                                            <strong><?php echo esc_html( $booking->customer_name ); ?></strong>
                                            <div style="font-size: 12px; color: #6750a4; margin-top: 2px;"><?php echo esc_html( $booking->customer_email ); ?></div>
                                        </td>
                                        <td><?php echo esc_html( $service_name ? $service_name : 'N/A (Deleted)' ); ?></td>
                                        <td><strong><?php echo esc_html( $staff_name ? $staff_name : 'N/A (Deleted)' ); ?></strong></td>
                                        <td><?php echo esc_html( $booking->booking_date ); ?></td>
                                        <td><span class="md-badge-time"><?php echo esc_html( $booking->booking_time ); ?></span></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else : ?>
                                <tr>
                                    <td colspan="6" class="md-no-data">No any booking records found.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <?php
        }
    }
}