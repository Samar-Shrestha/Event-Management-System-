<?php
include('Database/connect.php');
include('session.php');  // ensures user is logged in
include('paypal_config.php');

// Get current session ID
$session_id = session_id();

// ─── REMOVE SINGLE ITEM ─────────────────────────────────────────────
if (isset($_GET['remove_id'])) {
    $remove_id = (int)$_GET['remove_id'];
    mysqli_query($con, "DELETE FROM temp WHERE id = $remove_id AND user_session = '$session_id'");
    header("Location: cart.php");
    exit();
}

// ─── CLEAR ENTIRE CART ──────────────────────────────────────────────
if (isset($_GET['clear_cart'])) {
    mysqli_query($con, "DELETE FROM temp WHERE user_session = '$session_id'");
    header("Location: cart.php");
    exit();
}

// Fetch cart items
$result = mysqli_query($con, "SELECT * FROM temp WHERE user_session = '$session_id'");

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

// ── Handle booking submission ────────────────────────────────────────────────
if (isset($_POST['submit'])) {

    $customer_name   = mysqli_real_escape_string($con, $_POST['nm']);
    $customer_email  = mysqli_real_escape_string($con, $_POST['email']);
    $customer_mobile = mysqli_real_escape_string($con, $_POST['mo']);
    $booking_date    = mysqli_real_escape_string($con, $_POST['date']);

    $errors = [];

    // ── 1. Check: same user already has a booking (any theme) on this date ───
    $user_date_check = mysqli_query($con, "
        SELECT id, thm_nm FROM booking
        WHERE email = '$customer_email'
        AND date = '$booking_date'
        AND payment_status IN ('pending','completed')
        LIMIT 1
    ");
    if (mysqli_num_rows($user_date_check) > 0) {
        $existing = mysqli_fetch_assoc($user_date_check);
        $errors[] = "You have already booked an event on $booking_date (Theme: {$existing['thm_nm']}). You cannot book another event on the same day.";
    }

    // ── 2. Check: date already taken by ANY other user (global uniqueness) ───
    $global_date_check = mysqli_query($con, "
        SELECT id, email, thm_nm FROM booking
        WHERE date = '$booking_date'
        AND payment_status IN ('pending','completed')
        AND email != '$customer_email'
        LIMIT 1
    ");
    if (mysqli_num_rows($global_date_check) > 0) {
        $taken = mysqli_fetch_assoc($global_date_check);
        $errors[] = "The date $booking_date is already booked by another customer. Please choose a different date.";
    }

    if (!empty($errors)) {
        $error_msg = implode("\\n", $errors);
        echo "<script>alert('$error_msg'); window.history.back();</script>";
        exit();
    }

    // ── 3. All checks passed — insert bookings ───────────────────────────────
    if (!isset($_SESSION['booking_ids'])) {
        $_SESSION['booking_ids'] = [];
    }

    foreach ($theme_items as $item) {
        $thm_img = mysqli_real_escape_string($con, $item['img']);
        $thm_nm  = mysqli_real_escape_string($con, $item['nm']);
        $thm_prc = (int)$item['price'];

        $insert_query = "
            INSERT INTO booking (nm, email, mo, theme, thm_nm, price, date, payment_status)
            VALUES (
                '$customer_name',
                '$customer_email',
                '$customer_mobile',
                '$thm_img',
                '$thm_nm',
                '$thm_prc',
                '$booking_date',
                'pending'
            )
        ";
        mysqli_query($con, $insert_query);
        $_SESSION['booking_ids'][] = mysqli_insert_id($con);
    }

    // Store booking info in session for PayPal
    $_SESSION['booking_info'] = [
        'customer_name'  => $customer_name,
        'customer_email' => $customer_email,
        'booking_date'   => $booking_date,
        'total_price'    => $total_price
    ];

    // Clear the cart for this session
    mysqli_query($con, "DELETE FROM temp WHERE user_session = '$session_id'");

    header("Location: process_payment.php");
    exit();
}

// Display first theme
$first_theme = $theme_items[0];

// Pre-fill name and email from the registration table using the logged-in username
$prefill_name  = '';
$prefill_email = '';
if (isset($_SESSION['uname'])) {
    $sess_user = mysqli_real_escape_string($con, $_SESSION['uname']);
    $reg_row   = mysqli_query($con, "SELECT nm, email FROM registration WHERE unm='$sess_user' LIMIT 1");
    if ($reg_row && mysqli_num_rows($reg_row) > 0) {
        $reg_data      = mysqli_fetch_assoc($reg_row);
        $prefill_name  = $reg_data['nm'];
        $prefill_email = $reg_data['email'];
    }
}

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
        .paypal-button:hover { background: #005ea6; }
        .date-taken-msg {
            display: none;
            background: #FEF2F2;
            border: 1px solid #FECACA;
            color: #991B1B;
            padding: 10px 14px;
            border-radius: 6px;
            margin-top: 8px;
            font-size: 14px;
        }
        /* Cart item styling */
        .cart-item {
            background: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
        }
        .cart-item-info {
            flex: 1;
        }
        .cart-item-img {
            width: 80px;
            height: 80px;
            object-fit: cover;
            margin-right: 20px;
            border-radius: 5px;
        }
        .remove-link {
            color: #d9534f;
            font-weight: bold;
            text-decoration: none;
            padding: 5px 10px;
            border: 1px solid #d9534f;
            border-radius: 5px;
            background: white;
            transition: 0.2s;
        }
        .remove-link:hover {
            background: #d9534f;
            color: white;
        }
        .clear-cart-btn {
            background: #d9534f;
            color: white;
            border: none;
            padding: 8px 20px;
            border-radius: 5px;
            margin-bottom: 20px;
            cursor: pointer;
        }
        .clear-cart-btn:hover { background: #c9302c; }

        /* ── Terms popup ── */
        #terms_trigger {
            color: #007bff;
            text-decoration: underline;
            cursor: pointer;
            font-weight: 500;
        }
        #terms_trigger:hover {
            color: #0056b3;
        }
        #terms_popup {
            display: none;
            background: #fff;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 18px 20px;
            margin-top: 12px;
            font-size: 13px;
            box-shadow: 0 4px 16px rgba(0,0,0,0.13);
            position: relative;
            z-index: 100;
        }
        #terms_popup .terms-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 12px;
            border-bottom: 1px solid #eee;
            padding-bottom: 10px;
        }
        #terms_popup .terms-header strong {
            font-size: 15px;
            color: #333;
        }
        #close_terms {
            background: none;
            border: none;
            font-size: 22px;
            cursor: pointer;
            color: #888;
            line-height: 1;
            padding: 0 4px;
        }
        #close_terms:hover { color: #333; }
        #terms_popup ol {
            margin: 0 0 12px 0;
            padding-left: 18px;
            line-height: 1.9;
            color: #444;
        }
        #terms_popup ol li {
            margin-bottom: 4px;
        }
        .no-refund-box {
            background: #FEF2F2;
            border-left: 4px solid #d9534f;
            padding: 10px 14px;
            border-radius: 4px;
            color: #991B1B;
            font-weight: 600;
            font-size: 13px;
            margin-top: 12px;
        }
        .terms-footer-note {
            margin-top: 10px;
            font-size: 12px;
            color: #888;
        }
    </style>
