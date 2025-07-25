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

$sensor = isset($_GET['sensor']) ? $_GET['sensor'] : 'temperature';
$days = isset($_GET['days']) ? intval($_GET['days']) : 7;

// Validate sensor type
$validSensors = ['temperature', 'ph', 'turbidity', 'conductivity'];
if (!in_array($sensor, $validSensors)) {
    $sensor = 'temperature';
}

$sql = "SELECT $sensor as value, timestamp 
        FROM sensor_data 
        WHERE timestamp >= DATE_SUB(NOW(), INTERVAL ? DAY)
        ORDER BY timestamp ASC";
        
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $days);
$stmt->execute();
$result = $stmt->get_result();

$data = array();
while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

echo json_encode($data);

$stmt->close();
$conn->close();
?>