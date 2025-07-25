<?php
header("Content-Type: text/plain");

$deviceId = $_POST['device_id'] ?? '';
$latitude = floatval($_POST['latitude'] ?? 0);
$longitude = floatval($_POST['longitude'] ?? 0);

if (empty($deviceId)) {
    die("ERROR: Device ID required");
}

$conn = new mysqli("localhost", "kasunpre_water_quality", "Kasun9090", "kasunpre_water_qualitymap");

$stmt = $conn->prepare("UPDATE devices SET latitude = ?, longitude = ? WHERE device_id = ?");
$stmt->bind_param("dds", $latitude, $longitude, $deviceId);

if ($stmt->execute()) {
    echo "OK: Location updated";
} else {
    echo "ERROR: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>