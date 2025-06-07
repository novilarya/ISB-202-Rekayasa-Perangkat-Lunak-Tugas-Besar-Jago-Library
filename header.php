<?php
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    include 'database/connection.php';
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

<body data-bs-spy="scroll" data-bs-target="#header" tabindex="0">

	<div id="header-wrap">

    <div class="top-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-6">
                    <div class="social-links">
                        <ul>
                            <li><a href="#"><i class="icon icon-facebook"></i></a></li>
                            <li><a href="#"><i class="icon icon-twitter"></i></a></li>
                            <li><a href="#"><i class="icon icon-youtube-play"></i></a></li>
                            <li><a href="#"><i class="icon icon-behance-square"></i></a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="right-element">
                        <?php
                        if (isset($_SESSION['email'])) {
                            $email = $_SESSION['email'];
                            $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
                            $stmt->bind_param("s", $email);
                            $stmt->execute();
                            $user = $stmt->get_result();

                            if ($user && $user->num_rows === 1) {
                                $row = $user->fetch_assoc();
                                $foto = !empty($row["foto"]) ? htmlspecialchars($row["foto"]) : "default.jpg";
                                $imgPath = "/images/user/" . $foto;
                                echo '
                                <div class="dropdown">
                                    <a class="dropdown-toggle text-dark" href="#" id="dropdownUser" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="icon icon-user"></i> <span>Hi, ' . htmlspecialchars($row["username"]) . '</span>
                                    </a>
                                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownUser">
                                        <li><a class="dropdown-item" href="profile.php">My Profile</a></li>
                                        <li><a class="dropdown-item" href="logout.php">Logout</a></li>
                                    </ul>
                                </div>';
                            }
                        } else {
                            echo '
                                <a href="login.php" class="user-account for-buy"><span> Login</span></a>
                                <a href="registration.php" class="user-account for-buy"><span> SignUp</span></a>';
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div><!--top-content-->

    <header id="header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-2">
                    <div class="main-logo">
                        <a href="index.php"><img src="images/Group.png" alt="logo"></a>
                    </div>
                </div>
                <div class="col-md-10">
                    <nav id="navbar">
                        <div class="main-menu stellarnav">
                            <ul class="menu-list">
                                <li><a href="index.php" class="<?= basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : '' ?>">HOME</a></li>
                                <li class="menu-item has-sub">
                                    <a href="#pages" class="nav-link">Buku</a>
                                    <ul>
                                        <li><a href="daftar-buku.php" class="<?= basename($_SERVER['PHP_SELF']) == 'daftar-buku.php' ? 'active' : '' ?>">Daftar Buku</a></li>
                                        <li><a href="daftar-pinjam.php"class="<?= basename($_SERVER['PHP_SELF']) == 'daftar-pinjam.php' ? 'active' : '' ?>">Peminjaman</a></li>
                                    </ul>
                                </li>
                                <li class="menu-item"><a href="#featured-books" class="nav-link">Featured</a></li>
                                <li class="menu-item"><a href="#popular-books" class="nav-link">Popular</a></li>
                            </ul>

                            <div class="hamburger">
                                <span class="bar"></span>
                                <span class="bar"></span>
                                <span class="bar"></span>
                            </div>
                        </div>
                    </nav>
                </div>
            </div>
        </div>
    </header>

</div><!--header-wrap-->

<script src="js/jquery-1.11.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm"
    crossorigin="anonymous"></script>
<script src="js/plugins.js"></script>
<script src="js/script.js"></script>


</body>
</html>