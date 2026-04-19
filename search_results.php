<?php
	include_once("header.php");
	include_once("Database/connect.php");
?>

<style>
.search-results-container {
	padding: 50px 0;
	background-color: #f5f5f5;
	min-height: 70vh;
}

.search-info {
	background: white;
	padding: 20px;
	border-radius: 8px;
	margin-bottom: 30px;
	box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.search-info h2 {
	color: #8B2E2E;
	margin-bottom: 10px;
}

.search-query {
	color: #666;
	font-size: 16px;
}

.results-grid {
	display: grid;
	grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
	gap: 25px;
}

.result-card {
	background: white;
	border-radius: 8px;
	overflow: hidden;
	box-shadow: 0 2px 8px rgba(0,0,0,0.1);
	transition: transform 0.3s, box-shadow 0.3s;
}

.result-card:hover {
	transform: translateY(-5px);
	box-shadow: 0 4px 16px rgba(0,0,0,0.15);
}

.result-image {
	width: 100%;
	height: 200px;
	object-fit: cover;
}

.result-content {
	padding: 20px;
}

.result-title {
	font-size: 18px;
	font-weight: bold;
	color: #333;
	margin-bottom: 10px;
}

.result-type {
	display: inline-block;
	padding: 5px 12px;
	background-color: #8B2E2E;
	color: white;
	border-radius: 15px;
	font-size: 12px;
	margin-bottom: 10px;
}

.result-location {
	color: #666;
	font-size: 14px;
	margin-bottom: 8px;
}

.result-price {
	font-size: 20px;
	font-weight: bold;
	color: #4CAF50;
	margin-bottom: 10px;
}

.result-rating {
	color: #FFA500;
	margin-bottom: 15px;
}

.result-availability {
	font-size: 13px;
	margin-bottom: 15px;
}

.available {
	color: #4CAF50;
	font-weight: 600;
}

.almost-full {
	color: #FF9800;
	font-weight: 600;
}

.fully-booked {
	color: #f44336;
	font-weight: 600;
}

.view-btn {
	display: block;
	width: 100%;
	padding: 10px;
	background-color: #2196F3;
	color: white;
	text-align: center;
	border-radius: 4px;
	text-decoration: none;
	transition: background-color 0.3s;
}

.view-btn:hover {
	background-color: #0b7dda;
	color: white;
	text-decoration: none;
}

.no-results {
	text-align: center;
	padding: 60px 20px;
	background: white;
	border-radius: 8px;
}

.no-results h3 {
	color: #666;
	margin-bottom: 20px;
}

.back-btn {
	display: inline-block;
	padding: 12px 30px;
	background-color: #8B2E2E;
	color: white;
	border-radius: 25px;
	text-decoration: none;
	transition: background-color 0.3s;
}

.back-btn:hover {
	background-color: #6d2323;
	color: white;
	text-decoration: none;
}
</style>

<div class="search-results-container">
	<div class="container">
		<?php
		// Get search query
		$searchQuery = isset($_GET['search']) ? mysqli_real_escape_string($con, $_GET['search']) : '';
		
		if(empty($searchQuery)) {
			echo "<div class='no-results'>";
			echo "<h3>Please enter a search term</h3>";
			echo "<a href='index.php' class='back-btn'>Back to Home</a>";
			echo "</div>";
		} else {
			// Search in all event tables
			$allResults = [];
			
			// Search in wedding table
			$weddingQuery = "SELECT *, 'Wedding' as event_type, 'gallery.php' as page, 'book_wed.php' as booking_page 
			                 FROM wedding 
			                 WHERE nm LIKE '%$searchQuery%' 
			                 OR location LIKE '%$searchQuery%'";
			$weddingResults = mysqli_query($con, $weddingQuery);
			if($weddingResults) {
				while($row = mysqli_fetch_assoc($weddingResults)) {
					$allResults[] = $row;
				}
			}
			
			// Search in birthday table
			$birthdayQuery = "SELECT *, 'Birthday Party' as event_type, 'bday_gal.php' as page, 'book_birthd.php' as booking_page 
			                  FROM birthday 
			                  WHERE nm LIKE '%$searchQuery%' 
			                  OR location LIKE '%$searchQuery%'";
			$birthdayResults = mysqli_query($con, $birthdayQuery);
			if($birthdayResults) {
				while($row = mysqli_fetch_assoc($birthdayResults)) {
					$allResults[] = $row;
				}
			}
			
			// Search in anniversary table
			$anniversaryQuery = "SELECT *, 'Anniversary' as event_type, 'anni_gal.php' as page, 'book_anni.php' as booking_page 
			                     FROM anniversary 
			                     WHERE nm LIKE '%$searchQuery%' 
			                     OR location LIKE '%$searchQuery%'";
			$anniversaryResults = mysqli_query($con, $anniversaryQuery);
			if($anniversaryResults) {
				while($row = mysqli_fetch_assoc($anniversaryResults)) {
					$allResults[] = $row;
				}
			}
			
			// Search in otherevent table
			$otherQuery = "SELECT *, 'Entertainment' as event_type, 'other_gal.php' as page, 'book_other.php' as booking_page 
			               FROM otherevent 
			               WHERE nm LIKE '%$searchQuery%' 
			               OR location LIKE '%$searchQuery%'";
			$otherResults = mysqli_query($con, $otherQuery);
			if($otherResults) {
				while($row = mysqli_fetch_assoc($otherResults)) {
					$allResults[] = $row;
				}
			}
			
			// Display results
			$totalResults = count($allResults);
			?>
			
			<div class="search-info">
				<h2>Search Results</h2>
				<p class="search-query">Showing <?php echo $totalResults; ?> result(s) for "<strong><?php echo htmlspecialchars($searchQuery); ?></strong>"</p>
			</div>
			
			<?php
			if($totalResults == 0) {
				echo "<div class='no-results'>";
				echo "<h3>No events found matching your search</h3>";
				echo "<p>Try searching with different keywords or browse our gallery</p>";
				echo "<a href='gallery.php' class='back-btn'>Browse All Events</a>";
				echo "</div>";
			} else {
				echo "<div class='results-grid'>";
				
				foreach($allResults as $event) {
					// Calculate availability - with safety check
					$capacity = isset($event['capacity']) ? $event['capacity'] : 800;
					$bookedCount = isset($event['booked_count']) ? $event['booked_count'] : 0;
					$available = $capacity - $bookedCount;
					
					// Prevent division by zero
					if($capacity > 0) {
						$availabilityPercent = ($available / $capacity) * 100;
					} else {
						$availabilityPercent = 0;
					}
					
					if($availabilityPercent > 20) {
						$availabilityText = "<span class='available'>Available - $available seats left</span>";
					} else if($availabilityPercent > 0) {
						$availabilityText = "<span class='almost-full'>Almost Full - Only $available seats left!</span>";
					} else {
						$availabilityText = "<span class='fully-booked'>Fully Booked</span>";
					}
					
					// Generate star rating
					$rating = isset($event['rating']) ? $event['rating'] : 5;
					$stars = str_repeat('★', $rating) . str_repeat('☆', 5 - $rating);
					
					echo "<div class='result-card'>";
					echo "<img src='images/{$event['img']}' alt='{$event['nm']}' class='result-image'>";
					echo "<div class='result-content'>";
					echo "<span class='result-type'>{$event['event_type']}</span>";
					echo "<h3 class='result-title'>{$event['nm']}</h3>";
					echo "<p class='result-location'>📍 {$event['location']}</p>";
					echo "<p class='result-rating'>$stars ($rating/5)</p>";
					echo "<p class='result-price'>NPR " . number_format($event['price']) . "</p>";
					echo "<p class='result-availability'>$availabilityText</p>";
					
					if($availabilityPercent > 0) {
						echo "<a href='{$event['booking_page']}?id={$event['id']}' class='view-btn'>Book Now</a>";
					} else {
						echo "<button class='view-btn' style='background-color: #999; cursor: not-allowed;' disabled>Fully Booked</button>";
					}
					
					echo "</div>";
					echo "</div>";
				}
				
				echo "</div>";
			}
		}
		?>
	</div>
</div>

<?php
	include_once("footer.php");
?>