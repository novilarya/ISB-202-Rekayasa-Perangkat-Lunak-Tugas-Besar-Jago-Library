<?php
    include '../database/connection.php';
    session_start();
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
            header("Location: login.php");
            $conn->close();
        }
    }
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up | Library</title>
   <link rel="stylesheet" href="/css/styles.css">
    <script src="/scripts/role.js"></script>
   </head>
<header>
    <?php include "header.php" ?>
</header>
<body>
    <div class="container-regist">
        <div class="form-card">
            <h1>Sign Up</h1>
            <p>Join our Jago Library!</p>

            <form method="POST" action="">
                <div class="input-group">
                    <label for="role">Role</label>
                    <select name="role" id="role" onchange="toggleNrpNidn()">
                        <option value="mahasiswa" <?= $role == 'mahasiswa' ? 'selected' : '' ?>>Mahasiswa</option>
                        <option value="dosen" <?= $role == 'dosen' ? 'selected' : '' ?>>Dosen</option>
                    </select>
                </div>

                <div class="input-group" id="nrp-group">
                    <label for="nrp_nidn">NRP/NIDN</label>
                    <input type="nrp_nidn" name="nrp_nidn" id="nrp_nidn" placeholder="NRP" value="<?= htmlspecialchars($nrp_nidn) ?>" required>
                </div>

                <div class="input-group">
                    <label for="email">Email</label>
                    <input type="email" name="email" id="email" placeholder="Masukkan email" value="<?= htmlspecialchars($email) ?>" required>
                </div>

                <div class="input-group">
                    <label for="username">Username
                        <?php if ($message): ?>
                            <div class="message"><?= $message ?></div>
                        <?php endif; ?>
                    </label>
                    <input type="text" name="username" id="username" placeholder="Masukkan username" value="<?= htmlspecialchars($username) ?>"required>
                </div>

                <div class="input-group">
                    <label for="password">Password</label>
                    <input type="password" name="password" id="password" placeholder="Masukkan password" value="<?= htmlspecialchars($password) ?>"required>
                </div>

                <div class="form-check">
                    <input type="checkbox" id="tampilkanPassword" onclick="password.type = this.checked ? 'text' : 'password'">  
                    <label class="form-check-label" for="showPassword">Tampilkan Password</label>
                </div>


                <button type="submit" class="signup-button">Sign Up</button>
            </form>

            <p class="login">Sudah punya akun? <a href="login.php">Masuk</a></p>
        </div>
    </div>
</body>
</html>
