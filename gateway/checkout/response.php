<!DOCTYPE html>
<html>
<head>
    <title>Ayursoul Payment Confirmation Details</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <style>
        a, a:visited, a:hover, a:focus, a:active {
            color: inherit;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <h1 align="center">Ayursoul Payment Confirmation Details</h1>    

    <?php  
    require 'config.php'; // Include the database configuration

    $secretkey = "cfsk_ma_test_*****************************************";
    $orderId = $_POST["orderId"];
    $orderAmount = $_POST["orderAmount"];
    $referenceId = $_POST["referenceId"];
    $txStatus = $_POST["txStatus"];
    $paymentMode = $_POST["paymentMode"];
    $txMsg = $_POST["txMsg"];
    $txTime = $_POST["txTime"];
    $signature = $_POST["signature"];
    $data = $orderId . $orderAmount . $referenceId . $txStatus . $paymentMode . $txMsg . $txTime;
    $hash_hmac = hash_hmac('sha256', $data, $secretkey, true);
    $computedSignature = base64_encode($hash_hmac);

    // Assuming you have an appointment_id corresponding to the orderId
    // Make sure to adjust this query as per your database structure
    $appointment_sql = "SELECT appointment_id FROM appointments WHERE appointment_id = :orderId";
    $appointment_stmt = $conn->prepare($appointment_sql);
    $appointment_stmt->execute([':orderId' => $orderId]);
    $appointment = $appointment_stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($signature == $computedSignature) {
        // Payment is successful
        if ($txStatus == "SUCCESS") {
            // Update booking status and payment status to "Confirmed"
            $update_sql = "UPDATE appointments SET booking_status = 'Confirmed', payment_status = 'Success' WHERE appointment_id = :orderId";
            $update_stmt = $conn->prepare($update_sql);
            $update_stmt->execute([':orderId' => $orderId]);
    ?>
    <div class="container"> 
        <div class="panel panel-success">
            <div class="panel-heading">Signature Verification Successful</div>
            <div class="panel-body">
                <table class="table table-hover">
                    <tbody>
                        <tr>
                            <td>Order ID</td>
                            <td><?php echo $orderId; ?></td>
                        </tr>
                        <tr>
                            <td>Order Amount</td>
                            <td><?php echo $orderAmount; ?></td>
                        </tr>
                        <tr>
                            <td>Reference ID</td>
                            <td><?php echo $referenceId; ?></td>
                        </tr>
                        <tr>
                            <td>Transaction Status</td>
                            <td><?php echo $txStatus; ?></td>
                        </tr>
                        <tr>
                            <td>Payment Mode</td>
                            <td><?php echo $paymentMode; ?></td>
                        </tr>
                        <tr>
                            <td>Transaction Time</td>
                            <td><?php echo $txTime; ?></td>
                        </tr>
                    </tbody>
                </table>
                <button type="button" class="btn btn-primary btn-block"><a href="http://localhost/miniproject/profile.php">Go to history</a></button>
            </div>
        </div>
    </div>
    <?php   
        } else {
            // Payment failed, update statuses accordingly
            $update_sql = "UPDATE appointments SET booking_status = 'Failed', payment_status = 'Failed' WHERE appointment_id = :orderId";
            $update_stmt = $conn->prepare($update_sql);
            $update_stmt->execute([':orderId' => $orderId]);
    ?>
    <div class="container"> 
        <div class="panel panel-danger">
            <div class="panel-heading">Signature Verification Successful but Payment Failed</div>
            <div class="panel-body">
                <table class="table table-hover">
                    <tbody>
                        <tr>
                            <td>Order ID</td>
                            <td><?php echo $orderId; ?></td>
                        </tr>
                        <tr>
                            <td>Order Amount</td>
                            <td><?php echo $orderAmount; ?></td>
                        </tr>
                        <tr>
                            <td>Reference ID</td>
                            <td><?php echo $referenceId; ?></td>
                        </tr>
                        <tr>
                            <td>Transaction Status</td>
                            <td><?php echo $txStatus; ?></td>
                        </tr>
                        <tr>
                            <td>Payment Mode</td>
                            <td><?php echo $paymentMode; ?></td>
                        </tr>
                        <tr>
                            <td>Transaction Time</td>
                            <td><?php echo $txTime; ?></td>
                        </tr>
                    </tbody>
                </table>
                <button type="button" class="btn btn-primary btn-block"><a href="http://localhost/miniproject/index.php">Go to home</a></button>
            </div>	
        </div>	
    </div>
    <?php	
        }
    } else {
    ?>
    <div class="container"> 
        <div class="panel panel-danger">
            <div class="panel-heading">Signature Verification Failed</div>
            <div class="panel-body">
                <table class="table table-hover">
                    <tbody>
                        <tr>
                            <td>Order ID</td>
                            <td><?php echo $orderId; ?></td>
                        </tr>
                        <tr>
                            <td>Order Amount</td>
                            <td><?php echo $orderAmount; ?></td>
                        </tr>
                        <tr>
                            <td>Reference ID</td>
                            <td><?php echo $referenceId; ?></td>
                        </tr>
                        <tr>
                            <td>Transaction Status</td>
                            <td><?php echo $txStatus; ?></td>
                        </tr>
                        <tr>
                            <td>Payment Mode</td>
                            <td><?php echo $paymentMode; ?></td>
                        </tr>
                        <tr>
                            <td>Transaction Time</td>
                            <td><?php echo $txTime; ?></td>
                        </tr>
                    </tbody>
                </table>
                <button type="button" class="btn btn-primary btn-block"><a href="http://localhost/miniproject/index.php">Go to home</a></button>
            </div>	
        </div>	
    </div>
    <?php	
    }
    ?>

</body>
</html>
