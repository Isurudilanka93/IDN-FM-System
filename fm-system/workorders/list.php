<?php
require_once '../db/connection.php';
$result = mysqli_query($conn, "SELECT * FROM work_orders");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Work Orders</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- SheetJS -->
  <script src="https://cdn.sheetjs.com/xlsx-latest/package/dist/xlsx.full.min.js"></script>
  <!-- jsPDF -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.25/jspdf.plugin.autotable.min.js"></script>
</head>
<body>
  <div class="container mt-5">
    <h2 class="mb-4">Work Orders</h2>

    <div class="mb-3">
      <a href="add.php" class="btn btn-primary">+ Add New</a>
      <button onclick="exportTableToExcel('dataTable', 'work_orders')" class="btn btn-success">Export Excel</button>
      <button onclick="exportPDF()" class="btn btn-danger">Export PDF</button>
    </div>

    <div class="table-responsive">
      <table class="table table-bordered table-striped" id="dataTable">
        <thead class="table-dark">
          <tr>
            <th>ID</th>
            <th>Title</th>
            <th>Description</th>
            <th>Status</th>
            <th>Priority</th>
            <th>Created</th>
            <th>Attend</th>
            <th>Closure</th>
            <th>Actions</th> <!-- 👈 Make sure this is the 9th column -->
          </tr>
        </thead>
        <tbody>
          <?php while($row = mysqli_fetch_assoc($result)) { ?>
            <tr>
              <td><?= $row['id'] ?></td>
              <td><?= htmlspecialchars($row['title']) ?></td>
              <td><?= htmlspecialchars($row['description']) ?></td>
              <td><?= $row['status'] ?></td>
              <td><?= $row['priority'] ?></td>
              <td><?= $row['created_at'] ?></td>
              <td><?= $row['attend_Time'] ?></td>
              <td><?= $row['closure_Time'] ?></td>
              <td> <!-- 👈 Actions under this column -->
                <a href="edit.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-warning me-1">Edit</a>
                <a href="delete.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</a>
              </td>
            </tr>
          <?php } ?>
        </tbody>
      </table>
    </div>
  </div>

  <!-- Export Scripts -->
  <script>
    function exportTableToExcel(tableID, filename = '') {
      const wb = XLSX.utils.table_to_book(document.getElementById(tableID), { sheet: "Sheet1" });
      XLSX.writeFile(wb, filename + ".xlsx");
    }

    async function exportPDF() {
      const { jsPDF } = window.jspdf;
      const doc = new jsPDF();
      doc.text("Work Orders", 10, 10);
      doc.autoTable({ html: '#dataTable', startY: 20 });
      doc.save("work_orders.pdf");
    }
  </script>
</body>
</html>
