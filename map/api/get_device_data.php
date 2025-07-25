<?php
header("Content-Type: application/json");

$deviceId = $_GET['device_id'] ?? '';

if (empty($deviceId)) {
    http_response_code(400);
    die(json_encode(["error" => "Device ID required"]));
}

$conn = new mysqli("localhost", "kasunpre_water_quality", "Kasun9090", "kasunpre_water_qualitymap");

// Get device info
$device = $conn->query("SELECT * FROM devices WHERE device_id = '$deviceId'")->fetch_assoc();

// Get last 24 hours of data
$data = $conn->query("
    SELECT * FROM sensor_data 
    WHERE device_id = '$deviceId' 
    AND timestamp >= DATE_SUB(NOW(), INTERVAL 24 HOUR)
    ORDER BY timestamp ASC
");

$result = [
    'device' => $device,
    'readings' => []
];

while ($row = $data->fetch_assoc()) {
    $result['readings'][] = $row;
}

echo json_encode($result);
$conn->close();
?>