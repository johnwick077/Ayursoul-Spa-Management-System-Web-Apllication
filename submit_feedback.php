<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "userform";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize alert message
$alert_message = "";

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form data
    $name = $_POST['name'];
    $email = $_POST['email'];
    $feedback = $_POST['feedback'];

    // Prepare and bind
    $stmt = $conn->prepare("INSERT INTO testimonials (name, email, feedback) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $name, $email, $feedback);

    // Execute the statement
    if ($stmt->execute()) {
        $alert_message = "Thank you for your feedback!";
    } else {
        $alert_message = "Error: " . $stmt->error;
    }

    // Close connection
    $stmt->close();
    $conn->close();
    
    // Display the alert message and redirect back to the feedback form
    echo "<script>
        alert('$alert_message');
        window.location.href = 'index.php'; // Replace with your actual feedback form page
    </script>";
}
?>
