<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

echo '
<!-- MDBootstrap CDN (wajib agar ikon dan JS berfungsi) -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<link rel="stylesheet" href="/css/styles.css">

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm sticky-top">
  <div class="container-fluid">
    <!-- Logo -->
    <a class="navbar-brand" href="index.php">
      <img src="/images/jago-logo.png" alt="Logo" height="60" />
    </a>

    <!-- Toggle button -->
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent"
      aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <!-- Collapsible content -->
    <div class="collapse navbar-collapse" id="navbarContent">
      <!-- Left links -->
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link" href="index.php">Beranda</a>
        </li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownBuku" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            Buku
          </a>
          <ul class="dropdown-menu" aria-labelledby="navbarDropdownBuku">
            <li><a class="dropdown-item" href="/scripts/daftar-buku.php">Daftar Buku</a></li>
            <li><a class="dropdown-item" href="/scripts/daftar-pinjam.php">Peminjaman Buku</a></li>
          </ul>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="about.php">About</a>
        </li>
      </ul>

      <!-- Right side -->
      <div class="d-flex align-items-center">';
        // PHP: user logic
        if (isset($_SESSION['email'])) {
            include_once '../database/connection.php';
            $email = $_SESSION['email'];
            $query = "SELECT * FROM users WHERE email = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $user = $stmt->get_result();

            if ($user && $user->num_rows === 1) {
                $row = $user->fetch_assoc();
                $foto = !empty($row["foto"]) ? htmlspecialchars($row["foto"]) : "default.jpg";
                $imgPath = "/images/" . $foto;

                echo '
                <!-- Profile avatar -->
                <div class="dropdown">
                  <a class="dropdown-toggle d-flex align-items-center hidden-arrow" href="#" role="button" id="navbarDropdownMenuAvatar" data-bs-toggle="dropdown" aria-expanded="false">
                    <img src="' . $imgPath . '" class="rounded-circle" height="50" alt="User Avatar" loading="lazy" />
                  </a>
                  <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdownMenuAvatar">
                    <li><a class="dropdown-item" href="profile.php">My Profile</a></li>
                    <li><a class="dropdown-item" href="logout.php">Logout</a></li>
                  </ul>
                </div>';
            } else {
                echo '
                <div>
                  <a href="login.php" class="btn btn-outline-primary me-2">Login</a>
                  <a href="registration.php" class="btn btn-primary">Sign Up</a>
                </div>';
            }
        } else {
            echo '
            <div>
              <a href="login.php" class="btn btn-outline-primary me-2">Login</a>
              <a href="registration.php" class="btn btn-primary">Sign Up</a>
            </div>';
        }

echo '
      </div>
    </div>
  </div>
</nav>
';
?>
