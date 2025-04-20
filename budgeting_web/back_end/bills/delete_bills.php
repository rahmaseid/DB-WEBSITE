<?php
session_start();
require_once '../includes/db.php';

if (!isset($_SESSION['user_id']) || !isset($_POST['bill_id'])) {
    header("Location: ../front_end/signin.html");
    exit;
}

$bill_id = $_POST['bill_id'];
$user_id = $_SESSION['user_id'];

$stmt = $pdo->prepare("DELETE FROM Bills WHERE bill_id = ? AND user_id = ?");
$stmt->execute([$bill_id, $user_id]);

header("Location: view_bills.php?deleted=1");
exit;
