<?php include('server.php'); ?>
<html>
<head>
    <link rel="stylesheet" type="text/css" href="css/stile.css">
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
</head>
<body background="img/1.png">

<form class="form" method="post" action="forgot_password.php">
    <h2>Reset Password</h2>
    <?php include('errors.php'); ?>
    
    <div class="flex-column">
      <label>Email </label>
    </div>
    <div class="inputForm">
      <input placeholder="Enter your Email" class="input" type="email" required name="email">
    </div>
    
    <button class="button-submit" name="forgot_password">Send Reset Link</button>
    <a class="a" href="index.php">Back to home</a>
</form>

</body>
</html>

<?php
if (isset($_POST['forgot_password'])) {
    $email = mysqli_real_escape_string($db, $_POST['email']);

    // Check if email exists
    $query = "SELECT * FROM users WHERE email='$email'";
    $result = mysqli_query($db, $query);
    if (mysqli_num_rows($result) > 0) {
        // Generate token
        $token = bin2hex(random_bytes(50));
        
        // Save token to database
        $query = "UPDATE users SET reset_token='$token' WHERE email='$email'";
        mysqli_query($db, $query);
        
        // Send reset email
        sendResetEmail($email, $token);
        
        echo "password reset link has been sent to your email.";
    } else {
        array_push($errors, "No account found with that email.");
    }
}

// Function to send reset email using PHPMailer
function sendResetEmail($email, $token) {
    require 'vendor/autoload.php'; // Make sure you've installed PHPMailer via Composer

    $mail = new PHPMailer\PHPMailer\PHPMailer();
    
    // SMTP settings
    // Add this line to disable debug output
    $mail->SMTPDebug = 0; // Disable debug output
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com'; // Use your SMTP host (e.g., smtp.gmail.com for Gmail)
    $mail->SMTPAuth = true;
    $mail->Username = 'ayursoul35@gmail.com'; // Your SMTP username
    $mail->Password = 'fszbfntcmpaqwwbe'; // Your SMTP password (for Gmail, this would be the App password)
    $mail->SMTPSecure = 'ssl'; // Secure with SMTPS (SSL encryption)
    $mail->Port = 465; // Port 465 for SMTPS
    
    // Email content
    $mail->setFrom('ayursoul35@gmail.com', 'Ayursoul');
    $mail->addAddress($email);
    $mail->isHTML(true);
    $mail->Subject = 'Password Reset Request';
    $mail->Body = "Hi $email, <br><br>Click <a href='http://localhost/miniproject/reset_password.php?token=$token'>here</a> to reset your password.";

    if (!$mail->send()) {
        echo 'Mailer Error: ' . $mail->ErrorInfo;
    } else {
        echo 'A ';
    }
}

?>
