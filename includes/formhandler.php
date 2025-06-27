<?php
include('conn.php');

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $guide_name      = $_POST["guide_name"] ?? null;
    $team_leader_id  = $_POST["team_leader"] ?? null;
    $acct_number     = $_POST["acct_number"] ?? null;
    $date_applied    = $_POST["date_applied"] ?? null;
    $amount_credited = $_POST["amount_credited"] ?? null;
    $reason_id       = $_POST["reason"] ?? null;

    if ($guide_name && $team_leader_id && $acct_number && $date_applied && $amount_credited && $reason_id) {
        // Prepare the SQL statement
        $stmt = $conn->prepare("INSERT INTO credits (guide_name, team_leader_id, acct_number, date_applied, amount_credited, reason_id) VALUES (?, ?, ?, ?, ?, ?)");

        if ($stmt === false) {
            http_response_code(500);
            echo "Failed to prepare statement: " . $conn->error;
            exit;
        }

        // Bind parameters safely
        $stmt->bind_param("sissds", $guide_name, $team_leader_id, $acct_number, $date_applied, $amount_credited, $reason_id);

        if ($stmt->execute()) {
            http_response_code(200);
            echo "✅ Credit submitted for account #$acct_number.";
        } else {
            http_response_code(500);
            echo "❌ DB error: " . $stmt->error;
        }

        $stmt->close();
        $conn->close();
    } else {
        http_response_code(400);
        echo "❌ Missing required fields.";
    }
} else {
    http_response_code(405);
    echo "❌ Invalid request method.";
}
