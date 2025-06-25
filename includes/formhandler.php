<?php 
include ('conn.php');

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $guide_name = $_POST["guide_name"] ?? null;
    $team_leader = $_POST["team_leader"] ?? null;
    $acct_number = $_POST["acct_number"] ?? null;
    $date_applied = $_POST["date_applied"] ?? null;
    $amount_credited = $_POST["amount_credited"] ?? null;
    $reason = $_POST["reason"] ?? null;

    // Continue with DB insert...
    $stmt = $conn->prepare("INSERT INTO credits (guide_name, team_leader, acct_number, date_applied, amount_credited, reason) VALUES (?,?,?,?,?,?)");
    
    $stmt->bind_param("ssssds", $guide_name, $team_leader, $acct_number, $date_applied, $amount_credited, $reason);

    if ($stmt->execute()) {
        echo "New record created successfully!";

        header("Location: ../index.php");
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
} 
    





?>
