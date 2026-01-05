<?php
include("../Database/connect.php");

$success_msg = "";
$error_msg = "";

if(isset($_POST['submit']))
{
	$name = mysqli_real_escape_string($con, trim($_POST['nm']));
	$email = mysqli_real_escape_string($con, trim($_POST['email']));
	$pwd = $_POST['pwd'];
	$confirm_pwd = $_POST['confirm_pwd'];
	
	// Validation
	if(empty($name) || empty($email) || empty($pwd) || empty($confirm_pwd)) {
		$error_msg = "All fields are required!";
	}
	elseif($pwd !== $confirm_pwd) {
		$error_msg = "Passwords do not match!";
	}
	elseif(strlen($pwd) < 6) {
		$error_msg = "Password must be at least 6 characters long!";
	}
	elseif(!preg_match('/[a-z]/i', $pwd)) {
		$error_msg = "Password must contain letters!";
	}
	elseif(!preg_match('/\d/', $pwd)) {
		$error_msg = "Password must contain at least one number!";
	}
	else {
		// Check if username already exists
		$check_qry = mysqli_query($con, "SELECT * FROM admin WHERE nm='$name'");
		if(mysqli_num_rows($check_qry) > 0) {
			$error_msg = "Username already exists!";
		}
		else {
			// Hash the password
			$hashed_pwd = password_hash($pwd, PASSWORD_DEFAULT);
			
			// Insert new admin
			$insert_qry = mysqli_query($con, "INSERT INTO admin (nm, email, pswd) VALUES ('$name', '$email', '$hashed_pwd')");
			
			if($insert_qry) {
				$success_msg = "Registration successful! You can now login.";
			}
			else {
				$error_msg = "Registration failed! Please try again.";
			}
		}
	}
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<title>Admin Registration - Classic Events</title>
<!-- meta tags -->
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="keywords" content="Classic Events Admin Registration" />
<!-- //meta tags -->
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
	align-items: center;
	justify-content: center;
	padding: 20px;
}

