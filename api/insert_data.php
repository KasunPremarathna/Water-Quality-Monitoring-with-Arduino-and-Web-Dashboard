<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

$servername = "localhost";
$username = "kasunpre_water_quality";
$password = "Kasun9090";
$dbname = "kasunpre_water_quality";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get data from POST request
$temp = isset($_POST['temp']) ? floatval($_POST['temp']) : null;
$ph = isset($_POST['ph']) ? floatval($_POST['ph']) : null;
$turbidity = isset($_POST['turbidity']) ? floatval($_POST['turbidity']) : null;
$conductivity = isset($_POST['conductivity']) ? floatval($_POST['conductivity']) : null;

// Insert data into database
$sql = "INSERT INTO sensor_data (temperature, ph, turbidity, conductivity, timestamp) 
        VALUES (?, ?, ?, ?, NOW())";

$stmt = $conn->prepare($sql);
$stmt->bind_param("dddd", $temp, $ph, $turbidity, $conductivity);

if ($stmt->execute()) {
    echo json_encode(array("status" => "success", "message" => "Data inserted successfully"));
} else {
    echo json_encode(array("status" => "error", "message" => "Error inserting data"));
}

$stmt->close();
$conn->close();
?>