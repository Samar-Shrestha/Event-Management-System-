<?php
 	include_once("header.php");
	include_once("slider.php");
	include_once("Database/connect.php");
?>
	<!-- modal -->
	<div class="modal about-modal w3-agileits fade" id="myModal" tabindex="-1" role="dialog">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>						
				</div> 
				<div class="modal-body">
					<img src="images/cs_birthday.JPG" alt=""> 
					<p>	
					It has been enabling corporate and brands in reaching out to audiences through events,trade-shows and conferences.
		Being one of the top corporate planners in nepal,we create live events,brandec environments and integrated media experiences helping clients build brands and relationships with customers.</p>
		</div> 
			</div>
		</div>
	</div>

	<!-- //modal -->  
	<!-- banner-bottom -->
	<div class="w3-agile-text">
		<div class="container"> 
			<h2>Making Moments Memorable...</h2>
		</div>
	</div>
	<!-- //banner-bottom -->
	
	<!-- POPULAR EVENTS SECTION -->
	<style>
	.popular-events {
		padding: 60px 0;
		background-color: #f9f9f9;
	}
	
	.popular-header {
		text-align: center;
		margin-bottom: 50px;
	}
	
	.popular-header h2 {
		font-size: 36px;
		color: #8B2E2E;
		margin-bottom: 10px;
	}
	
	.popular-header p {
		font-size: 16px;
		color: #666;
	}
	
	.popular-grid {
		display: grid;
		grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
		gap: 25px;
	}
	
	.popular-card {
		background: white;
		border-radius: 10px;
		overflow: hidden;
		box-shadow: 0 3px 10px rgba(0,0,0,0.1);
		transition: transform 0.3s, box-shadow 0.3s;
		position: relative;
	}
	
	.popular-card:hover {
		transform: translateY(-8px);
		box-shadow: 0 6px 20px rgba(0,0,0,0.15);
	}
	
	.popular-badge {
		position: absolute;
		top: 15px;
		right: 15px;
		background: #FF6B6B;
		color: white;
		padding: 8px 15px;
		border-radius: 20px;
		font-size: 12px;
		font-weight: bold;
		z-index: 10;
	}
	
	.popular-image {
		width: 100%;
		height: 200px;
		object-fit: cover;
	}
	
	.popular-content {
		padding: 20px;
	}
	
	.popular-type {
		display: inline-block;
		padding: 5px 12px;
		background-color: #8B2E2E;
		color: white;
		border-radius: 15px;
		font-size: 11px;
		margin-bottom: 10px;
	}
	
	.popular-title {
		font-size: 18px;
		font-weight: bold;
		color: #333;
		margin-bottom: 10px;
	}
	
	.popular-location {
		color: #666;
		font-size: 14px;
		margin-bottom: 8px;
	}
	
	.popular-rating {
		color: #FFA500;
		margin-bottom: 10px;
		font-size: 14px;
	}
	
	.popular-price {
		font-size: 22px;
		font-weight: bold;
		color: #4CAF50;
		margin-bottom: 10px;
	}
	
	.popular-views {
		font-size: 13px;
		color: #999;
		margin-bottom: 15px;
	}
	
	.popular-btn {
		display: block;
		width: 100%;
		padding: 12px;
		background-color: #2196F3;
		color: white;
		text-align: center;
		border-radius: 5px;
		text-decoration: none;
		transition: background-color 0.3s;
		border: none;
		cursor: pointer;
	}
	
	.popular-btn:hover {
		background-color: #0b7dda;
		color: white;
		text-decoration: none;
	}
	
	.view-all-btn {
		text-align: center;
		margin-top: 40px;
	}
	
	.view-all-btn a {
		display: inline-block;
		padding: 15px 40px;
		background-color: #8B2E2E;
		color: white;
		border-radius: 30px;
		text-decoration: none;
		font-size: 16px;
		transition: background-color 0.3s;
	}
	
	.view-all-btn a:hover {
		background-color: #6d2323;
		color: white;
		text-decoration: none;
	}
	</style>
	
	<div class="popular-events">
		<div class="container">
			<div class="popular-header">
				<h2>🔥 Most Popular Events</h2>
				<p>Events loved by our customers</p>
			</div>
			
			<div class="popular-grid">
				<?php
				// Get popular events from wedding table
				$weddingQuery = "SELECT id, nm, img, price, location, rating, view_count, capacity, booked_count, 
				                 'Wedding' as event_type, 'book_wed.php' as booking_page 
				                 FROM wedding ORDER BY view_count DESC LIMIT 2";
				$weddingResult = mysqli_query($con, $weddingQuery);
				
				// Get popular events from birthday table
				$birthdayQuery = "SELECT id, nm, img, price, location, rating, view_count, capacity, booked_count, 
				                  'Birthday' as event_type, 'book_birthd.php' as booking_page 
				                  FROM birthday ORDER BY view_count DESC LIMIT 2";
				$birthdayResult = mysqli_query($con, $birthdayQuery);
				
				// Get popular events from anniversary table
				$anniversaryQuery = "SELECT id, nm, img, price, location, rating, view_count, capacity, booked_count, 
				                     'Anniversary' as event_type, 'book_anni.php' as booking_page 
				                     FROM anniversary ORDER BY view_count DESC LIMIT 2";
				$anniversaryResult = mysqli_query($con, $anniversaryQuery);
				
				// Get popular events from otherevent table
				$otherQuery = "SELECT id, nm, img, price, location, rating, view_count, capacity, booked_count, 
				               'Entertainment' as event_type, 'book_other.php' as booking_page 
				               FROM otherevent ORDER BY view_count DESC LIMIT 2";
				$otherResult = mysqli_query($con, $otherQuery);
				
				// Combine all results
				$allEvents = [];
				
				if($weddingResult && mysqli_num_rows($weddingResult) > 0) {
					while($row = mysqli_fetch_assoc($weddingResult)) {
						$allEvents[] = $row;
					}
				}
				
				if($birthdayResult && mysqli_num_rows($birthdayResult) > 0) {
					while($row = mysqli_fetch_assoc($birthdayResult)) {
						$allEvents[] = $row;
					}
				}
				
				if($anniversaryResult && mysqli_num_rows($anniversaryResult) > 0) {
					while($row = mysqli_fetch_assoc($anniversaryResult)) {
						$allEvents[] = $row;
					}
				}
				
				if($otherResult && mysqli_num_rows($otherResult) > 0) {
					while($row = mysqli_fetch_assoc($otherResult)) {
						$allEvents[] = $row;
					}
				}
				
				// Sort by view_count
				usort($allEvents, function($a, $b) {
					return $b['view_count'] - $a['view_count'];
				});
				
				// Display top 8
				$allEvents = array_slice($allEvents, 0, 8);
				
				if(count($allEvents) > 0) {
					$rank = 1;
					foreach($allEvents as $event) {
						// Calculate availability
						$capacity = isset($event['capacity']) ? $event['capacity'] : 800;
						$bookedCount = isset($event['booked_count']) ? $event['booked_count'] : 0;
						$available = $capacity - $bookedCount;
						
						// Generate stars
						$rating = isset($event['rating']) ? $event['rating'] : 5;
						$stars = str_repeat('★', $rating) . str_repeat('☆', 5 - $rating);
						
						echo "<div class='popular-card'>";
						
						// Show badge for top 3
						if($rank <= 3) {
							$badges = ['🥇 #1 Most Popular', '🥈 #2 Most Popular', '🥉 #3 Most Popular'];
							echo "<div class='popular-badge'>{$badges[$rank-1]}</div>";
						}
						
						echo "<img src='images/{$event['img']}' alt='{$event['nm']}' class='popular-image'>";
						echo "<div class='popular-content'>";
						echo "<span class='popular-type'>{$event['event_type']}</span>";
						echo "<h3 class='popular-title'>{$event['nm']}</h3>";
						echo "<p class='popular-location'>📍 {$event['location']}</p>";
						echo "<p class='popular-rating'>$stars ($rating/5)</p>";
						echo "<p class='popular-price'>NPR " . number_format($event['price']) . "</p>";
						echo "<p class='popular-views'>👁️ {$event['view_count']} views</p>";
						
						if($available > 0) {
							echo "<a href='{$event['booking_page']}?id={$event['id']}' class='popular-btn'>Book Now - $available seats left</a>";
						} else {
							echo "<button class='popular-btn' style='background-color: #999; cursor: not-allowed;' disabled>Fully Booked</button>";
						}
						
						echo "</div>";
						echo "</div>";
						
						$rank++;
					}
				} else {
					echo "<p style='text-align: center; grid-column: 1/-1;'>No popular events yet. Be the first to explore!</p>";
				}
				?>
			</div>
			
			<div class="view-all-btn">
				<a href="gallery.php">View All Events →</a>
			</div>
		</div>
	</div>
	<!-- END POPULAR EVENTS -->
	
	<!-- features -->
	<div class="features">
		<div class="container">
			<div class="col-md-4 feature-grids">
				<h3 class="w3ltitle">WHAT <span>WE ARE</span></h3>
				<p> Classic Events is young and dynamic event management compnay, which positions itself as "One-Stop Solutions" for all event needs. At Classic Events,we strive to be the most reliable and creative provider of a wide range of services to the clients.</p>
				<p>	Classic Events is one of the leading corporate event management companies in nepal.</p>
				<div class="w3ls-more">
					<a href="#" class="effect6" data-toggle="modal" data-target="#myModal"><span>Read More</span></a>
				</div>
			</div>
			<div class="col-md-4 feature-grids">
				<img src="images/cs_event.jpg" alt=""/>
			</div>
			<div class="col-md-4 feature-grids">
				<h3 class="w3ltitle">OUR SPECIFICATIONS</h3>
				<div class="w3ls-pince">
					<div class="pince-left">
						<h5>01</h5>
					</div>
					<div class="pince-right">
						<h4>Designer Wedding </h4>
						<p>Classic Events offers comprehensive wedding planning solutions.</p>
					</div>
					<div class="clearfix"> </div>
				</div>
				<div class="w3ls-pince">
					<div class="pince-left">
						<h5>02</h5>
					</div>
					<div class="pince-right">
						<h4>Destination Wedding </h4>
						<p>Choose a beautiful location for your wedding function.</p>
					</div>
					<div class="clearfix"> </div>
				</div>
				<div class="w3ls-pince">
					<div class="pince-left">
						<h5>03</h5>
					</div>
					<div class="pince-right">
						<h4>Theme Wedding </h4>
						<p>Our wedding themes come with numerous varieties.</p>
					</div>
					<div class="clearfix"> </div>
				</div>
			</div>
			<div class="clearfix"> </div>
		</div>
	</div>
	<!-- //features -->
	<?php
		include("projects.php");
	?>
	
	<!-- services -->
	<div class="services">
		<div class="container">
			<h3 class="w3ltitle"> OUR SERVICES</h3>
			<div class="services-agileinfo">
				<div class="servc-icon">
					<a href="wedding.php" class="agile-shape"><span class="glyphicon glyphicon-heart" aria-hidden="true"></span>
					<p class="serw3-agiletext">Wedding</p>
					</a>
				</div>
				<div class="servc-icon">
					<a href="anniversary.php" class="agile-shape"><span class="glyphicon glyphicon-glass" aria-hidden="true"></span>
					<p class="serw3-agiletext"> Anniversary </p>
					</a>
				</div>
				<div class="servc-icon">
					<a href="birthday.php" class="agile-shape"><span class="glyphicon fa fa-gift" aria-hidden="true"></span>
					<p class="serw3-agiletext">Birthday party</p>
					</a>
				</div>
				<div class="servc-icon">
					<a href="other_events.php" class="agile-shape"><span class="glyphicon glyphicon-music" aria-hidden="true"></span>
					<p class="serw3-agiletext">Enjoyments</p>
					</a>
				</div>
				<div class="clearfix"> </div>
			</div>
		</div>
	</div>
	<!-- //services -->
	
	<?php
		include_once("footer.php");
	?>