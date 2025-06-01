<?php
include './database/connection.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$message = '';
$role = $_POST['role'] ?? '';
$email = $_POST['email'] ?? '';
$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';
$nrp_nidn = $_POST['nrp_nidn'] ?? '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows > 0) {
        $message = ' (Username sudah ada!)';
    } else {
        $conn->query("INSERT INTO users (role, email, username, password, nrp_nidn) VALUES ('$role', '$email', '$username', '$password', '$nrp_nidn')");
        echo '<script>
            alert("Akun sudah berhasil dibuat!");
            window.location.href = "login.php";
        </script>';
        $conn->close();
        exit();
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
    <script src="js/role.js"></script>
</head>
<body>
    <header>
        <?php include "header.php"; ?>
    </header>

    <div class="container">
        <div class="register-container">
            <h1 class="text-center">Sign Up</h1>
            <p class="text-center">Join our Jago Library!</p>

            <form method="POST" action="">
                <div class="mb-3">
                    <label for="role" class="form-label">Role</label>
                    <select class="form-select" name="role" id="role" onchange="toggleNrpNidn()">
                        <option value="mahasiswa" <?= $role == 'mahasiswa' ? 'selected' : '' ?>>Mahasiswa</option>
                        <option value="dosen" <?= $role == 'dosen' ? 'selected' : '' ?>>Dosen</option>
                    </select>
                </div>

                <div class="mb-3" id="nrp-group">
                    <label for="nrp_nidn" class="form-label">NRP/NIDN</label>
                    <input type="text" class="form-control" name="nrp_nidn" id="nrp_nidn" value="<?= htmlspecialchars($nrp_nidn) ?>" required>
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" name="email" id="email" value="<?= htmlspecialchars($email) ?>" required>
                </div>

                <div class="mb-3">
                    <label for="username" class="form-label">
                        Username
                        <?php if ($message): ?>
                            <span class="message"><?= $message ?></span>
                        <?php endif; ?>
                    </label>
                    <input type="text" class="form-control" name="username" id="username" value="<?= htmlspecialchars($username) ?>" required>
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" name="password" id="password" value="<?= htmlspecialchars($password) ?>" required>
                </div>

                <div class="form-check mb-3">
                    <input class="form-check-input" type="checkbox" id="tampilkanPassword" onclick="togglePassword()">
                    <label class="form-check-label" for="tampilkanPassword">
                        Tampilkan Password
                    </label>
                </div>

                <button type="submit" class="btn btn-primary-1 rounded 5">Sign Up</button>
            </form>

            <div class="login">
                Sudah punya akun? <a href="login.php">Masuk</a>
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