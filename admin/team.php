<?php
session_start();
include '../includes/conn.php';
include "includes/header.php";

$teamName = null;
$teamId = (int)($_GET['id'] ?? 0);

if (!$teamId > 0) {
    exit;
}


if ($stmt = $conn->prepare("SELECT name FROM team_leaders WHERE id = ?")) {
    $stmt->bind_param("i", $teamId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        $teamName = $row['name'];
    }
    $stmt->close();
}

$sql = "SELECT
    c.guide_name,
    SUM(c.amount_credited) AS total_credited,
    COUNT(c.amount_credited) AS num_of_credits,
    ROUND(SUM(c.amount_credited) * 100.0 / team_total.total, 2) AS percent_of_team_total
    FROM 
        credits c
    JOIN (
        SELECT 
            SUM(amount_credited) AS total
        FROM 
            credits
        WHERE 
            team_leader_id = $teamId
            AND MONTH(date_applied) = MONTH(CURRENT_DATE())
            AND YEAR(date_applied) = YEAR(CURRENT_DATE())
    ) team_total ON 1=1
    WHERE 
        c.team_leader_id = $teamId
        AND MONTH(c.date_applied) = MONTH(CURRENT_DATE())
        AND YEAR(c.date_applied) = YEAR(CURRENT_DATE())
    GROUP BY 
        c.guide_name, team_total.total
    ORDER BY 
        total_credited DESC;";

$team_stats_query = $conn->query($sql);

$labels = [];
$amounts = [];
$percentages = [];
$team_stats = [];
$grandTotal = 0;
$totalNumOfCredits = 0;

while ($row = $team_stats_query->fetch_assoc()) {
    $team_stats[] = $row; // Add the entire row to your $data array
    $labels[] = $row['guide_name'];
    $amounts[] = $row['total_credited'];
    $num_of_credits[] = $row['num_of_credits'];
    $percentages[] = $row['percent_of_team_total'];
    $grandTotal += $row['total_credited'];
    $totalNumOfCredits += $row['num_of_credits'];
}

$cards_sql = "SELECT
    -- Total Credit Today
    (SELECT COALESCE(SUM(amount_credited), 0)
     FROM credits
     WHERE DATE(date_applied) = CURRENT_DATE()
       AND team_leader_id = $teamId) AS total_today,

    -- Total Credit This Month
    (SELECT COALESCE(SUM(amount_credited), 0)
     FROM credits
     WHERE MONTH(date_applied) = MONTH(CURRENT_DATE())
       AND YEAR(date_applied) = YEAR(CURRENT_DATE())
       AND team_leader_id = $teamId) AS total_month,

    -- Total Unique Guides This Month
    (SELECT COUNT(DISTINCT guide_name)
     FROM credits
     WHERE MONTH(date_applied) = MONTH(CURRENT_DATE())
       AND YEAR(date_applied) = YEAR(CURRENT_DATE())
       AND team_leader_id = $teamId) AS total_guides,

    -- Highest Single Credit Applied
    (SELECT COALESCE(MAX(amount_credited), 0)
     FROM credits
     WHERE team_leader_id = $teamId) AS max_credit;";

$stats = $conn->query($cards_sql)->fetch_assoc();

$statCards = [
    'total_today' => [
        'label' => 'Total Credit Applied Today',
        'icon'  => 'calendar_today',
        'type' => 'money'
    ],
    'total_month' => [
        'label' => 'Total Credit This Month',
        'icon'  => 'calendar_month',
        'type' => 'money'
    ],
    'total_guides' => [
        'label' => 'Total Guides This Month',
        'icon'  => 'groups',
        'type' => 'text'
    ],
    'max_credit' => [
        'label' => 'Highest Credit Applied',
        'icon'  => 'trending_up',
        'type' => 'money'
    ],
];


?>

