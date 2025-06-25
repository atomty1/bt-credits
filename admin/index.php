<?php
    session_start();
    include '../includes/conn.php';
    include "includes/header.php";


    $reasons = ['Auto Compensation Not Applied', 'Billing Error', 'Complaint Closure', 'Complete Wifi Guarantee', 'Equipment Charge', 'Home Tech Expert', 'Held To Term Charge', 'Incorrect Charge', 'Missed Engineer', 'Reward Card', 'Held To Term Charge 1.6'];
    $creditData = [];
    $totalCredit = 0;

    foreach ($reasons as $reason) {
        $sql = "SELECT SUM(AMOUNT_CREDITED) AS total FROM CREDITS
                    WHERE REASON = '$reason'
                        AND MONTH(date_applied) = MONTH(CURRENT_DATE()) 
                        AND YEAR(date_applied) = YEAR(CURRENT_DATE())";

        $result = $conn->query($sql);

        if ($result && $row = $result->fetch_assoc()) {
            $amount = $row['total'] ?? 0;
            $creditData[$reason] = floatval($amount);
            $totalCredit += floatval($amount);
        } else {
            $creditData[$reason] = 0;
        }
    }
?>

<body>
    <aside class="sidenav navbar navbar-vertical navbar-expand-xs border-radius-lg fixed-start ms-2  bg-white my-2" id="sidenav-main">
        <div class="sidenav-header">
            <i class="fas fa-times p-3 cursor-pointer text-dark opacity-5 position-absolute end-0 top-0 d-none d-xl-none" aria-hidden="true" id="iconSidenav"></i>
            <a class="navbar-brand px-4 py-3 m-0" href="index.php">
                <img src="assets/img/logo.png" class="navbar-brand-img" width="64" height="26" alt="main_logo">
                <span class="ms-1 text-sm text-uppercase text-dark"><strong>BT|EE Credits</strong></span>
            </a>
        </div>
        <hr class="horizontal dark mt-0 mb-2">
        <div class="collapse navbar-collapse  w-auto" id="sidenav-collapse-main">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link active bg-dark text-white" href="index.php">
                        <i class="material-symbols-rounded opacity-5">dashboard</i>
                        <span class="nav-link-text ms-1">Dashboard</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-dark" href="overview.php">
                        <i class="material-symbols-rounded opacity-5">overview</i>
                        <span class="nav-link-text ms-1">Overview</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-dark" data-bs-toggle="collapse" href="#teamsSubmenu" role="button" aria-expanded="false" aria-controls="teamsSubmenu">
                        <i class="material-symbols-rounded opacity-5">table_view</i>
                        <span class="nav-link-text ms-1">Teams Statistics</span>
                        <i class="material-symbols-rounded float-end opacity-5">expand_more</i>
                    </a>
                    <div class="collapse ps-4" id="teamsSubmenu">
                        <ul class="navbar-nav">
                            <li class="nav-item">
                                <a class="nav-link text-dark" href="#">
                                    <i class="material-symbols-rounded opacity-5 pl-2">person</i>
                                    <span>Team Luke</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-dark" href="team_pages/team_stuart.php">
                                    <i class="material-symbols-rounded opacity-5 pl-2">person</i>
                                    <span>Team Stuart</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-dark" href="#">
                                    <i class="material-symbols-rounded opacity-5 pl-2">person</i>
                                    <span>Team Tammy</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-dark" href="#">
                                    <i class="material-symbols-rounded opacity-5 pl-2">person</i>
                                    <span>Team Nathan</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-dark" href="#">
                                    <i class="material-symbols-rounded opacity-5 pl-2">person</i>
                                    <span>Team Hollie</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-dark" href="#">
                                    <i class="material-symbols-rounded opacity-5 pl-2">person</i>
                                    <span>Team Leanne</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-dark" href="#">
                                    <i class="material-symbols-rounded opacity-5 pl-2">person</i>
                                    <span>Team Anto</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-dark" href="#">
                                    <i class="material-symbols-rounded opacity-5 pl-2">person</i>
                                    <span>Team Johnnie</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
                
                <li class="nav-item mt-3">
                    <h6 class="ps-4 ms-2 text-uppercase text-xs text-dark font-weight-bolder opacity-5">Account pages</h6>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-dark" href="">
                        <i class="material-symbols-rounded opacity-5">manage_accounts</i>
                        <span class="nav-link-text ms-1">Profile</span>
                    </a>
                </li>
            </ul>
        </div>
        <div class="sidenav-footer position-absolute w-100 bottom-0 ">
            <div class="mx-3">
                <a class="btn bg-gradient-dark w-100" href="#" type="button">Sign Out</a>
            </div>
        </div>
    </aside>

    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
        <div class="container-fluid py-2">
            <div class="row">
                <div class="ms-3">
                    <h3 class="mb-0 h4 font-weight-bolder">Dashboard</h3>
                    <p class="mb-4">
                        Check the credits analysis applied by guides.
                    </p>
                </div>
                <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                    <div class="card">
                        <div class="card-header p-2 ps-3">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <p class="text-sm mb-0 text-capitalize">Total Credit Applied Today</p>
                                    <h4 class="mb-4 mt-3">
                                        <?php

                                        $sql = "SELECT SUM(amount_credited) AS total_credited FROM credits
                                            WHERE DAY(date_applied) = DAY(CURRENT_DATE()) 
                                            AND MONTH(date_applied) = MONTH(CURRENT_DATE())
                                            AND YEAR(date_applied) = YEAR(CURRENT_DATE())";

                                        $result = $conn->query($sql);

                                        // Check if query ran successfully
                                        if (!$result) {
                                            die("Query error: " . $conn->error);
                                        }

                                        // Fetch result row
                                        $row = $result->fetch_assoc();

                                        // Safely get the total, handle NULL
                                        $totalCredited = $row['total_credited'] ?? 0;

                                        // Display result
                                        echo "£ " . number_format($totalCredited, 2);

                                        ?>
                                    </h4>
                                </div>
                                <div
                                    class="icon icon-md icon-shape bg-gradient-dark shadow-dark shadow text-center border-radius-lg">
                                    <i class="material-symbols-rounded opacity-10">weekend</i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                    <div class="card">
                        <div class="card-header p-2 ps-3">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <p class="text-sm mb-0 text-capitalize">Total Credit this Month</p>
                                    <h4 class="mb-4 mt-3">
                                        <?php

                                        $sql = "SELECT SUM(amount_credited) AS total_credited_month FROM credits
                                            WHERE
                                            MONTH(date_applied) = MONTH(CURRENT_DATE()) 
                                            AND YEAR(date_applied) = YEAR(CURRENT_DATE())";

                                        $result = $conn->query($sql);

                                        // Check if query ran successfully
                                        if (!$result) {
                                            die("Query error: " . $conn->error);
                                        }

                                        // Fetch result row
                                        $row = $result->fetch_assoc();

                                        // Safely get the total, handle NULL
                                        $totalCreditedMonth = $row['total_credited_month'] ?? 0;

                                        $_SESSION['total_monthly_credit'] = $totalCreditedMonth;

                                        // Display result
                                        echo "£ " . number_format($totalCreditedMonth, 2);

                                        ?>
                                    </h4>
                                </div>
                                <div
                                    class="icon icon-md icon-shape bg-gradient-dark shadow-dark shadow text-center border-radius-lg">
                                    <i class="material-symbols-rounded opacity-10">weekend</i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                    <div class="card">
                        <div class="card-header p-2 ps-3">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <p class="text-sm mb-0 text-capitalize">Total Guides This Month</p>
                                    <h4 class="mb-4 mt-3">
                                        <?php
                                        $sql = "SELECT COUNT(GUIDE_NAME) AS total_guides FROM CREDITS
                                             WHERE MONTH(date_applied) = MONTH(CURRENT_DATE())
                                            AND YEAR(date_applied) = YEAR(CURRENT_DATE())";

                                        $result = $conn->query($sql);

                                        // Check if query ran successfully
                                        if (!$result) {
                                            die("Query error: " . $conn->error);
                                        }

                                        // Fetch result row
                                        $row = $result->fetch_assoc();

                                        // Safely get the total, handle NULL
                                        $totalGuides = $row['total_guides'] ?? 0;

                                        // Display result
                                        echo " " . ($totalGuides);

                                        ?>
                                    </h4>
                                </div>
                                <div
                                    class="icon icon-md icon-shape bg-gradient-dark shadow-dark shadow text-center border-radius-lg">
                                    <i class="material-symbols-rounded opacity-10">person</i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                    <div class="card">
                        <div class="card-header p-2 ps-3">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <p class="text-sm mb-0 text-capitalize">Highest Credit Applied</p>
                                    <h4 class="mt-3 mb-4">
                                        <?php
                                        $sql = "SELECT MAX(AMOUNT_CREDITED) AS max_amount FROM CREDITS";
                                        $result = $conn->query($sql);

                                        // Check if query ran successfully
                                        if (!$result) {
                                            die("Query error: " . $conn->error);
                                        }

                                        // Fetch result row
                                        $row = $result->fetch_assoc();

                                        // Safely get the total, handle NULL
                                        $maximumCredit = $row['max_amount'] ?? 0;

                                        // Display result
                                        echo "£ " . ($maximumCredit = $row['max_amount'] ?? 0
                                        );

                                        ?>
                                    </h4>
                                </div>
                                <div
                                    class="icon icon-md icon-shape bg-gradient-dark shadow-dark shadow text-center border-radius-lg">
                                    <i class="material-symbols-rounded opacity-10">leaderboard</i>
                                </div>
                            </div>
                        </div>
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
                                            teams</span> this month
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="card-body px-0 pb-2 ">
                            <div class="table-responsive">
                                <table class="table align-items-center mb-0">
                                    <thead>
                                        <tr>
                                            <th
                                                class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                Reasons for credits</th>
                                            <th
                                                class="text-uppercase text-secondary text-xxs text-end font-weight-bolder opacity-7">
                                                Amount Credited</th>
                                            <th
                                                class="text-uppercase text-secondary text-xxs text-center font-weight-bolder opacity-7">
                                                Percentage</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($creditData as $reason => $amount):
                                            $percentage = ($totalCredit > 0) ? ($amount / $totalCredit) * 100 : 0;
                                        ?>
                                            <tr>
                                                <td>
                                                    <p class="text-sm font-weight-normal mb-0"><?= $reason ?></p>
                                                </td>
                                                <td>
                                                    <p class="text-sm text-end font-weight-bold mb-0">
                                                        £<?= number_format($amount, 2) ?></p>
                                                </td>
                                                <td>
                                                    <p class="text-sm text-center mb-0">
                                                        <?= number_format($percentage, 2) ?>%</p>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                        <tr class="border-top">
                                            <td><strong class="text-uppercase">Total</strong></td>
                                            <td class="text-end"><strong>£<?= number_format($totalCredit, 2) ?></strong>
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

            <div class="row">
                <div class="col-lg-12 col-md-12 mb-md-0 mb-4 pr-5">
                    <div class="card-body">
                        <canvas id="creditHistogram" height="120"></canvas>
                    </div>
                </div>
                <div class="col-lg-6 col-md-12 mb-md-0 mb-4 pr-5">
                    <div class="card-body">
                        <canvas id="creditHistogram" height="150"></canvas>
                    </div>
                </div>
            </div>

            <?php include 'includes/footer.php' ?>
        </div>
    </main>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('creditHistogram').getContext('2d');

        const data = {
            labels: <?php echo json_encode(array_keys($creditData)); ?>,
            datasets: [{
                label: 'Amount Credited (£)',
                data: <?php echo json_encode(array_values($creditData)); ?>,
                backgroundColor: ['#4e73df', '#1cc88a', '#f6c23e'],
                borderRadius: 3
            }]
        };

        const config = {
            type: 'bar',
            data: data,
            options: {
                responsive: true,
                plugins: {
                    title: {
                        display: true,
                        text: 'Monthly Credits by Reason'
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return '£ ' + context.raw.toFixed(2);
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return '£ ' + value;
                            }
                        }
                    }
                }
            }
        };

        new Chart(ctx, config);
    </script>
</body>
</html>