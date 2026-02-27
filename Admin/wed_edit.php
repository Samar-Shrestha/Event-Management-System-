<?php
	include_once("header.php");
	include('../Database/connect.php');
	include('session.php');
	if(isset($_REQUEST['submit']))
	{
		$id=$_REQUEST['id'];
		$nm=$_REQUEST['nm'];
		$pr=$_REQUEST['price'];
		$location=$_REQUEST['location'];
		$rating=$_REQUEST['rating'];
		$capacity=$_REQUEST['capacity'];
		$venue_type=$_REQUEST['venue_type'];
		$space_preference=$_REQUEST['space_preference'];
		
		// Check if a new image was uploaded
		if(!empty($_FILES["image"]["name"]))
		{
			// New image uploaded - update with new image
			$fnm=$_FILES["image"]["name"];
			move_uploaded_file($_FILES["image"]["tmp_name"],"../images/" .$_FILES["image"]["name"]);
			$update=mysqli_query($con,"UPDATE wedding SET img='$fnm',nm='$nm',price='$pr',location='$location',rating='$rating',capacity='$capacity',venue_type='$venue_type',space_preference='$space_preference' where id='$id'");
		}
		else
		{
			// No new image - update without changing image
			$update=mysqli_query($con,"UPDATE wedding SET nm='$nm',price='$pr',location='$location',rating='$rating',capacity='$capacity',venue_type='$venue_type',space_preference='$space_preference' where id='$id'");
		}
		
		if($update>0)
		{
			echo "<script> alert('Updated');</script>";
			echo '<script type="text/javascript">window.location="wed_disp.php";</script>';
		}
		else
		{
			echo "<script> alert('Not Updated');</script>";
		}
	}
	$id=$_REQUEST['id'];
	$se=mysqli_query($con,"select * from wedding where id=$id");
	$row=mysqli_fetch_array($se);
	
?>
<link href="../css/style.css" rel="stylesheet" type="text/css" />

<div class="codes">
<div class="container"> 
<h3 class='w3ls-hdg' align="center">EDIT WEDDING</h3>
<div class="grid_3 grid_4">
				<div class="tab-content">
					<div class="tab-pane active" id="horizontal-form">
						<form class="form-horizontal" action="" method="post" enctype="multipart/form-data">
							<div class="form-group">
								<label for="focusedinput" class="col-sm-2 control-label">Enter Image :</label>
								<div class="col-sm-8">
								<input type="file"  name="image">
								</div><div align="center">
								<img src="../images/<?php echo $row['img']; ?>" height="200"  width="200"/></div>		
							</div>
							<div class="form-group">
								<label for="txtarea1" class="col-sm-2 control-label">Enter Name :</label>
								<div class="col-sm-8"><input type="text" value="<?php echo $row['nm']; ?>" name="nm" id="focusedinput" class="form-control1" required>
								</div>
							</div>
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
					<div class="contact-w3form" align="center">
					<input type="submit" name="submit" class="btn" value="UPDATE"> <input type="button" value="DISPLAY" class="btn my" onClick="javascript:location.href='wed_disp.php'" />
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