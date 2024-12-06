<?php
// Connect to the database
$db = mysqli_connect('localhost', 'root', '', 'userform');

if (isset($_GET['token'])) {
    $token = mysqli_real_escape_string($db, $_GET['token']);
    
    // Check if the token exists
    $query = "SELECT * FROM users WHERE email_verification_token='$token' LIMIT 1";
    $result = mysqli_query($db, $query);

    if (mysqli_num_rows($result) > 0) {
        // If token is valid, mark email as verified
        $update_query = "UPDATE users SET email_verified=1, email_verification_token=NULL WHERE email_verification_token='$token'";
        if (mysqli_query($db, $update_query)) {
            echo "Email verification successful! You will be redirected to the signin page in 3 seconds.";
            // Redirect to signin.php after 3 seconds
            echo "<script>
                    setTimeout(function() {
                        window.location.href = 'signin.php';
                    }, 3000);
                  </script>";
        } else {
            echo "Failed to verify email. Please try again.";
            // Optional: Redirect to another page if verification fails
        }
    } else {
        echo "Invalid verification link.";
    }
} else {
    echo "No token provided.";
}
?>
