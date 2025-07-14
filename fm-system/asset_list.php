<?php
require 'db.php';

// Filters
$search = $_GET['search'] ?? '';
$status_filter = $_GET['status'] ?? '';
$start_date = $_GET['start_date'] ?? '';
$end_date = $_GET['end_date'] ?? '';

// Build SQL WHERE clauses
$where = [];
$params = [];
$types = "";

if ($search) {
    $where[] = "(name LIKE ? OR category LIKE ?)";
    $params[] = "%$search%";
    $params[] = "%$search%";
    $types .= "ss";
}
if ($status_filter && in_array($status_filter, ['Active', 'Inactive', 'Maintenance'])) {
    $where[] = "status = ?";
    $params[] = $status_filter;
    $types .= "s";
}
if ($start_date) {
    $where[] = "purchase_date >= ?";
    $params[] = $start_date;
    $types .= "s";
}
if ($end_date) {
    $where[] = "purchase_date <= ?";
    $params[] = $end_date;
    $types .= "s";
}

$where_sql = $where ? "WHERE " . implode(" AND ", $where) : "";

// Sorting
$allowed_sort = ['name', 'category', 'status', 'purchase_date', 'value'];
$sort = $_GET['sort'] ?? 'name';
$order = $_GET['order'] ?? 'asc';

if (!in_array($sort, $allowed_sort)) $sort = 'name';
if (!in_array(strtolower($order), ['asc', 'desc'])) $order = 'asc';

$order_sql = "ORDER BY $sort $order";

$stmt = $conn->prepare("SELECT * FROM assets $where_sql $order_sql");
if ($params) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <title>Asset List</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    th a { text-decoration: none; }
  </style>
</head>
<body class="p-4">
  <div class="container">
    <h2>Assets</h2>
    <a href="asset_form.php" class="btn btn-success mb-3">Add New Asset</a>

    <form class="row g-3 mb-3" method="get" action="">
      <div class="col-md-3">
        <input type="text" name="search" class="form-control" placeholder="Search name or category" value="<?= htmlspecialchars($search) ?>">
      </div>
      <div class="col-md-2">
        <select name="status" class="form-select">
          <option value="">All Statuses</option>
          <?php foreach (['Active', 'Inactive', 'Maintenance'] as $st): 
            $sel = $status_filter === $st ? 'selected' : '';
          ?>
            <option value="<?= $st ?>" <?= $sel ?>><?= $st ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="col-md-2">
        <input type="date" name="start_date" class="form-control" placeholder="Start Purchase Date" value="<?= $start_date ?>">
      </div>
      <div class="col-md-2">
        <input type="date" name="end_date" class="form-control" placeholder="End Purchase Date" value="<?= $end_date ?>">
      </div>
      <div class="col-md-3">
        <button type="submit" class="btn btn-primary">Filter</button>
        <a href="asset_list.php" class="btn btn-secondary">Reset</a>
      </div>
    </form>

    <table class="table table-bordered table-striped">
      <thead>
        <tr>
          <?php
          $columns = [
            'name' => 'Name',
            'category' => 'Category',
            'status' => 'Status',
            'purchase_date' => 'Purchase Date',
            'value' => 'Value',
          ];
          foreach ($columns as $col => $label) {
            $new_order = ($sort === $col && strtolower($order) === 'asc') ? 'desc' : 'asc';
            $arrow = '';
            if ($sort === $col) {
              $arrow = strtolower($order) === 'asc' ? '↑' : '↓';
            }
            // Preserve filters when sorting links clicked
            $query_params = $_GET;
            $query_params['sort'] = $col;
            $query_params['order'] = $new_order;
            $link = '?' . http_build_query($query_params);

            echo "<th><a href=\"$link\">$label $arrow</a></th>";
          }
          ?>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
          <td><?= htmlspecialchars($row['name']) ?></td>
          <td><?= htmlspecialchars($row['category']) ?></td>
          <td><?= htmlspecialchars($row['status']) ?></td>
          <td><?= htmlspecialchars($row['purchase_date']) ?></td>
          <td><?= number_format($row['value'], 2) ?></td>
          <td>
            <a href="asset_form.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-warning">Edit</a>
            <a href="asset_delete.php?id=<?= $row['id'] ?>" onclick="return confirm('Are you sure?')" class="btn btn-sm btn-danger">Delete</a>
          </td>
        </tr>
        <?php endwhile; ?>
      </tbody>
    </table>

    <a href="generate_report.php" class="btn btn-info">Generate PDF Summary Report</a>
  </div>
</body>
</html>
