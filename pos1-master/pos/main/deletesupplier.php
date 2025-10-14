<?php
include('../connect.php');
$id = $_GET['id'];
$result = $db->prepare("DELETE FROM supliers WHERE suplier_id= :memid");
$result->bindParam(':memid', $id);
$result->execute();

// Redirect back to supplier page after successful deletion
header("Location: supplier.php");
exit();
