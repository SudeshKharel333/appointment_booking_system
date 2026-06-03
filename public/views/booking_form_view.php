<?php
// Suraksha Guard
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

date_default_timezone_set('Asia/Kathmandu');

$staff_members = get_posts( array( 'post_type' => 'mv-staff', 'posts_per_page' => -1, 'post_status' => 'publish' ) );
$services      = get_posts( array( 'post_type' => 'mv-service', 'posts_per_page' => -1, 'post_status' => 'publish' ) );
$working_hours = array(
    '09:00 AM', '10:00 AM', '11:00 AM', '12:00 PM',
    '01:00 PM', '02:00 PM', '03:00 PM', '04:00 PM',
    '05:00 PM'
);

// पहिलो पटक पेज लोड हुँदाको डिफाल्ट तयारी
$selected_date     = date('Y-m-d');
$today             = date('Y-m-d'); 
$current_timestamp = time();

function verify_booking_slot($time_string, $selected_date, $today, $current_time) {
    if ($selected_date === $today) {
        $slot_timestamp = strtotime($time_string);
        if ($slot_timestamp < $current_time) {
            return 'disabled style="color: #a0a0a0; background-color: #f5f5f5; cursor: not-allowed;"';
        }
    }
    return '';
}
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
            <input type="date" id="mv_booking_date" name="booking_date" class="md-input" min="<?php echo date('Y-m-d'); ?>" value="<?php echo esc_attr($selected_date); ?>" required>
        </div>

        <div class="md-field-group">
            <label class="md-label">Select Time Slot</label>
            <select id="mv_booking_time" name="booking_time" class="md-input" required>
                <option value="">-- Choose a Time --</option>
                <?php foreach ( $working_hours as $time ) : ?>
                    <?php $disabled_status = verify_booking_slot($time, $selected_date, $today, $current_timestamp); ?>
                    <option value="<?php echo esc_attr( $time ); ?>" <?php echo $disabled_status; ?>>
                        <?php echo esc_html( $time ); ?>
                        <?php if (!empty($disabled_status)) echo ' (Passed)'; ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <button type="submit" name="mv_submit_booking" class="md-btn">Book Appointment</button>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const dateInput = document.getElementById('mv_booking_date');
    const timeSelect = document.getElementById('mv_booking_time');

    if (!dateInput || !timeSelect) return;

    dateInput.addEventListener('change', function() {
        const selectedDate = this.value;
        
        // वर्डप्रेसको डिफाल्ट एडमिन AJAX URL पत्ता लगाउने
        const ajaxUrl = '<?php echo admin_url('admin-ajax.php'); ?>';
        
        // सुरक्षा टोन समात्ने
        const nonce = document.getElementById('mv_booking_nonce').value;

        // फारम डाटा तयार गर्ने
        const formData = new FormData();
        formData.append('action', 'mv_get_available_slots');
        formData.append('selected_date', selectedDate);
        formData.append('nonce', nonce);

        // समय लोड हुँदै गर्दा बक्सलाई मधुरो (Loading Effect) बनाउने
        timeSelect.style.opacity = '0.5';

        // वर्डप्रेस ब्याकइन्डमा सुटुक्क अनुरोध पठाउने (AJAX Fetch API)
        fetch(ajaxUrl, {
            method: 'POST',
            body: formData
        })
        .then(response => response.text())
        .then(htmlData => {
            // ब्याकइन्डबाट आएको ताजा र फिल्टर गरिएको HTML सिधै ड्रपडाउनमा हालिदिने
            timeSelect.innerHTML = htmlData;
            timeSelect.style.opacity = '1';
        })
        .catch(error => {
            console.error('Error fetching time slots:', error);
            timeSelect.style.opacity = '1';
        });
    });
});
</script>