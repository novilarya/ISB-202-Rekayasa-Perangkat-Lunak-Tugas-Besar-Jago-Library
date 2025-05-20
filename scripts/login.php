<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Library</title>
    <link rel="stylesheet" href="/Jago_library%20Program/css/styles.css">
    </head>
<header>
  <?php include "header.php" ?>
</header>
<body>
    <div class="container-login">
        <div class="form-card">
            <h1>Login</h1>
            <p>Welcome back! Please login to your account.</p>

            <form method="post" action="login.php">
                <div class="input-group">
                    <label for="email">Email</label>
                    <input type="email" name="email" id="email" placeholder="Email Address" required>
                </div>

                <div class="input-group">
                    <label for="password">Password</label>
                    <input type="password" name="password" id="password" placeholder="Password" required>
                </div>

                <button type="submit" class="signup-button">Login</button>
            </form>

            <p class="signin-link">Don't have an account? <a href="registration.php">Sign Up</a></p>
        </div>
    </div>
</body>

</html>
