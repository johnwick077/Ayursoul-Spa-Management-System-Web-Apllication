<?php
// fetch_services.php
$mysqli = new mysqli("localhost", "root", "", "userform");

if (isset($_POST['service_category'])) {
    $category = $_POST['service_category'];
    $query = "SELECT * FROM services WHERE category = ?";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("s", $category);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($service = $result->fetch_assoc()) {
        echo '<div class="service-item">';
        echo '<div><h3>' . $service['service_name'] . '</h3></div>';
        echo '<div class="price">$' . $service['price'] . '</div>';
        echo '<div class="radio"><input type="radio" name="service_id" value="' . $service['id'] . '" /></div>';
        echo '</div>';
    }
}
?>
