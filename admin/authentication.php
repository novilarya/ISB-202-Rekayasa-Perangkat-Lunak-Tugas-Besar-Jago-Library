<?php
    include '../database/connection.php';
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/normalize.css">
    <link rel="stylesheet" href="../icomoon/icomoon.css">
    <link rel="stylesheet" href="../css/vendor.css">
    <link rel="stylesheet" href="../style.css">
    </head>
    <header>
      <?php include "../header.php" ?>
    </header>
<body>
    <div class="auth-container">
        <div class="auth-card">
            <h2>Autentikasi Admin</h2>
            <form method="POST" action="">
                <div class="mb-3">
                    <label for="kode_autentikasi" class="form-label">Kode Autentikasi</label>
                    <input type="text" class="form-control" id="kode_autentikasi" name="kode_autentikasi" placeholder="Masukkan kode autentikasi" required>
                </div>
                <button type="submit" class="btn btn-primary-1 rounded 5">Masuk</button>
                <?php if ($message): ?>
                    <div class="message"><?= $message ?></div>
                <?php endif; ?>
            </form>
            <p class="signin-link">Belum punya akun? <a href="registration.php">Daftar</a></p>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
