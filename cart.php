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
				
			
				//CHECK FOR DOUBLE BOOKING

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
	<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
	<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
	<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
	
	<script>
	$(function()
	{
		$("#event_date").datepicker
		({
			changeMonth: true,
			changeYear: true,
			minDate: 0,  // Prevent past dates
			dateFormat: 'yy-mm-dd'
		});
	});
	</script>
</head>
<body>

<div class="codes">
	<div class="container"> 
		<h3 class='w3ls-hdg' align="center">BOOKING</h3>
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
								<?php if(isset($row[1])): ?>
									<img src="./images/<?php echo $row[1]; ?>" height="200" width="300"/>
								<?php endif; ?>
							</div>		
						</div>
						
						<div class="form-group">
							<label for="disabledinput" class="col-sm-2 control-label">Theme Name :</label>
							<div class="col-sm-8">
								<input disabled="" type="text" class="form-control1" value="<?php echo isset($row[2]) ? $row[2] : ''; ?>" id="theme_name_display" placeholder="Theme Name">
							</div>
						</div>
						
						<div class="form-group">
							<label for="disabledinput" class="col-sm-2 control-label">Theme Price :</label>
							<div class="col-sm-8">
								<input disabled="" type="text" class="form-control1" value="<?php echo isset($row[3]) ? $row[3] : ''; ?>" id="price_display" placeholder="Theme Price">
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
						
						<div class="contact-w3form" align="center">
							<input type="submit" name="submit" class="btn" value="BOOK NOW">
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>

<!-- AJAX Script to check date availability -->
<script>
$(document).ready(function(){
	$('#check_availability').click(function(){
		var date = $('#event_date').val();
		var theme_name = '<?php echo isset($row[2]) ? $row[2] : ""; ?>';
		
		if(date == '')
		{
			alert('Please select a date first');
			return false;
		}
		
		if(theme_name == '')
		{
			alert('No theme selected. Please go back and select a theme.');
			return false;
		}
		
		// Show loading message
		$('#availability_result').html('<span style="color: #888;">⏳ Checking availability...</span>');
		
		$.ajax({
			url: 'check_date_availability.php',
			method: 'POST',
			data: {date: date, theme_name: theme_name},
			success: function(response){
				if(response.trim() == 'available')
				{
					$('#availability_result').html('<span style="color: green; font-size: 16px;">✓ Great! This date is available for booking!</span>');
				}
				else
				{
					$('#availability_result').html('<span style="color: red; font-size: 16px;">✗ Sorry! This theme is already booked for this date. Please choose another date.</span>');
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