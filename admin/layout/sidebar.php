<?php
  include '../database/connection.php';
  
  $currentPage = basename($_SERVER['PHP_SELF']);
  if (session_status() === PHP_SESSION_NONE) {
    session_start();
  }

  if (isset($_SESSION['email'])) {
    $email = $_SESSION['email'];
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ? AND role = 'admin'");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $user = $stmt->get_result();
    $row = $user->fetch_assoc();
  } 
?>
<!DOCTYPE html>
<html lang="en">
<body>
<div class="sidebar" data-color="white" data-active-color="danger">
  <div class="logo">
    <a href="https://www.creative-tim.com" class="simple-text logo-mini">
      <div class="logo-image-small">
        <img src="../images/user/<?php echo $row['foto']; ?>" alt="logo">
      </div>
    </a>
    <a href="https://www.creative-tim.com" class="simple-text logo-normal">
      Admin Jago
    </a>
  </div>
  <div class="sidebar-wrapper">
    <ul class="nav">
      <li class="<?= ($currentPage == 'dashboard.php') ? 'active' : '' ?>">
        <a href="/admin/dashboard.php">
          <i class="nc-icon nc-bank"></i>
          <p>Dashboard</p>
        </a>
      </li>
      <li class="<?= ($currentPage == 'user.php') ? 'active' : '' ?>">
        <a href="/admin/user.php">
          <i class="nc-icon nc-single-02"></i>
          <p>User Profile</p>
        </a>
      </li>
      <li class="<?= ($currentPage == 'manajemen-buku.php') ? 'active' : '' ?>">
        <a href="/admin/manajemen-buku.php">
          <i class="nc-icon nc-tile-56"></i>
          <p>Manajemen Buku</p>
        </a>
      </li>
      <li class="<?= ($currentPage == 'manajemen-admin.php') ? 'active' : '' ?>">
        <a href="/admin/manajemen-admin.php">
          <i class="nc-icon nc-tile-56"></i>
          <p>Manajemen Admin</p>
        </a>
      </li>
      <li class="<?= ($currentPage == 'kritik-saran.php') ? 'active' : '' ?>">
        <a href="/admin/kritik-saran.php">
          <i class="nc-icon nc-tile-56"></i>
          <p>Kritik dan Saran</p>
        </a>
      </li>
      <li class="active-pro">
        <a href="../logout.php">
          <i class="nc-icon nc-spaceship"></i>
          <p>Logout</p>
        </a>
      </li>
    </ul>
  </div>
</div>
</body>
</html>