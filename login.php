<?php
// login.php
// User login with password verification
session_start();
include 'db.php';
 
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];
 
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
 
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['name'];
        echo "<script>window.location.href = 'index.php';</script>";
    } else {
        echo "<script>alert('Invalid credentials');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - OLX Clone</title>
    <style>
        /* Internal CSS - Matching theme, real OLX feel */
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
        <h2>Login</h2>
        <form method="POST">
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Login</button>
        </form>
        <a href="signup.php">Don't have an account? Signup</a>
    </div>
    <script>
        // Internal JS
        console.log('Login page loaded');
    </script>
</body>
</html>
