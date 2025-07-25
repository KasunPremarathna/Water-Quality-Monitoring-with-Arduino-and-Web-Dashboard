<?php
// map.php
$conn = new mysqli("localhost", "kasunpre_water_quality", "Kasun9090", "kasunpre_water_qualitymap");
$devices = $conn->query("SELECT * FROM devices WHERE latitude IS NOT NULL AND longitude IS NOT NULL");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Water Quality Monitoring Map</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
    <style>
        #map { height: 600px; }
        .device-popup img { width: 100%; }
        .chart-container { width: 100%; height: 200px; }
    </style>
</head>
<body>
    <div class="container-fluid">
        <h1>Water Quality Monitoring Network</h1>
        <div id="map"></div>
    </div>

    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Initialize map
        const map = L.map('map').setView([0, 0], 2);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        // Device icons
        const waterIcon = L.icon({
            iconUrl: 'https://cdn-icons-png.flaticon.com/512/3163/3163478.png',
            iconSize: [32, 32],
            iconAnchor: [16, 32],
            popupAnchor: [0, -32]
        });

        // Add devices to map
        <?php while($device = $devices->fetch_assoc()): ?>
        const marker<?= $device['id'] ?> = L.marker(
            [<?= $device['latitude'] ?>, <?= $device['longitude'] ?>], 
            {icon: waterIcon}
        ).addTo(map);
        
        marker<?= $device['id'] ?>.bindPopup(`
            <div class="device-popup">
                <h3><?= addslashes($device['device_name']) ?></h3>
                <p>Location: <?= $device['latitude'] ?>, <?= $device['longitude'] ?></p>
                <p>Last seen: <?= $device['last_seen'] ?></p>
                <div class="chart-container">
                    <canvas id="chart<?= $device['id'] ?>"></canvas>
                </div>
                <a href="device.php?id=<?= $device['id'] ?>" target="_blank">View Details</a>
            </div>
        `);
        
        // Load chart data when popup opens
        marker<?= $device['id'] ?>.on('popupopen', function() {
            fetch(`api/get_device_data.php?device_id=<?= $device['device_id'] ?>`)
                .then(response => response.json())
                .then(data => {
                    const ctx = document.getElementById('chart<?= $device['id'] ?>').getContext('2d');
                    new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: data.readings.map(r => new Date(r.timestamp).toLocaleTimeString()),
                            datasets: [{
                                label: 'Temperature (°C)',
                                data: data.readings.map(r => r.temperature),
                                borderColor: 'rgb(255, 99, 132)',
                                tension: 0.1
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false
                        }
                    });
                });
        });
        <?php endwhile; ?>

        // Fit map to markers
        const group = new L.featureGroup([<?php 
            $first = true;
            $devices->data_seek(0);
            while($device = $devices->fetch_assoc()) {
                echo ($first ? '' : ', ') . "marker{$device['id']}";
                $first = false;
            }
        ?>]);
        map.fitBounds(group.getBounds());
    </script>
</body>
</html>
<?php $conn->close(); ?>