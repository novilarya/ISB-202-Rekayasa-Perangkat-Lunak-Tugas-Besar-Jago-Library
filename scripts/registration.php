<?php
    include '../database/connection.php';

    if (isset($_POST['submit'])){
        $role = $_POST['role'];
        $email = $_POST['email'];
        $username = $_POST['username'];
        $password = $_POST['password'];
        $nrp_nidn = $_POST['nrp_nidn'];
        $conn->query("INSERT INTO user (role, email, username, password. nrp_nidn) VALUES ('$role', '$email', '$username', '$password', '$nrp_nidn')");
        header("Location: login.php");
        $conn->close();
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

            <form method="POST" action="registration.php">
                <div class="input-group">
                    <label for="role">Role</label>
                    <select name="role" id="role" onchange="toggleNrpNidn()">
                        <option value="mahasiswa">Mahasiswa</option>
                        <option value="dosen">Dosen</option>
                    </select>
                </div>

                <div class="input-group">
                    <label for="email">Email</label>
                    <input type="email" name="email" id="email" placeholder="Masukkan email" required>
                </div>

                <div class="input-group">
                    <label for="username">Username</label>
                    <input type="text" name="username" id="username" placeholder="Masukkan username" required>
                </div>

                <div class="input-group">
                    <label for="password">Password</label>
                    <input type="password" name="password" id="password" placeholder="Masukkan password" required>
                </div>

                <div class="input-group" id="nrp-group">
                    <label for="nrp_nidn">NRP/NIDN</label>
                    <input type="nrp_nidn" name="nrp_nidn" id="nrp_nidn" placeholder="NRP" required>
                </div>

                <button type="submit" class="signup-button">Sign Up</button>
            </form>

            <p class="login">Sudah punya akun?<a href="login.php">Masuk</a></p>
        </div>
    </div>
</body>
</html>
