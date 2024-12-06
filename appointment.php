<?php
    session_start();  // Start the session to check if the user is logged in
    
    // Check if the user is logged in
    $is_logged_in = isset($_SESSION['email']);
    $email = $is_logged_in ? $_SESSION['email'] : null;  // Fetch email if logged in
    ?>
    <?php
// Database connection
$host = 'localhost';
$dbname = 'userform';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Fetch all services grouped by category
    $stmt = $pdo->query("SELECT * FROM services ORDER BY category");
    $services = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Ayursoul</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="Free HTML Templates" name="keywords">
    <meta content="Free HTML Templates" name="description">

    <!-- Favicon -->
    <link href="img/favicon.ico" rel="icon">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">

    <!-- Libraries Stylesheet -->
    <link href="lib/animate/animate.min.css" rel="stylesheet">
    <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
    <link href="lib/tempusdominus/css/tempusdominus-bootstrap-4.min.css" rel="stylesheet" />

    <!-- Customized Bootstrap Stylesheet -->
    <link href="css/style.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&amp;display=swap" rel="stylesheet"/>
        <style>
          body {
            background: #F9A392;
        }

        .container {
            display: flex;
            flex-direction: column;
            align-items: center;
            width: 100%;
            padding: 2%;
        }

        .left-panel {
            width: 100%;
            padding: 20px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        

        .appointment {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
            justify-content: flex-start;
        }

        .appointment p {
            width: auto;
            padding: 10px;
            margin-right: 10px;
            font-size: 16px;
        }

        .appointment select {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        .service-search {
            margin-bottom: 20px;
            width: 100%;
        }

        .service-search input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        .service-categories {
            display: flex;
            flex-wrap: wrap;
            margin-bottom: 20px;
            justify-content: center;
        }

        .service-categories div {
            flex: 1 1 20%;
            padding: 15px;
            text-align: center;
            border: 1px solid #ccc;
            border-radius: 10px;
            margin: 10px;
            cursor: pointer;
            background-color: #fff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .service-categories div.highlighted {
    background-color: #f8b6a9;
    border-color: #f58a7b;
    color: white;
}


        .service-item {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            align-items: center;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 8px;
            background-color: #fff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 100%;
        }

        .service-item h3 {
            margin: 0;
            font-size: 16px;
            
        }

        .service-item p {
            margin: 0;
            font-size: 14px;
            color: #666;
        }

        .service-item .price {
            font-size: 14px;
            color: #333;
            
        }

        .service-item .radio {
            margin-left: 10px;
        }

        .footer {
            text-align: center;
            padding: 20px 0;
            margin-top: 20px;
        }

        .footer button {
            background-color: #F9A392;
            color: #fff;
            border: none;
            border-radius: 7px;
            padding: 15px 30px;
            cursor: pointer;
            font-family: 'Roboto', sans-serif;
            font-size: 16px;
        }

        .footer button:hover {
            background-color: #f8b6a9;
        }
        .guest-details {
            margin-bottom: 20px;
        }

        .guest-details input {
            margin-right: 10px;
            padding: 8px;
            width: calc(25% - 10px);
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        .guest-details select {
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
            margin-right: 10px;
        }

        .guest-controls {
            margin-bottom: 20px;
        }

        .guest-controls button {
            background-color: #f8b6a9;
            color: #fff;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
            font-family: 'Roboto', sans-serif;
            font-size: 14px;
            border-radius: 7px;
        }

        .guest-controls button:hover {
            background-color: #f0a08f;
        }

        .appointment-date-time {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.date-picker label, .available-slots label {
    font-weight: bold;
    margin-bottom: 10px;
    font-size: 16px;
}

        
        .content {
            padding: 10px;
            max-height: 400px;
            overflow-y: auto;
        }
        .section {
            margin-bottom: 20px;
        }
        .section h2 {
            font-size: 16px;
            margin: 10px 0;
            display: flex;
            align-items: center;
        }
        .section h2 i {
            margin-right: 5px;
        }
        .time-slot {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }
        .time-slot button {
            flex: 1 1 30%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            background-color: white;
            cursor: pointer;
            font-size: 14px;
        }

        .category-buttons {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }
        .category-buttons button {
            flex: 1 1 30%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            
            cursor: pointer;
            font-size: 14px;
        }

        .active-category {
    background-color: #F9A392;
    color: white;
}
       

    /* Hover effect on time slot buttons */
    .time-slot button:hover {
        background-color: #e0e0e0;
        border-color: #F9A392;
    }

    /* Styling for the selected time slot */
    .time-slot button.selected {
        background-color: #F9A392;
        color: white;
        border-color: #F9A392;
    }

    /* Optionally, add an active focus style when the button is clicked */
    .time-slot button:focus {
        outline: none;
    }
        </style>
    

</head>

<body>

    <!-- Navbar Start -->
    <div class="container-fluid p-0">
        <nav class="navbar navbar-expand-lg bg-white navbar-light py-3 py-lg-0 px-lg-5">
            <a href="index.php" class="navbar-brand ml-lg-3">
                <h1 class="m-0 text-primary"><span class="text-dark">AYUR</span>SOUL</h1>
            </a>
            <button type="button" class="navbar-toggler" data-toggle="collapse" data-target="#navbarCollapse">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-between px-lg-3" id="navbarCollapse" >
                <div class="navbar-nav py-0">
                    <a href="index.php" class="nav-item nav-link">Home</a>
                    <a href="about.html" class="nav-item nav-link">About</a>
                    <a href="service.html" class="nav-item nav-link">Services</a>
                    <a href="contact.html" class="nav-item nav-link">Contact</a>
                </div>
                

            </div>
        </nav>
    </div>
    <!-- Navbar End -->


    <!-- Header Start -->
    
    <!-- Header End -->
 <!-- Appointment Start -->
<div class="container">
    <div class="left-panel">
        <form method="POST" action="book_appointment.php" onsubmit="return validateSelection()">
            <!-- Guest details section -->
            <h3>Guest Information</h3>
            <div id="guest-controls" class="guest-controls">
                <button type="button" onclick="addGuest()">Add Guest</button>
                <button type="button" onclick="removeGuest()">Remove Guest</button>
            </div>

            <!-- Guest fields will appear here -->
            <div id="guest-container"></div>

            <!-- Appointment Date and Time -->
            <div class="appointment-datetime">
                <p>Select Appointment Date:</p>
                <input type="date" name="appointment_date" required>
            </div>

            <!-- Time Slot Selection -->
            <div class="content">
                <div class="section">
                    <h2><i class="fas fa-cloud"></i> Morning</h2>
                    <div class="time-slot" id="morning-slots">
                        <button type="button" onclick="selectTimeSlot(this, '9:30 AM')">9:30 AM</button>
                        <button type="button" onclick="selectTimeSlot(this, '10:30 AM')">10:30 AM</button>
                        <button type="button" onclick="selectTimeSlot(this, '11:30 AM')">11:30 AM</button>
                    </div>
                </div>
                <div class="section">
                    <h2><i class="fas fa-sun"></i> Afternoon</h2>
                    <div class="time-slot" id="afternoon-slots">
                        <button type="button" onclick="selectTimeSlot(this, '1:30 PM')">1:30 PM</button>
                        <button type="button" onclick="selectTimeSlot(this, '2:30 PM')">2:30 PM</button>
                        <button type="button" onclick="selectTimeSlot(this, '3:30 PM')">3:30 PM</button>
                    </div>
                </div>
                <div class="section">
                    <h2><i class="fas fa-moon"></i> Evening</h2>
                    <div class="time-slot" id="evening-slots">
                        <button type="button" onclick="selectTimeSlot(this, '4:30 PM')">4:30 PM</button>
                        <button type="button" onclick="selectTimeSlot(this, '5:30 PM')">5:30 PM</button>
                        <button type="button" onclick="selectTimeSlot(this, '6:30 PM')">6:30 PM</button>
                    </div>
                </div>
            </div>

            <!-- Service Selection -->
            <h3>Select a service</h3>

            <!-- Category Buttons -->
            <div class="category-buttons">
                <?php
                $categories = []; // To hold unique categories
                $servicesByCategory = []; // To hold services grouped by categories

                // Group services by category
                foreach ($services as $service) {
                    $categories[$service['category']] = true; // Unique category
                    $servicesByCategory[$service['category']][] = $service; // Group services
                }

                // Sort categories alphabetically
                $categories = array_keys($categories);
                sort($categories);

                // Create buttons for each category
                foreach ($categories as $category) {
                    // Add an active class for the default "CUTTING" button if it's the first category
                    $buttonClass = ($category === '') ? 'active-category' : '';
                    echo '<button type="button" class="' . $buttonClass . '" onclick="showServices(\'' . htmlspecialchars($category) . '\')">' . htmlspecialchars($category) . '</button>';
                }
                ?>
            </div>

            <!-- Default: Display the first category services -->
            <div id="default-services" class="service-categories">
                <h4><?php echo htmlspecialchars($categories[0]); ?> Services</h4>
                <div class="service-category">
                    <?php
                    foreach ($servicesByCategory[$categories[0]] as $service) {
                        echo '<div class="service-item">
                                <label>
                                    <input type="radio" name="service_id" value="' . $service['id'] . '" onchange="updatePrice()">
                                    ' . htmlspecialchars($service['name']) . ' - ₹' . htmlspecialchars($service['price']) . '
                                </label>
                              </div>';
                    }
                    ?>
                </div>
            </div>

            <!-- Dynamic service display for other categories -->
            <div id="service-list-container">
                <?php
                foreach ($categories as $category) {
                    // Skip the first category since it is already displayed above
                    if ($category !== $categories[0]) {
                        echo '<div id="category-' . htmlspecialchars($category) . '" class="service-categories" style="display:none;">';
                        echo '<h4>' . htmlspecialchars($category) . ' Services</h4>';
                        echo '<div class="service-category">';

                        foreach ($servicesByCategory[$category] as $service) {
                            echo '<div class="service-item">
                                    <label>
                                        <input type="radio" name="service_id" value="' . $service['id'] . '" onchange="updatePrice()">
                                        ' . htmlspecialchars($service['name']) . ' - ₹' . htmlspecialchars($service['price']) . '
                                    </label>
                                  </div>';
                        }
                        echo '</div></div>'; // Close the category div
                    }
                }
                ?>
            </div>

            <div class="footer">
                <h4>Total Price: ₹<span id="total-price">0</span></h4>
                <button type="submit">Book Appointment</button>
            </div>
        </form>
    </div>
</div>
<!-- Appointment End -->

<!-- Back to Top -->
<a href="#" class="btn btn-lg btn-primary back-to-top"><i class="fa fa-angle-double-up"></i></a>

<!-- JavaScript Libraries -->
<script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.bundle.min.js"></script>
<script src="lib/easing/easing.min.js"></script>
<script src="lib/waypoints/waypoints.min.js"></script>
<script src="lib/counterup/counterup.min.js"></script>
<script src="lib/owlcarousel/owl.carousel.min.js"></script>
<script src="lib/tempusdominus/js/moment.min.js"></script>
<script src="lib/tempusdominus/js/moment-timezone.min.js"></script>
<script src="lib/tempusdominus/js/tempusdominus-bootstrap-4.min.js"></script>

<!-- Contact Javascript File -->
<script src="mail/jqBootstrapValidation.min.js"></script>
<script src="mail/contact.js"></script>

<!-- Template Javascript -->
<script src="js/main.js"></script>


  <!-- JavaScript -->
  <script>
// Show services based on selected category
function showServices(category) {
    // Hide all category sections
    document.querySelectorAll('.service-categories').forEach(function(element) {
        element.style.display = 'none';
    });

    // Display selected category's services or default to the first category's services
    if (category === '<?php echo htmlspecialchars($categories[0]); ?>') {
        document.getElementById('default-services').style.display = 'block';
    } else {
        document.getElementById('category-' + category).style.display = 'block';
    }

    // Update button to show the active category
    document.querySelectorAll('.category-buttons button').forEach(function(button) {
        button.classList.remove('active-category');
    });
    document.querySelector('button[onclick="showServices(\'' + category + '\')"]').classList.add('active-category');
}

// Show the first category services by default
document.addEventListener('DOMContentLoaded', () => {
    showServices('<?php echo htmlspecialchars($categories[0]); ?>'); // Display the first category by default
});


let guestCount = 1; // Start with 1 guest by default
let selectedTime = null;
let servicePrice = 0;
const isLoggedIn = <?php echo $is_logged_in ? 'true' : 'false'; ?>;
const loggedInEmail = "<?php echo $email; ?>";

function updateGuestFields() {
    const guestContainer = document.getElementById('guest-container');
    guestContainer.innerHTML = ''; // Clear current guest fields

    for (let i = 0; i < guestCount; i++) {
        guestContainer.innerHTML += `
            <div class="guest-details">
                <input type="text" name="first-name[]" placeholder="First Name" required />
                <input type="text" name="last-name[]" placeholder="Last Name" required />
                ${isLoggedIn ? `
                    <input type="email" name="email[]" value="${loggedInEmail}" readonly />
                ` : `
                    <input type="email" name="email[]" placeholder="Email" required />
                `}
                <select name="gender[]" required>
                    <option value="">Gender</option>
                    <option value="male">Male</option>
                    <option value="female">Female</option>
                    <option value="other">Other</option>
                </select>
            </div>
        `;
    }

    updatePrice(); // Update price when guests are added or removed
}

function addGuest() {
    if (guestCount < 6) {
        guestCount++;
        updateGuestFields();
    }
}

function removeGuest() {
    if (guestCount > 1) {
        guestCount--;
        updateGuestFields();
    }
}

document.addEventListener('DOMContentLoaded', () => {
    updateGuestFields();
});

function selectTimeSlot(button, time) {
    // Remove 'selected' class from all buttons
    document.querySelectorAll('.time-slot button').forEach(btn => btn.classList.remove('selected'));

    // Add 'selected' class to the clicked button
    button.classList.add('selected');
    selectedTime = time;
}

function updatePrice() {
    const serviceInputs = document.querySelectorAll('input[name="service_id"]:checked');
    if (serviceInputs.length > 0) {
        const serviceElement = serviceInputs[0].parentElement;
        const priceText = serviceElement.innerText.split(' - ₹')[1];
        servicePrice = parseInt(priceText.replace(/\s+/g, '')); // Parse the service price
    } else {
        servicePrice = 0; // No service selected
    }

    const totalPriceElement = document.getElementById('total-price');
    totalPriceElement.innerText = servicePrice * guestCount; // Update total price based on guest count
}
// Validate time selection and check login status before submitting the form
function validateSelection() {
    // Check if the user is logged in
    if (!isLoggedIn) {
        // Show the sign-in modal if not logged in
        document.getElementById('signInModal').style.display = 'flex';
        return false; // Prevent form submission
    }

    // Check if a time slot is selected
    if (!selectedTime) {
        alert("Please select a time slot.");
        return false;
    }

    // Append selected time as a hidden field to the form
    const form = document.querySelector('form');
    const hiddenTimeInput = document.createElement('input');
    hiddenTimeInput.type = 'hidden';
    hiddenTimeInput.name = 'selected_time';
    hiddenTimeInput.value = selectedTime;
    form.appendChild(hiddenTimeInput);

    return true; // Proceed with form submission
} 
</script>

<!-- Sign-In Modal -->
<div id="signInModal" class="modal">
    <div class="modal-content">
        <h3>You need to sign in to continue</h3>
        <p>Please sign in to book your appointment.</p>
        <button onclick="window.location.href='signin.php'">Go to Sign In Page</button>
        <button onclick="closeModal()">Cancel</button>
    </div>
</div>

<!-- Modal CSS -->
<style>
    .modal {
        display: none; /* Hidden by default */
        position: fixed; 
        z-index: 1; 
        left: 0;
        top: 0;
        width: 100%; 
        height: 100%;
        background-color: rgba(0,0,0,0.5); 
        justify-content: center;
        align-items: center;
    }

    .modal-content {
        background-color: #fff;
        padding: 20px;
        border-radius: 5px;
        width: 300px;
        text-align: center;
    }

    .modal-content h3 {
        margin-bottom: 15px;
    }

    .modal-content button {
        padding: 10px 20px;
        margin-top: 10px;
    }

    .modal-content button:first-of-type {
        background-color: #007bff;
        color: white;
        border: none;
    }

    .modal-content button:last-of-type {
        background-color: #6c757d;
        color: white;
        border: none;
    }
</style>

<!-- Close Modal Script -->
<script>
function closeModal() {
    document.getElementById('signInModal').style.display = 'none';
}
</script>


</body>

</html>

