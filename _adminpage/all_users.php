<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>DASHMIN - Admin</title>
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
                <a href="index.html" class="navbar-brand mx-4 mb-3">
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
                <a href="index.php" class="navbar-brand d-flex d-lg-none me-4">
                    <h2 class="text-primary mb-0"><i class="fa fa-hashtag"></i></h2>
                </a>
                <a href="#" class="sidebar-toggler flex-shrink-0">
                    <i class="fa fa-bars"></i>
                </a>
                
                <!-- Search Form -->
        <form method="GET" action="" class="d-none d-md-flex ms-4" style="width: 100%;">
            <input type="text" name="search" class="form-control border-0 flex-grow-1" placeholder="Search by name or email" value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
            <button type="submit" class="btn btn-primary ms-2">Search</button>
        </form>
                
                    
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

<?php


// Database connection
$db = mysqli_connect('localhost', 'root', '', 'userform');

// Pagination setup
$results_per_page = 20;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$start_from = ($page - 1) * $results_per_page;

// Fetch all users with pagination
$query = "SELECT id, fname AS first_name, lname AS last_name, email, email_verified FROM users ORDER BY created_at DESC LIMIT $start_from, $results_per_page";
$result = mysqli_query($db, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>All Users - Admin Dashboard</title>
    <link href="css/style.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center">All Users</h2>

        

        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>User ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Verification</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Database connection
                    $db = mysqli_connect('localhost', 'root', '', 'userform');

                    // Pagination setup
                    $results_per_page = 20;
                    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
                    $start_from = ($page - 1) * $results_per_page;

                    // Search query
                    $search = isset($_GET['search']) ? mysqli_real_escape_string($db, $_GET['search']) : '';

                    // Fetch users with pagination and search filter
                    $query = "SELECT id, fname AS first_name, lname AS last_name, email, email_verified 
                              FROM users 
                              WHERE (fname LIKE '%$search%' OR lname LIKE '%$search%' OR email LIKE '%$search%')
                              ORDER BY created_at DESC 
                              LIMIT $start_from, $results_per_page";
                    $result = mysqli_query($db, $query);

                    // Display user data
                    while ($user = mysqli_fetch_assoc($result)) : ?>
                        <tr>
                            <td><?php echo htmlspecialchars($user['id']); ?></td>
                            <td><?php echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?></td>
                            <td><?php echo htmlspecialchars($user['email']); ?></td>
                            <td><?php echo $user['email_verified'] ? '<span class="badge bg-success">Verified</span>' : '<span class="badge bg-danger">Not Verified</span>'; ?></td>
                            <td><a class="btn btn-sm btn-primary" href="user_detail.php?id=<?php echo $user['id']; ?>">Detail</a></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>

        <!-- Pagination Links -->
        <nav aria-label="Page navigation">
            <ul class="pagination justify-content-center">
                <?php
                // Get total user count for pagination
                $total_users_query = "SELECT COUNT(id) AS total 
                                      FROM users 
                                      WHERE (fname LIKE '%$search%' OR lname LIKE '%$search%' OR email LIKE '%$search%')";
                $total_users_result = mysqli_query($db, $total_users_query);
                $total_users = mysqli_fetch_assoc($total_users_result)['total'];
                $total_pages = ceil($total_users / $results_per_page);

                for ($i = 1; $i <= $total_pages; $i++) {
                    echo "<li class='page-item" . ($i == $page ? ' active' : '') . "'>
                            <a class='page-link' href='all_users.php?page=$i&search=" . urlencode($search) . "'>$i</a>
                          </li>";
                }
                ?>
            </ul>
        </nav>
    </div>


<!-- Footer Start -->

        <!-- Content End -->


        <!-- Back to Top -->
       

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
