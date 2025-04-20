<?php
session_start();
require_once '../includes/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../front_end/signin.html");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'];
    $transaction_id = $_POST['transaction_id'] ?? null;

    if ($transaction_id) {
        // Ensure transaction belongs to this user
        $checkStmt = $pdo->prepare("SELECT * FROM Transactions WHERE transaction_id = ? AND user_id = ?");
        $checkStmt->execute([$transaction_id, $user_id]);
        $transaction = $checkStmt->fetch();

        if ($transaction) {
            $stmt = $pdo->prepare("DELETE FROM Transactions WHERE transaction_id = ?");
            if ($stmt->execute([$transaction_id])) {
                header("Location: view_transactions.php?deleted=1");
                exit;
            } else {
                header("Location: view_transactions.php?error=delete_failed");
                exit;
            }
        } else {
            header("Location: view_transactions.php?error=unauthorized");
            exit;
        }
    } else {
        header("Location: view_transactions.php?error=missing_id");
        exit;
    }
}
