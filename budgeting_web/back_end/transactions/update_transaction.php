<?php
session_start();
require_once '../includes/db.php';

// Redirect if user not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../front_end/signin.html");
    exit;
}

$user_id = $_SESSION['user_id'];

// If POST: Update transaction in DB
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $transaction_id = intval($_POST['transaction_id']);
    $location = trim($_POST['expense_name']);
    $amount = floatval($_POST['expense_amount']);
    $category_id = intval($_POST['category_id']);

    $stmt = $pdo->prepare("UPDATE Transactions SET transaction_location = ?, transaction_amount = ?, category_id = ? WHERE transaction_id = ? AND user_id = ?");
    $success = $stmt->execute([$location, $amount, $category_id, $transaction_id, $user_id]);

    if ($success) {
        header("Location: view_transactions.php?success=updated");
        exit;
    } else {
        echo "Failed to update transaction.";
    }
}

// If GET: Load transaction for editing
if (!isset($_GET['transaction_id'])) {
    die("No transaction ID provided.");
}

$transaction_id = intval($_GET['transaction_id']);
$stmt = $pdo->prepare("SELECT * FROM Transactions WHERE transaction_id = ? AND user_id = ?");
$stmt->execute([$transaction_id, $user_id]);
$transaction = $stmt->fetch();

if (!$transaction) {
    die("Transaction not found or access denied.");
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Update Transaction</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../styling/style.css">
</head>

<body>
    <div class="container mt-5">
        <div class="card shadow-sm">
            <div class="card-header bg-success text-white">
                <h4 class="mb-0">Update Transaction</h4>
            </div>
            <div class="card-body">
                <form method="POST" action="update_transaction.php">
                    <input type="hidden" name="transaction_id" value="<?= $transaction['transaction_id'] ?>">

                    <div class="mb-3">
                        <label class="form-label required">Transaction Type</label>
                        <select class="form-select" name="category_id" required>
                            <option value="">Select Category</option>
                            <option value="1" <?= $transaction['category_id'] == 1 ? 'selected' : '' ?>>Retail</option>
                            <option value="2" <?= $transaction['category_id'] == 2 ? 'selected' : '' ?>>Grocery</option>
                            <option value="3" <?= $transaction['category_id'] == 3 ? 'selected' : '' ?>>Tech & Electronics</option>
                            <option value="4" <?= $transaction['category_id'] == 4 ? 'selected' : '' ?>>Pharmacy</option>
                            <option value="5" <?= $transaction['category_id'] == 5 ? 'selected' : '' ?>>Books & Supplies</option>
                            <option value="6" <?= $transaction['category_id'] == 6 ? 'selected' : '' ?>>Clothing & Accessories</option>
                            <option value="7" <?= $transaction['category_id'] == 7 ? 'selected' : '' ?>>Home & Garden</option>
                            <option value="8" <?= $transaction['category_id'] == 8 ? 'selected' : '' ?>>Beauty & Personal Care</option>
                            <option value="9" <?= $transaction['category_id'] == 9 ? 'selected' : '' ?>>Automotive</option>
                            <option value="10" <?= $transaction['category_id'] == 10 ? 'selected' : '' ?>>Pet Supplies</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label required">Expense Name</label>
                        <input type="text" class="form-control" name="expense_name" value="<?= htmlspecialchars($transaction['transaction_location']) ?>" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label required">Amount ($)</label>
                        <input type="number" step="0.01" class="form-control" name="expense_amount" value="<?= htmlspecialchars($transaction['transaction_amount']) ?>" required>
                    </div>

                    <button type="submit" class="btn btn-success">Save Changes</button>
                    <a href="view_transactions.php" class="btn btn-secondary ms-2">Cancel</a>
                </form>
            </div>
        </div>
    </div>
</body>

</html>