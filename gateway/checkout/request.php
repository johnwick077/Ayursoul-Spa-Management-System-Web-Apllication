<!DOCTYPE html>
<html>
<head>
  <title>Ayursoul - Signature Generator</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body onload="document.frm1.submit()">

<?php 
$mode = "TEST"; //<------------ Change to TEST for test server, PROD for production

require 'config.php'; // Include the database connection

// Assuming you are getting the appointment ID from a previous page or POST data
$appointment_id = isset($_POST['appointment_id']) ? $_POST['appointment_id'] : '';

// Fetch guest details from the database
try {
    $guest_sql = "SELECT first_name, last_name FROM guests WHERE appointment_id = :appointment_id";
    $guest_stmt = $conn->prepare($guest_sql);
    $guest_stmt->execute([':appointment_id' => $appointment_id]);
    $guests = $guest_stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Check if there are guests and get the first one
    if (count($guests) > 0) {
        $customerName = $guests[0]['first_name'] . ' ' . $guests[0]['last_name'];
    } else {
        // Default guest name if none found
        $customerName = 'Default Guest';
    }

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
    exit();
}

extract($_POST);
$secretKey = "cfsk_ma_test_b19470cdf16f564487a9663e3923046b_6eb33929"; // Replace with your secret key
$postData = array( 
  "appId" => $appId, 
  "orderId" => $orderId, 
  "orderAmount" => $orderAmount, 
  "sname" => $sname, 
  "sdate" => $sdate, 
  "customerName" => $customerName, // Using fetched guest name
  "stime" => $stime,
  "orderCurrency" => $orderCurrency, 
  "customerPhone" => $customerPhone,
  "customerEmail" => $customerEmail,
  "returnUrl" => $returnUrl, 
  "notifyUrl" => $notifyUrl,
);

ksort($postData);
$signatureData = "";
foreach ($postData as $key => $value){
    $signatureData .= $key . $value;
}
$signature = hash_hmac('sha256', $signatureData, $secretKey, true);
$signature = base64_encode($signature);

if ($mode == "PROD") {
  $url = "https://www.cashfree.com/checkout/post/submit";
} else {
  $url = "https://test.cashfree.com/billpay/checkout/post/submit";
}

?>

<!-- Cashfree Payment Form -->
<form action="<?php echo $url; ?>" name="frm1" method="post">
    <p>Please wait.......</p>
    <input type="hidden" name="signature" value='<?php echo $signature; ?>'/>
    <input type="hidden" name="sname" value='<?php echo $sname; ?>'/>
    <input type="hidden" name="sdate" value='<?php echo $sdate; ?>'/>
    <input type="hidden" name="customerName" value='<?php echo $customerName; ?>'/>
    <input type="hidden" name="orderAmount" value='<?php echo $orderAmount; ?>'/>
    <input type="hidden" name="customerPhone" value='<?php echo $customerPhone; ?>'/>
    <input type="hidden" name="customerEmail" value='<?php echo $customerEmail; ?>'/>
    <input type="hidden" name="stime" value='<?php echo $stime; ?>'/>
    <input type="hidden" name="appId" value='<?php echo $appId; ?>'/>
    <input type="hidden" name="orderCurrency" value='<?php echo $orderCurrency; ?>'/>
    <input type ="hidden" name="notifyUrl" value='<?php echo $notifyUrl; ?>'/>
      <input type ="hidden" name="returnUrl" value='<?php echo $returnUrl; ?>'/>
    <input type="hidden" name="orderId" value='<?php echo $orderId; ?>'/>
</form>

</body>
</html>
