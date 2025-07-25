<?php
// device.php
$deviceId = $_GET['id'] ?? 0;
$conn = new mysqli("localhost", "kasunpre_water_quality", "Kasun9090", "kasunpre_water_qualitymap");

$device = $conn->query("
    SELECT * FROM devices 
    WHERE id = $deviceId
")->fetch_assoc();

$readings = $conn->query("
    SELECT * FROM sensor_data 
    WHERE device_id = '{$device['device_id']}' 
    ORDER BY timestamp DESC 
    LIMIT 100
");
?>
<!DOCTYPE html>
<html>
<head>
    <title><?= $device['device_name'] ?> Details</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <div class="container mt-4">
        <h1><?= $device['device_name'] ?></h1>
        <p class="lead">Device ID: <?= $device['device_id'] ?></p>
        
        <div class="row">
            <div class="col-md-6">
                <div class="card mb-4">
                    <div class="card-header">
                        <h3>Location</h3>
                    </div>
                    <div class="card-body">
                        <div id="miniMap" style="height: 300px;"></div>
                        <p class="mt-2">
                            Coordinates: <?= $device['latitude'] ?>, <?= $device['longitude'] ?>
                        </p>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="card mb-4">
                    <div class="card-header">
                        <h3>Latest Reading</h3>
                    </div>
                    <div class="card-body">
                        <?php if($latest = $readings->fetch_assoc()): ?>
                        <div class="row">
                            <div class="col-6">
                                <p>Temperature: <strong><?= $latest['temperature'] ?> °C</strong></p>
                                <p>pH: <strong><?= $latest['ph'] ?></strong></p>
                            </div>
                            <div class="col-6">
                                <p>Turbidity: <strong><?= $latest['turbidity'] ?> NTU</strong></p>
                                <p>Conductivity: <strong><?= $latest['conductivity'] ?> µS/cm</strong></p>
                            </div>
                            <p class="text-muted"><?= $latest['timestamp'] ?></p>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="card mb-4">
            <div class="card-header">
                <h3>Historical Data</h3>
            </div>
            <div class="card-body">
                <div class="chart-container" style="height: 300px;">
                    <canvas id="historyChart"></canvas>
                </div>
            </div>
        </div>
        
        <div class="card">
            <div class="card-header">
                <h3>Recent Readings</h3>
            </div>
            <div class="card-body">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Time</th>
                            <th>Temp (°C)</th>
                            <th>pH</th>
                            <th>Turbidity</th>
                            <th>Cond.</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $readings->data_seek(0); while($row = $readings->fetch_assoc()): ?>
                        <tr>
                            <td><?= $row['timestamp'] ?></td>
                            <td><?= $row['temperature'] ?></td>
                            <td><?= $row['ph'] ?></td>
                            <td><?= $row['turbidity'] ?></td>
                            <td><?= $row['conductivity'] ?></td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
    <script>
        // Mini map
        const miniMap = L.map('miniMap').setView(
            [<?= $device['latitude'] ?>, <?= $device['longitude'] ?>], 
            13
        );
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(miniMap);
        L.marker([<?= $device['latitude'] ?>, <?= $device['longitude'] ?>])
            .addTo(miniMap)
            .bindPopup('<?= addslashes($device['device_name']) ?>');
        
        // History chart
        const ctx = document.getElementById('historyChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: [<?php 
                    $readings->data_seek(0);
                    $labels = [];
                    while($row = $readings->fetch_assoc()) {
                        $labels[] = "'" . date('H:i', strtotime($row['timestamp'])) . "'";
                    }
                    echo implode(', ', array_reverse($labels));
                ?>],
                datasets: [
                    {
                        label: 'Temperature (°C)',
                        data: [<?php 
                            $readings->data_seek(0);
                            $temps = [];
                            while($row = $readings->fetch_assoc()) {
                                $temps[] = $row['temperature'];
                            }
                            echo implode(', ', array_reverse($temps));
                        ?>],
                        borderColor: 'rgb(255, 99, 132)',
                        yAxisID: 'y'
                    },
                    {
                        label: 'pH',
                        data: [<?php 
                            $readings->data_seek(0);
                            $phs = [];
                            while($row = $readings->fetch_assoc()) {
                                $phs[] = $row['ph'];
                            }
                            echo implode(', ', array_reverse($phs));
                        ?>],
                        borderColor: 'rgb(54, 162, 235)',
                        yAxisID: 'y1'
                    }
                ]
            },
            options: {
                responsive: true,
                interaction: {
                    mode: 'index',
                    intersect: false
                },
                scales: {
                    y: {
                        type: 'linear',
                        display: true,
                        position: 'left',
                        title: { text: 'Temperature (°C)', display: true }
                    },
                    y1: {
                        type: 'linear',
                        display: true,
                        position: 'right',
                        title: { text: 'pH Value', display: true },
                        grid: { drawOnChartArea: false }
                    }
                }
            }
        });
    </script>
</body>
</html>
<?php $conn->close(); ?>