<body>
    <?php include './includes/nav.php'; ?>

    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
        <div class="container-fluid py-2">
            <div class="row d-print-none">
                <div class="ms-3">
                    <h3 class="mb-0 h4 font-weight-bolder">Team <?= $teamName ?> This Month</h3>
                    <p class="mb-4">
                        Check the credits analysis applied by guides.
                    </p>
                </div>
                <?php foreach ($statCards as $key => $meta): ?>
                    <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                        <div class="card">
                            <div class="card-header p-2 ps-3">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <p class="text-sm mb-0 text-capitalize"><?= $meta['label'] ?></p>
                                        <h4 class="mb-4 mt-3">
                                            <?= $meta['type'] === 'text' ? $stats[$key] : '£' . number_format($stats[$key], 2) ?>
                                        </h4>
                                    </div>
                                    <div class="icon icon-md icon-shape bg-gradient-dark shadow-dark shadow text-center border-radius-lg">
                                        <i class="material-symbols-rounded opacity-10"><?= $meta['icon'] ?></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="row mb-4 mt-4">
                <div class="col-lg-8 col-md-8 mb-md-0 mb-4 pr-5">
                    <div class="card-body">
                        <canvas id="creditAmountChart" height="150"></canvas>
                    </div>
                </div>
                <div class="col-lg-4 col-md-4 mb-md-0 mb-4 pr-5">
                    <div class="card-body">
                        <canvas id="creditPercentChart" height="150"></canvas>
                    </div>
                </div>
            </div>

            <!--table display-->
            <div class="row mb-4 mt-4">
                <div class="col-lg-12  col-md-6 mb-md-0 mb-4">
                    <div class="card">
                        <div class="card-header pb-0">
                            <div class="row">
                                <div class="col-lg-6 col-7">
                                    <h6 class="text-bold">Monthly Credits</h6>
                                    <p class="text-sm mb-3">
                                        <i class="fa fa-check text-info" aria-hidden="true"></i>
                                        <span class="font-weight-bold ms-1">View analysis of credits applied by
                                            guides of <?= $teamName ?></span> this month
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="card-body px-2 pb-2 ">
                            <div class="table-responsive">
                                <table class="table align-items-center mb-0 table-striped">
                                    <thead class="px-0">
                                        <tr>
                                            <th
                                                class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                Guide Name</th>
                                            <th
                                                class="text-uppercase text-secondary text-xxs text-center font-weight-bolder opacity-7">
                                                Number of Credits</th>
                                            <th text-end 
                                                class="text-uppercase text-secondary text-xxs text-end font-weight-bolder opacity-7">
                                                Amount Credited</th>
                                            <th
                                                class="text-uppercase text-secondary text-xxs text-center font-weight-bolder opacity-7">
                                                Percentage</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($team_stats as $row): ?>
                                            <tr>
                                                <td>
                                                    <p class="text-sm font-weight-normal mb-0"><?= htmlspecialchars($row['guide_name']) ?></p>
                                                </td>
                                                <td>
                                                    <p class="text-sm text-center font-weight-normal mb-0"><?= $row['num_of_credits'] ?></p>
                                                </td>
                                                <td>
                                                    <p class="text-sm text-end font-weight-bold mb-0">
                                                        £<?= number_format($row['total_credited'], 2) ?></p>
                                                </td>
                                                <td>
                                                    <p class="text-sm text-center mb-0">
                                                        <?= $row['percent_of_team_total'] ?>%</p>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                        <tr class="border-top">
                                            <td><strong class="text-uppercase">Total</strong></td>
                                            <td class="text-center"><strong><?= $totalNumOfCredits ?></strong>
                                            </td>
                                            <td class="text-end"><strong>£<?= number_format($grandTotal, 2) ?></strong>
                                            </td>
                                            <td class="text-center"><strong>100%</strong></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <?php include 'includes/footer.php' ?>
        </div>
    </main>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const labels = <?= json_encode($labels) ?>;
        const amounts = <?= json_encode($amounts) ?>;
        const percentages = <?= json_encode($percentages) ?>;

        // Histogram (Bar Chart) for Amount Credited
        new Chart(document.getElementById('creditAmountChart').getContext('2d'), {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Amount Credited (£)',
                    data: amounts,
                    backgroundColor: 'rgba(54, 162, 235, 0.7)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    title: {
                        display: true,
                        text: 'Total Amount Credited by Guides'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Amount (£)'
                        }
                    }
                }
            }
        });

        // console.log(`hsl(${i * (360 / labels.length)}, 70%, 60%)`);
        

        // Pie Chart for Percentage Distribution
        new Chart(document.getElementById('creditPercentChart').getContext('2d'), {
            type: 'pie',
            data: {
                labels: labels,
                datasets: [{
                    label: '% of Total',
                    data: percentages,
                    backgroundColor: labels.map((_, i) =>
                        `hsl(${i * (360 / labels.length)}, 70%, 60%)`
                    )
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    title: {
                        display: true,
                        text: 'Percentage of Total Credit by Guides'
                    },
                    tooltip: {
                        callbacks: {
                            label: ctx => `${ctx.label}: ${ctx.parsed}%`
                        }
                    }
                }
            }
        });
    </script>
</body>

</html>