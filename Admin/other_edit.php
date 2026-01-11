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
		
		// Check if new image uploaded
		if(!empty($_FILES["image"]["name"]))
		{
			// Get new image filename
			$fnm=$_FILES["image"]["name"];
			
			// Move uploaded file to images folder
			move_uploaded_file($_FILES["image"]["tmp_name"],"../images/" .$_FILES["image"]["name"]);
			
			// Update all fields including image
			$update=mysqli_query($con,"UPDATE otherevent SET img='$fnm',nm='$nm',price='$pr' where id='$id'");
		}
		else
		{
			// Update only name and price, keep existing image
			$update=mysqli_query($con,"UPDATE otherevent SET nm='$nm',price='$pr' where id='$id'");
		}
		
		// Check if update successful
		if($update>0)
		{
			// Show success message and redirect
			echo "<script> alert('Updated');</script>";
			echo '<script type="text/javascript">window.location="other_disp.php";</script>';
		}
		else
		{
			// Show error message
			echo "<script> alert('Not Updated');</script>";
		}
	}
	
	// Fetch existing other event data for form
	$id=$_REQUEST['id'];
	$se=mysqli_query($con,"select * from otherevent where id=$id");
	$row=mysqli_fetch_array($se);
	
?>
<link href="../css/style.css" rel="stylesheet" type="text/css" />

<div class="codes">
<div class="container"> 
<h3 class='w3ls-hdg' align="center">EDIT OTHER EVENT</h3>
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
							
							<!-- Price input field -->
							<div class="form-group">
								<label for="focusedinput" class="col-sm-2 control-label">Enter Price :</label>
								<div class="col-sm-8">
									<input type="text" class="form-control1" value="<?php echo $row['price']; ?>" name="price" id="focusedinput" placeholder="Theme Price" >
								</div>
							</div>
							
							<!-- Name input field -->
							<div class="form-group">
								<label for="txtarea1" class="col-sm-2 control-label">Enter Name :</label>
								<div class="col-sm-8">
									<input type="text" value="<?php echo $row['nm']; ?>" name="nm" id="focusedinput" class="form-control1">
								</div>
							</div>
							
					<!-- Submit and display buttons -->
					<div class="contact-w3form" align="center">
					<input type="submit" name="submit" class="btn" value="UPDATE"> 
					<input type="button" value="DISPLAY" class="btn my" onClick="javascript:location.href='other_disp.php'"/>
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