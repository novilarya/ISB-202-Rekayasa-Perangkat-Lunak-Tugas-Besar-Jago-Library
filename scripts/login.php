<?php
    include '../database/connection.php';
    if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

    $message = '';

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $email = $_POST['email'];
        $password = $_POST['password'];

        $stmt = $conn->prepare("SELECT * FROM users WHERE email = ? AND password = ?");
        $stmt->bind_param("ss", $email, $password);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result && $result->num_rows === 1) {
            $stmt2 = $conn->prepare("UPDATE users SET status = 'logged' WHERE email = ?");
            $stmt2->bind_param("s", $email);
            $stmt2->execute();
            $_SESSION['email'] = $email;
            header("Location: index.php");
            exit();
        } else {
            $message = '(Email atau Password Anda Salah!)';
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Library</title>
    <link rel="stylesheet" href="/css/styles.css">
    </head>
<header>
  <?php include "header.php" ?>
</header>
<body>
    <div class="container-login">
        <div class="form-card">
            <h1>Login</h1>
            <p>Selamat datang! Silakan login dengan akun Anda!</p>

            <form method="POST" action="">
                <div class="input-group">
                    <label for="email">Email</label>
                    <input type="email" name="email" id="email" placeholder="Masukkan email" required>
                </div>

                <div class="input-group">
                    <label for="password">Password</label>
                    <input type="password" name="password" id="password" placeholder="Masukkan password" required>
                </div>

                <div class="form-check">
                    <input type="checkbox" id="tampilkanPassword" onclick="password.type = this.checked ? 'text' : 'password'">  
                    <label class="form-check-label" for="showPassword">Tampilkan Password</label>
                </div>

                <button type="submit" class="signup-button">Login</button>
                <?php if ($message): ?>
                    <div class="message" style="text-align: center; margin-top: 10px"><?= $message ?></div>
                <?php endif; ?>
            </form>
            <p class="signin-link">Belum punya akun? <a href="registration.php">Daftar</a></p>
        </div>
    </div>
</body>

</html>
