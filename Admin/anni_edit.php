<?php
	// Include header, database connection, and session
	include_once("header.php");
	include('../Database/connect.php');
	include('session.php');
	
	// Process form submission
	if(isset($_REQUEST['submit']))
	{
		// Get form data
		$id=$_REQUEST['id'];
		$nm=$_REQUEST['nm'];
		$pr=$_REQUEST['price'];
		$location=$_REQUEST['location'];
		$rating=$_REQUEST['rating'];
		$capacity=$_REQUEST['capacity'];
		$venue_type=$_REQUEST['venue_type'];
		$space_preference=$_REQUEST['space_preference'];
		
		// Check if new image uploaded
		if(!empty($_FILES["image"]["name"]))
		{
			// Get new image filename
			$fnm=$_FILES["image"]["name"];
			
			// Move uploaded file to images folder
			move_uploaded_file($_FILES["image"]["tmp_name"],"../images/" .$_FILES["image"]["name"]);
			
			// Update all fields including image
			$update=mysqli_query($con,"UPDATE anniversary SET img='$fnm',nm='$nm',price='$pr',location='$location',rating='$rating',capacity='$capacity',venue_type='$venue_type',space_preference='$space_preference' where id='$id'");
		}
		else
		{
			// Update only name, price and other fields, keep existing image
			$update=mysqli_query($con,"UPDATE anniversary SET nm='$nm',price='$pr',location='$location',rating='$rating',capacity='$capacity',venue_type='$venue_type',space_preference='$space_preference' where id='$id'");
		}
		
		// Check if update successful
		if($update>0)
		{
			// Show success message and redirect
			echo "<script> alert('Updated');</script>";
			echo '<script type="text/javascript">window.location="anni_disp.php";</script>';
		}
		else
		{
			// Show error message
			echo "<script> alert('Not Updated');</script>";
		}
	}
	
	// Fetch existing anniversary data for form
	$id=$_REQUEST['id'];
	$se=mysqli_query($con,"select * from anniversary where id=$id");
	$row=mysqli_fetch_array($se);
	
?>
<link href="../css/style.css" rel="stylesheet" type="text/css" />

<div class="codes">
<div class="container"> 
<h3 class='w3ls-hdg' align="center">EDIT ANNIVERSARY</h3>
<div class="grid_3 grid_4">
				<div class="tab-content">
					<div class="tab-pane active" id="horizontal-form">
						<!-- Edit form with file upload support -->
						<form class="form-horizontal" action="" method="post" enctype="multipart/form-data">
							
							<!-- Image upload field -->
							<div class="form-group">
								<label for="focusedinput" class="col-sm-2 control-label">Enter Image :</label>
								<div class="col-sm-8">
								<input type="file"  name="image">
								</div>
								<!-- Display current image -->
								<div align="center">
								<img src="../images/<?php echo $row['img']; ?>" height="200"  width="200"/>
								</div>		
							</div>
							
							<!-- Name input field -->
							<div class="form-group">
								<label for="txtarea1" class="col-sm-2 control-label">Enter Name :</label>
								<div class="col-sm-8">
									<input type="text" value="<?php echo $row['nm']; ?>" name="nm" id="focusedinput" class="form-control1" required>
								</div>
							</div>
							
							<!-- Price input field -->
							<div class="form-group">
								<label for="focusedinput" class="col-sm-2 control-label">Enter Price :</label>
								<div class="col-sm-8">
									<input type="text" class="form-control1" value="<?php echo $row['price']; ?>" name="price" id="focusedinput" placeholder="Theme Price" required>
								</div>
							</div>
							
							<div class="form-group">
								<label for="focusedinput" class="col-sm-2 control-label">Location :</label>
								<div class="col-sm-8">
									<input type="text" class="form-control1" value="<?php echo $row['location']; ?>" name="location" id="focusedinput" placeholder="Location" required>
								</div>
							</div>
							
							<div class="form-group">
								<label for="focusedinput" class="col-sm-2 control-label">Rating :</label>
								<div class="col-sm-8">
									<select name="rating" class="form-control1" required>
										<option value="">Select Rating</option>
										<option value="1" <?php if($row['rating']==1) echo 'selected'; ?>>1</option>
										<option value="2" <?php if($row['rating']==2) echo 'selected'; ?>>2</option>
										<option value="3" <?php if($row['rating']==3) echo 'selected'; ?>>3</option>
										<option value="4" <?php if($row['rating']==4) echo 'selected'; ?>>4</option>
										<option value="5" <?php if($row['rating']==5) echo 'selected'; ?>>5</option>
									</select>
								</div>
							</div>
							
							<div class="form-group">
								<label for="focusedinput" class="col-sm-2 control-label">Capacity :</label>
								<div class="col-sm-8">
									<input type="number" class="form-control1" value="<?php echo $row['capacity']; ?>" name="capacity" id="focusedinput" placeholder="Capacity" required>
								</div>
							</div>
							
							<div class="form-group">
								<label for="focusedinput" class="col-sm-2 control-label">Venue Type :</label>
								<div class="col-sm-8">
									<input type="text" class="form-control1" value="<?php echo $row['venue_type']; ?>" name="venue_type" id="focusedinput" placeholder="Venue Type" required>
								</div>
							</div>
							
							<div class="form-group">
								<label for="focusedinput" class="col-sm-2 control-label">Space Preference :</label>
								<div class="col-sm-8">
									<select name="space_preference" class="form-control1" required>
										<option value="">Select Space Preference</option>
										<option value="Indoor" <?php if($row['space_preference']=='Indoor') echo 'selected'; ?>>Indoor</option>
										<option value="Outdoor" <?php if($row['space_preference']=='Outdoor') echo 'selected'; ?>>Outdoor</option>
										<option value="Indoor, Outdoor" <?php if($row['space_preference']=='Indoor, Outdoor') echo 'selected'; ?>>Indoor, Outdoor</option>
									</select>
								</div>
							</div>
							
					<!-- Submit and display buttons -->
					<div class="contact-w3form" align="center">
					<input type="submit" name="submit" class="btn" value="UPDATE"> 
					<input type="button" value="DISPLAY" class="btn my" onClick="window.location.href='anni_disp.php'" />
					</div>
					</form>
					</div>
				</div>
			</div>
		</div>
	</div>
	</div>
	<?php
		include_once("footer.php");
	?>