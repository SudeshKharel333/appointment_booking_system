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
            
            <style>
                .md-admin-wrap {
                    margin: 24px 20px 0 0;
                    font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen-Sans, Ubuntu, Cantarell, "Helvetica Neue", sans-serif;
                }
                .md-header-title {
                    font-size: 22px;
                    font-weight: 600;
                    color: #1d1b20;
                    margin-bottom: 20px !important;
                }
                .md-table-container {
                    background: #ffffff;
                    border-radius: 12px;
                    box-shadow: 0px 2px 12px rgba(0, 0, 0, 0.04);
                    border: 1px solid #e0e0e0;
                    overflow: hidden;
                    margin-top: 15px;
                }
                .md-table {
                    width: 100%;
                    border-collapse: collapse;
                    text-align: left;
                }
                .md-table th {
                    background: #f4f2f7; /* Soft light purple material tone */
                    color: #49454f;
                    font-weight: 600;
                    font-size: 13px;
                    padding: 16px;
                    border-bottom: 1px solid #e0e0e0;
                }
                .md-table td {
                    padding: 16px;
                    color: #1d1b20;
                    font-size: 14px;
                    border-bottom: 1px solid #f0f0f0;
                    vertical-align: middle;
                }
                .md-table tr:hover {
                    background-color: #faf8fc; /* Subtle row hover */
                }
                .md-badge-id {
                    background: #6750a4;
                    color: white;
                    padding: 4px 8px;
                    border-radius: 6px;
                    font-size: 11px;
                    font-weight: bold;
                }
                .md-badge-time {
                    background: #e8def8;
                    color: #21005d;
                    padding: 6px 12px;
                    border-radius: 8px;
                    font-weight: 600;
                    font-size: 12px;
                    display: inline-block;
                }
                .md-no-data {
                    padding: 30px !important;
                    text-align: center;
                    color: #79747e;
                    font-style: italic;
                }
            </style>
            
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