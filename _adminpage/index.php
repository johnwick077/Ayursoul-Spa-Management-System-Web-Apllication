<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Ayursoul Admin</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="" name="keywords">
    <meta content="" name="description">

    <!-- Favicon -->
    <link href="img/favicon.ico" rel="icon">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Heebo:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Icon Font Stylesheet -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Libraries Stylesheet -->
    <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
    <link href="lib/tempusdominus/css/tempusdominus-bootstrap-4.min.css" rel="stylesheet" />

    <!-- Customized Bootstrap Stylesheet -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Template Stylesheet -->
    <link href="css/style.css" rel="stylesheet">
</head>

<body>
    <div class="container-fluid position-relative bg-white d-flex p-0">
        <!-- Spinner Start -->
        
        <!-- Spinner End -->


        <!-- Sidebar Start -->
        <div class="sidebar pe-4 pb-3">
            <nav class="navbar bg-light navbar-light">
                <a href="index.php" class="navbar-brand mx-4 mb-3">
                    <h3 class="text-primary"><i class="fa fa-hashtag me-2"></i>Ayursoul</h3>
                </a>
                <div class="d-flex align-items-center ms-4 mb-4">
                    
                    <div class="ms-3">
                        <h6 class="mb-0">Admin</h6>
                        
                    </div>
                </div>
                <div class="navbar-nav w-100">
                    <a href="index.php" class="nav-item nav-link active"><i class="fa fa-tachometer-alt me-2"></i>Dashboard</a>
                    <a href="all_services.php" class="nav-item nav-link" ><i class="fa fa-laptop me-2"></i>Services</a>
                    <a href="feedback.php" class="nav-item nav-link"><i class="fa fa-comments me-2"></i>Feedback</a>
                    
                </div>
            </nav>
        </div>
        <!-- Sidebar End -->


        <!-- Content Start -->
        <div class="content">
            <!-- Navbar Start -->
            <nav class="navbar navbar-expand bg-light navbar-light sticky-top px-4 py-0">
                <a href="index.html" class="navbar-brand d-flex d-lg-none me-4">
                    <h2 class="text-primary mb-0"><i class="fa fa-hashtag"></i></h2>
                </a>
                <a href="#" class="sidebar-toggler flex-shrink-0">
                    <i class="fa fa-bars"></i>
                </a>
                
                <div class="navbar-nav align-items-center ms-auto">
                    
                    
                    <div class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                            
                            <span class="d-none d-lg-inline-flex">Admin</span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end bg-light border-0 rounded-0 rounded-bottom m-0">
                            
                            <a href="signin.php" class="dropdown-item">Log Out</a>
                        </div>
                    </div>
                </div>
            </nav>
            <!-- Navbar End -->


            <!-- Sale & Revenue Start -->
           
            <!-- Sale & Revenue End -->


            <!-- Sales Chart Start -->
            
            <!-- Sales Chart End -->


            <?php
// Database connection
$db = mysqli_connect('localhost', 'root', '', 'userform');

// Fetch the latest 10 users
$user_query = "SELECT id, email, fname AS first_name, lname AS last_name, email_verified FROM users ORDER BY created_at DESC LIMIT 10";
$user_result = mysqli_query($db, $user_query);
?>

<!-- Recent Users Start -->
<div class="container-fluid pt-4 px-4">
    <div class="bg-light text-center rounded p-4">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <h6 class="mb-0">All Signed-Up Users</h6>
            <a href="all_users.php">Show All</a>
        </div>
        <div class="table-responsive">
            <table class="table text-start align-middle table-bordered table-hover mb-0">
                <thead>
                    <tr class="text-dark">
                        <th scope="col">User ID</th>
                        <th scope="col">Name</th>
                        <th scope="col">Email</th>
                        <th scope="col">Verification</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($user = mysqli_fetch_assoc($user_result)): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($user['id']); ?></td>
                            <td><?php echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?></td>
                            <td><?php echo htmlspecialchars($user['email']); ?></td>
                            <td>
                                <?php echo $user['email_verified'] ? '<span class="badge bg-success">Verified</span>' : '<span class="badge bg-danger">Not Verified</span>'; ?>
                            </td>
                            <td><a class="btn btn-sm btn-primary" href="user_detail.php?id=<?php echo $user['id']; ?>">Detail</a></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<!-- Recent Users End -->

