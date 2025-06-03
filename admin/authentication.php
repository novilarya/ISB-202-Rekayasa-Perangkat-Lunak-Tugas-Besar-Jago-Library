<?php
    include '../database/connection.php';
    session_start();
    $message = '';

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (isset($_SESSION['email'])) {
            $email = $_SESSION['email'];
            $query = "SELECT * FROM users WHERE email = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result && $result->num_rows === 1) {
                $row = $result->fetch_assoc();
                $kode = $row['kode_autentikasi'];
                $test_kode = $_POST['kode_autentikasi'];

                if ($kode == $test_kode) {
                    header("Location: dashboard.php");
                } else {
                    $message = 'Kode Autentikasi Anda Salah!';
                }
            } else {
                $message = 'User tidak ditemukan.';
            }
        } else {
            $message = 'Session tidak ditemukan.';
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Autentikasi Admin</title>
    <link rel="stylesheet" href="/css/styles.css">
    </head>
<header>
  <?php include "../header.php" ?>
</header>
<body>
    <div class="container-login">
        <div class="form-card">
            <h1>Masukkan Kode Autentikasi</h1>
            <form method="POST" action="">
                <div class="input-group">
                    <label for="kode_autentikasi">Kode Autentikasi</label>
                    <input type="kode_autentikasi" name="kode_autentikasi" id="kode_autentikasi" placeholder="Masukkan kode autentikasi" required>
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
