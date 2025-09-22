<?php
// delete_ad.php
// Delete ad
session_start();
if (!isset($_SESSION['user_id'])) {
    echo "<script>window.location.href = 'login.php';</script>";
    exit;
}
include 'db.php';
 
$id = $_GET['id'];
$stmt = $pdo->prepare("DELETE FROM listings WHERE id = ? AND user_id = ?");
$stmt->execute([$id, $_SESSION['user_id']]);
echo "<script>alert('Ad deleted!'); window.location.href = 'profile.php';</script>";
?>
