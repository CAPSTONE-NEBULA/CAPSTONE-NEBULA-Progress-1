<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chart.js Example - Multiple Charts</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> 
</head>
<body>

    <h2>Total Revenue per Material Group - Bar Chart</h2>
    <canvas id="revenueChart1" width="400" height="200"></canvas> 

    <h2>Total Revenue per Material Group - Line Chart</h2>
    <canvas id="revenueChart2" width="400" height="200"></canvas> 

    <h2>Total Revenue per Material Group - Pie Chart</h2>
    <canvas id="revenueChart3" width="400" height="200"></canvas> <!-- Chart 3 -->

    <h2>Total Revenue per Material Group - Radar Chart</h2>
    <canvas id="revenueChart4" width="400" height="200"></canvas> <!-- Chart 4 -->

    <script>
        $(document).ready(function() {
            $.ajax({
                url: 'get_data.php', // Endpoint PHP untuk mengambil data
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    console.log(data);  // Memeriksa data yang diterima

                    var labels = [];
                    var revenueData = [];

                    // Proses data dan masukkan ke dalam array labels dan revenueData
                    data.forEach(function(item) {
                        labels.push(item.mat_group_name); // Menambahkan mat_group_name ke labels
                        revenueData.push(item.total_revenue); // Menambahkan total_revenue ke revenueData
                    });

                    // Chart 1 - Total Revenue per Material Group (Bar Chart)
                    var ctx1 = document.getElementById('revenueChart1').getContext('2d');
                    var chart1 = new Chart(ctx1, {
                        type: 'bar',
                        data: {
                            labels: labels,
                            datasets: [{
                                label: 'Total Revenue - Bar Chart',
                                data: revenueData,
                                backgroundColor: 'rgba(54, 162, 235, 0.2)', 
                                borderColor: 'rgba(54, 162, 235, 1)',
                                borderWidth: 1
                            }]
                        },
                        options: {
                            responsive: false,
                            maintainAspectRatio: false,
                            scales: {
                                y: {
                                    beginAtZero: true
                                }
                            }
                        }
                    });

                    // Chart 2 - Total Revenue per Material Group (Line Chart)
                    var ctx2 = document.getElementById('revenueChart2').getContext('2d');
                    var chart2 = new Chart(ctx2, {
                        type: 'line', // Menggunakan line chart
                        data: {
                            labels: labels,
                            datasets: [{
                                label: 'Total Revenue - Line Chart',
                                data: revenueData,
                                backgroundColor: 'rgba(255, 99, 132, 0.2)', 
                                borderColor: 'rgba(255, 99, 132, 1)',
                                borderWidth: 1
                            }]
                        },
                        options: {
                            responsive: false,
                            maintainAspectRatio: false,
                            scales: {
                                y: {
                                    beginAtZero: true
                                }
                            }
                        }
                    });

                    // Chart 3 - Total Revenue per Material Group (Pie Chart)
                    var ctx3 = document.getElementById('revenueChart3').getContext('2d');
                    var chart3 = new Chart(ctx3, {
                        type: 'pie', // Menggunakan pie chart
                        data: {
                            labels: labels,
                            datasets: [{
                                label: 'Total Revenue per Material Group',
                                data: revenueData,
                                backgroundColor: ['rgba(54, 162, 235, 0.2)', 'rgba(255, 99, 132, 0.2)', 'rgba(75, 192, 192, 0.2)', 'rgba(153, 102, 255, 0.2)', 'rgba(255, 159, 64, 0.2)'],
                                borderColor: ['rgba(54, 162, 235, 1)', 'rgba(255, 99, 132, 1)', 'rgba(75, 192, 192, 1)', 'rgba(153, 102, 255, 1)', 'rgba(255, 159, 64, 1)'],
                                borderWidth: 1
                            }]
                        },
                        options: {
                            responsive: false,
                            maintainAspectRatio: false
                        }
                    });

                    // Chart 4 - Total Revenue per Material Group (Radar Chart)
                    var ctx4 = document.getElementById('revenueChart4').getContext('2d');
                    var chart4 = new Chart(ctx4, {
                        type: 'radar', // Menggunakan radar chart
                        data: {
                            labels: labels,
                            datasets: [{
                                label: 'Total Revenue - Radar Chart',
                                data: revenueData,
                                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                                borderColor: 'rgba(54, 162, 235, 1)',
                                borderWidth: 1
                            }]
                        },
                        options: {
                            responsive: false,
                            maintainAspectRatio: false,
                            scale: {
                                ticks: {
                                    beginAtZero: true
                                }
                            }
                        }
                    });
                },
                error: function(xhr, status, error) {
                    console.error("Error fetching data: " + error);
                }
            });
        });
    </script>
</body>
</html>
