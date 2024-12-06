
<?php
require 'config.php';  // Include the database configuration

// Assuming you have an appointment ID from a previous process or page
$appointment_id = isset($_GET['appointment_id']) ? $_GET['appointment_id'] : '';

if ($appointment_id) {
    // Fetch appointment details from the 'appointments' table
    $appointment_sql = "SELECT * FROM appointments WHERE appointment_id = :appointment_id";
    $appointment_stmt = $conn->prepare($appointment_sql);
    $appointment_stmt->execute([':appointment_id' => $appointment_id]);
    $appointment = $appointment_stmt->fetch(PDO::FETCH_ASSOC);

    // Fetch guest details from the 'guests' table
    $guests_sql = "SELECT * FROM guests WHERE appointment_id = :appointment_id";
    $guests_stmt = $conn->prepare($guests_sql);
    $guests_stmt->execute([':appointment_id' => $appointment_id]);
    $guests = $guests_stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Check if appointment data is found
    if ($appointment) {
        $service_name = $appointment['service_name'];
        $service_price = $appointment['service_price'];
        $appointment_date = $appointment['appointment_date'];
        $selected_time = $appointment['selected_time'];
        $customerEmail = $appointment['email'];
    } else {
        echo "No appointment found.";
        exit;
    }
} else {
    echo "No appointment ID provided.";
    exit;
}

// Handle appointment cancellation
if (isset($_POST['cancel_appointment'])) {
  $cancel_sql = "UPDATE appointments SET booking_status = 'Cancelled', payment_status = 'Cancelled' WHERE appointment_id = :appointment_id";
  $cancel_stmt = $conn->prepare($cancel_sql);
  $cancel_stmt->execute([':appointment_id' => $appointment_id]);

  // Redirect to the appointment page after cancellation
  header("Location: http://localhost/miniproject/appointment.php");
  exit;
}
?>

<!DOCTYPE html>
<html>
  <head>
    <title>Ayursoul Payment Page</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    
  </head>
  <body>
    <br>
    <br>
    <div class="container fluid">
      <h1 align="center">Ayursoul Payment Page</h1>
      <div class="alert alert-info">
        <strong>Appointment Details:</strong>
      </div>


      <!-- Now process the payment via Cashfree with the fetched data -->
      <form id="redirectForm" method="post" action="request.php">

      <div class="form-group">
          
          <input type="hidden" class="form-control" name="appId" value="TEST102438457b6273a54672061a5c0d54834201"/>
        </div>
  
      <div class="form-group">
          <label>Order ID:</label><br>
          <input class="form-control" name="orderId" value="<?php echo $appointment_id ; ?>" readonly/>
        </div>

        <!-- Display appointment and guest details -->
      <div class="form-group">
        <label>Service Name:</label><br>
        <input class="form-control" type="text" name="sname" value="<?php echo $service_name; ?>" readonly />
      </div>
      <div class="form-group">
        <label>Service Price:</label><br>
        <input class="form-control" type="text" name="orderAmount" value="<?php echo $service_price; ?>" readonly />
      </div>
      <div class="form-group">
        <label>Appointment Date:</label><br>
        <input class="form-control" type="text" name="sdate" value="<?php echo $appointment_date; ?>" readonly />
      </div>
      <div class="form-group">
        <label>Selected Time:</label><br>
        <input class="form-control" type="text" name="stime" value="<?php echo $selected_time; ?>" readonly />
      </div>

      <!-- Loop through guests and display their details -->
      <?php foreach ($guests as $guest): ?>
        <div class="form-group">
          <label>Guest Name:</label><br>
          <input class="form-control" type="text" name="customerName" value="<?php echo $guest['first_name'] . ' ' . $guest['last_name']; ?>" readonly />
        </div>
       
      <?php endforeach; ?>

      <div class="form-group">
          <label>Phone:</label><br>
          <input class="form-control" name="customerPhone" placeholder="Enter your phone number here (Ex. 9999999999)"/>
        </div>
        <div class="form-group">
          <label>Email:</label><br>
          <input class="form-control" name="customerEmail" value="<?php echo $customerEmail; ?>" readonly/>
        </div>
        <div class="form-group">
          
          <input type="hidden" class="form-control" name="orderCurrency" value="INR" placeholder="Enter Currency here (Ex. INR)"/>
        </div>


        <div class="form-group">
          
          <input type="hidden" class="form-control" name="returnUrl" value="http://localhost/miniproject/gateway/checkout/response.php"/>
        </div>        
        <div class="form-group">
          
          <input type="hidden" class="form-control" name="notifyUrl" placeholder="Enter the URL to get server notificaitons (Ex. www.example.com)"/>
        </div>
        
        <button type="submit" class="btn btn-primary btn-block" value="Pay">Continue to payment</button>
      </form>
      <!-- Cancel Appointment Form -->
      <form method="post" action="">
        <button type="submit" name="cancel_appointment" class="btn btn-danger btn-block">Cancel Appointment</button>
      </form>
    </div>
  </body>
</html>
