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
                <li><a href="index.php">Beranda</a></li>
                <div class="dropdown">
                    <button onclick="myFunction()" class="dropbtn">Buku</button>
                    <div id="myDropdown" class="dropdown-content">
                        <a href="/scripts/daftar-buku.php">Daftar Buku</a>
                        <a href="/scripts/daftar-pinjam.php">Peminjaman Buku</a>
                    </div>
                </div>
                <li><a href="about.php">About</a></li>
            </ul>
        </div>
        <div class="user-options">';

        echo '
        <script>
        function myFunction() {
            document.getElementById("myDropdown").classList.toggle("show");
        }

        window.onclick = function(event) {
            if (!event.target.matches(".dropbtn")) {
                var dropdowns = document.getElementsByClassName("dropdown-content");
                for (var i = 0; i < dropdowns.length; i++) {
                    var openDropdown = dropdowns[i];
                    if (openDropdown.classList.contains("show")) {
                        openDropdown.classList.remove("show");
                    }
                }
            }
        }
        </script>
        ';


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
