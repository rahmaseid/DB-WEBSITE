<?php
session_start();
require_once '../includes/db.php';

if (!isset($_SESSION['user_id']) || !isset($_POST['bill_id'])) {
    header("Location: ../front_end/signin.html");
    exit;
}

$bill_id = $_POST['bill_id'];
$user_id = $_SESSION['user_id'];
$today = date('Y-m-d');

$stmt = $pdo->prepare("UPDATE Bills SET payment_status = 1, payment_date = ? WHERE bill_id = ? AND user_id = ?");
$stmt->execute([$today, $bill_id, $user_id]);

header("Location: view_bills.php?updated=1");
exit;
