<?php
				include_once('../Database/connect.php');
				include_once('session.php');
				include_once("header.php");
				$list=mysqli_query($con,"select id,img,nm,price,location,rating,capacity,venue_type,space_preference from birthday ORDER BY id DESC");
				echo "<div class='codes'>
				<div class='container'>
				<a href='add_birthd.php'>BACK</a>
				<h3 class='w3ls-hdg' align='center'>Birthday Display</h3>
				<div class='grid_3 grid_5 '><br/>
					<div style='overflow-x:auto;'>
					<table class='table table-bordered' >
						<thead>
							<tr>
								<th>Id</th>
								<th>Images</th>
								<th>Name</th>
								<th>Price</th>
								<th>Location</th>
								<th>Rating</th>
								<th>Capacity</th>
								<th>Venue Type</th>
								<th>Space Preference</th>
								<th></th><th></th>
							</tr>
						</thead><tbody>";
						
				while($q = mysqli_fetch_assoc($list))
				{
					echo '<tr> 
					<td><span class="badge">'.$q['id'].'</span></td>
					<td><img src="../images/'.$q['img'].'" height="150" width="220"></td>
					<td>'.$q['nm'].'</td>
					<td>'.$q['price'].'</td>
					<td>'.$q['location'].'</td>
					<td>'.$q['rating'].'</td>
					<td>'.$q['capacity'].'</td>
					<td>'.$q['venue_type'].'</td>
					<td>'.$q['space_preference'].'</td>
					<td><u><a href="birthd_edit.php?id='.$q['id'].'">Edit</u></a></td>
					<td><a href="birthd_delete.php?id='.$q['id'].'" onClick="return confirm(\'Do you want to Delete Y/N\')"><u>Delete</u></a></td>
					</tr>';
						}
					echo "</tbody></table></div></div></div></div>";
					include_once("footer.php");
					
?>