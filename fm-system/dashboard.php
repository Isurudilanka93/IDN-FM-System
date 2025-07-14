<?php include 'includes/auth.php'; ?>
<!DOCTYPE html>
<html>
<head>
    <title>FM Dashboard</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-4">
    <h2>Welcome, <?= $_SESSION['user'] ?> (<?= $_SESSION['role'] ?>)</h2>
    <a href="logout.php" class="btn btn-sm btn-danger float-end">Logout</a>
    <div class="row mt-4">
        <div class="col-md-4"><div class="card p-3 text-center shadow-sm">Total Assets<br><strong>12</strong></div></div>
        <div class="col-md-4"><div class="card p-3 text-center shadow-sm">Open Work Orders<br><strong>5</strong></div></div>
        <div class="col-md-4"><div class="card p-3 text-center shadow-sm">Technicians<br><strong>3</strong></div></div>
    </div>
</div>
</body>
</html>
