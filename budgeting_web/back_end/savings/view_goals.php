<?php
session_start();
require_once '../includes/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../front_end/signin.html");
    exit;
}

$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT * FROM SavingGoals WHERE user_id = ?");
$stmt->execute([$user_id]);
$goals = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Your Savings Goals</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-5">
        <div class="card shadow">
            <div class="card-header bg-success text-white">
                <h4 class="mb-0">Savings Goals</h4>
            </div>
            <div class="card-body">

                <?php if (isset($_GET['success'])): ?>
                    <div class="alert alert-success">New goal added!</div>
                <?php elseif (isset($_GET['updated'])): ?>
                    <div class="alert alert-success">Goal updated!</div>
                <?php elseif (isset($_GET['error'])): ?>
                    <div class="alert alert-danger"><?= htmlspecialchars($_GET['error']) ?></div>
                <?php endif; ?>

                <form action="add_goal.php" method="POST" class="mb-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <input type="text" name="goal_name" class="form-control" placeholder="Goal Name" required>
                        </div>
                        <div class="col-md-4">
                            <input type="number" step="0.01" name="target_amount" class="form-control" placeholder="Target Amount ($)" required>
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-success w-100">Add Goal</button>
                        </div>
                    </div>
                </form>

                <?php if (count($goals) > 0): ?>
                    <table class="table table-bordered">
                        <thead class="table-light">
                            <tr>
                                <th>Goal</th>
                                <th>Target ($)</th>
                                <th>Current ($)</th>
                                <th>Progress</th>
                                <th>Update</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($goals as $goal): ?>
                                <tr>
                                    <td><?= htmlspecialchars($goal['goal_name']) ?></td>
                                    <td>$<?= number_format($goal['target_amount'], 2) ?></td>
                                    <td>$<?= number_format($goal['current_amount'], 2) ?></td>
                                    <td>
                                        <?php
                                        $percent = $goal['target_amount'] > 0
                                            ? min(100, ($goal['current_amount'] / $goal['target_amount']) * 100)
                                            : 0;
                                        ?>
                                        <div class="progress">
                                            <div class="progress-bar" style="width: <?= $percent ?>%">
                                                <?= round($percent) ?>%
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <form action="update_goal.php" method="POST" class="d-flex">
                                            <input type="hidden" name="goal_id" value="<?= $goal['goal_id'] ?>">
                                            <input type="number" step="0.01" name="current_amount" value="<?= $goal['current_amount'] ?>" class="form-control me-2" style="max-width: 100px;">
                                            <button class="btn btn-primary btn-sm">Update</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p class="text-muted">No savings goals set yet.</p>
                <?php endif; ?>

                <div class="text-center mt-4">
                    <a href="../../front_end/home.html" class="btn btn-outline-primary btn-lg">← Back to Home</a>
                </div>

            </div>
        </div>
    </div>
</body>

</html>