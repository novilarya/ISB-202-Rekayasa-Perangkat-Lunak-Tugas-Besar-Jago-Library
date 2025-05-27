<?php
    include '../database/connection.php';
    $message ='';
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

    if($_SERVER["REQUEST_METHOD"] == "POST"){
        $nrp_nidn_new = $_POST['nrp_nidn'] ?? '';
        $username_new = $_POST['username'] ?? '';
        $email_new = $_SESSION['email']; 
        $password_new = $_POST['password'] ?? '';
        $foto = $_POST['foto'] ?? '';

        $stmt = $conn->prepare("UPDATE users SET nrp_nidn = ?, username = ?, password = ?, foto = ? WHERE email = ?");
        $stmt->bind_param("sssss", $nrp_nidn_new, $username_new, $password_new, $foto, $email_new);
        
        if ($stmt->execute()) {
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
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <link rel="stylesheet" href="/css/styles.css">
    </head>
<header>
  <?php include "header.php" ?>
</header>
<body>
    <div class="container-profile">
  <div class="profile-sidebar">
      <img src="/images/<?php echo $row['foto']; ?>" alt="Foto Profil">
      <input type="file" name="foto" class="form-control mb-2" required>
  </div>

  <div class="form-card-profile">
      <h1>My Profile<?php 
          if ($row['role'] === 'mahasiswa') {
              echo ' Mahasiswa';
          } elseif ($row['role'] === 'dosen') {
              echo ' Dosen';
          }
      ?></h1>         
          <div class="input-profile" id="nrp-group-profile">
              <label for="nrp_nidn">
              <?php 
                  if ($row['role'] === 'mahasiswa') {
                      echo 'NRP';
                  } elseif ($row['role'] === 'dosen') {
                      echo 'NIDN';
                  } else {
                      echo 'NRP/NIDN';
                  }
              ?>
              </label>
              <input type="text" name="nrp_nidn" id="nrp_nidn" placeholder="<?php echo ($row['role'] === 'mahasiswa') ? 'NRP' : 'NIDN'; ?>" value="<?php echo $row['nrp_nidn']; ?>" required>
          </div>

          <div class="input-profile">
              <label for="email">Email</label>
              <input type="email" name="email" id="email" placeholder="Masukkan email" value="<?php echo $row['email']; ?>" required>
          </div>

          <div class="input-profile">
              <label for="username">Username
                  <?php if ($message): ?>
                      <div class="message"><?= $message ?></div>
                  <?php endif; ?>
              </label>
              <input type="text" name="username" id="username" placeholder="Masukkan username" value="<?php echo $row['username']; ?>"required>
          </div>

          <div class="input-profile">
              <label for="password">Password</label>
              <input type="password" name="password" id="password" placeholder="Masukkan password" value="<?php echo $row['password']; ?>"required>
          </div>

          <div class="form-check">
          <input type="checkbox" id="tampilkanPassword" onclick="password.type = this.checked ? 'text' : 'password'" />
          <label for="tampilkanPassword">Tampilkan Password</label>
        </div>

        <button type="submit" class="signup-button">Update</button>
  </div>
</div>
</body>

</html>