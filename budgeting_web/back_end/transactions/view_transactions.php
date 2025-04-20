<?php
session_start();
require_once '../includes/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../front_end/signin.html");
    exit;
}

$user_id = $_SESSION['user_id'];

// Fetch all transactions for this user
$stmt = $pdo->prepare("SELECT transaction_id, transaction_amount, transaction_location, transaction_date, category_id FROM Transactions WHERE user_id = ?");
$stmt->execute([$user_id]);
$transactions = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Your Transactions</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-5">
        <div class="card shadow">
            <div class="card-header bg-success text-white">
                <h4 class="mb-0">Your Transactions</h4>
            </div>
            <div class="card-body">
                <?php if (count($transactions) > 0): ?>
                    <table class="table table-striped table-bordered">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Location</th>
                                <th>Amount ($)</th>
                                <th>Date</th>
                                <th>Category ID</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($transactions as $tx): ?>
                                <tr>
                                    <td><?= htmlspecialchars($tx['transaction_id']) ?></td>
                                    <td><?= htmlspecialchars($tx['transaction_location']) ?></td>
                                    <td>$<?= number_format($tx['transaction_amount'], 2) ?></td>
                                    <td><?= htmlspecialchars($tx['transaction_date']) ?></td>
                                    <td><?= htmlspecialchars($tx['category_id']) ?></td>
                                    <td class="d-flex gap-2">
                                        <!-- Update Button -->
                                        <form action="update_transaction.php" method="GET" class="d-inline">
                                            <input type="hidden" name="transaction_id" value="<?= $tx['transaction_id'] ?>">
                                            <button type="submit" class="btn btn-success btn-sm">Update</button>
                                        </form>

                                        <!-- Delete Button -->
                                        <form action="delete_transaction.php" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this transaction?');">
                                            <input type="hidden" name="transaction_id" value="<?= $tx['transaction_id'] ?>">
                                            <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p class="text-muted">No transactions found.</p>
                <?php endif; ?>

                <div class="text-center mt-4">
                    <a href="../../front_end/home.html" class="btn btn-outline-primary btn-lg">← Back to Home</a>
                </div>
                <div class="text-center mt-3">
                    <a href="../auth/signout.php" class="btn btn-outline-danger btn-lg px-4">Logout</a>
                </div>
            </div>
            <script>
                const url = new URL(window.location);
                if (url.searchParams.get("success")) {
                    alert("Transaction added successfully!");
                    url.searchParams.delete("success");
                    window.history.replaceState({}, document.title, url.pathname);
                }
            </script>
            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
        </div>
    </div>
</body>

</html>