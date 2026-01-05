<?php
include("../Database/connect.php");

$error_msg = "";

if(isset($_POST['submit']))
{
	session_start();
	
	// Sanitize inputs to prevent SQL injection
	$name = mysqli_real_escape_string($con, trim($_POST['nm']));
	$pwd = $_POST['pwd'];
	
	// Use prepared statement for better security
	$stmt = mysqli_prepare($con, "SELECT pswd FROM admin WHERE nm=?");
	mysqli_stmt_bind_param($stmt, "s", $name);
	mysqli_stmt_execute($stmt);
	$result = mysqli_stmt_get_result($stmt);
	
	if($row = mysqli_fetch_assoc($result))
	{
		// Verify password hash
		if(password_verify($pwd, $row['pswd']))
		{
			$_SESSION['admin'] = $name;
			header('Location:index.php');
			exit();
		}
		else
		{
			$error_msg = "Username or Password does not exist";
		}
	}
	else
	{
		$error_msg = "Username or Password does not exist";
	}
	
	mysqli_stmt_close($stmt);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<title>Admin Login - Classic Events</title>
<!-- meta tags -->
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="keywords" content="Classic Events Admin Login" />
<!-- //meta tags -->
<!-- Custom Theme files -->
<link href="../css/bootstrap.css" type="text/css" rel="stylesheet" media="all">
<link href="../css/style.css" type="text/css" rel="stylesheet" media="all">
<link rel="stylesheet" href="../css/flexslider.css" type="text/css" media="screen" />
<link href="../css/font-awesome.css" rel="stylesheet"> 
<!-- //Custom Theme files -->
<!-- js -->
<script src="js/jquery-1.11.1.min.js"></script> 
<!-- //js --> 
<!-- Google Fonts -->
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<!-- Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<style>
* {
	margin: 0;
	padding: 0;
	box-sizing: border-box;
}

body {
	font-family: 'Poppins', sans-serif;
	background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
	min-height: 100vh;
	display: flex;
	flex-direction: column;
	position: relative;
	overflow-x: hidden;
}

body::before {
	content: '';
	position: absolute;
	width: 500px;
	height: 500px;
	background: rgba(255, 255, 255, 0.1);
	border-radius: 50%;
	top: -250px;
	left: -250px;
	animation: float 6s ease-in-out infinite;
	z-index: 0;
}

body::after {
	content: '';
	position: absolute;
	width: 400px;
	height: 400px;
	background: rgba(255, 255, 255, 0.1);
	border-radius: 50%;
	bottom: -200px;
	right: -200px;
	animation: float 8s ease-in-out infinite reverse;
	z-index: 0;
}

@keyframes float {
	0%, 100% { transform: translateY(0) translateX(0); }
	50% { transform: translateY(20px) translateX(20px); }
}

/* Login Container */
.login-wrapper {
	flex: 1;
	display: flex;
	align-items: center;
	justify-content: center;
	padding: 40px 20px;
	position: relative;
	z-index: 1;
}

.login-container {
	width: 100%;
	max-width: 450px;
	background: #fff;
	border-radius: 20px;
	box-shadow: 0 20px 60px rgba(0,0,0,0.3);
	overflow: hidden;
	animation: slideUp 0.6s ease;
}

@keyframes slideUp {
	from {
		opacity: 0;
		transform: translateY(30px);
	}
	to {
		opacity: 1;
		transform: translateY(0);
	}
}

.login-header {
	background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
	padding: 50px 30px;
	text-align: center;
	color: #fff;
}

.logo-circle {
	width: 100px;
	height: 100px;
	background: rgba(255, 255, 255, 0.2);
	border-radius: 50%;
	display: flex;
	align-items: center;
	justify-content: center;
	margin: 0 auto 20px;
	backdrop-filter: blur(10px);
	border: 3px solid rgba(255, 255, 255, 0.3);
}

.logo-circle i {
	font-size: 48px;
	color: #fff;
}

.login-header h1 {
	font-size: 32px;
	font-weight: 700;
	margin-bottom: 10px;
	letter-spacing: 1px;
}

.login-header p {
	font-size: 14px;
	opacity: 0.9;
	font-weight: 300;
}

.login-body {
	padding: 40px 30px;
}

.alert {
	padding: 15px 20px;
	margin-bottom: 25px;
	border-radius: 10px;
	font-size: 14px;
	display: flex;
	align-items: center;
	animation: shake 0.5s ease;
}

@keyframes shake {
	0%, 100% { transform: translateX(0); }
	25% { transform: translateX(-10px); }
	75% { transform: translateX(10px); }
}

.alert i {
	margin-right: 10px;
	font-size: 18px;
}

.alert-danger {
	background: #f8d7da;
	color: #721c24;
	border: 1px solid #f5c6cb;
}

.form-group {
	margin-bottom: 25px;
	position: relative;
}

.form-group label {
	display: block;
	margin-bottom: 8px;
	color: #333;
	font-weight: 500;
	font-size: 14px;
}

.input-wrapper {
	position: relative;
	display: flex;
	align-items: center;
}

.input-wrapper i {
	position: absolute;
	left: 18px;
	color: #667eea;
	font-size: 16px;
	z-index: 1;
}

.form-control {
	width: 100%;
	padding: 15px 20px 15px 50px;
	border: 2px solid #e0e0e0;
	border-radius: 12px;
	font-size: 15px;
	font-family: 'Poppins', sans-serif;
	transition: all 0.3s ease;
	background: #f8f9fa;
}

.form-control:focus {
	outline: none;
	border-color: #667eea;
	background: #fff;
	box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
}

.password-toggle {
	position: absolute;
	right: 18px;
	cursor: pointer;
	color: #999;
	font-size: 16px;
	z-index: 2;
	transition: color 0.3s ease;
}

.password-toggle:hover {
	color: #667eea;
}

.remember-forgot {
	display: flex;
	justify-content: space-between;
	align-items: center;
	margin-bottom: 25px;
	font-size: 14px;
}

.remember-me {
	display: flex;
	align-items: center;
	color: #666;
}

.remember-me input {
	margin-right: 8px;
	cursor: pointer;
}

.forgot-link {
	color: #667eea;
	text-decoration: none;
	transition: color 0.3s ease;
}

.forgot-link:hover {
	color: #764ba2;
	text-decoration: underline;
}

.btn-login {
	width: 100%;
	padding: 16px;
	background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
	color: #fff;
	border: none;
	border-radius: 12px;
	font-size: 16px;
	font-weight: 600;
	cursor: pointer;
	transition: all 0.3s ease;
	text-transform: uppercase;
	letter-spacing: 1px;
}

.btn-login:hover {
	transform: translateY(-2px);
	box-shadow: 0 10px 25px rgba(102, 126, 234, 0.4);
}

.btn-login:active {
	transform: translateY(0);
}

.divider {
	display: flex;
	align-items: center;
	margin: 30px 0;
	color: #999;
	font-size: 14px;
}

.divider::before,
.divider::after {
	content: '';
	flex: 1;
	height: 1px;
	background: #e0e0e0;
}

.divider::before {
	margin-right: 15px;
}

.divider::after {
	margin-left: 15px;
}

.register-link {
	text-align: center;
	margin-top: 25px;
	color: #666;
	font-size: 14px;
}

.register-link a {
	color: #667eea;
	text-decoration: none;
	font-weight: 600;
	transition: color 0.3s ease;
}

.register-link a:hover {
	color: #764ba2;
	text-decoration: underline;
}

.features {
	display: grid;
	grid-template-columns: repeat(3, 1fr);
	gap: 15px;
	margin-top: 30px;
	padding-top: 30px;
	border-top: 1px solid #e0e0e0;
}

.feature-item {
	text-align: center;
	color: #666;
	font-size: 12px;
}

.feature-item i {
	display: block;
	font-size: 24px;
	color: #667eea;
	margin-bottom: 8px;
}

/* Footer */
.footer-bottom {
	background: rgba(255, 255, 255, 0.1);
	backdrop-filter: blur(10px);
	padding: 20px;
	text-align: center;
	color: #fff;
	position: relative;
	z-index: 10;
}

.footer-bottom p {
	margin: 0;
	font-size: 14px;
	opacity: 0.9;
}

@media (max-width: 576px) {
	.login-container {
		border-radius: 15px;
	}
	
	.login-header {
		padding: 40px 20px;
	}
	
	.logo-circle {
		width: 80px;
		height: 80px;
	}
	
	.logo-circle i {
		font-size: 36px;
	}
	
	.login-header h1 {
		font-size: 26px;
	}
	
	.login-body {
		padding: 30px 20px;
	}
	
	.features {
		grid-template-columns: 1fr;
		gap: 10px;
	}
	
	.remember-forgot {
		flex-direction: column;
		gap: 10px;
		align-items: flex-start;
	}
}
</style>
</head>
<body>

	
	<!-- Login Form -->
	<div class="login-wrapper">
		<div class="login-container">
			<div class="login-header">
				<div class="logo-circle">
					<i class="fas fa-shield-halved"></i>
				</div>
				<h1>Welcome Back!</h1>
				<p>Sign in to Classic Events Admin Panel</p>
			</div>
			
			<div class="login-body">
				<?php if(!empty($error_msg)): ?>
					<div class="alert alert-danger">
						<i class="fas fa-exclamation-circle"></i>
						<span><?php echo htmlspecialchars($error_msg); ?></span>
					</div>
				<?php endif; ?>
				
				<form name="login" action="" method="post" id="loginForm">
					<div class="form-group">
						<label for="username">Username</label>
						<div class="input-wrapper">
							<i class="fas fa-user"></i>
							<input type="text" 
								   id="username" 
								   name="nm" 
								   class="form-control" 
								   placeholder="Enter your username" 
								   required 
								   autocomplete="username">
						</div>
					</div>
					
					<div class="form-group">
						<label for="password">Password</label>
						<div class="input-wrapper">
							<i class="fas fa-lock"></i>
							<input type="password" 
								   id="password" 
								   name="pwd" 
								   class="form-control" 
								   placeholder="Enter your password" 
								   required
								   autocomplete="current-password">
							<i class="fas fa-eye password-toggle" onclick="togglePassword()"></i>
						</div>
					</div>
					
					<div class="remember-forgot">
						<label class="remember-me">
							<input type="checkbox" name="remember">
							<span>Remember me</span>
						</label>
						<a href="#" class="forgot-link">Forgot Password?</a>
					</div>
					
					<button type="submit" name="submit" class="btn-login">
						<i class="fas fa-sign-in-alt"></i> Sign In
					</button>
				</form>
				
				<div class="divider">OR</div>
				
				<div class="register-link">
					Don't have an account? <a href="registration.php"><i class="fas fa-user-plus"></i> Register here</a>
				</div>
				
				<div class="features">
					<div class="feature-item">
						<i class="fas fa-shield-alt"></i>
						<span>Secure Login</span>
					</div>
					<div class="feature-item">
						<i class="fas fa-clock"></i>
						<span>24/7 Access</span>
					</div>
					<div class="feature-item">
						<i class="fas fa-headset"></i>
						<span>Support</span>
					</div>
				</div>
			</div>
		</div>
	</div>
	
	<!-- footer -->
	<div class="footer-bottom">
		<div class="container">
			<p>© 2017 Classic Events, Rajkot. All rights reserved | Design by Drashti</p>
		</div>
	</div>
	<!-- //footer --> 

	<script>
		// Toggle password visibility
		function togglePassword() {
			const passwordInput = document.getElementById('password');
			const toggleIcon = document.querySelector('.password-toggle');
			
			if (passwordInput.type === 'password') {
				passwordInput.type = 'text';
				toggleIcon.classList.remove('fa-eye');
				toggleIcon.classList.add('fa-eye-slash');
			} 	elseif(strlen($pwd) < 6) {
		$error_msg = "Password must be at least 6 characters long!";
	}
	elseif(!preg_match('/[a-z]/i', $pwd)) {
		$error_msg = "Password must contain letters!";
	}
	elseif(!preg_match('/\d/', $pwd)) {
		$error_msg = "Password must contain at least one number!";
	}
	else {
				passwordInput.type = 'password';
				toggleIcon.classList.remove('fa-eye-slash');
				toggleIcon.classList.add('fa-eye');
			}
		}

		// Form validation
		document.getElementById('loginForm').addEventListener('submit', function(e) {
			const username = document.getElementById('username').value.trim();
			const password = document.getElementById('password').value;
			
			if (username === '' || password === '') {
				e.preventDefault();
				alert('Please fill in all fields!');
				return false;
			}
		});

		// Add loading state to button
		document.getElementById('loginForm').addEventListener('submit', function() {
			const btn = this.querySelector('button[type="submit"]');
			btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Signing In...';
			btn.disabled = true;
		});
	</script>
	
	<!-- start-smooth-scrolling-->
	<script type="text/javascript" src="js/move-top.js"></script>
	<script type="text/javascript" src="js/easing.js"></script>	
	<script type="text/javascript">
		jQuery(document).ready(function($) {
			$(".scroll").click(function(event){		
				event.preventDefault();
				$('html,body').animate({scrollTop:$(this.hash).offset().top},1000);
			});
		});
	</script>
	<!-- //end-smooth-scrolling -->	
	<!-- smooth-scrolling-of-move-up -->
	<script type="text/javascript">
		$(document).ready(function() {
			$().UItoTop({ easingType: 'easeOutQuart' });
		});
	</script>
	<!-- //smooth-scrolling-of-move-up -->
	<!-- Bootstrap core JavaScript -->
    <script src="js/bootstrap.js"></script>
</body>
</html>