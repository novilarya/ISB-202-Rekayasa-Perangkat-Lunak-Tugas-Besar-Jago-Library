<?php
    session_start();
    echo '
    <link rel="stylesheet" href="/css/styles.css">
    <div class="header-container">
        <div class="logo">
            <a href="index.php">Jago Library</a>
        </div>
        <div class="footer-links">
            <ul>
                <li><a href="about.php">About</a></li>
                <li><a href="/scripts/daftar-pinjam.php">Book</a></li>
                <li><a href="authors.php">Authors</a></li>
                <li><a href="geners.php">Geners</a></li>
                <li><a href="lists.php">Lists</a></li>
            </ul>
        </div>
        <div class="user-options">';

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
                $foto = !empty($row['foto']) ? htmlspecialchars($row['foto']) : 'default.jpg';
                $imgPath = '/images/' . $foto;

                echo '
                <div class="profile-icon">
                    <a href="profile.php"><img src="' . $imgPath . '" alt="Profile" class="profile-img"></a>
                </div>';
            } else {
                echo '
                <div class="auth-buttons">
                    <a href="login.php" class="login-btn">Login</a>
                    <a href="registration.php" class="signup-btn">Sign Up</a>
                </div>';
            }
        } else {
            echo '
            <div class="auth-buttons">
                <a href="login.php" class="login-btn">Login</a>
                <a href="registration.php" class="signup-btn">Sign Up</a>
            </div>';
        }

    echo '
        </div>
    </div>
    ';
?>
