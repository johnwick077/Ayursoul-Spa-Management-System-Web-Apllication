<?php include('server.php'); ?>
<html>
<head>
    <link rel="stylesheet" type="text/css" href="css/stile.css">
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
</head>
<body background="img/1.png">

<form class="form" method="post" action="reset_password.php">
    <h2>Reset Password</h2>
    <?php include('errors.php'); ?>
    
    <?php
    // Check if the token exists in the URL before setting it
    if (isset($_GET['token'])) {
        $token = $_GET['token'];
        echo '<input type="hidden" name="token" value="' . htmlspecialchars($token) . '">';
    } else {
        //echo '<p style="color:red;">Error: Missing or invalid token.</p>';

    }
    ?>
    
    <div class="flex-column">
      <label>New Password</label>
    </div>
    <div class="inputForm">
      <input placeholder="Enter new password" class="input" type="password" required name="password_1">
    </div>
    
    <div class="flex-column">
      <label>Confirm New Password</label>
    </div>
    <div class="inputForm">
      <input placeholder="Confirm new password" class="input" type="password" required name="password_2">
    </div>
    
    <button class="button-submit" name="reset_password">Reset Password</button>
    <a class="a" href="signin.php">Back to Sign In</a>
</form>

</body>
</html>

<?php
if (isset($_POST['reset_password'])) {
    $errors = [];
    // Check if token is set in the POST request
    $token = $_POST['token'] ?? null;
    $password_1 = mysqli_real_escape_string($db, $_POST['password_1']);
    $password_2 = mysqli_real_escape_string($db, $_POST['password_2']);

    if (!$token) {
        array_push($errors, "Invalid or missing token.");
    } elseif ($password_1 != $password_2) {
        array_push($errors, "Passwords do not match");
    } else {
        // Hash the password before saving it to the database
        $password = md5($password_1);

        // Update the password in the database where the reset token matches
        $query = "UPDATE users SET password='$password', reset_token=NULL WHERE reset_token='$token'";
        if (mysqli_query($db, $query) && mysqli_affected_rows($db) > 0) {
            echo "<p style='color:green;'>Password has been reset successfully. You can now <a href='signin.php'>Sign In</a>.</p>";
        } else {
            array_push($errors, "Invalid token or password reset failed.");
        }
    }
}
?>
