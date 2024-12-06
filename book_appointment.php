<?php
require 'config.php';  // Include the database configuration

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Process the form data
   // $paymentWay = $_POST['payment_way'];     // Selected payment method
    $appointment_date = $_POST['appointment_date'];
    $selected_time = $_POST['selected_time'];

    // Assuming the first guest's email is the primary email for the appointment
    $primary_email = !empty($_POST['email'][0]) ? $_POST['email'][0] : '';

    // Check if a service is selected and get its ID
    if (isset($_POST['service_id']) && !empty($_POST['service_id'])) {
        $service_id = $_POST['service_id'];  // Service ID

        // Retrieve service details from the database using the selected service ID
        $service_sql = "SELECT name, price FROM services WHERE id = :service_id";
        $service_stmt = $conn->prepare($service_sql);
        $service_stmt->execute([':service_id' => $service_id]);
        $service = $service_stmt->fetch(PDO::FETCH_ASSOC);

        if ($service) {
            $service_name = $service['name'];
            $service_price = $service['price'];
        } else {
            // If no service is found with the selected ID
            $service_name = '';
            $service_price = '';
        }
    } else {
        // Handle case where no service is selected
        $service_name = '';
        $service_price = '';
    }

    // Booking and Payment details
    $booking_status = 'pending';   // Default is confirmed
    $payment_status = 'pending';     // Default is pending

    // Insert Appointment Data into Database
    try {
        // Insert into 'appointments' table, including email
        $sql = "INSERT INTO appointments (appointment_date, selected_time, service_name, service_price, booking_status, payment_status, email)
                VALUES (:appointment_date, :selected_time, :service_name, :service_price, :booking_status, :payment_status, :email)";
        $stmt = $conn->prepare($sql);
        $stmt->execute([
            ':appointment_date' => $appointment_date,
            ':selected_time' => $selected_time,
            ':service_name' => $service_name,
            ':service_price' => $service_price,
            ':booking_status' => $booking_status,
            
            ':payment_status' => $payment_status,
            ':email' => $primary_email  // Insert the primary guest's email
        ]);

        // Get the last inserted appointment ID
        $appointment_id = $conn->lastInsertId();

        // Insert Guests Data (if any)
        if (!empty($_POST['first-name']) && is_array($_POST['first-name'])) {
            for ($i = 0; $i < count($_POST['first-name']); $i++) {
                $first_name = $_POST['first-name'][$i];
                $last_name = $_POST['last-name'][$i];
                $email = $_POST['email'][$i];
                $gender = $_POST['gender'][$i];

                // Insert each guest into the database
                $guest_sql = "INSERT INTO guests (appointment_id, first_name, last_name, email, gender)
                              VALUES (:appointment_id, :first_name, :last_name, :email, :gender)";
                $guest_stmt = $conn->prepare($guest_sql);
                $guest_stmt->execute([
                    ':appointment_id' => $appointment_id,
                    ':first_name' => $first_name,
                    ':last_name' => $last_name,
                    ':email' => $email,
                    ':gender' => $gender
                ]);
            }
        }

        // Redirect to confirmation page or display success message
        header("Location: gateway\checkout\start.php?appointment_id=" . $appointment_id);  // Redirect after successful booking
        exit();

    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>
