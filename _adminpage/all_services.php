<?php
// Include the database configuration
require 'config.php';

// Alert message variable
$alertMessage = '';

// Check if form data is submitted via POST for adding a new service
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_service'])) {
    try {
        $category = $_POST['category'];
        $name = $_POST['name'];
        $price = floatval($_POST['price']); // Ensure price is treated as a float

        $sql = "INSERT INTO services (category, name, price) VALUES (:category, :name, :price)";
        $stmt = $conn->prepare($sql);
        $stmt->execute([
            ':category' => $category,
            ':name' => $name,
            ':price' => $price
        ]);

        $alertMessage = "Service added successfully!";
    } catch (PDOException $e) {
        $alertMessage = "Failed to add service: " . $e->getMessage();
    }
}

// Database connection for other operations
$db = mysqli_connect('localhost', 'root', '', 'userform');

// Update Service
if (isset($_POST['update_service'])) {
    $service_id = intval($_POST['service_id']);
    $category = mysqli_real_escape_string($db, $_POST['category']);
    $name = mysqli_real_escape_string($db, $_POST['name']);
    $price = floatval($_POST['price']);

    $update_query = "UPDATE services SET category='$category', name='$name', price='$price' WHERE id=$service_id";
    if (mysqli_query($db, $update_query)) {
        $alertMessage = "Service updated successfully!";
    } else {
        $alertMessage = "Failed to update service!";
    }
}

// Delete Service
if (isset($_POST['delete_service'])) {
    $service_id = intval($_POST['service_id']);
    $delete_query = "DELETE FROM services WHERE id=$service_id";
    if (mysqli_query($db, $delete_query)) {
        $alertMessage = "Service deleted successfully!";
    } else {
        $alertMessage = "Failed to delete service!";
    }
}

// Fetch all services
$services_query = "SELECT * FROM services";
$search_query = '';
if (isset($_GET['search'])) {
    $search_query = mysqli_real_escape_string($db, trim($_GET['search']));
    $services_query = "SELECT * FROM services WHERE category LIKE '%$search_query%' OR name LIKE '%$search_query%'";
}
$services_result = mysqli_query($db, $services_query);
?>

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
                <a href="index.php" class="navbar-brand d-flex d-lg-none me-4">
                    <h2 class="text-primary mb-0"><i class="fa fa-hashtag"></i></h2>
                </a>
                <a href="#" class="sidebar-toggler flex-shrink-0">
                    <i class="fa fa-bars"></i>
                </a>
                
                 <!-- Search Form with Flexible Width -->
<form method="GET" action="all_services.php" class="d-none d-md-flex ms-4" style="width: 100%;">
    <input type="text" name="search" class="form-control border-0 flex-grow-1" placeholder="Search by category or name" value="<?php echo htmlspecialchars($search_query); ?>">
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
            <!-- Navbar End -->

           <!-- Display alert message -->
            <?php if (!empty($alertMessage)) : ?>
    <div class="alert alert-<?php echo (strpos($alertMessage, 'successfully') !== false) ? 'success' : 'danger'; ?> alert-dismissible fade show" role="alert">
        <?php echo $alertMessage; ?>
    </div>
<?php endif; ?>

<div class="container-fluid pt-4 px-4">
    <!-- Add Service Form -->
    <div class="bg-light rounded p-4 mb-4">
        <h4>Add New Service</h4>
        <form method="POST" action="all_services.php">
            <input type="hidden" name="add_service" value="1">
            <div class="form-group">
                <label for="category">Service Category:</label>
                <input type="text" name="category" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="name">Service Name:</label>
                <input type="text" name="name" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="price">Price (₹):</label>
                <input type="number" step="0.01" name="price" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary mt-3">Add Service</button>
        </form>
    </div>

   
    

   <!-- Service List -->
<div class="bg-light rounded p-4">
    <h4>All Services</h4>
    <div class="table-responsive">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Service ID</th>
                    <th>Service Category</th>
                    <th>Service Name</th>
                    <th>Price</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Array to hold services grouped by category
                $servicesByCategory = [];

                // Fetch services and group them by category
                while ($service = mysqli_fetch_assoc($services_result)) {
                    $servicesByCategory[$service['category']][] = $service;
                }

                // Sort categories alphabetically
                ksort($servicesByCategory);

                // Loop through each category and its services
                foreach ($servicesByCategory as $category => $services) {
                    foreach ($services as $service) {
                        echo '<tr>';
                        echo '<td>' . htmlspecialchars($service['id']) . '</td>';
                        echo '<td>' . htmlspecialchars($category) . '</td>';
                        echo '<td>' . htmlspecialchars($service['name']) . '</td>';
                        echo '<td>' . htmlspecialchars(number_format($service['price'], 2)) . '</td>';
                        echo '<td>
                                <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editServiceModal' . $service['id'] . '">Edit</button>
                                <form action="" method="POST" style="display:inline-block;">
                                    <input type="hidden" name="service_id" value="' . $service['id'] . '">
                                    <button type="submit" name="delete_service" class="btn btn-danger btn-sm">Delete</button>
                                </form>
                              </td>';
                        echo '</tr>';

                        // Edit Service Modal
                        echo '<div class="modal fade" id="editServiceModal' . $service['id'] . '" tabindex="-1" aria-labelledby="editServiceModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="editServiceModalLabel">Edit Service</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <form action="" method="POST">
                                                <input type="hidden" name="update_service" value="1">
                                                <input type="hidden" name="service_id" value="' . $service['id'] . '">
                                                <div class="mb-3">
                                                    <label for="category" class="form-label">Category</label>
                                                    <input type="text" class="form-control" name="category" value="' . htmlspecialchars($service['category']) . '" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="name" class="form-label">Service Name</label>
                                                    <input type="text" class="form-control" name="name" value="' . htmlspecialchars($service['name']) . '" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="price" class="form-label">Price</label>
                                                    <input type="number" class="form-control" name="price" step="0.01" value="' . htmlspecialchars(number_format($service['price'], 2)) . '" required>
                                                </div>
                                                <button type="submit" class="btn btn-primary">Update Service</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                              </div>';
                    }
                }
                ?>
            </tbody>
        </table>
    </div>
</div>




            <!-- Button Start -->
            
            <!-- Button End -->


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