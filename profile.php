<?php
    session_start();
    include 'database/connection.php';
    $message = '';
    $foto = '';

    if (isset($_SESSION['email'])) {
        $email = $_SESSION['email'];
        $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $user = $stmt->get_result();
        $row = $user->fetch_assoc();
    } else {
        header('location: index.php');
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $nrp_nidn_new = $_POST['nrp_nidn'] ?? '';
        $username_new = $_POST['username'] ?? '';
        $email_new = $_SESSION['email'];
        $password_new = $_POST['password'] ?? '';
        $foto = $_FILES['foto'];
        $uploadDir = "images/user/";
        $nama_file = basename($foto['name']);
        $filePath = $uploadDir . $nama_file;

        if (move_uploaded_file($foto['tmp_name'], $filePath)) {
            $stmt = $conn->prepare("UPDATE users SET nrp_nidn = ?, username = ?, password = ?, foto = ? WHERE email = ?");
            $stmt->bind_param("sssss", $nrp_nidn_new, $username_new, $password_new, $nama_file, $email_new);

            if ($stmt->execute()) {
                echo '<script>
                        alert("Akun berhasil diperbarui!");
                        window.location.href = "profile.php";
                    </script>';
            } else {
                echo '<script>alert("Gagal mengupdate data.");</script>';
            }
        } else {
            echo "Gagal mengupload file foto.";
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
        <?php include "header.php" ?>
    </header>
    <div class="min-vh-100 d-flex align-items-center justify-content-center bg-light">
        <div class="container py-4">
            <div class="row justify-content-center">
                <div class="col-lg-10">

                    <div class="card shadow border-0">
                        <div class="row g-0">
                            <form method="POST" action="" enctype="multipart/form-data" class="d-flex flex-wrap w-100">

                                <!-- FOTO PROFIL -->
                                <div class="col-md-4 text-center bg-light d-flex flex-column align-items-center justify-content-center p-4 border-end">
                                    <img src="/images/user/<?php echo $row['foto']; ?>" alt="Foto Profil" class="img-fluid rounded-circle mb-3" style="width: 140px; height: 140px; object-fit: cover;">
                                    <input type="file" name="foto" class="form-control" required>
                                </div>

                                <!-- FORM PROFIL -->
                                <div class="col-md-8 p-4">
                                    <h3 class="card-title mb-4">My Profile <?php echo ucfirst($row['role']); ?></h3>

                                    <div class="mb-3">
                                        <label for="nrp_nidn" class="form-label">
                                            <?php echo ($row['role'] === 'mahasiswa') ? 'NRP' : (($row['role'] === 'dosen') ? 'NIDN' : 'NRP/NIDN'); ?>
                                        </label>
                                        <input type="text" class="form-control" name="nrp_nidn" id="nrp_nidn" value="<?php echo $row['nrp_nidn']; ?>" required>
                                    </div>

                                    <div class="mb-3">
                                        <label for="email" class="form-label">Email</label>
                                        <input type="email" class="form-control" name="email" id="email" value="<?php echo $row['email']; ?>" disabled>
                                    </div>

                                    <div class="mb-3">
                                        <label for="username" class="form-label">Username</label>
                                        <?php if ($message): ?>
                                            <div class="text-danger small mb-1"><?= $message ?></div>
                                        <?php endif; ?>
                                        <input type="text" class="form-control" name="username" id="username" value="<?php echo $row['username']; ?>" required>
                                    </div>

                                    <div class="mb-3">
                                        <label for="password" class="form-label">Password</label>
                                        <input type="password" class="form-control" name="password" id="password" value="<?php echo $row['password']; ?>" disabled>
                                    </div>

                                    <div class="form-check mb-3">
                                        <input type="checkbox" class="form-check-input" id="tampilkanPassword" onclick="togglePassword()">
                                        <label for="tampilkanPassword" class="form-check-label">Tampilkan Password</label>
                                    </div>

                                    <div class="d-grid">
                                        <button type="submit" class="btn btn-primary px-4 rounded">Update</button>
                                    </div>

                                </div>
                            </form>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <script src="js/jquery-1.11.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm"
        crossorigin="anonymous"></script>
    <script src="js/plugins.js"></script>
    <script src="js/script.js"></script>
    <footer>
        <?php
        include 'footer.php';
        ?>
    </footer>
    <script>
        function togglePassword() {
            var pwd = document.getElementById('password');
            pwd.type = (pwd.type === 'password') ? 'text' : 'password';
        }
    </script>
</body>

</html>