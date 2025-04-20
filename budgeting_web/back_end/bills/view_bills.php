<?php
session_start();
require_once '../includes/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../front_end/signin.html");
    exit;
}

$user_id = $_SESSION['user_id'];

$stmt = $pdo->prepare("SELECT * FROM Bills WHERE user_id = ? ORDER BY due_date ASC");
$stmt->execute([$user_id]);
$bills = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Your Bills</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-5">
        <div class="card shadow">
            <div class="card-header bg-success text-white">
                <h4 class="mb-0">Your Bills</h4>
            </div>
            <div class="card-body">
                <?php if (count($bills) > 0): ?>
                    <table class="table table-bordered table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Bill</th>
                                <th>Amount ($)</th>
                                <th>Due Date</th>
                                <th>Status</th>
                                <th>Payment Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($bills as $bill): ?>
                                <tr>
                                    <td><?= htmlspecialchars($bill['bill_name']) ?></td>
                                    <td>$<?= number_format($bill['bill_amount'], 2) ?></td>
                                    <td><?= htmlspecialchars($bill['due_date']) ?></td>
                                    <td>
                                        <?= $bill['payment_status'] ? 'Paid' : 'Due' ?>
                                    </td>
                                    <td><?= $bill['payment_date'] ?? '-' ?></td>
                                    <td class="d-flex gap-2">
                                        <?php if (!$bill['payment_status']): ?>
                                            <form action="mark_paid.php" method="POST">
                                                <input type="hidden" name="bill_id" value="<?= $bill['bill_id'] ?>">
                                                <button class="btn btn-warning btn-sm">Mark Paid</button>
                                            </form>
                                        <?php endif; ?>

                                        <form action="delete_bill.php" method="POST" onsubmit="return confirm('Delete this bill?');">
                                            <input type="hidden" name="bill_id" value="<?= $bill['bill_id'] ?>">
                                            <button class="btn btn-danger btn-sm">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p class="text-muted">No bills found.</p>
                <?php endif; ?>

                <div class="text-center mt-4">
                    <a href="../../front_end/home.html" class="btn btn-outline-primary btn-lg">← Back to Home</a>
                </div>
            </div>
        </div>
    </div>
</body>

</html>