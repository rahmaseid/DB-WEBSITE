<?php
session_start();
require_once '../includes/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../front_end/signin.html");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'];
    $goal_name = trim($_POST['goal_name']);
    $target_amount = floatval($_POST['target_amount']);

    $stmt = $pdo->prepare("INSERT INTO SavingGoals (user_id, goal_name, target_amount) VALUES (?, ?, ?)");

    try {
        $stmt->execute([$user_id, $goal_name, $target_amount]);
        header("Location: view_goals.php?success=1");
        exit;
    } catch (PDOException $e) {
        header("Location: view_goals.php?error=" . urlencode($e->getMessage()));
        exit;
    }
}
