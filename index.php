<?php
session_start();
include 'koneksi.php';

if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $result = mysqli_query($conn, "SELECT * FROM users WHERE username='$username'");
    $row = mysqli_fetch_assoc($result);

    if ($row && password_verify($password, $row['password'])) {
        $_SESSION['login'] = true;
        $_SESSION['nama'] = $row['nama'];
        header("Location: dashboard.php");
        exit;
    } else {
        echo "<script>alert('Username atau Password salah!');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login | Website Perpustakaan</title>
<style>
    body {
        font-family: 'Segoe UI', sans-serif;
        background: url('bg2.jpg') no-repeat center center/cover;
        height: 100vh;
        display: flex;
        justify-content: center;
        align-items: center;
        margin: 0;
    }
    .overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0,0,0,0.4);
    }
    .container {
        position: relative;
        background: rgba(255, 255, 255, 0.95);
        width: 380px;
        padding: 40px 30px;
        border-radius: 15px;
        box-shadow: 0 8px 25px rgba(0,0,0,0.2);
        text-align: center;
        z-index: 1;
    }
    .logo {
        width: 120px;
        height: 120px;
        margin-bottom: 15px;
    }
    h2 {
        margin-bottom: 20px;
        color: #333;
    }
    input {
        width: 90%;
        padding: 12px;
        margin: 8px 0;
        border: 1px solid #ccc;
        border-radius: 8px;
        font-size: 14px;
    }
    button {
        width: 100%;
        padding: 12px;
        background: linear-gradient(135deg, #2575fc, #6a11cb);
        color: white;
        border: none;
        border-radius: 8px;
        font-size: 16px;
        cursor: pointer;
        margin-top: 10px;
        transition: 0.3s;
    }
    button:hover {
        background: linear-gradient(135deg, #1e5ad8, #5811a6);
    }
    p {
        margin-top: 15px;
        font-size: 14px;
        color: #333;
    }
    a {
        color: #6a11cb;
        text-decoration: none;
    }
    a:hover {
        text-decoration: underline;
    }
</style>
</head>
<body>
    <div class="overlay"></div>
    <div class="container">
        <img src="logo.png" alt="Logo Unhan" class="logo">
        <h2>Login Akun</h2>
        <form method="POST">
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit" name="login">Masuk</button>
        </form>
        <p>Belum punya akun? <a href="register.php">Daftar di sini</a></p>
    </div>
</body>
</html>
