<?php
session_start(); // start session for potential error/success messages
include('Database/connect.php');

$error = '';
$success = '';

if(isset($_POST['submit'])) {
    // Sanitize and validate inputs
    $name = trim($_POST['Name']);
    $email = trim($_POST['Email']);
    $message = trim($_POST['Message']);
    
    // Validation
    if(empty($name)) {
        $error = "Please enter your name.";
    } elseif(empty($email)) {
        $error = "Please enter your email address.";
    } elseif(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Please enter a valid email address (e.g., name@example.com).";
    } elseif(empty($message)) {
        $error = "Please enter your message.";
    } else {
        // Escape to prevent SQL injection
        $safe_name = mysqli_real_escape_string($con, $name);
        $safe_email = mysqli_real_escape_string($con, $email);
        $safe_message = mysqli_real_escape_string($con, $message);
        
        // Insert into feedback table (assuming id auto-increment)
        $query = "INSERT INTO feedback (nm, email, msg) VALUES ('$safe_name', '$safe_email', '$safe_message')";
        if(mysqli_query($con, $query)) {
            $success = "Your message has been sent successfully! We'll get back to you soon.";
            // Clear form inputs after success (optional)
            $name = $email = $message = '';
        } else {
            $error = "Something went wrong. Please try again later.";
        }
    }
}

include_once("header.php");
?>
<!-- //header -->
<div class="banner about-bnr w3-agileits">
    <div class="container"></div>
</div>
<!-- contact -->
<div class="contact">
    <div class="container">
        <h2 class="w3ls-title1">Contact <span>Us</span></h2>
        <div class="contact-agileitsinfo">
            <div class="col-md-8 contact-grids">
                <p>As times go by in your life, it becomes more precious. So, make every moment mindful, meaningful and memorable – and the most memorable moments in life are the ones you have never planned.</p><br />
                <h5>...BECAUSE WE WILL BE THERE TO PLAN THEM FOR YOU !!</h5>	
                <div class="contact-w3form">
                    <h3 class="w3ls-title1">Drop Us a Line</h3>
                    
                    <?php if($error): ?>
                        <div style="background: #f8d7da; color: #721c24; padding: 10px; margin-bottom: 15px; border-radius: 5px;">
                            <?php echo htmlspecialchars($error); ?>
                        </div>
                    <?php endif; ?>
                    
                    <?php if($success): ?>
                        <div style="background: #d4edda; color: #155724; padding: 10px; margin-bottom: 15px; border-radius: 5px;">
                            <?php echo htmlspecialchars($success); ?>
                        </div>
                    <?php endif; ?>
                    
                    <form action="#" method="post"> 
                        <textarea name="Message" placeholder="Message..." required><?php echo htmlspecialchars($message ?? ''); ?></textarea>
                        <input type="text" name="Name" placeholder="Your Name" required value="<?php echo htmlspecialchars($name ?? ''); ?>"/>
                        <input type="email" name="Email" placeholder="Email" required value="<?php echo htmlspecialchars($email ?? ''); ?>"/>
                        <input type="submit" name="submit" value="SEND">
                    </form>
                </div>
            </div>
            <div class="col-md-4 contact-grids">
                <div class="cnt-address">
                    <h3 class="w3ls-title1">Address</h3>
                    <h4>Classic Events</h4>
                    <p>Patan Lalitpur,<span></span>Nepal.</p>
                    <h4>Get In Touch</h4>
                    <p>Samar Shrestha: +977 90333 36811<br>
                       Mohit Shakya: +977 96870 00004<br>
                       E-mail: <a href="mailto:Classicevents@gmail.com">Classicevents@gmail.com</a>
                    </p>
                </div>
            </div>
            <div class="clearfix"> </div>
        </div>
    </div>
</div>
<!-- //contact -->
<?php include_once("footer.php"); ?>