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

$limit = isset($_GET['limit']) ? intval($_GET['limit']) : 50;

$sql = "SELECT temperature, ph, turbidity, conductivity, timestamp 
        FROM sensor_data 
        ORDER BY timestamp DESC 
        LIMIT ?";
        
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $limit);
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