<?php
include './database/connection.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$message = '';
$kode = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ? AND password = ?");
    $stmt->bind_param("ss", $email, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows === 1) {
        $user = $result->fetch_assoc();
        $stmt2 = $conn->prepare("UPDATE users SET status = 'logged' WHERE email = ?");
        $stmt2->bind_param("s", $email);
        $stmt2->execute();
        $_SESSION['email'] = $email;
        $role = $user['role'];
        if ($role === 'dosen' || $role === 'mahasiswa') {
            header("Location: index.php");
        } else {
            header("Location: ../admin/authentication.php");
        }
        exit();
    } else {
        $message = '(Email atau Password Anda Salah!)';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>BookSaw - Free Book Store HTML CSS Template</title>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="format-detection" content="telephone=no">
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name="author" content="">
	<meta name="keywords" content="">
	<meta name="description" content="">

	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet"
		integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">

	<link rel="stylesheet" type="text/css" href="css/normalize.css">
	<link rel="stylesheet" type="text/css" href="icomoon/icomoon.css">
	<link rel="stylesheet" type="text/css" href="css/vendor.css">
	<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
    <header>
        <?php include "header.php"; ?>
    </header>

    <div class="container">
        <div class="login-container">
            <h1 class="text-center">Login</h1>
            <p class="text-center">Selamat datang! Silakan login dengan akun Anda.</p>

            <form method="POST" action="">
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" name="email" id="email" placeholder="Masukkan email" required>
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" name="password" id="password" placeholder="Masukkan password" required>
                </div>

                <div class="form-check mb-3">
                    <input type="checkbox" class="form-check-input" id="tampilkanPassword" onclick="togglePassword()">
                    <label class="form-check-label" for="tampilkanPassword">Tampilkan Password</label>
                </div>

                <button type="submit" class="btn btn-primary-1 rounded 5">Login</button>

                <?php if ($message): ?>
                    <div class="text-center mt-3 message"><?= $message ?></div>
                <?php endif; ?>
            </form>

            <div class="signup-link">
                Belum punya akun? <a href="registration.php">Daftar</a>
            </div>
        </div>
    </div>

    <script src="js/jquery-1.11.0.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"
		integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm"
		crossorigin="anonymous"></script>
	<script src="js/plugins.js"></script>
	<script src="js/script.js"></script>

    <script>
        function togglePassword() {
            const pass = document.getElementById("password");
            pass.type = pass.type === "password" ? "text" : "password";
        }
    </script>

</body>
</html>