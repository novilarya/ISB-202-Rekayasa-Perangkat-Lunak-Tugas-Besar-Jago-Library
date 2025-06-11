<?php
session_start();
include 'database/connection.php';
$message = '';
$foto = '';

if (!isset($_SESSION['email'])) {
    header('location: index.php');
    exit;
}

$email = $_SESSION['email'];
$stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$user = $stmt->get_result();
$row = $user->fetch_assoc();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nrp_nidn_new = $_POST['nrp_nidn'] ?? '';
    $username_new = $_POST['username'] ?? '';
    $email_new = $_POST['email'] ?? '';
    $password_new = $_POST['password'] ?? '';
    $semester_new = isset($_POST['semester']) ? (int) $_POST['semester'] : null;
    $email_lama = $_SESSION['email'];

    $foto = $_FILES['foto'];
    $uploadDir = "images/user/";
    $fotoBaru = $row['foto']; 

    if (!empty($foto['name'])) {
        $nama_file = basename($foto['name']);
        $file_ext = strtolower(pathinfo($nama_file, PATHINFO_EXTENSION));
        $allowed_ext = ['jpg', 'jpeg', 'png'];

        if (in_array($file_ext, $allowed_ext)) {
            $filePath = $uploadDir . $nama_file;
            if (move_uploaded_file($foto['tmp_name'], $filePath)) {
                $fotoBaru = $nama_file;
            } else {
                echo '<script>alert("Gagal mengupload file foto.");</script>';
            }
        } else {
            echo '<script>alert("Format foto tidak didukung. Hanya JPG, JPEG, dan PNG yang diperbolehkan.");</script>';
            exit;
        }
    }


    if ($row['role'] === 'mahasiswa') {
        $stmt = $conn->prepare("UPDATE users SET nrp_nidn = ?, username = ?, email = ?, password = ?, foto = ?, semester = ? WHERE email = ?");
        $stmt->bind_param("sssssis", $nrp_nidn_new, $username_new, $email_new, $password_new, $fotoBaru, $semester_new, $email_lama);
    } else {
        $stmt = $conn->prepare("UPDATE users SET nrp_nidn = ?, username = ?, email = ?, password = ?, foto = ? WHERE email = ?");
        $stmt->bind_param("ssssss", $nrp_nidn_new, $username_new, $email_new, $password_new, $fotoBaru, $email_lama);
    }

    if ($stmt->execute()) {
        $_SESSION['email'] = $email_new;
        echo '<script>
            alert("Akun berhasil diperbarui!");
            window.location.href = "profile.php";
        </script>';
    } else {
        echo '<script>alert("Gagal mengupdate data.");</script>';
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Profile | E-SILIB</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/normalize.css">
    <link rel="stylesheet" href="icomoon/icomoon.css">
    <link rel="stylesheet" href="css/vendor.css">
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <header>
        <?php include "header.php"; ?>
    </header>

    <div class="min-vh-100 d-flex align-items-center justify-content-center bg-light">
        <div class="container py-4">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="card shadow border-0">
                        <div class="row g-0">
                            <form method="POST" enctype="multipart/form-data" class="d-flex flex-wrap w-100 rounded-4" id="profileForm">
                                <div class="col-md-4 text-center d-flex flex-column align-items-center justify-content-center p-4 border-end">
                                    <img src="/images/user/<?php echo $row['foto']; ?>" alt="Foto Profil" class="img-fluid mb-3 rounded-4" style="width: auto; height: 400px; object-fit: cover;">
                                    <input type="file" name="foto" id="fotoInput" class="form-control rounded 4 d-none" accept=".jpg, .jpeg, .png">
                                </div>

                                <div class="col-md-8 p-4">
                                    <h3 class="card-title mb-4">My Profile <?php echo ucfirst($row['role']); ?></h3>

                                    <div id="displayMode">
                                        <p><strong><?= ($row['role'] === 'mahasiswa') ? 'NRP' : 'NIDN' ?>:</strong> <?= $row['nrp_nidn'] ?></p>
                                        <p><strong>Email:</strong> <?= $row['email'] ?></p>
                                        <p><strong>Username:</strong> <?= $row['username'] ?></p>
                                        <p><strong>Password:</strong> ********</p>
                                        <?php if ($row['role'] === 'mahasiswa'): ?>
                                            <p><strong>Semester:</strong> <?= $row['semester'] ?></p>
                                        <?php endif; ?>
                                        <button type="button" class="btn btn-outline-primary rounded-4" style="height: 50px;" onclick="toggleEdit(true)">Edit Profile</button>
                                    </div>

                                    <div id="editMode" class="d-none">
                                        <div class="mb-3">
                                            <label for="nrp_nidn" class="form-label"><?= ($row['role'] === 'mahasiswa') ? 'NRP' : 'NIDN' ?></label>
                                            <input type="text" class="form-control" name="nrp_nidn" id="nrp_nidn" value="<?= $row['nrp_nidn'] ?>" readonly>
                                        </div>
                                        <div class="mb-3">
                                            <label for="email" class="form-label">Email</label>
                                            <input type="text" class="form-control" name="email" id="email" value="<?= $row['email'] ?>" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="username" class="form-label">Username</label>
                                            <input type="text" class="form-control" name="username" id="username" value="<?= $row['username'] ?>" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="password" class="form-label">Password</label>
                                            <input type="password" class="form-control" name="password" id="password" value="<?= $row['password'] ?>" required>
                                            <div class="form-check mt-1">
                                                <input type="checkbox" class="form-check-input" id="tampilkanPassword" onclick="togglePassword()">
                                                <label for="tampilkanPassword" class="form-check-label">Tampilkan Password</label>
                                            </div>
                                        </div>
                                        <?php if ($row['role'] === 'mahasiswa'): ?>
                                            <div class="mb-3">
                                                <label for="semester" class="form-label">Semester</label>
                                                <select name="semester" id="semester" class="form-select" required>
                                                    <?php for ($i = 1; $i <= 14; $i++): ?>
                                                        <option value="<?= $i ?>" <?= $row['semester'] == $i ? 'selected' : '' ?>><?= $i ?></option>
                                                    <?php endfor; ?>
                                                </select>
                                            </div>
                                        <?php endif; ?>
                                        <div class="d-flex gap-2 mt-4">
                                            <button type="submit" class="btn btn-outline-primary rounded-4" style="height: 50px;">Simpan Perubahan</button>
                                            <button type="button" class="btn btn-secondary rounded-4" style="height: 50px;" onclick="toggleEdit(false)">Cancel</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <footer>
        <?php include 'footer.php'; ?>
    </footer>

    <script>
        function togglePassword() {
            const pwd = document.getElementById('password');
            pwd.type = (pwd.type === 'password') ? 'text' : 'password';
        }

        function toggleEdit(edit) {
            const displayMode = document.getElementById('displayMode');
            const editMode = document.getElementById('editMode');
            const fotoInput = document.getElementById('fotoInput');

            if (edit) {
                displayMode.classList.add('d-none');
                editMode.classList.remove('d-none');
                fotoInput.classList.remove('d-none');
            } else {
                displayMode.classList.remove('d-none');
                editMode.classList.add('d-none');
                fotoInput.classList.add('d-none');
            }
        }
    </script>
</body>

</html>