.registration-container {
	width: 100%;
	max-width: 480px;
	background: #fff;
	border-radius: 20px;
	box-shadow: 0 20px 60px rgba(0,0,0,0.3);
	overflow: hidden;
	animation: slideUp 0.5s ease;
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

.registration-header {
	background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
	padding: 40px 30px;
	text-align: center;
	color: #fff;
}

.registration-header h1 {
	font-size: 32px;
	font-weight: 700;
	margin-bottom: 10px;
	letter-spacing: 1px;
}

.registration-header p {
	font-size: 14px;
	opacity: 0.9;
	font-weight: 300;
}

.registration-body {
	padding: 40px 30px;
}

.alert {
	padding: 15px 20px;
	margin-bottom: 25px;
	border-radius: 10px;
	font-size: 14px;
	display: flex;
	align-items: center;
	animation: slideDown 0.3s ease;
}

@keyframes slideDown {
	from {
		opacity: 0;
		transform: translateY(-10px);
	}
	to {
		opacity: 1;
		transform: translateY(0);
	}
}

.alert i {
	margin-right: 10px;
	font-size: 18px;
}

.alert-success {
	background: #d4edda;
	color: #155724;
	border: 1px solid #c3e6cb;
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

.btn-register {
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
	margin-top: 10px;
	text-transform: uppercase;
	letter-spacing: 1px;
}

.btn-register:hover {
	transform: translateY(-2px);
	box-shadow: 0 10px 25px rgba(102, 126, 234, 0.4);
}

.btn-register:active {
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

.login-link {
	text-align: center;
	margin-top: 25px;
	color: #666;
	font-size: 14px;
}

.login-link a {
	color: #667eea;
	text-decoration: none;
	font-weight: 600;
	transition: color 0.3s ease;
}

.login-link a:hover {
	color: #764ba2;
	text-decoration: underline;
}

.password-strength {
	margin-top: 12px;
	font-size: 12px;
}

.strength-bar {
	height: 5px;
	background: #e0e0e0;
	border-radius: 3px;
	margin-bottom: 12px;
	overflow: hidden;
}

.strength-fill {
	height: 100%;
	width: 0;
	transition: all 0.3s ease;
	border-radius: 3px;
}

.strength-weak .strength-fill {
	width: 33%;
	background: #f44336;
}

.strength-medium .strength-fill {
	width: 66%;
	background: #ff9800;
}

.strength-strong .strength-fill {
	width: 100%;
	background: #4caf50;
}

.password-requirements {
	display: none;
	background: #f8f9fa;
	padding: 15px;
	border-radius: 8px;
	border: 1px solid #e0e0e0;
}

.password-requirements.show {
	display: block;
	animation: fadeIn 0.3s ease;
}

@keyframes fadeIn {
	from { opacity: 0; transform: translateY(-5px); }
	to { opacity: 1; transform: translateY(0); }
}

.requirement {
	display: flex;
	align-items: center;
	margin-bottom: 8px;
	color: #999;
	font-size: 13px;
	transition: all 0.3s ease;
}

.requirement:last-child {
	margin-bottom: 0;
}

.requirement i {
	font-size: 8px;
	margin-right: 10px;
	transition: all 0.3s ease;
}

.requirement.met {
	color: #4caf50;
}

.requirement.met i {
	color: #4caf50;
}

.requirement.not-met {
	color: #f44336;
}

.requirement.not-met i {
	color: #f44336;
}

@media (max-width: 576px) {
	.registration-container {
		border-radius: 15px;
	}
	
	.registration-header {
		padding: 30px 20px;
	}
	
	.registration-header h1 {
		font-size: 26px;
	}
	
	.registration-body {
		padding: 30px 20px;
	}
}
</style>
</head>
<body>
	<div class="registration-container">
		<div class="registration-header">
			<h1><i class="fas fa-user-shield"></i> Admin Registration</h1>
			<p>Create your admin account for Classic Events</p>
		</div>
		
		<div class="registration-body">
			<?php if(!empty($success_msg)): ?>
				<div class="alert alert-success">
					<i class="fas fa-check-circle"></i>
					<span><?php echo $success_msg; ?></span>
				</div>
			<?php endif; ?>
			
			<?php if(!empty($error_msg)): ?>
				<div class="alert alert-danger">
					<i class="fas fa-exclamation-circle"></i>
					<span><?php echo $error_msg; ?></span>
				</div>
			<?php endif; ?>
			
			<form name="registration" action="" method="post" id="registrationForm">
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
							   value="<?php echo isset($_POST['nm']) ? htmlspecialchars($_POST['nm']) : ''; ?>">
					</div>
				</div>
				
				<div class="form-group">
					<label for="email">Email Address</label>
					<div class="input-wrapper">
						<i class="fas fa-envelope"></i>
						<input type="email" 
							   id="email" 
							   name="email" 
							   class="form-control" 
							   placeholder="Enter your email" 
							   required 
							   value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
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
							   required>
						<i class="fas fa-eye password-toggle" onclick="togglePassword('password', this)"></i>
					</div>
					<div class="password-strength" id="passwordStrength">
						<div class="strength-bar">
							<div class="strength-fill"></div>
						</div>
					</div>
				</div>
				
				<div class="form-group">
					<label for="confirm_password">Confirm Password</label>
					<div class="input-wrapper">
						<i class="fas fa-lock"></i>
						<input type="password" 
							   id="confirm_password" 
							   name="confirm_pwd" 
							   class="form-control" 
							   placeholder="Confirm your password" 
							   required>
						<i class="fas fa-eye password-toggle" onclick="togglePassword('confirm_password', this)"></i>
					</div>
				</div>
				
				<button type="submit" name="submit" class="btn-register">
					<i class="fas fa-user-plus"></i> Create Account
				</button>
			</form>
			
			<div class="divider">OR</div>
			
			<div class="login-link">
				Already have an account? <a href="login.php"><i class="fas fa-sign-in-alt"></i> Login here</a>
			</div>
		</div>
	</div>

	<script>
		// Toggle password visibility
		function togglePassword(inputId, icon) {
			const input = document.getElementById(inputId);
			if (input.type === 'password') {
				input.type = 'text';
				icon.classList.remove('fa-eye');
				icon.classList.add('fa-eye-slash');
			} else {
				input.type = 'password';
				icon.classList.remove('fa-eye-slash');
				icon.classList.add('fa-eye');
			}
		}

		// Password strength indicator with requirements
		const passwordInput = document.getElementById('password');
		const strengthIndicator = document.getElementById('passwordStrength');
		const requirementsBox = document.getElementById('passwordRequirements');

		// Requirement elements
		const reqLength = document.getElementById('req-length');
		const reqLowercase = document.getElementById('req-lowercase');
		const reqNumber = document.getElementById('req-number');

		passwordInput.addEventListener('focus', function() {
			requirementsBox.classList.add('show');
		});

		passwordInput.addEventListener('input', function() {
			const password = this.value;
			
			// Check each requirement
			const hasLength = password.length >= 6;
			const hasLowercase = /[a-z]/i.test(password);
			const hasNumber = /\d/.test(password);

			// Update requirement indicators
			updateRequirement(reqLength, hasLength);
			updateRequirement(reqLowercase, hasLowercase);
			updateRequirement(reqNumber, hasNumber);

			// Calculate overall strength
			const metRequirements = [hasLength, hasLowercase, hasNumber].filter(Boolean).length;
			
			strengthIndicator.className = 'password-strength';
			
			if (password.length === 0) {
				strengthIndicator.className = 'password-strength';
				requirementsBox.classList.remove('show');
			} else if (metRequirements === 1) {
				strengthIndicator.className = 'password-strength strength-weak';
			} else if (metRequirements === 2) {
				strengthIndicator.className = 'password-strength strength-medium';
			} else {
				strengthIndicator.className = 'password-strength strength-strong';
			}
		});

		function updateRequirement(element, isMet) {
			if (isMet) {
				element.classList.remove('not-met');
				element.classList.add('met');
				element.querySelector('i').className = 'fas fa-check-circle';
			} else {
				element.classList.remove('met');
				element.classList.add('not-met');
				element.querySelector('i').className = 'fas fa-times-circle';
			}
		}

		// Form validation
		document.getElementById('registrationForm').addEventListener('submit', function(e) {
			const password = document.getElementById('password').value;
			const confirmPassword = document.getElementById('confirm_password').value;
			
			if (password !== confirmPassword) {
				e.preventDefault();
				alert('Passwords do not match!');
				return false;
			}
			
			// Check all password requirements
			const hasLength = password.length >= 6;
			const hasLowercase = /[a-z]/i.test(password);
			const hasNumber = /\d/.test(password);

			if (!hasLength) {
				e.preventDefault();
				alert('Password must be at least 6 characters long!');
				return false;
			}

			if (!hasLowercase) {
				e.preventDefault();
				alert('Password must contain letters (a-z)!');
				return false;
			}

			if (!hasNumber) {
				e.preventDefault();
				alert('Password must contain at least one number (0-9)!');
				return false;
			}
		});
	</script>
</body>
</html>