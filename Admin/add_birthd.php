<?php
	include_once("header.php");
	include('../Database/connect.php');
	include('session.php');
	if(isset($_REQUEST['submit']))
	{
		$fnm=$_FILES["image"]["name"];
		$nm=$_REQUEST['nm'];
		$pr=$_REQUEST['price'];
		$location=$_REQUEST['location'];
		$rating=$_REQUEST['rating'];
		$capacity=$_REQUEST['capacity'];
		$venue_type=$_REQUEST['venue_type'];
		$space_preference=$_REQUEST['space_preference'];
		
		move_uploaded_file($_FILES["image"]["tmp_name"],"../images/" .$_FILES["image"]["name"]);
		@session_start();
		if(isset($_SESSION['admin']))
				{
					$qry1=mysqli_query($con,"INSERT INTO birthday(img,nm,price,location,rating,capacity,venue_type,space_preference)VALUES('$fnm','$nm','$pr','$location','$rating','$capacity','$venue_type','$space_preference')");
					if($qry1)
					{
						echo "<script> alert('Added');</script>";		
						echo '<script type="text/javascript">window.location="birthd_disp.php";</script>';
					}	
					else
					{
						echo "<script> alert('Not added');</script>";		
					
					}
				}	
			}
?>
<div class="codes">
<div class="container"> 
<h3 class='w3ls-hdg' align="center">ADD BIRTHDAY</h3>
<div class="grid_3 grid_4">
				<div class="tab-content">
					<div class="tab-pane active" id="horizontal-form">
						<form class="form-horizontal" action="" method="post" enctype="multipart/form-data">
							<div class="form-group">
								<label for="focusedinput" class="col-sm-2 control-label">Enter Image</label>
								<div class="col-sm-8">
									<input type="file"  name="image" required>
								</div>
							</div>
							<div class="form-group">
								<label for="focusedinput" class="col-sm-2 control-label">Enter Name</label>
								<div class="col-sm-8">
									<input type="text" class="form-control1"  name="nm" id="focusedinput" placeholder="Theme Name" required>
								</div>
							</div>
							<div class="form-group">
								<label for="focusedinput" class="col-sm-2 control-label">Enter Price</label>
								<div class="col-sm-8">
									<input type="text" class="form-control1"  name="price" id="focusedinput" placeholder="Theme Price" required>
								</div>
							</div>
							<div class="form-group">
								<label for="focusedinput" class="col-sm-2 control-label">Location</label>
								<div class="col-sm-8">
									<input type="text" class="form-control1"  name="location" id="focusedinput" placeholder="Location" required>
								</div>
							</div>
							<div class="form-group">
								<label for="focusedinput" class="col-sm-2 control-label">Rating</label>
								<div class="col-sm-8">
									<select name="rating" class="form-control1" required>
										<option value="">Select Rating</option>
										<option value="1">1</option>
										<option value="2">2</option>
										<option value="3">3</option>
										<option value="4">4</option>
										<option value="5">5</option>
									</select>
								</div>
							</div>
							<div class="form-group">
								<label for="focusedinput" class="col-sm-2 control-label">Capacity</label>
								<div class="col-sm-8">
									<input type="number" class="form-control1"  name="capacity" id="focusedinput" placeholder="Capacity" required>
								</div>
							</div>
							<div class="form-group">
								<label for="focusedinput" class="col-sm-2 control-label">Venue Type</label>
								<div class="col-sm-8">
									<input type="text" class="form-control1"  name="venue_type" id="focusedinput" placeholder="Venue Type (e.g., Party Hall)" required>
								</div>
							</div>
							<div class="form-group">
								<label for="focusedinput" class="col-sm-2 control-label">Space Preference</label>
								<div class="col-sm-8">
									<select name="space_preference" class="form-control1" required>
										<option value="">Select Space Preference</option>
										<option value="Indoor">Indoor</option>
										<option value="Outdoor">Outdoor</option>
										<option value="Indoor, Outdoor">Indoor, Outdoor</option>
									</select>
								</div>
							</div>
					<div class="contact-w3form" align="center">
					<input type="submit" name="submit" class="btn" value="SEND"> 
					<input type="button" value="DISPLAY" class="btn my" onClick="window.location.href='birthd_disp.php'"/>
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