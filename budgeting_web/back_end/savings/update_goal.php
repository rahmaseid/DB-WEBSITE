<?php
session_start();
require_once '../includes/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../front_end/signin.html");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'];
    $goal_id = $_POST['goal_id'];
    $current_amount = floatval($_POST['current_amount']);

    $stmt = $pdo->prepare("UPDATE SavingGoals SET current_amount = ? WHERE goal_id = ? AND user_id = ?");

    try {
        $stmt->execute([$current_amount, $goal_id, $user_id]);
        header("Location: view_goals.php?updated=1");
        exit;
    } catch (PDOException $e) {
        header("Location: view_goals.php?error=" . urlencode($e->getMessage()));
        exit;
    }
}
