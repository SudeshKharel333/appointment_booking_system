<?php
// Suraksha Guard
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

$staff_members = get_posts( array( 'post_type' => 'mv-staff', 'posts_per_page' => -1, 'post_status' => 'publish' ) );
$services      = get_posts( array( 'post_type' => 'mv-service', 'posts_per_page' => -1, 'post_status' => 'publish' ) );
$working_hours = array( '09:00 AM', '10:00 AM', '11:00 AM', '12:00 PM', '01:00 PM', '02:00 PM', '03:00 PM', '04:00 PM' );
?>



<div class="md-card">
    <div class="md-title">Book an Appointment</div>
    
    <?php if ( isset( $_GET['booking'] ) ) : ?>
        <?php if ( $_GET['booking'] === 'success' ) : ?>
            <div class="md-alert md-success"> Success! Booking Confirmed.</div>
        <?php elseif ( $_GET['booking'] === 'already_booked' ) : ?>
            <div class="md-alert md-error"> Error: This slot is already booked!</div>
        <?php endif; ?>
    <?php endif; ?>
    
    <form method="POST" action="">
        <?php wp_nonce_field( 'mv_secure_booking_action', 'mv_booking_nonce' ); ?>

        <div class="md-field-group">
            <label class="md-label">Your Name</label>
            <input type="text" name="customer_name" class="md-input" placeholder="e.g. Ram Thapa" required>
        </div>

        <div class="md-field-group">
            <label class="md-label">Your Email</label>
            <input type="email" name="customer_email" class="md-input" placeholder="name@example.com" required>
        </div>

        <div class="md-field-group">
            <label class="md-label">Select Service</label>
            <select name="service_id" class="md-input" required>
                <option value="">-- Choose a Service --</option>
                <?php foreach ( $services as $service ) : ?>
                    <option value="<?php echo esc_attr( $service->ID ); ?>"><?php echo esc_html( $service->post_title ); ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="md-field-group">
            <label class="md-label">Select Staff Member</label>
            <select name="staff_id" class="md-input" required>
                <option value="">-- Choose a Staff --</option>
                <?php foreach ( $staff_members as $staff ) : ?>
                    <option value="<?php echo esc_attr( $staff->ID ); ?>"><?php echo esc_html( $staff->post_title ); ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="md-field-group">
            <label class="md-label">Select Date</label>
            <input type="date" name="booking_date" class="md-input" min="<?php echo date('Y-m-d'); ?>" required>
        </div>

        <div class="md-field-group">
            <label class="md-label">Select Time Slot</label>
            <select name="booking_time" class="md-input" required>
                <option value="">-- Choose a Time --</option>
                <?php foreach ( $working_hours as $time ) : ?>
                    <option value="<?php echo esc_attr( $time ); ?>"><?php echo esc_html( $time ); ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <button type="submit" name="mv_submit_booking" class="md-btn">Book Appointment</button>
    </form>
</div>