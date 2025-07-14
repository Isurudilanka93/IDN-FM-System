<?php include 'includes/auth.php'; include 'includes/db.php'; ?>
<!DOCTYPE html>
<html>
<head>
    <title>Work Orders</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-4">
    <h2>Work Orders</h2>
    <a href="dashboard.php" class="btn btn-sm btn-secondary mb-2">Back to Dashboard</a>
    <form method="POST" class="mb-3">
        <div class="row">
            <div class="col-md-4"><input name="title" class="form-control" placeholder="Work Order Title" required></div>
            <div class="col-md-3">
                <select name="status" class="form-control" required>
                    <option value="Open">Open</option>
                    <option value="In Progress">In Progress</option>
                    <option value="Completed">Completed</option>
                </select>
            </div>
            <div class="col-md-3"><input name="assigned_to" class="form-control" placeholder="Assigned To"></div>
            <div class="col-md-2"><button class="btn btn-primary w-100">Add</button></div>
        </div>
    </form>
    <?php
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $stmt = $pdo->prepare("INSERT INTO workorders (title, status, assigned_to) VALUES (?, ?, ?)");
        $stmt->execute([$_POST['title'], $_POST['status'], $_POST['assigned_to']]);
    }
    $orders = $pdo->query("SELECT * FROM workorders ORDER BY id DESC")->fetchAll();
    ?>
    <table class="table table-bordered">
        <tr><th>ID</th><th>Title</th><th>Status</th><th>Assigned To</th></tr>
        <?php foreach ($orders as $order): ?>
            <tr>
                <td><?= $order['id'] ?></td>
                <td><?= $order['title'] ?></td>
                <td><?= $order['status'] ?></td>
                <td><?= $order['assigned_to'] ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
</div>
</body>
</html>
