<?php
include 'koneksi.php';
function getDataGrouped($field) {
    global $connection;
    $data = [];
    $query = "SELECT $field AS label, COUNT(*) as total FROM data_siswa GROUP BY $field";
    $result = $connection->query($query);
    while ($row = $result->fetch_assoc()) {
        $data[] = [
            'label' => htmlspecialchars($row['label']),
            'total' => (int)$row['total']
        ];
    }
    return [
        'labels' => array_column($data, 'label'),
        'counts' => array_column($data, 'total')
    ];
}


$ekonomi     = getDataGrouped('kategori_ekonomi');
$penghasilan = getDataGrouped('total_penghasilan');
?>

<style>
    .chart-wrapper {
        display: flex;
        justify-content: center;
        flex-wrap: wrap;
        gap: 30px;
        margin-top: 30px;
    }
    .chart-box {
        background: white;
        padding: 25px;
        width: 400px;
        border-radius: 16px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        text-align: center;
    }
    .chart-box h3 {
        font-weight: 600;
        margin-bottom: 15px;
        font-size: 16px;
    }
    canvas {
        height: 250px !important;
    }
</style>
    <div class="chart-wrapper">
        <div class="chart-box">
            <h3>Diagram Kategori Ekonomi</h3>
            <canvas id="chartEkonomi"></canvas>
        </div>
        <div class="chart-box">
            <h3>Grafik penghasilan orang tua</h3>
            <canvas id="chartPenghasilan"></canvas>
        </div>
    </div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        function createChart(id, type, labels, data, colors = []) {
            new Chart(document.getElementById(id), {
                type: type,
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Jumlah',
                        data: data,
                        backgroundColor: colors.length ? colors : '#3498db'
                    }]
                },
                options: {
                    responsive: true,
                    plugins: { legend: { display: type !== 'bar' } },
                    scales: type === 'bar' ? { y: { beginAtZero: true } } : {}
                }
            });
        }
        const ekonomiLabels = <?= json_encode($ekonomi['labels']) ?>;
        const ekonomiCounts = <?= json_encode($ekonomi['counts']) ?>;

        const penghasilanLabels = <?= json_encode($penghasilan['labels']) ?>;
        const penghasilanCounts = <?= json_encode($penghasilan['counts']) ?>;

        createChart('chartEkonomi', 'pie', ekonomiLabels, ekonomiCounts, ['#e67e22', '#f1c40f', '#2ecc71']);
        createChart('chartPenghasilan', 'bar', penghasilanLabels, penghasilanCounts, ['#8e44ad', '#3498db', '#e74c3c']);
    </script>