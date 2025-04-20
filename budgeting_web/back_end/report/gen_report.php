<?php
session_start();
require_once '../includes/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../front_end/signin.html");
    exit;
}

$user_id = $_SESSION['user_id'];
$current_month = date('Y-m-01'); // First day of current month

// Fetch total income for the month
$incomeStmt = $pdo->prepare("SELECT SUM(income_amount) FROM Income WHERE user_id = ? AND MONTH(CURDATE()) = MONTH(NOW()) AND YEAR(CURDATE()) = YEAR(NOW())");
$incomeStmt->execute([$user_id]);
$total_income = $incomeStmt->fetchColumn() ?: 0;

// Fetch total transactions (expenses) for the month
$expenseStmt = $pdo->prepare("SELECT SUM(transaction_amount) FROM Transactions WHERE user_id = ? AND MONTH(transaction_date) = MONTH(NOW()) AND YEAR(transaction_date) = YEAR(NOW())");
$expenseStmt->execute([$user_id]);
$total_expenses = $expenseStmt->fetchColumn() ?: 0;

// Determine budget status
if ($total_expenses < $total_income) {
    $budget_status = "Under budget";
} elseif ($total_expenses == $total_income) {
    $budget_status = "On budget";
} else {
    $budget_status = "Over budget";
}

// Check if report already exists for this month
$checkStmt = $pdo->prepare("SELECT report_id FROM Report WHERE user_id = ? AND report_month = ?");
$checkStmt->execute([$user_id, $current_month]);

if ($checkStmt->fetch()) {
    // Update existing
    $updateStmt = $pdo->prepare("UPDATE Report SET total_income = ?, total_transactions = ?, budget_status = ? WHERE user_id = ? AND report_month = ?");
    $updateStmt->execute([$total_income, $total_expenses, $budget_status, $user_id, $current_month]);
} else {
    // Insert new
    $insertStmt = $pdo->prepare("INSERT INTO Report (user_id, report_month, total_income, total_transactions, budget_status) VALUES (?, ?, ?, ?, ?)");
    $insertStmt->execute([$user_id, $current_month, $total_income, $total_expenses, $budget_status]);
}

header("Location: report_data.php?generated=1");
exit;
