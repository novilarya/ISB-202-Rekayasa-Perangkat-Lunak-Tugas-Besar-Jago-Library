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
	<link rel="stylesheet" type="text/css" href="/css/normalize.css">
	<link rel="stylesheet" type="text/css" href="/icomoon/icomoon.css">
	<link rel="stylesheet" type="text/css" href="/css/vendor.css">
	<link rel="stylesheet" type="text/css" href="../style.css">
</head>
<body>
    <div class="container">
        <div class="auth-card login-container"> <!-- Tambah 'login-container' agar konsisten -->
            <h2 class="text-center">Autentikasi Admin</h2>
            <p class="text-center">Masukkan kode autentikasi yang Anda punya.</p>

            <form method="POST" action="">
                <div class="mb-3">
                    <label for="kode_autentikasi" class="form-label">Kode Autentikasi</label>
                    <input type="text" class="form-control mb-3" id="kode_autentikasi" name="kode_autentikasi" placeholder="Masukkan kode autentikasi" required>
 
                    <button type="submit" class="btn btn-primary w-100 rounded-1" style="height:50px">Masuk</button>

                    <p class="text-center">Jika lupa, silakan tanya kepada Admin yang lainnya.</p>
                </div>

                <?php if ($message): ?>
                    <div class="text-center mt-3 message"><?= $message ?></div>
                <?php endif; ?>
            </form>
        </div>
    </div>

    <!-- Tambahan script dan JS seperti di login.php -->
    <script src="../js/jquery-1.11.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm"
        crossorigin="anonymous"></script>
    <script src="../js/plugins.js"></script>
    <script src="../js/script.js"></script>
</body>

</html>
