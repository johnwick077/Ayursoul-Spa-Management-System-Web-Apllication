<?php
session_start();
require('fpdf/fpdf.php'); // Ensure FPDF library is included

// Check if the user is logged in
if (!isset($_SESSION['email'])) {
    header('location: signin.php');
    exit();
}

// Connect to the database
$db = mysqli_connect('localhost', 'root', '', 'userform');

// Fetch user details from the database
$email = $_SESSION['email'];
$query = "SELECT fname, lname, email, dob, gender FROM users WHERE email='$email'";
$result = mysqli_query($db, $query);
$user = mysqli_fetch_assoc($result);

// Fetch appointment history (assuming appointment history is stored in a table called 'appointments')
$appointment_query = "SELECT * FROM appointments WHERE email='$email'";
$appointments_result = mysqli_query($db, $appointment_query);

// If the logout button is clicked
if (isset($_POST['logout'])) {
    session_destroy();
    header('location: index.php');
    exit();
}

// Initialize an alert message variable
$alert_message = "";

// If the profile update form is submitted
if (isset($_POST['update_profile'])) {
    $fname = mysqli_real_escape_string($db, $_POST['fname']);
    $lname = mysqli_real_escape_string($db, $_POST['lname']);
    $dob = mysqli_real_escape_string($db, $_POST['dob']);
    $gender = mysqli_real_escape_string($db, $_POST['gender']);
    
    $update_query = "UPDATE users SET fname='$fname', lname='$lname', dob='$dob', gender='$gender' WHERE email='$email'";
    if (mysqli_query($db, $update_query)) {
        // Set a success message
        $alert_message = "Profile updated successfully!";
    } else {
        // Set an error message if something goes wrong
        $alert_message = "Error updating profile. Please try again.";
    }
    
    // Refresh the user details
    $user['fname'] = $fname;
    $user['lname'] = $lname;
    $user['dob'] = $dob;
    $user['gender'] = $gender;
}
// If download invoice button is clicked
if (isset($_GET['download_invoice'])) {
    $appointment_id = $_GET['download_invoice'];

    // Fetch specific appointment details
    $appointment_query = "SELECT * FROM appointments WHERE appointment_id='$appointment_id' AND email='$email'";
    $appointment_result = mysqli_query($db, $appointment_query);
    $appointment = mysqli_fetch_assoc($appointment_result);

    if ($appointment) {

        // Fetch guest details
        $guest_query = "SELECT first_name, last_name, gender FROM guests WHERE appointment_id='$appointment_id'";
        $guest_result = mysqli_query($db, $guest_query);

        // Generate PDF
        $pdf = new FPDF();
        $pdf->AddPage();

        // Set colors
        $pdf->SetDrawColor(50, 50, 100);
        $pdf->SetTextColor(50, 50, 100);

       

        // Company Name and Invoice Title
        $pdf->SetFont('Arial', 'B', 16);
        $pdf->Cell(0, 10, 'Ayursoul Spa & Wellness', 0, 1, 'C');
        $pdf->SetFont('Arial', 'I', 12);
        $pdf->Cell(0, 10, 'Wellness Appointment Invoice', 0, 1, 'C');
        $pdf->Ln(10);
        
        // Line separator
        $pdf->SetDrawColor(200, 200, 200);
        $pdf->Line(10, 40, 200, 40);
        $pdf->Ln(10);

        // User and Appointment Details Section
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(100, 10, 'Customer Information:', 0, 0);
        $pdf->Cell(90, 10, 'Invoice Date: ' . date('Y-m-d'), 0, 1, 'R');
        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(100, 10, $user['fname'] . ' ' . $user['lname'], 0, 1);
        $pdf->Cell(100, 10, 'Email: ' . $user['email'], 0, 1);
        $pdf->Ln(10);
        
        // Table Header Styling for Appointment Details
        $pdf->SetFillColor(230, 230, 250);
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(60, 10, 'Field', 1, 0, 'C', true);
        $pdf->Cell(130, 10, 'Details', 1, 1, 'C', true);
        
        // Appointment Details Table Data
        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(60, 10, 'Order ID', 1, 0, 'C');
        $pdf->Cell(130, 10, $appointment['appointment_id'], 1, 1, 'L');
        $pdf->Cell(60, 10, 'Service', 1, 0, 'C');
        $pdf->Cell(130, 10, $appointment['service_name'], 1, 1, 'L');
        $pdf->Cell(60, 10, 'Date', 1, 0, 'C');
        $pdf->Cell(130, 10, $appointment['appointment_date'], 1, 1, 'L');
        $pdf->Cell(60, 10, 'Time', 1, 0, 'C');
        $pdf->Cell(130, 10, $appointment['selected_time'], 1, 1, 'L');
        $pdf->Cell(60, 10, 'Amount', 1, 0, 'C');
        $pdf->Cell(130, 10, $appointment['service_price'], 1, 1, 'L');
        
        $pdf->Ln(10);

        // Table Header for Guest Details
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->SetFillColor(230, 230, 250);
        $pdf->Cell(60, 10, 'Guest Name', 1, 0, 'C', true);
        $pdf->Cell(60, 10, 'Gender', 1, 1, 'C', true);
        
        // Guest Details Table Data
        $pdf->SetFont('Arial', '', 12);
        if (mysqli_num_rows($guest_result) > 0) {
            while ($guest = mysqli_fetch_assoc($guest_result)) {
                $pdf->Cell(60, 10, $guest['first_name'] . ' ' . $guest['last_name'], 1, 0, 'L');
                $pdf->Cell(60, 10, $guest['gender'], 1, 1, 'L');
            }
        } else {
            $pdf->Cell(0, 10, 'No guests for this appointment.', 0, 1);
        }

        // Line separator above footer
        $pdf->Ln(10);
        $pdf->SetDrawColor(200, 200, 200);
        $pdf->Line(10, $pdf->GetY(), 200, $pdf->GetY());
        $pdf->Ln(5);

        // Footer: Payment Summary and Thank You Note
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(0, 10, 'Total Amount: ' . $appointment['service_price'], 0, 1, 'R');
        $pdf->SetFont('Arial', 'I', 10);
        $pdf->Ln(5);
        $pdf->SetTextColor(100, 100, 100);
        $pdf->Cell(0, 10, 'Thank you for choosing Ayursoul Spa. We hope you had a relaxing experience!', 0, 1, 'C');
        $pdf->Cell(0, 10, 'For inquiries, please contact us at support@ayursoul.com', 0, 1, 'C');

        // Output the PDF
        $pdf->Output('D', 'Invoice_' . $appointment_id . '.pdf');
        exit();
    } else {
        echo "Appointment not found.";
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Profile - Ayursoul</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="Profile page for Ayursoul" name="description">

    <!-- Favicon -->
    <link href="img/favicon.ico" rel="icon">

    <!-- Google Web Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">

    <!-- Bootstrap Stylesheet -->
    <link href="css/style.css" rel="stylesheet">
</head>

<body>
    <!-- Navbar Start -->
    <div class="container-fluid p-0">
        <nav class="navbar navbar-expand-lg bg-white navbar-light py-3 px-lg-5">
            <a href="index.php" class="navbar-brand">
                <h1 class="m-0 text-primary"><span class="text-dark">AYUR</span>SOUL</h1>
            </a>
            <div class="collapse navbar-collapse justify-content-between px-lg-3" id="navbarCollapse">
                <div class="navbar-nav py-0">
                    <a href="index.php" class="nav-item nav-link">Home</a>
                    <a href="about.html" class="nav-item nav-link">About</a>
                    <a href="service.html" class="nav-item nav-link">Services</a>
                    <a href="contact.html" class="nav-item nav-link">Contact</a>
                </div>
                <form method="POST" class="d-none d-lg-block">
                    <button type="submit" name="logout" class="btn btn-primary">Logout</button>
                </form>
            </div>
        </nav>
    </div>
    <!-- Navbar End -->

    <!-- Profile Section Start -->
    <div class="container mt-5">
        <h2 class="text-center">Your Profile</h2>
        <div class="row justify-content-center">
            <div class="col-lg-6">

                <!-- Display alert message -->
                <?php if (!empty($alert_message)): ?>
                    <div class="alert <?php echo ($alert_message == 'Profile updated successfully!') ? 'alert-success' : 'alert-danger'; ?> alert-dismissible fade show" role="alert">
                        <?php echo $alert_message; ?>
                        
                    </div>
                <?php endif; ?>

                <div class="card shadow-sm">
                    <div class="card-body">
                        <h4 class="card-title text-center">Personal Information</h4>

                        <!-- Profile Edit Form -->
                        <form method="POST">
    <div class="form-group">
        <label for="fname">First Name</label>
        <input type="text" class="form-control" id="fname" name="fname" value="<?php echo htmlspecialchars($user['fname']); ?>" required>
    </div>
    <div class="form-group">
        <label for="lname">Last Name</label>
        <input type="text" class="form-control" id="lname" name="lname" value="<?php echo htmlspecialchars($user['lname']); ?>" required>
    </div>
    <div class="form-group">
        <label for="email">Email</label>
        <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" readonly>
    </div>
    <div class="form-group">
        <label for="dob">Date of Birth</label>
        <input type="date" class="form-control" id="dob" name="dob" value="<?php echo htmlspecialchars($user['dob']); ?>" required>
    </div>
    <div class="form-group">
        <label for="gender">Gender</label>
        <select class="form-control" id="gender" name="gender" required>
            <option value="Male" <?php if ($user['gender'] == 'Male') echo 'selected'; ?>>Male</option>
            <option value="Female" <?php if ($user['gender'] == 'Female') echo 'selected'; ?>>Female</option>
            <option value="Other" <?php if ($user['gender'] == 'Other') echo 'selected'; ?>>Other</option>
        </select>
    </div>
    <button type="submit" name="update_profile" class="btn btn-primary mt-3">Update Profile</button>
</form>

                    </div>
                </div>
            </div>
        </div>

        <!-- Appointment History Section -->
        <div class="row justify-content-center mt-5">
            <div class="col-lg-8">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h4 class="card-title text-center">Appointment History</h4>
                        <?php if (mysqli_num_rows($appointments_result) > 0): ?>
                            <ul class="list-group">
                                <?php while ($appointment = mysqli_fetch_assoc($appointments_result)): ?>
                                    <li class="list-group-item">
                                        <strong>Service:</strong> <?php echo htmlspecialchars($appointment['service_name']); ?> <br>
                                        <strong>Date:</strong> <?php echo htmlspecialchars($appointment['appointment_date']); ?> <br>
                                        <strong>Time:</strong> <?php echo htmlspecialchars($appointment['selected_time']); ?><br>
                                        <strong>Payment Status:</strong> <?php echo htmlspecialchars($appointment['payment_status']); ?><br>
                                        <!-- Download Invoice Button -->
                                        <a href="?download_invoice=<?php echo $appointment['appointment_id']; ?>" class="btn btn-primary btn-sm mt-2">Download Invoice</a>
                                    </li>
                                <?php endwhile; ?>
                            </ul>
                        <?php else: ?>
                            <p class="text-center">No appointments found.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Profile Section End -->

    <!-- Footer Start -->
    <div class="container-fluid bg-dark text-white mt-5 py-4 px-sm-3 px-md-5">
        <div class="row">
            <div class="col-lg-6 text-center text-md-left mb-3 mb-md-0">
                <p class="m-0"> &copy; Ayursoul. All Rights Reserved. </p>
            </div>
        </div>
    </div>
    <!-- Footer End -->

    <!-- Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.bundle.min.js"></script>
</body>

</html>
