<?php include 'includes/auth.php'; include 'includes/db.php'; ?>
<!DOCTYPE html>
<html>
<head>
    <title>Assets</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-4">
    <h2>Assets</h2>
    <a href="dashboard.php" class="btn btn-sm btn-secondary mb-2">Back to Dashboard</a>
    <form method="POST" class="mb-3">
        <div class="row">
            <div class="col-md-4"><input name="name" class="form-control" placeholder="Asset Name" required></div>
            <div class="col-md-4"><input name="location" class="form-control" placeholder="Location" required></div>
            <div class="col-md-4"><button class="btn btn-primary w-100">Add Asset</button></div>
        </div>
    </form>
    <?php
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $stmt = $pdo->prepare("INSERT INTO assets (name, location) VALUES (?, ?)");
        $stmt->execute([$_POST['name'], $_POST['location']]);
    }
    $assets = $pdo->query("SELECT * FROM assets ORDER BY id DESC")->fetchAll();
    ?>
    <table class="table table-bordered">
        <tr><th>ID</th><th>Name</th><th>Location</th></tr>
        <?php foreach ($assets as $asset): ?>
            <tr>
                <td><?= $asset['id'] ?></td>
                <td><?= $asset['name'] ?></td>
                <td><?= $asset['location'] ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
</div>
</body>
</html>
