<?php
require 'db.php';

$id = $_GET['id'] ?? null;
$asset = null;

if ($id) {
    $stmt = $conn->prepare("SELECT * FROM assets WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $asset = $result->fetch_assoc();
    $stmt->close();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $category = $_POST['category'];
    $status = $_POST['status'];
    $purchase_date = $_POST['purchase_date'];
    $value = $_POST['value'];
    $description = $_POST['description'];

    if ($id) {
        // Update existing
        $stmt = $conn->prepare("UPDATE assets SET name=?, category=?, status=?, purchase_date=?, value=?, description=? WHERE id=?");
        $stmt->bind_param("ssssdis", $name, $category, $status, $purchase_date, $value, $description, $id);
        $stmt->execute();
        $stmt->close();
    } else {
        // Insert new
        $stmt = $conn->prepare("INSERT INTO assets (name, category, status, purchase_date, value, description) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssds", $name, $category, $status, $purchase_date, $value, $description);
        $stmt->execute();
        $stmt->close();
    }
    header("Location: asset_list.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <title><?= $id ? "Edit Asset" : "Add Asset" ?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4">
  <div class="container">
    <h2><?= $id ? "Edit Asset" : "Add Asset" ?></h2>
    <form method="post">
      <div class="mb-3">
        <label class="form-label">Asset Name</label>
        <input type="text" name="name" class="form-control" required value="<?= htmlspecialchars($asset['name'] ?? '') ?>">
      </div>
      <div class="mb-3">
        <label class="form-label">Category</label>
        <input type="text" name="category" class="form-control" value="<?= htmlspecialchars($asset['category'] ?? '') ?>">
      </div>
      <div class="mb-3">
        <label class="form-label">Status</label>
        <select name="status" class="form-select" required>
          <?php
          $statuses = ['Active', 'Inactive', 'Maintenance'];
          foreach ($statuses as $status_option) {
              $selected = ($asset['status'] ?? '') === $status_option ? 'selected' : '';
              echo "<option value=\"$status_option\" $selected>$status_option</option>";
          }
          ?>
        </select>
      </div>
      <div class="mb-3">
        <label class="form-label">Purchase Date</label>
        <input type="date" name="purchase_date" class="form-control" value="<?= $asset['purchase_date'] ?? '' ?>">
      </div>
      <div class="mb-3">
        <label class="form-label">Value</label>
        <input type="number" step="0.01" name="value" class="form-control" value="<?= $asset['value'] ?? '' ?>">
      </div>
      <div class="mb-3">
        <label class="form-label">Description</label>
        <textarea name="description" class="form-control"><?= htmlspecialchars($asset['description'] ?? '') ?></textarea>
      </div>
      <button type="submit" class="btn btn-primary"><?= $id ? "Update" : "Add" ?></button>
      <a href="asset_list.php" class="btn btn-secondary">Cancel</a>
    </form>
  </div>
</body>
</html>