<!-- Date Picker and Appointments Section Start -->
<div class="container-fluid pt-4 px-4">
    <div class="bg-light text-center rounded p-4">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <h6 class="mb-0">Appointments</h6>
        </div>
        <form method="GET" action="">
            <div class="input-group mb-3">
                <input type="date" class="form-control" name="selected_date" value="<?php echo isset($_GET['selected_date']) ? $_GET['selected_date'] : date('Y-m-d'); ?>">
                <button class="btn btn-primary" type="submit">View Appointments</button>
            </div>
        </form>
        <div class="table-responsive">
            <table class="table text-start align-middle table-bordered table-hover mb-0">
                <thead>
                    <tr class="text-dark">
                        <th scope="col">Appointment ID</th>
                        <th scope="col">User ID</th>
                        <th scope="col">Service</th>
                        <th scope="col">Date</th>
                        <th scope="col">Time</th>
                        <th scope="col">Guests</th>
                        <th scope="col">Booking Status</th>
                        <th scope="col">Payment_Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Database connection
                    $db = mysqli_connect('localhost', 'root', '', 'userform');

                    // Get selected date or default to today
                    $selected_date = isset($_GET['selected_date']) ? $_GET['selected_date'] : date('Y-m-d');

                    // Fetch appointments for the selected date, including guest names
                    $appointment_query = "SELECT a.appointment_id, a.email, a.service_name, a.appointment_date, a.selected_time, a.booking_status, a.payment_status,
                                                 GROUP_CONCAT(CONCAT(g.first_name, ' ', g.last_name) SEPARATOR ', ') AS guests
                                          FROM appointments a
                                          LEFT JOIN guests g ON a.appointment_id = g.appointment_id
                                          WHERE a.appointment_date = '$selected_date'
                                          GROUP BY a.appointment_id
                                          ORDER BY a.selected_time ASC";
                    $appointment_result = mysqli_query($db, $appointment_query);

                    // Display appointments
                    if (mysqli_num_rows($appointment_result) > 0) {
                        while ($appointment = mysqli_fetch_assoc($appointment_result)) {
                            echo "<tr>
                                <td>" . htmlspecialchars($appointment['appointment_id']) . "</td>
                                <td>" . htmlspecialchars($appointment['email']) . "</td>
                                <td>" . htmlspecialchars($appointment['service_name']) . "</td>
                                <td>" . htmlspecialchars($appointment['appointment_date']) . "</td>
                                <td>" . htmlspecialchars($appointment['selected_time']) . "</td>
                                <td>" . htmlspecialchars($appointment['guests'] ? $appointment['guests'] : 'No guests') . "</td>
                                <td>" . htmlspecialchars($appointment['booking_status']) . "</td>
                                <td>" . htmlspecialchars($appointment['payment_status']) . "</td>
                            </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='6' class='text-center'>No appointments found for this date.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<!-- Date Picker and Appointments Section End -->





            <!-- Widgets Start -->
            
            <!-- Widgets End -->


            <!-- Footer Start -->
            <div class="container-fluid pt-4 px-4">
                <div class="bg-light rounded-top p-4">
                    <div class="row">
                        <div class="col-12 col-sm-6 text-center text-sm-start">
                            &copy; <a href="#">Ayursoul</a>, All Right Reserved. 
                        </div>
                        <div class="col-12 col-sm-6 text-center text-sm-end">
                            <!--/*** This template is free as long as you keep the footer author’s credit link/attribution link/backlink. If you'd like to use the template without the footer author’s credit link/attribution link/backlink, you can purchase the Credit Removal License from "https://htmlcodex.com/credit-removal". Thank you for your support. ***/-->
                            Designed By <a href="#">Ayursoul</a>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Footer End -->
        </div>
        <!-- Content End -->


        <!-- Back to Top -->
        <a href="#" class="btn btn-lg btn-primary btn-lg-square back-to-top"><i class="bi bi-arrow-up"></i></a>
    </div>

    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="lib/chart/chart.min.js"></script>
    <script src="lib/easing/easing.min.js"></script>
    <script src="lib/waypoints/waypoints.min.js"></script>
    <script src="lib/owlcarousel/owl.carousel.min.js"></script>
    <script src="lib/tempusdominus/js/moment.min.js"></script>
    <script src="lib/tempusdominus/js/moment-timezone.min.js"></script>
    <script src="lib/tempusdominus/js/tempusdominus-bootstrap-4.min.js"></script>

    <!-- Template Javascript -->
    <script src="js/main.js"></script>
</body>

</html>