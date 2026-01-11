<?php
	include('Database/connect.php');
	include('session.php');		
	include("header.php");
	
	// Enable error reporting
	error_reporting(E_ALL);
	ini_set('display_errors', 1);
	
	// Get theme details from temp table
	$q = mysqli_query($con, "SELECT * FROM temp");
	$id = "";
	$image = "";
	$name = "";
	$price = "";
	
	if(mysqli_num_rows($q) == 0)
	{
		echo "<script>alert('Your cart is empty!');</script>";
		echo "<script>window.location='gallery.php';</script>";
		exit();
	}
	
	while($f = mysqli_fetch_row($q))
	{
		$id = $f[0];
		$image = $f[1];
		$name = $f[2];
		$price = $f[3];
	}
	
	// Handle form submission
	if(isset($_POST['submit']))
	{
		// Get and sanitize form data
		$customer_name = mysqli_real_escape_string($con, $_POST['nm']);
		$customer_email = mysqli_real_escape_string($con, $_POST['email']);
		$customer_mobile = mysqli_real_escape_string($con, $_POST['mo']);
		$booking_date = mysqli_real_escape_string($con, $_POST['date']);
		
		// Get theme details from temp table
		$q = mysqli_query($con, "SELECT * FROM temp");
		$r = mysqli_num_rows($q);
		
		if($r > 0)
		{
			while($res = mysqli_fetch_array($q))
			{
				$temp_id = $res[0];
				$theme_image = $res[1];
				$theme_name = $res[2];
				$theme_price = $res[3];
				
				// ========================================
				// CRITICAL: CHECK FOR DOUBLE BOOKING
				// ========================================
				$check_booking = mysqli_query($con, "SELECT * FROM booking WHERE thm_nm='$theme_name' AND date='$booking_date'");
				
				if(!$check_booking)
				{
					echo "<script>alert('Database error: " . mysqli_error($con) . "');</script>";
					exit();
				}
				
				if(mysqli_num_rows($check_booking) > 0)
				{
					// PREVENT DOUBLE BOOKING
					echo "<script>alert('Sorry! This theme \"$theme_name\" is already booked for $booking_date. Please choose another date.');</script>";
					// Don't redirect, let user change the date
				}
				else
				{
					// Theme is AVAILABLE - Proceed with booking
					$q1 = mysqli_query($con, "INSERT INTO booking(nm, email, mo, theme, thm_nm, price, date) VALUES('$customer_name', '$customer_email', '$customer_mobile', '$theme_image', '$theme_name', '$theme_price', '$booking_date')");
					
					if(!$q1)
					{
						echo "<script>alert('Booking failed: " . mysqli_error($con) . "');</script>";
						exit();
					}
					
					if($q1)
					{
						// Clear temp table after successful booking
						mysqli_query($con, "DELETE FROM temp WHERE id='$temp_id'");
						
						echo "<script>alert('Your Event is Booked for $booking_date. THANK YOU!');</script>";
						echo '<script type="text/javascript">window.location="index.php";</script>';
						exit();
					}
				}
			}
		}
		else
		{
			echo "<script>alert('Your cart is empty!');</script>";
			echo '<script type="text/javascript">window.location="gallery.php";</script>';
			exit();
		}
	}
	
	// Get theme details for display
	$qry = mysqli_query($con, "SELECT * FROM temp WHERE id=" . $id);
	$row = mysqli_fetch_row($qry);	
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