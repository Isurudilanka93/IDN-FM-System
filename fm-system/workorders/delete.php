<?php
require_once '../db/connection.php';
$id = intval($_GET['id']);
mysqli_query($conn, "DELETE FROM work_orders WHERE id = $id");
header("Location: list.php");
exit();
?>
