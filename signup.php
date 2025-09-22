<?php
// signup.php
// User signup with password hashing
session_start();
include 'db.php';
 
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $location = $_POST['location'];
 
    try {
        $stmt = $pdo->prepare("INSERT INTO users (email, password, name, phone, location) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$email, $password, $name, $phone, $location]);
        echo "<script>alert('Signup successful!'); window.location.href = 'login.php';</script>";
    } catch (PDOException $e) {
        echo "<script>alert('Error: " . addslashes($e->getMessage()) . "');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signup - OLX Clone</title>
    <style>
        /* Internal CSS - Professional and appealing */
        body { font-family: Arial, sans-serif; background-color: #f4f4f4; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .form-container { background-color: white; padding: 40px; border-radius: 8px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); width: 300px; }
        .form-container h2 { text-align: center; color: #002f34; }
        .form-container input { width: 100%; padding: 10px; margin: 10px 0; border: 1px solid #ccc; border-radius: 4px; }
        .form-container button { width: 100%; padding: 10px; background-color: #002f34; color: white; border: none; border-radius: 4px; cursor: pointer; }
        .form-container button:hover { background-color: #004f54; }
        .form-container a { display: block; text-align: center; margin-top: 10px; color: #002f34; }
        @media (max-width: 768px) { .form-container { width: 90%; padding: 20px; } }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Signup</h2>
        <form method="POST">
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <input type="text" name="name" placeholder="Name" required>
            <input type="text" name="phone" placeholder="Phone">
            <input type="text" name="location" placeholder="Location">
            <button type="submit">Signup</button>
        </form>
        <a href="login.php">Already have an account? Login</a>
    </div>
    <script>
        // Internal JS
        console.log('Signup page loaded');
    </script>
</body>
</html>
