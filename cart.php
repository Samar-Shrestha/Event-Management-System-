<?php
include('Database/connect.php');
include('session.php');
include('paypal_config.php');

// Fetch selected themes from temporary cart
$result = mysqli_query($con, "SELECT * FROM temp");

if (mysqli_num_rows($result) === 0) {
    header("Location: gallery.php");
    exit();
}

$theme_items = [];
$total_price = 0;

while ($row = mysqli_fetch_assoc($result)) {
    $theme_items[] = $row;
    $total_price += $row['price'];
}

// Handle booking submission
if (isset($_POST['submit'])) {

    $customer_name   = mysqli_real_escape_string($con, $_POST['nm']);
    $customer_email  = mysqli_real_escape_string($con, $_POST['email']);
    $customer_mobile = mysqli_real_escape_string($con, $_POST['mo']);
    $booking_date    = mysqli_real_escape_string($con, $_POST['date']);

    $unavailable_themes = [];

    // Check availability for each theme
    foreach ($theme_items as $item) {
        $check = mysqli_query(
            $con,
            "SELECT id FROM booking 
             WHERE thm_nm = '{$item['nm']}'
             AND date = '{$booking_date}'
             AND payment_status = 'completed'"
        );

        if (mysqli_num_rows($check) > 0) {
            $unavailable_themes[] = $item['nm'];
        }
    }

    if (!empty($unavailable_themes)) {
        $themes = implode(", ", $unavailable_themes);
        echo "<script>alert('The following themes are already booked on $booking_date: $themes');</script>";
    } else {

        // Initialize session array safely
        if (!isset($_SESSION['booking_ids'])) {
            $_SESSION['booking_ids'] = [];
        }

        // Insert booking records
        foreach ($theme_items as $item) {
            mysqli_query(
                $con,
                "INSERT INTO booking (nm, email, mo, theme, thm_nm, price, date, payment_status)
                 VALUES (
                    '$customer_name',
                    '$customer_email',
                    '$customer_mobile',
                    '{$item['img']}',
                    '{$item['nm']}',
                    '{$item['price']}',
                    '$booking_date',
                    'pending'
                 )"
            );

            $_SESSION['booking_ids'][] = mysqli_insert_id($con);
        }

        // Store PayPal session data
        $_SESSION['booking_info'] = [
            'customer_name'  => $customer_name,
            'customer_email' => $customer_email,
            'booking_date'   => $booking_date,
            'total_price'    => $total_price
        ];

        header("Location: process_payment.php");
        exit();
    }
}

// Display first theme
$first_theme = $theme_items[0];

// ✅ Include header AFTER all PHP logic
include("header.php");
?>



<!DOCTYPE html>
<html>
<head>
    <title>Book Event - Classic Event</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <style>
        .payment-info {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
            border-left: 4px solid #007bff;
        }
        .price-summary {
            background: #e9f7ef;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
            border-left: 4px solid #28a745;
        }
        .paypal-button {
            background: #0070ba;
            color: white;
            border: none;
            padding: 15px 30px;
            border-radius: 5px;
            font-size: 18px;
            cursor: pointer;
            margin: 20px 0;
            width: 100%;
        }
        .paypal-button:hover {
            background: #005ea6;
        }
    </style>
</head>
<body>

