<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Water Quality Monitoring Dashboard</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/moment"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-adapter-moment"></script>
    
    <!-- Custom CSS -->
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
            padding-top: 20px;
        }
        .dashboard-card {
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
            transition: transform 0.3s;
            height: 100%;
        }
        .dashboard-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
        }
        .current-value {
            font-size: 2rem;
            font-weight: bold;
            margin: 10px 0;
        }
        .sensor-label {
            font-size: 1rem;
            color: #6c757d;
        }
        .unit {
            font-size: 0.9rem;
            color: #6c757d;
        }
        .chart-container {
            position: relative;
            height: 250px;
            width: 100%;
        }
        .history-link {
            color: #0d6efd;
            text-decoration: none;
            font-size: 0.9rem;
            display: inline-block;
            margin-top: 10px;
        }
        .history-link:hover {
            text-decoration: underline;
        }
        .navbar-brand {
            font-weight: bold;
        }
        .last-updated {
            font-size: 0.8rem;
            color: #6c757d;
            text-align: right;
        }
        @media (max-width: 768px) {
            .current-value {
                font-size: 1.5rem;
            }
            .col-md-3 {
                margin-bottom: 15px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <nav class="navbar navbar-expand-lg navbar-light bg-light rounded mb-4">
            <div class="container-fluid">
                <a class="navbar-brand" href="#">
                    <i class="fas fa-tint"></i> Water Quality Monitor
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item">
                            <a class="nav-link active" href="index.php">Dashboard</a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="historyDropdown" role="button" data-bs-toggle="dropdown">
                                History
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="temperature_history.php">Temperature</a></li>
                                <li><a class="dropdown-item" href="ph_history.php">pH Value</a></li>
                                <li><a class="dropdown-item" href="turbidity_history.php">Turbidity</a></li>
                                <li><a class="dropdown-item" href="conductivity_history.php">Conductivity</a></li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
        
        <!-- Current Values Row -->
        <div class="row mb-4">
            <!-- Temperature Card -->
            <div class="col-md-3 col-sm-6">
                <div class="dashboard-card p-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="sensor-label">Temperature</div>
                            <div class="current-value" id="current-temp">--</div>
                            <div class="unit">°C</div>
                        </div>
                        <div>
                            <i class="fas fa-thermometer-half fa-3x" style="color: #ff6384;"></i>
                        </div>
                    </div>
                    <a href="temperature_history.php" class="history-link">
                        View History <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
            </div>
            
            <!-- pH Card -->
            <div class="col-md-3 col-sm-6">
                <div class="dashboard-card p-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="sensor-label">pH Value</div>
                            <div class="current-value" id="current-ph">--</div>
                            <div class="unit">pH</div>
                        </div>
                        <div>
                            <i class="fas fa-flask fa-3x" style="color: #36a2eb;"></i>
                        </div>
                    </div>
                    <a href="ph_history.php" class="history-link">
                        View History <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
            </div>
            
            <!-- Turbidity Card -->
            <div class="col-md-3 col-sm-6">
                <div class="dashboard-card p-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="sensor-label">Turbidity</div>
                            <div class="current-value" id="current-turbidity">--</div>
                            <div class="unit">NTU</div>
                        </div>
                        <div>
                            <i class="fas fa-cloud fa-3x" style="color: #ffce56;"></i>
                        </div>
                    </div>
                    <a href="turbidity_history.php" class="history-link">
                        View History <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
            </div>
            
            <!-- Conductivity Card -->
            <div class="col-md-3 col-sm-6">
                <div class="dashboard-card p-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="sensor-label">Conductivity</div>
                            <div class="current-value" id="current-conductivity">--</div>
                            <div class="unit">µS/cm</div>
                        </div>
                        <div>
                            <i class="fas fa-bolt fa-3x" style="color: #4bc0c0;"></i>
                        </div>
                    </div>
                    <a href="conductivity_history.php" class="history-link">
                        View History <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Last Updated -->
        <div class="row mb-3">
            <div class="col">
                <div class="last-updated" id="last-updated">
                    Last updated: --
                </div>
            </div>
        </div>
        
        <!-- Charts Row -->
        <div class="row">
            <!-- Temperature Chart -->
            <div class="col-lg-6 mb-4">
                <div class="dashboard-card p-3">
                    <h5>Temperature History</h5>
                    <div class="chart-container">
                        <canvas id="tempChart"></canvas>
                    </div>
                    <a href="temperature_history.php" class="history-link">
                        View Detailed History <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
            </div>
            
            <!-- pH Chart -->
            <div class="col-lg-6 mb-4">
                <div class="dashboard-card p-3">
                    <h5>pH Value History</h5>
                    <div class="chart-container">
                        <canvas id="phChart"></canvas>
                    </div>
                    <a href="ph_history.php" class="history-link">
                        View Detailed History <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
            </div>
            
            <!-- Turbidity Chart -->
            <div class="col-lg-6 mb-4">
                <div class="dashboard-card p-3">
                    <h5>Turbidity History</h5>
                    <div class="chart-container">
                        <canvas id="turbidityChart"></canvas>
                    </div>
                    <a href="turbidity_history.php" class="history-link">
                        View Detailed History <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
            </div>
            
            <!-- Conductivity Chart -->
            <div class="col-lg-6 mb-4">
                <div class="dashboard-card p-3">
                    <h5>Conductivity History</h5>
                    <div class="chart-container">
                        <canvas id="conductivityChart"></canvas>
                    </div>
                    <a href="conductivity_history.php" class="history-link">
                        View Detailed History <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Font Awesome -->
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Custom JavaScript -->
    <script>
        // Initialize charts
        const tempChart = createChart('tempChart', 'Temperature (°C)', 'rgba(255, 99, 132, 0.2)');
        const phChart = createChart('phChart', 'pH Value', 'rgba(54, 162, 235, 0.2)');
        const turbidityChart = createChart('turbidityChart', 'Turbidity (NTU)', 'rgba(255, 206, 86, 0.2)');
        const conductivityChart = createChart('conductivityChart', 'Conductivity (µS/cm)', 'rgba(75, 192, 192, 0.2)');

        function createChart(id, label, backgroundColor) {
            const ctx = document.getElementById(id).getContext('2d');
            return new Chart(ctx, {
                type: 'line',
                data: {
                    datasets: [{
                        label: label,
                        backgroundColor: backgroundColor,
                        borderColor: backgroundColor.replace('0.2', '1'),
                        borderWidth: 1,
                        fill: true,
                        data: []
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        x: {
                            type: 'time',
                            time: {
                                unit: 'hour',
                                displayFormats: {
                                    hour: 'HH:mm'
                                }
                            }
                        },
                        y: {
                            beginAtZero: false
                        }
                    },
                    plugins: {
                        legend: {
                            display: false
                        }
                    }
                }
            });
        }

        // Fetch data and update dashboard
        function fetchData() {
            fetch('api/get_data.php?limit=24')  // Get last 24 readings
                .then(response => response.json())
                .then(data => {
                    if (data.length > 0) {
                        // Update current values
                        const latest = data[0];
                        document.getElementById('current-temp').textContent = latest.temperature.toFixed(1);
                        document.getElementById('current-ph').textContent = latest.ph.toFixed(2);
                        document.getElementById('current-turbidity').textContent = latest.turbidity.toFixed(2);
                        document.getElementById('current-conductivity').textContent = latest.conductivity.toFixed(0);
                        
                        // Update last updated time
                        const updatedTime = new Date(latest.timestamp).toLocaleTimeString();
                        document.getElementById('last-updated').textContent = `Last updated: ${updatedTime}`;

                        // Update charts
                        updateChart(tempChart, data, 'temperature');
                        updateChart(phChart, data, 'ph');
                        updateChart(turbidityChart, data, 'turbidity');
                        updateChart(conductivityChart, data, 'conductivity');
                    }
                })
                .catch(error => {
                    console.error('Error fetching data:', error);
                });
        }

        function updateChart(chart, data, property) {
            chart.data.datasets[0].data = data.map(item => ({
                x: new Date(item.timestamp),
                y: item[property]
            }));
            chart.update();
        }

        // Initial data load
        fetchData();
        
        // Refresh data every 30 seconds
        setInterval(fetchData, 30000);
    </script>
</body>
</html>