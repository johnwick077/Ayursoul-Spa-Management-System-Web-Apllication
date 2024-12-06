<?php 
include('server.php'); // Include server-side logic (ensure server.php handles the $result)
require_once 'vendor/autoload.php'; // Ensure path is correct

?>

<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" type="text/css" href="css/stile.css">
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <style>
      .a{
        text-align: center;
      }
      
    </style>
</head> 
<body background="img/1.png">

<?php


// Display message if set
if (isset($_SESSION['message'])) {
    echo "<script>alert('" . $_SESSION['message'] . "');</script>";
    unset($_SESSION['message']); // Clear the message after displaying it
}
?>


<!-- Registration Form -->
<form class="form" method="post" action="signup.php">
  <h2>Sign Up</h2>
  <?php include('errors.php'); ?>
  
  <div class="flex-column">
    <label>First Name</label>
  </div>
  <div class="inputForm">
    <input placeholder="Enter your First Name" class="input" type="text" required name="fname">
  </div>
  
  <div class="flex-column">
    <label>Last Name</label>
  </div>
  <div class="inputForm">
    <input placeholder="Enter your Last Name" class="input" type="text" required name="lname">
  </div>

  <div class="flex-column">
    <label>Date of Birth</label>
  </div>
  <div class="inputForm">
    <input placeholder="Enter your Date of Birth" class="input" type="date" required name="dob">
  </div>
  
  <div class="flex-column">
    <label>Gender</label>
  </div>
  <div class="inputForm">
    <select name="gender" required class="input">
      <option value="">Select Gender</option>
      <option value="male">Male</option>
      <option value="female">Female</option>
      <option value="other">Other</option>
    </select>
  </div>
  
  <div class="flex-column">
    <label>Email</label>
  </div>
  <div class="inputForm">
    <input placeholder="Enter your Email" class="input" type="email" required name="email">
  </div>

  <div class="flex-column">
    <label>Password</label>
  </div>
  <div class="inputForm">
    <input placeholder="Enter your Password" class="input" type="password" required name="password_1">
  </div>

  <div class="flex-column">
    <label>Confirm Password</label>
  </div>
  <div class="inputForm">
    <input placeholder="Confirm your Password" class="input" type="password" required name="password_2">
  </div>

  <button class="button-submit" name="reg_user">Sign Up</button>
  
  <span id="switchToSignin">Already have an account? <a class="span" href="signin.php">Sign In</a></span>
  
  <br>
  <a class="a" href="index.php">Back to home</a>
</form>



<!-- PHPMailer email verification -->
<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// After inserting user data into database
if (isset($result) && $result) {
    $token = bin2hex(random_bytes(50)); // Generate token
    
    // Insert token into the database
    $update_query = "UPDATE users SET email_verification_token='$token' WHERE email='$email'";
    mysqli_query($db, $update_query);

    // Prepare the verification email
    $verification_link = "http://localhost/miniproject/verify_email.php?token=" . $token;
    
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com'; // Gmail SMTP
        $mail->SMTPAuth = true;
        $mail->Username = 'ayursoul35@gmail.com'; 
        $mail->Password = 'fszbfntcmpaqwwbe'; 
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port = 465;

        // Set email recipients
        $mail->setFrom('ayursoul35@gmail.com', 'Ayursoul');
        $mail->addAddress($email);  

        // Email content
        $mail->isHTML(true);
        $mail->Subject = 'Verify Your Email Address';
        $mail->Body = "Hello $fname, <br><br> Please click on the link below to verify your email address: <br><br> <a href='$verification_link'>Verify Email</a><br><br>Thank you!";
        
        $mail->send();

        // Set success message in session
        $_SESSION['message'] = "A verification email has been sent to your email address. Please check your inbox or spam folder.";
        header('location: signup.php');
        exit();
        
        

    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}
?>



</body>
</html>