<div class="codes">
    <div class="container"> 
        <h3 class='w3ls-hdg' align="center">BOOKING & PAYMENT</h3>
        
        <div class="price-summary">
            <h4>Price Summary</h4>
            <?php foreach($theme_items as $item): ?>
                <p><?php echo $item['nm']; ?>: Rs. <?php echo number_format($item['price']); ?></p>
            <?php endforeach; ?>
            <hr>
            <h5>Total Amount: Rs. <?php echo number_format($total_price); ?></h5>
        </div>
        
        <div class="payment-info">
            <h4><i class="fa fa-lock"></i> Secure Payment</h4>
            <p>Your payment will be processed securely through PayPal. You can pay with credit/debit card or PayPal account.</p>
        </div>
        
        <div class="grid_3 grid_4">
            <div class="tab-content">
                <div class="tab-pane active" id="horizontal-form">
                    <form class="form-horizontal" action="" method="post" enctype="multipart/form-data">
                        
                        <div class="form-group">
                            <label for="focusedinput" class="col-sm-2 control-label">Name</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control1" name="nm" pattern="[A-Za-z\s]{2,30}" title="Only Letter For Name" id="focusedinput" placeholder="Name" required="">
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="smallinput" class="col-sm-2 control-label label-input-sm">Email</label>
                            <div class="col-sm-8">
                                <input type="email" class="form-control1 input-sm" name="email" title="Enter Proper Email Id" id="smallinput" placeholder="Email" required="">
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="smallinput" class="col-sm-2 control-label label-input-sm">Mobile no</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control1 input-sm" name="mo" id="smallinput" pattern="([7-9]{1})+([0-9]{9})" title="Only Number" maxlength="10" placeholder="Mobile no" required=""/>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="focusedinput" class="col-sm-2 control-label">Your Theme :</label>
                            <div class="col-sm-8">
                                <img src="./images/<?php echo $first_theme['img']; ?>" height="200" width="300"/>
                                <?php if(count($theme_items) > 1): ?>
                                    <p><small>+ <?php echo count($theme_items)-1; ?> more theme(s)</small></p>
                                <?php endif; ?>
                            </div>		
                        </div>
                        
                        <div class="form-group">
                            <label for="disabledinput" class="col-sm-2 control-label">Theme Name :</label>
                            <div class="col-sm-8">
                                <input disabled="" type="text" class="form-control1" value="<?php echo $first_theme['nm']; ?>" placeholder="Theme Name">
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="event_date" class="col-sm-2 control-label label-input-sm">Event Date</label>
                            <div class="col-sm-8">
                                <input type="date" class="form-control1 input-sm" name="date" id="event_date" min="<?php echo date('Y-m-d'); ?>" placeholder="DD/MM/YYYY" required=""/>
                                <small style="color: #888; display: block; margin-top: 5px;">Select your event date (future dates only)</small>
                            </div>
                        </div>
                        
                        <!-- Check Availability Button -->
                        <div class="form-group">
                            <label class="col-sm-2 control-label"></label>
                            <div class="col-sm-8">
                                <button type="button" id="check_availability" class="btn btn-info" style="background-color: #5bc0de; color: white; padding: 10px 20px; border: none; cursor: pointer; border-radius: 4px; margin-bottom: 10px;">
                                    Check Date Availability
                                </button>
                                <p id="availability_result" style="margin-top: 10px; font-weight: bold; font-size: 14px;"></p>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="col-sm-2 control-label"></label>
                            <div class="col-sm-8">
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" name="terms" required> I agree to the <a href="terms.php" target="_blank">Terms and Conditions</a>
                                    </label>
                                </div>
                            </div>
                        </div>
                        
                        <div class="contact-w3form" align="center">
                            <button type="submit" name="submit" class="paypal-button">
                                <i class="fa fa-paypal"></i> Proceed to PayPal Payment
                            </button>
                            <p style="margin-top: 10px; color: #666;">
                                <small>You will be redirected to PayPal for secure payment</small>
                            </p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function(){
    // Datepicker
    $("#event_date").datepicker({
        changeMonth: true,
        changeYear: true,
        minDate: 0,
        dateFormat: 'yy-mm-dd'
    });
    
    // Check availability
    $('#check_availability').click(function(){
        var date = $('#event_date').val();
        var theme_names = <?php echo json_encode(array_column($theme_items, 'nm')); ?>;
        
        if(date == '') {
            alert('Please select a date first');
            return false;
        }
        
        $('#availability_result').html('<span style="color: #888;">⏳ Checking availability...</span>');
        
        $.ajax({
            url: 'check_date_availability.php',
            method: 'POST',
            data: {date: date, theme_names: theme_names},
            success: function(response){
                if(response.trim() == 'available') {
                    $('#availability_result').html('<span style="color: green; font-size: 16px;">✓ Great! All themes are available for this date!</span>');
                } else {
                    $('#availability_result').html('<span style="color: red; font-size: 16px;">✗ ' + response + '</span>');
                }
            },
            error: function(){
                $('#availability_result').html('<span style="color: orange;">⚠ Error checking availability. Please try again.</span>');
            }
        });
    });
});
</script>

</body>
</html>

<?php 
    include_once("footer.php");
?>