<?php
// send_message.php
// Send message to seller
session_start();
if (!isset($_SESSION['user_id'])) {
    echo "<script>window.location.href = 'login.php';</script>";
    exit;
}
include 'db.php';
 
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $to_user = $_POST['to_user'];
    $listing_id = $_POST['listing_id'];
    $message = $_POST['message'];
 
    $stmt = $pdo->prepare("INSERT INTO messages (from_user, to_user, listing_id, message) VALUES (?, ?, ?, ?)");
    $stmt->execute([$_SESSION['user_id'], $to_user, $listing_id, $message]);
    echo "<script>alert('Message sent!'); window.location.href = 'view_listing.php?id=$listing_id';</script>";
}
?>
