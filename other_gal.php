<?php
	include_once("header.php");
?>
<link rel="stylesheet" href="css/lightbox.css">
<link href="css/font-awesome.css" rel="stylesheet">

<style>
.gallery-container-wrapper { display: flex; min-height: 100vh; }
.filter-sidebar { width: 400px; background-color: #fff; box-shadow: 2px 0 5px rgba(0,0,0,0.1); position: sticky; top: 0; height: 100vh; overflow-y: auto; }
.filter-header { background-color: #8B2E2E; color: white; padding: 20px 25px; margin-bottom: 0; }
.filter-header h2 { font-size: 32px; font-weight: bold; margin: 0; }
.filter-content { padding: 25px; }
.filter-section { margin-bottom: 30px; }
.filter-section label { display: block; font-size: 18px; font-weight: 600; margin-bottom: 10px; color: #333; }
.filter-section select { width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 4px; font-size: 14px; color: #666; background-color: #fff; }
.rating-stars { display: flex; gap: 8px; margin-top: 10px; }
.star { font-size: 32px; color: #ddd; cursor: pointer; transition: color 0.2s; }
.star.active { color: #FFA500; }
.star:hover { color: #FFB84D; }
.price-slider-container { margin: 15px 0; }
#priceSlider { width: 100%; height: 8px; background: #A4B82C; border-radius: 5px; outline: none; -webkit-appearance: none; }
#priceSlider::-webkit-slider-thumb { -webkit-appearance: none; appearance: none; width: 24px; height: 24px; background: #A4B82C; border-radius: 50%; cursor: pointer; border: 3px solid #fff; box-shadow: 0 2px 4px rgba(0,0,0,0.2); }
#priceSlider::-moz-range-thumb { width: 24px; height: 24px; background: #A4B82C; border-radius: 50%; cursor: pointer; border: 3px solid #fff; box-shadow: 0 2px 4px rgba(0,0,0,0.2); }
.price-inputs { display: flex; align-items: center; gap: 10px; margin-top: 15px; }
.price-input-group { flex: 1; }
.price-input-group label { font-size: 14px; font-weight: 500; margin-bottom: 5px; }
.price-input-group input { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; font-size: 14px; }
.price-separator { margin-top: 20px; color: #999; }
.services-header { background-color: #8B2E2E; color: white; padding: 20px 25px; margin: 30px -25px 0 -25px; }
.services-header h2 { font-size: 32px; font-weight: bold; margin: 0; }
.services-content { padding: 25px 0; }
.service-item-link { text-decoration: none; color: inherit; display: block; transition: background-color 0.3s; }
.service-item-link:hover { background-color: #f5f5f5; }
.service-item-link:hover .service-item { color: #8B2E2E; padding-left: 5px; }
.service-item { padding: 15px 25px; font-size: 16px; font-weight: 500; border-bottom: 1px solid #f0f0f0; cursor: pointer; transition: all 0.3s; margin: 0 -25px; }
.service-item.active-service { background-color: #A4B82C; color: white; font-weight: 700; border-left: 5px solid #8B9621; }
.btn-reset-filters { width: 100%; padding: 15px; background-color: #8B2E2E; color: white; border: none; border-radius: 4px; font-size: 16px; font-weight: bold; cursor: pointer; margin-top: 20px; display: block; text-align: center; text-decoration: none; }
.btn-reset-filters:hover { background-color: #6d2323; color: white; text-decoration: none; }
.gallery-main-content { flex: 1; padding: 30px; background-color: #f5f5f5; }
.category-title { font-size: 28px; font-weight: bold; color: #8B2E2E; margin: 40px 0 25px 0; padding-bottom: 10px; border-bottom: 3px solid #A4B82C; }
/* Venue Card Styles */
.venue-card {
	background: white;
	border-radius: 12px;
	overflow: hidden;
	box-shadow: 0 2px 8px rgba(0,0,0,0.1);
	display: flex;
	height: 280px;
	transition: box-shadow 0.3s;
}

.venue-card:hover {
	box-shadow: 0 4px 16px rgba(0,0,0,0.15);
}

.venue-image {
	width: 50%;
	position: relative;
	overflow: hidden;
}

.venue-image img {
	width: 100%;
	height: 100%;
	object-fit: cover;
	transition: transform 0.3s;
}

.venue-image:hover img {
	transform: scale(1.05);
}

.venue-details {
	width: 50%;
	padding: 20px 25px;
	display: flex;
	flex-direction: column;
	justify-content: space-between;
}

.venue-details h3 {
	font-size: 22px;
	font-weight: bold;
	margin: 0 0 6px 0;
	color: #333;
}

.venue-rating {
	margin-bottom: 8px;
}

.venue-rating span {
	font-size: 16px;
}

.venue-info {
	font-size: 13px;
	color: #666;
	line-height: 1.6;
	flex: 1;
}

.venue-info p {
	margin: 3px 0;
}

.details-btn {
	display: block;
	width: 100%;
	padding: 11px;
	background-color: #8B2E2E;
	border: none;
	border-radius: 25px;
	font-size: 14px;
	font-weight: 700;
	color: white;
	cursor: pointer;
	transition: all 0.3s;
	text-decoration: none;
	text-align: center;
	letter-spacing: 1px;
	box-shadow: 0 3px 8px rgba(139,46,46,0.25);
}

.details-btn:hover {
	background-color: #6d2323;
	color: white;
	text-decoration: none;
	transform: translateY(-2px);
	box-shadow: 0 5px 14px rgba(139,46,46,0.35);
}
@media (max-width: 968px) {
	.gallery-container-wrapper { flex-direction: column; }
	.filter-sidebar { width: 100%; height: auto; position: relative; }
	.venue-card { flex-direction: column; height: auto; }
	.venue-image, .venue-details { width: 100%; }
	.venue-image { height: 200px; }
}
</style>

<div class="banner about-bnr"><div class="container"></div></div>

<div class="gallery-container-wrapper">
	<aside class="filter-sidebar">
		<div class="filter-header"><h2>Filter</h2></div>
		<div class="filter-content">
			<form method="GET" action="other_gal.php" id="filterForm">
				<div class="filter-section">
					<label for="location">Location</label>
					<select name="location" id="location">
						<option value="">Select location</option>
						<option value="Kathmandu" <?php echo (isset($_GET['location']) && $_GET['location']=='Kathmandu')?'selected':''; ?>>Kathmandu</option>
						<option value="Pokhara" <?php echo (isset($_GET['location']) && $_GET['location']=='Pokhara')?'selected':''; ?>>Pokhara</option>
						<option value="Lalitpur" <?php echo (isset($_GET['location']) && $_GET['location']=='Lalitpur')?'selected':''; ?>>Lalitpur</option>
						<option value="Bhaktapur" <?php echo (isset($_GET['location']) && $_GET['location']=='Bhaktapur')?'selected':''; ?>>Bhaktapur</option>
					</select>
				</div>
				<div class="filter-section">
					<label>Rating</label>
					<div class="rating-stars">
						<span class="star" data-rating="1">★</span>
						<span class="star" data-rating="2">★</span>
						<span class="star" data-rating="3">★</span>
						<span class="star" data-rating="4">★</span>
						<span class="star" data-rating="5">★</span>
					</div>
					<input type="hidden" id="rating" name="rating" value="<?php echo isset($_GET['rating'])?$_GET['rating']:'0'; ?>">
				</div>
				<div class="filter-section">
					<label>Price Range</label>
					<div class="price-slider-container">
						<input type="range" id="priceSlider" min="0" max="500000" value="<?php echo isset($_GET['maxPrice'])?$_GET['maxPrice']:'500000'; ?>" step="1000">
					</div>
					<div class="price-inputs">
						<div class="price-input-group">
							<label for="minPrice">Min</label>
							<input type="number" id="minPrice" name="minPrice" value="<?php echo isset($_GET['minPrice'])?$_GET['minPrice']:'0'; ?>" min="0">
						</div>
						<span class="price-separator">-</span>
						<div class="price-input-group">
							<label for="maxPrice">Max</label>
							<input type="number" id="maxPrice" name="maxPrice" value="<?php echo isset($_GET['maxPrice'])?$_GET['maxPrice']:'500000'; ?>" max="500000">
						</div>
					</div>
				</div>
				<button type="submit" class="btn-reset-filters" style="background-color: #4CAF50;">Apply Filters</button>
			</form>
		</div>
		<div class="services-header"><h2>Services</h2></div>
		<div class="services-content">
			<a href="gallery.php" class="service-item-link"><div class="service-item">Wedding</div></a>
			<a href="bday_gal.php" class="service-item-link"><div class="service-item">Birthday Party</div></a>
			<a href="anni_gal.php" class="service-item-link"><div class="service-item">Anniversary</div></a>
			<a href="other_gal.php" class="service-item-link"><div class="service-item active-service">Entertainment</div></a>
		</div>
		<div class="filter-content">
			<a href="other_gal.php" class="btn-reset-filters">Reset Filters</a>
		</div>
	</aside>

	<div class="gallery-main-content">
		<div class="container" style="max-width: 100%; width: 100%; padding: 0;">
			<h2 class="w3ls-title1">Our <span>Gallery</span></h2>
			<div class="grid_3 grid_5"><br /><br/>
				<div class="but_list w3layouts">
					<h1>
						<a href="gallery.php"><span class="label label-default">Wedding</span></a>
						<a href="bday_gal.php"><span class="label label-primary">Birthday Party</span></a>
						<a href="anni_gal.php"><span class="label label-success">Anniversary</span></a>
						<a href="other_gal.php"><span class="label label-warning">Entertainment</span></a>
					</h1>
				</div>
			</div>

			<?php
				include_once("Database/connect.php");
				$qry_all = "SELECT * FROM otherevent WHERE 1=1";
				if(isset($_GET['location']) && !empty($_GET['location'])){
					$location = mysqli_real_escape_string($con, $_GET['location']);
					$qry_all .= " AND location = '$location'";
				}
				if(isset($_GET['rating']) && $_GET['rating'] != '' && $_GET['rating'] != '0'){
					$qry_all .= " AND rating = " . intval($_GET['rating']);
				}
				if(isset($_GET['minPrice']) && $_GET['minPrice'] !== '' && $_GET['minPrice'] !== '0'){
					$qry_all .= " AND price >= " . intval($_GET['minPrice']);
				}
				if(isset($_GET['maxPrice']) && $_GET['maxPrice'] !== '' && $_GET['maxPrice'] != '500000'){
					$qry_all .= " AND price <= " . intval($_GET['maxPrice']);
				}
				$qry_all .= " ORDER BY id DESC";
				$res_all = mysqli_query($con, $qry_all) or die("Query Error: " . mysqli_error($con));
				$count_all = mysqli_num_rows($res_all);
			?>

			<div class="gallery-grids-top">
				<h3 class="category-title">Entertainment Venues & Services (<?php echo $count_all; ?> Results)</h3>
				<div style="display: flex; flex-wrap: wrap; gap: 20px;">
				<?php
					if($count_all == 0){
						echo "<div style='width:100%;padding:40px;text-align:center;'><div style='background:white;padding:40px;border-radius:12px;box-shadow:0 2px 8px rgba(0,0,0,0.1);'><h3 style='color:#8B2E2E;margin-bottom:10px;'>No venues found</h3><p style='color:#666;'>Try adjusting your filters to see more results.</p><a href='other_gal.php' style='display:inline-block;margin-top:20px;padding:12px 30px;background:#8B2E2E;color:white;text-decoration:none;border-radius:6px;'>Reset Filters</a></div></div>";
					}
					while($row = mysqli_fetch_array($res_all)){
						$rating_val = isset($row['rating']) ? floatval($row['rating']) : 0;
				?>
				<div style="width: 100%; margin-bottom: 25px;">
					<div class="venue-card">
						<div class="venue-image">
							<a class="example-image-link" href="images/<?php echo $row['img']; ?>" data-lightbox="other-set">
								<img src="images/<?php echo $row['img']; ?>" alt="<?php echo isset($row['nm']) ? $row['nm'] : 'Venue'; ?>"/>
							</a>
						</div>
						<div class="venue-details">
							<div>
								<h3><?php echo isset($row['nm']) ? $row['nm'] : 'Venue Name'; ?></h3>
								<div class="venue-rating">
									<?php
									for($i = 1; $i <= 5; $i++){
										echo ($i <= $rating_val) ? '<span style="color:#FFA500;">★</span>' : '<span style="color:#ddd;">★</span>';
									}
									echo " <small>(" . number_format($rating_val, 1) . ")</small>";
									?>
								</div>
								<div class="venue-info">
									<p><strong>Starting From:</strong> NPR <?php echo isset($row['price']) ? number_format($row['price']) : '0'; ?></p>
									<p><strong>Seat Capacity:</strong> <?php echo isset($row['capacity']) ? number_format($row['capacity']) : '0'; ?> people</p>
									<p><strong>Venue Type:</strong> <?php echo isset($row['venue_type']) ? $row['venue_type'] : 'N/A'; ?></p>
									<p><strong>Space Preference:</strong> <?php echo isset($row['space_preference']) ? $row['space_preference'] : 'N/A'; ?></p>
									<p><strong>Service Location:</strong> <?php echo isset($row['location']) ? $row['location'] : 'N/A'; ?></p>
								</div>
							</div>
							<div>
								<a href="book_other.php?id=<?php echo $row['id']; ?>" class="details-btn">DETAILS</a>
							</div>
						</div>
					</div>
				</div>
				<?php } ?>
				</div>
				<script src="js/lightbox-plus-jquery.min.js"></script>
			</div>
			<div class="clearfix"> </div>
		</div>
	</div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
	const stars = document.querySelectorAll('.star');
	const ratingInput = document.getElementById('rating');
	const priceSlider = document.getElementById('priceSlider');
	const minPriceInput = document.getElementById('minPrice');
	const maxPriceInput = document.getElementById('maxPrice');
	updateStarDisplay(parseInt(ratingInput.value));
	stars.forEach(star => {
		star.addEventListener('click', function() { ratingInput.value = parseInt(this.dataset.rating); updateStarDisplay(parseInt(ratingInput.value)); });
		star.addEventListener('mouseenter', function() { highlightStars(parseInt(this.dataset.rating)); });
	});
	document.querySelector('.rating-stars').addEventListener('mouseleave', function() { updateStarDisplay(parseInt(ratingInput.value)); });
	function updateStarDisplay(rating) { stars.forEach((star, i) => star.classList.toggle('active', i < rating)); }
	function highlightStars(rating) { stars.forEach((star, i) => star.style.color = i < rating ? '#FFB84D' : '#ddd'); }
	priceSlider.addEventListener('input', function() { maxPriceInput.value = this.value; });
	minPriceInput.addEventListener('input', function() { if(parseInt(this.value) > parseInt(maxPriceInput.value)) this.value = maxPriceInput.value; });
	maxPriceInput.addEventListener('input', function() { if(parseInt(this.value) < parseInt(minPriceInput.value)) this.value = minPriceInput.value; priceSlider.value = this.value; });
});
</script>

<?php include_once("footer.php"); ?>