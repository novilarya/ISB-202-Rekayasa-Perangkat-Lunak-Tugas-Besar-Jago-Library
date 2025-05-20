<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up | Library</title>
   <link rel="stylesheet" href="/Jago_library%20Program/css/styles.css">
    <script src="/Jago_library%20Program/scripts/role.js"></script>
   </head>
<header>
    <?php include "header.php" ?>
</header>
<body>
    <div class="container-regist">
        <div class="form-card">
            <h1>Sign Up</h1>
            <p>Join our Jago Library!</p>

            <form method="post" action="registration.php">
                <div class="input-group">
                    <label for="email">Email</label>
                    <input type="email" name="email" id="email" placeholder="Email Address" required>
                </div>

                <div class="input-group">
                    <label for="username">Username</label>
                    <input type="text" name="username" id="username" placeholder="Username" required>
                </div>

                <div class="input-group">
                    <label for="password">Password</label>
                    <input type="password" name="password" id="password" placeholder="Password" required>
                </div>

                <div class="input-group" id="nrp-group">
                    <label for="nrp">NRP</label>
                    <input type="nrp" name="nrp" id="nrp" placeholder="NRP" required>
                </div>

                <div class="input-group">
                    <label for="role">Role</label>
                    <select name="role" id="role" onchange="toggleNRP()">
                        <option value="mahasiswa">Mahasiswa</option>
                        <option value="dosen">Dosen</option>
                    </select>
                </div>

                <button type="submit" class="signup-button">Sign Up</button>
            </form>

            <p class="login">Already a member? <a href="login.php">Log in</a></p>
        </div>
    </div>
</body>
</html>
