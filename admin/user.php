<?php
session_start();
include '../database/connection.php';
include 'layout/sidebar.php';

if (isset($_SESSION['email'])) {
  $email = $_SESSION['email'];
  $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
  $stmt->bind_param("s", $email);
  $stmt->execute();
  $user = $stmt->get_result();
  $row = $user->fetch_assoc();
} else {
  header('location: ../scripts/login.php');
}


if (isset($_POST['update'])) {
  $email_baru = $_POST['email_baru'] ?? '';
  $password_baru = $_POST['password_baru'] ?? '';
  $nama_baru = $_POST['nama_baru'] ?? '';

  $email_session = $_SESSION['email'];
  $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
  $stmt->bind_param("s", $email_session);
  $stmt->execute();
  $user = $stmt->get_result();
  if ($user && $user->num_rows > 0) {
    $row = $user->fetch_assoc();
  } else {
    $row = null;
  }
  $id = $row['nrp_nidn'];

  if ($_FILES['foto_baru']['error'] == UPLOAD_ERR_OK) {
    $nama_file = basename($_FILES['foto_baru']['name']);
    $filePath = "../images/user/" . $nama_file;
    move_uploaded_file($_FILES['foto_baru']['tmp_name'], $filePath);
    $foto_final = $nama_file;
  } else {
    $foto_final = $row['foto'];
  }

  $stmt = $conn->prepare("UPDATE users SET email = ?, password = ?, nama = ?, foto = ? WHERE nrp_nidn = ?");
  $stmt->bind_param("sssss", $email_baru, $password_baru, $nama_baru, $foto_final, $id);

  $success = false;
  if ($stmt->execute()) {
    $success = true;
  }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <link rel="apple-touch-icon" sizes="76x76" href="../assets/img/apple-icon.png">
  <link rel="icon" type="image/png" href="../assets/img/favicon.png">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
  <title>
    Paper Dashboard 2 by Creative Tim
  </title>
  <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0, shrink-to-fit=no' name='viewport' />
  <!--     Fonts and icons     -->
  <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700,200" rel="stylesheet" />
  <link href="https://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css" rel="stylesheet">
  <!-- CSS Files -->
  <link href="assets/css/bootstrap.min.css" rel="stylesheet" />
  <link href="assets/css/paper-dashboard.css?v=2.0.1" rel="stylesheet" />
  <!-- CSS Just for demo purpose, don't include it in your project -->
  <link href="assets/demo/demo.css" rel="stylesheet" />
</head>

<body class="">
  <div class="wrapper">
    <div class="main-panel bg-light">
      <!-- Navbar -->
      <nav class="navbar navbar-expand-lg navbar-absolute fixed-top navbar-transparent">
        <div class="container-fluid">
          <div class="navbar-wrapper">
            <div class="navbar-toggle">
              <button type="button" class="navbar-toggler">
                <span class="navbar-toggler-bar bar1"></span>
                <span class="navbar-toggler-bar bar2"></span>
                <span class="navbar-toggler-bar bar3"></span>
              </button>
            </div>
            <a class="navbar-brand" href="javascript:;">Profile Admin</a>
          </div>
          <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navigation" aria-controls="navigation-index" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-bar navbar-kebab"></span>
            <span class="navbar-toggler-bar navbar-kebab"></span>
            <span class="navbar-toggler-bar navbar-kebab"></span>
          </button>
        </div>
      </nav>


      <!-- End Navbar -->
      <div class="content">
        <div class="row">
          <div class="col-md-12">
            <div class="card card-user">
              <div class="card-header">
                <h5 class="card-title">Edit Profile</h5>
              </div>
              <div class="card-body">
                <form method="POST" enctype="multipart/form-data">
                  <div class="row">
                    <div class="col-md-5 pr-1">
                      <div class="form-group">
                        <label>Username</label>
                        <input type="text" name="username_baru" class="form-control" value="<?php echo $row['username'] ?? ''; ?>">
                      </div>
                    </div>
                    <div class="col-md-4 pl-1">
                      <div class="form-group">
                        <label for="exampleInputEmail1">Email address</label>
                        <input type="email" name="email_baru" class="form-control" value="<?php echo $row['email'] ?? ''; ?>">
                      </div>
                    </div>
                    <div class="col-md-3 px-1">
                      <div class="form-group">
                        <label>Password</label>
                        <input type="text" name="password_baru" class="form-control" value="<?php echo $row['password'] ?? ''; ?>">
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-12">
                      <div class="form-group">
                        <label>Nama</label>
                        <input type="text" name="nama_baru" class="form-control" value="<?php echo $row['nama'] ?? ''; ?>">
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-12">
                      <div class="form-group">
                        <label>Kode Autentikasi</label>
                        <input type="text" name="kode_autentikasi" class="form-control" value="<?php echo $row['kode_autentikasi'] ?? ''; ?>">
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-12 pr-1">
                      <label>Foto</label>
                      <input type="file" name="foto_baru" class="form-control">
                    </div>
                  </div>
                  <div class="row">
                    <div class="update ml-auto mr-auto">
                      <button type="submit" name="update" class="btn btn-primary btn-round">Update Profile</button>
                    </div>
                  </div>
                </form>

                <div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
                  <div class="modal-dialog">
                    <div class="modal-content">
                      <div class="modal-header bg-light">
                        <h5 class="modal-title" id="successModalLabel">Sukses</h5>
                      </div>
                      <div class="modal-body">
                        Update Berhasil!
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>



  <!--   Core JS Files   -->
  <script src="assets/js/core/jquery.min.js"></script>
  <script src="assets/js/core/popper.min.js"></script>
  <script src="assets/js/core/bootstrap.min.js"></script>
  <script src="assets/js/plugins/perfect-scrollbar.jquery.min.js"></script>
  <!--  Google Maps Plugin    -->
  <script src="https://maps.googleapis.com/maps/api/js?key=YOUR_KEY_HERE"></script>
  <!-- Chart JS -->
  <script src="assets/js/plugins/chartjs.min.js"></script>
  <!--  Notifications Plugin    -->
  <script src="assets/js/plugins/bootstrap-notify.js"></script>
  <!-- Control Center for Now Ui Dashboard: parallax effects, scripts for the example pages etc -->
  <script src="assets/js/paper-dashboard.min.js?v=2.0.1" type="text/javascript"></script><!-- Paper Dashboard DEMO methods, don't include it in your project! -->
  <script src="assets/demo/demo.js"></script>

  <?php if (isset($success) && $success): ?>
    <script>
      $(document).ready(function() {
        $('#successModal').modal('show');
        setTimeout(function() {
          window.location.href = 'user.php';
        }, 2000);
      });
    </script>
  <?php endif; ?>

</body>

</html>