</head>
<body>

<div class="codes">
    <div class="container">
        <h3 class='w3ls-hdg' align="center">BOOKING & PAYMENT</h3>

        <!-- Cart Items List with Remove Options -->
        <div style="margin-bottom: 30px;">
            <h4>Your Selected Events</h4>
            <div style="text-align: right; margin-bottom: 10px;">
                <a href="?clear_cart=1" class="clear-cart-btn" onclick="return confirm('Remove all items from cart?')">&#128465; Clear Cart</a>
            </div>
            <?php foreach($theme_items as $item): ?>
                <div class="cart-item">
                    <div style="display: flex; align-items: center;">
                        <img src="./images/<?php echo htmlspecialchars($item['img']); ?>" class="cart-item-img">
                        <div class="cart-item-info">
                            <strong><?php echo htmlspecialchars($item['nm']); ?></strong><br>
                            Price: Rs. <?php echo number_format($item['price']); ?>
                        </div>
                    </div>
                    <div>
                        <a href="?remove_id=<?php echo $item['id']; ?>" class="remove-link" onclick="return confirm('Remove this event?')">&#10006; Remove</a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="price-summary">
            <h4>Price Summary</h4>
            <?php foreach($theme_items as $item): ?>
                <p><?php echo htmlspecialchars($item['nm']); ?>: Rs. <?php echo number_format($item['price']); ?></p>
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
                            <label class="col-sm-2 control-label">Name</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control1" name="nm"
                                    pattern="[A-Za-z\s]{2,30}" title="Only letters for name"
                                    placeholder="Name" required value="<?php echo htmlspecialchars($prefill_name); ?>">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label label-input-sm">Email</label>
                            <div class="col-sm-8">
                                <input type="email" class="form-control1 input-sm" name="email"
                                    placeholder="Email" required value="<?php echo htmlspecialchars($prefill_email); ?>">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label label-input-sm">Mobile no</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control1 input-sm" name="mo"
                                    pattern="([7-9]{1})+([0-9]{9})" title="10-digit mobile number"
                                    maxlength="10" placeholder="Mobile no" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label">Your Theme:</label>
                            <div class="col-sm-8">
                                <img src="./images/<?php echo htmlspecialchars($first_theme['img']); ?>" height="200" width="300"/>
                                <?php if(count($theme_items) > 1): ?>
                                    <p><small>+ <?php echo count($theme_items)-1; ?> more theme(s)</small></p>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label">Theme Name:</label>
                            <div class="col-sm-8">
                                <input disabled type="text" class="form-control1"
                                    value="<?php echo htmlspecialchars($first_theme['nm']); ?>">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label label-input-sm">Event Date</label>
                            <div class="col-sm-8">
                                <input type="date" class="form-control1 input-sm" name="date"
                                    id="event_date" min="<?php echo date('Y-m-d'); ?>"
                                    placeholder="DD/MM/YYYY" required>
                                <small style="color:#888;display:block;margin-top:5px;">
                                    Select your event date (future dates only)
                                </small>
                                <div class="date-taken-msg" id="date_taken_msg">
                                    &#9888;&#65039; This date is already booked. Please choose a different date.
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label"></label>
                            <div class="col-sm-8">
                                <button type="button" id="check_availability" class="btn btn-info"
                                    style="background:#5bc0de;color:white;padding:10px 20px;border:none;cursor:pointer;border-radius:4px;margin-bottom:10px;">
                                    Check Date Availability
                                </button>
                                <p id="availability_result" style="margin-top:10px;font-weight:bold;font-size:14px;"></p>
                            </div>
                        </div>

                        <!-- ── Terms & Conditions with popup ── -->
                        <div class="form-group">
                            <label class="col-sm-2 control-label"></label>
                            <div class="col-sm-8">
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" name="terms" required>
                                        I agree to the
                                        <span id="terms_trigger">Terms and Conditions</span>
                                    </label>
                                </div>

                                <!-- Popup Panel -->
                                <div id="terms_popup">
                                    <div class="terms-header">
                                        <strong>Terms &amp; Conditions</strong>
                                        <button type="button" id="close_terms">&times;</button>
                                    </div>
                                    <ol>
                                        <li>Bookings are confirmed only after full payment is received via PayPal.</li>
                                        <li>All event dates are subject to availability on a first-come, first-served basis.</li>
                                        <li>Only one booking per customer is allowed per date.</li>
                                        <li>Accurate personal details (name, email, mobile number) must be provided at the time of booking.</li>
                                        <li>Classic Event reserves the right to cancel any booking if the provided information is found to be false or misleading.</li>
                                        <li>Event themes are as described on the website; minor decorative variations may apply.</li>
                                        <li>Customers are responsible for arriving on time. Classic Event is not liable for delays caused by the customer.</li>
                                        <li>Classic Event is not responsible for any personal loss, damage, or injury occurring during the event.</li>
                                        <li>By proceeding with the booking, you confirm that you have read, understood, and agreed to all terms listed here.</li>
                                    </ol>
                                    <div class="no-refund-box">
                                        &#9888; No Refund Policy: All payments are strictly non-refundable. Once a booking is confirmed and payment is processed, no cancellations or refunds will be issued under any circumstances.
                                    </div>
                                    <p class="terms-footer-note">By checking the box, you acknowledge that you have read and agree to these terms and conditions.</p>
                                </div>
                                <!-- End Popup Panel -->

                            </div>
                        </div>
                        <!-- ── End Terms & Conditions ── -->

                        <div class="contact-w3form" align="center">
                            <button type="submit" name="submit" class="paypal-button" id="submit_btn">
                                <i class="fa fa-paypal"></i> Proceed to PayPal Payment
                            </button>
                            <p style="margin-top:10px;color:#666;">
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

    // ── Date picker ──────────────────────────────────────────────────────────
    $("#event_date").datepicker({
        changeMonth: true,
        changeYear: true,
        minDate: 0,
        dateFormat: 'yy-mm-dd',
        onSelect: function(dateText) {
            checkDateLive(dateText);
        }
    });

    $("#event_date").on('change', function(){
        checkDateLive($(this).val());
    });

    function checkDateLive(date) {
        if (!date) return;
        $.ajax({
            url: 'check_date_availability.php',
            method: 'POST',
            data: {
                date: date,
                theme_names: <?php echo json_encode(array_column($theme_items, 'nm')); ?>
            },
            success: function(response){
                if (response.trim() === 'available') {
                    $('#date_taken_msg').hide();
                    $('#submit_btn').prop('disabled', false).css('opacity','1');
                    $('#availability_result').html('<span style="color:green;font-size:16px;">&#10003; Date is available!</span>');
                } else {
                    $('#date_taken_msg').show();
                    $('#submit_btn').prop('disabled', true).css('opacity','0.5');
                    $('#availability_result').html('<span style="color:red;font-size:16px;">&#10007; ' + response + '</span>');
                }
            }
        });
    }

    $('#check_availability').click(function(){
        var date = $('#event_date').val();
        if (date == '') {
            alert('Please select a date first');
            return;
        }
        checkDateLive(date);
    });

    // ── Terms & Conditions popup ─────────────────────────────────────────────
    $('#terms_trigger').click(function(e){
        e.preventDefault();
        var $popup = $('#terms_popup');
        if ($popup.is(':visible')) {
            $popup.slideUp(180);
        } else {
            $popup.slideDown(220);
        }
    });

    $('#close_terms').click(function(){
        $('#terms_popup').slideUp(180);
    });

    // Close popup if user clicks outside of it
    $(document).on('click', function(e){
        if (
            !$(e.target).closest('#terms_popup').length &&
            !$(e.target).is('#terms_trigger')
        ) {
            $('#terms_popup').slideUp(180);
        }
    });

});
</script>

</body>
</html>

<?php include_once("footer.php"); ?>