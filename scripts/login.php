<?php
    include '../database/connection.php';

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $username = mysqli_real_escape_string($conn, $_POST['username']);
        $password = mysqli_real_escape_string($conn, $_POST['password']);

        $query = "SELECT * FROM user WHERE username='$username' AND password='$password'";
        $result = mysqli_query($conn, $query);
        
        if (mysqli_num_rows($result) == 1) {
            $_SESSION['username'] = $username;
            header("Location: index.php");
            exit();
        } else {
            echo "<script>alert('Username atau Password salah!'); window.location.href='login.php';</script>";
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Library</title>
    <link rel="stylesheet" href="/Jago_library%20Program/css/styles.css">
    </head>
<header>
  <?php include "header.php" ?>
</header>
<body>
    <div class="container-login">
        <div class="form-card">
            <h1>Login</h1>
            <p>Welcome back! Please login to your account.</p>

            <form method="POST" action="login.php">
                <div class="input-group">
                    <label for="email">Email</label>
                    <input type="email" name="email" id="email" placeholder="Masukkan email" required>
                </div>

                <div class="input-group">
                    <label for="password">Password</label>
                    <input type="password" name="password" id="password" placeholder="Masukkan password" required>
                </div>

                <button type="submit" class="signup-button">Login</button>
            </form>
            <p class="signin-link">Belum punya akun><a href="registration.php">Daftar</a></p>
        </div>
    </div>
</body>

</html>
