<?php
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
    

    $query = "SELECT * FROM users WHERE status = 'logged'";
    $stmt = $conn->prepare($query);
    $stmt->execute();
    $user = $stmt->get_result();

    if ($user && $user->num_rows === 1) {
        $row = $user->fetch_assoc();
        echo '
        <div class="profile-icon">
            <a href="profile.php"><img src="/images/footers/' . htmlspecialchars($row['foto']) . '" alt="Profile"></a>
        </div>';
    } else {
        echo '
    <div class="auth-buttons">
                <a href="login.php" class="login-btn">Login</a>
                <a href="registration.php" class="signup-btn">Sign Up</a>';
    }

echo '
    </div>
</div>
';
?>
