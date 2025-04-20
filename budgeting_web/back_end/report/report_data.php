<?php
session_start();
require_once '../includes/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../front_end/signin.html");
    exit;
}

$user_id = $_SESSION['user_id'];

$stmt = $pdo->prepare("SELECT * FROM Report WHERE user_id = ? ORDER BY report_month DESC LIMIT 1");
$stmt->execute([$user_id]);
$report = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Monthly Report</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-5">
        <div class="card shadow">
            <div class="card-header bg-success text-white">
                <h4 class="mb-0">Monthly Report</h4>
            </div>
            <div class="card-body">
                <?php if ($report): ?>
                    <table class="table table-bordered">
                        <tr>
                            <th>Month</th>
                            <td><?= date('F Y', strtotime($report['report_month'])) ?></td>
                        </tr>
                        <tr>
                            <th>Total Income</th>
                            <td>$<?= number_format($report['total_income'], 2) ?></td>
                        </tr>
                        <tr>
                            <th>Total Expenses</th>
                            <td>$<?= number_format($report['total_transactions'], 2) ?></td>
                        </tr>
                        <tr>
                            <th>Budget Status</th>
                            <td><?= htmlspecialchars($report['budget_status']) ?></td>
                        </tr>
                    </table>
                <?php else: ?>
                    <p class="text-muted">No report found. Please <a href="generate_report.php">generate a report</a>.</p>
                <?php endif; ?>

                <div class="text-center mt-4">
                    <a href="../../front_end/home.html" class="btn btn-outline-primary">← Back to Home</a>
                </div>
            </div>
        </div>
    </div>
</body>

</html>