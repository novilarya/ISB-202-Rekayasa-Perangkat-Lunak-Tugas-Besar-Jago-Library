<?php
  include 'layout/sidebar.php';
  include '../database/connection.php';
  if (session_status() === PHP_SESSION_NONE) {
    session_start();
  }

  $query = "SELECT count(kode_buku) FROM buku";
  $stmt = $conn->prepare($query);
  $stmt->execute();
  $jumlahBuku = $stmt->get_result();
  $jumlahKeseluruhanBuku = $jumlahBuku->fetch_assoc();

  $query = "SELECT count(nrp_nidn) FROM users WHERE role = 'admin'";
  $stmt = $conn->prepare($query);
  $stmt->execute();
  $jumlahAdmin = $stmt->get_result();
  $jumlahKeseluruhanAdmin = $jumlahAdmin->fetch_assoc();

  $query = "SELECT count(nrp_nidn) FROM users WHERE role = 'dosen'";
  $stmt = $conn->prepare($query);
  $stmt->execute();
  $jumlahDosen = $stmt->get_result();
  $jumlahKeseluruhanDosen = $jumlahDosen->fetch_assoc();

  $query = "SELECT count(nrp_nidn) FROM users WHERE role = 'mahasiswa'";
  $stmt = $conn->prepare($query);
  $stmt->execute();
  $jumlahMahasiswa = $stmt->get_result();
  $jumlahKeseluruhanMahasiswa = $jumlahMahasiswa->fetch_assoc();

  $query = "SELECT count(id_peminjaman) FROM peminjaman";
  $stmt2 = $conn->prepare($query);
  $stmt2->execute();
  $jumlahDipinjam = $stmt2->get_result();
  $jumlahKeseluruhanDipinjam = $jumlahDipinjam->fetch_assoc();

  $query = "SELECT count(id_peminjaman) FROM peminjaman WHERE status = 'dipinjam'";
  $stmt2 = $conn->prepare($query);
  $stmt2->execute();
  $jumlahDipinjam = $stmt2->get_result();
  $jumlahKeseluruhanDipinjam = $jumlahDipinjam->fetch_assoc();

  $query = "SELECT count(id_peminjaman) FROM peminjaman WHERE status = 'menunggu diambil'";
  $stmt2 = $conn->prepare($query);
  $stmt2->execute();
  $jumlahDiambil = $stmt2->get_result();
  $jumlahKeseluruhanDiambil = $jumlahDiambil->fetch_assoc();

  $query = "SELECT count(id_peminjaman) FROM peminjaman WHERE status = 'pembayaran'";
  $stmt2 = $conn->prepare($query);
  $stmt2->execute();
  $jumlahPembayaran = $stmt2->get_result();
  $jumlahKeseluruhanPembayaran = $jumlahPembayaran->fetch_assoc();

  $query = "SELECT count(id_peminjaman) FROM peminjaman WHERE status = 'dikembalikan'";
  $stmt2 = $conn->prepare($query);
  $stmt2->execute();
  $jumlahDikembalikan = $stmt2->get_result();
  $jumlahKeseluruhanDikembalikan = $jumlahDikembalikan->fetch_assoc();
  


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
  <div class="wrapper ">
    <div class="main-panel">
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
            <a class="navbar-brand" href="javascript:;">Dashboard</a>
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
          <div class="col-lg-3 col-md-6 col-sm-6">
              <div class="card card-stats">
                <div class="card-body ">
                  <div class="row">
                    <div class="col-5 col-md-4">
                      <div class="icon-big text-center icon-warning">
                        <i class="nc-icon nc-globe text-warning"></i>
                      </div>
                    </div>
                    <div class="col-7 col-md-8">
                      <div class="numbers">
                        <p class="card-category">Jumlah Admin</p>
                        <p class="card-title"><?php echo $jumlahKeseluruhanAdmin['count(nrp_nidn)']; ?><p>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
          </div>
          <div class="col-lg-3 col-md-6 col-sm-6">
              <div class="card card-stats">
                <div class="card-body ">
                  <div class="row">
                    <div class="col-5 col-md-4">
                      <div class="icon-big text-center icon-warning">
                        <i class="nc-icon nc-globe text-warning"></i>
                      </div>
                    </div>
                    <div class="col-7 col-md-8">
                      <div class="numbers">
                        <p class="card-category">Jumlah Dosen</p>
                        <p class="card-title"><?php echo $jumlahKeseluruhanDosen['count(nrp_nidn)']; ?><p>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
          </div>
          <div class="col-lg-3 col-md-6 col-sm-6">
              <div class="card card-stats">
                <div class="card-body ">
                  <div class="row">
                    <div class="col-5 col-md-4">
                      <div class="icon-big text-center icon-warning">
                        <i class="nc-icon nc-globe text-warning"></i>
                      </div>
                    </div>
                    <div class="col-7 col-md-8">
                      <div class="numbers">
                        <p class="card-category">Jumlah Mahasiswa</p>
                        <p class="card-title"><?php echo $jumlahKeseluruhanMahasiswa['count(nrp_nidn)']; ?><p>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
          </div>
        </div>

        <div class="row">
          <div class="col-lg-3 col-md-6 col-sm-6">
              <div class="card card-stats">
                <div class="card-body ">
                  <div class="row">
                    <div class="col-5 col-md-4">
                      <div class="icon-big text-center icon-warning">
                        <i class="nc-icon nc-globe text-warning"></i>
                      </div>
                    </div>
                    <div class="col-7 col-md-8">
                      <div class="numbers">
                        <p class="card-category">Jumlah Sedang Dipinjam</p>
                        <p class="card-title"><?php echo $jumlahKeseluruhanDipinjam['count(id_peminjaman)']; ?><p>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
          </div>
          <div class="col-lg-3 col-md-6 col-sm-6">
              <div class="card card-stats">
                <div class="card-body ">
                  <div class="row">
                    <div class="col-5 col-md-4">
                      <div class="icon-big text-center icon-warning">
                        <i class="nc-icon nc-globe text-warning"></i>
                      </div>
                    </div>
                    <div class="col-7 col-md-8">
                      <div class="numbers">
                        <p class="card-category">Jumlah Menunggu Buku Diambil</p>
                        <p class="card-title"><?php echo $jumlahKeseluruhanDiambil['count(id_peminjaman)']; ?><p>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
          </div>
          <div class="col-lg-3 col-md-6 col-sm-6">
              <div class="card card-stats">
                <div class="card-body ">
                  <div class="row">
                    <div class="col-5 col-md-4">
                      <div class="icon-big text-center icon-warning">
                        <i class="nc-icon nc-globe text-warning"></i>
                      </div>
                    </div>
                    <div class="col-7 col-md-8">
                      <div class="numbers">
                        <p class="card-category">Jumlah Menunggu Pembayaran</p>
                        <p class="card-title"><?php echo $jumlahKeseluruhanPembayaran['count(id_peminjaman)']; ?><p>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
          </div>
          <div class="col-lg-3 col-md-6 col-sm-6">
              <div class="card card-stats">
                <div class="card-body ">
                  <div class="row">
                    <div class="col-5 col-md-4">
                      <div class="icon-big text-center icon-warning">
                        <i class="nc-icon nc-globe text-warning"></i>
                      </div>
                    </div>
                    <div class="col-7 col-md-8">
                      <div class="numbers">
                        <p class="card-category">Jumlah Buku Dikembalikan</p>
                        <p class="card-title"><?php echo $jumlahKeseluruhanDikembalikan['count(id_peminjaman)']; ?><p>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
          </div>
        </div>
        <div class="row">
          <div class="col-lg-3 col-md-6 col-sm-6">
            <div class="card card-stats">
              <div class="card-body ">
                <div class="row">
                  <div class="col-5 col-md-4">
                    <div class="icon-big text-center icon-warning">
                      <i class="nc-icon nc-globe text-warning"></i>
                    </div>
                  </div>
                  <div class="col-7 col-md-8">
                    <div class="numbers">
                      <p class="card-category">Jumlah Buku</p>
                      <p class="card-title"><?php echo $jumlahKeseluruhanBuku['count(kode_buku)']; ?><p>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-lg-3 col-md-6 col-sm-6">
            <div class="card card-stats">
              <div class="card-body ">
                <div class="row">
                  <div class="col-5 col-md-4">
                    <div class="icon-big text-center icon-warning">
                      <i class="nc-icon nc-globe text-warning"></i>
                    </div>
                  </div>
                  <div class="col-7 col-md-8">
                    <div class="numbers">
                      <p class="card-category">Jumlah Buku Dipinjam</p>
                      <p class="card-title"><?php echo $jumlahKeseluruhanDipinjam['count(id_peminjaman)']; ?><p>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>


        <!--<div class="row">
          <div class="col-md-12">
            <div class="card ">
              <div class="card-header ">
                <h5 class="card-title">Users Behavior</h5>
                <p class="card-category">24 Hours performance</p>
              </div>
              <div class="card-body ">
                <canvas id=chartHours width="400" height="100"></canvas>
              </div>
              <div class="card-footer ">
                <hr>
                <div class="stats">
                  <i class="fa fa-history"></i> Updated 3 minutes ago
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-4">
            <div class="card ">
              <div class="card-header ">
                <h5 class="card-title">Email Statistics</h5>
                <p class="card-category">Last Campaign Performance</p>
              </div>
              <div class="card-body ">
                <canvas id="chartEmail"></canvas>
              </div>
              <div class="card-footer ">
                <div class="legend">
                  <i class="fa fa-circle text-primary"></i> Opened
                  <i class="fa fa-circle text-warning"></i> Read
                  <i class="fa fa-circle text-danger"></i> Deleted
                  <i class="fa fa-circle text-gray"></i> Unopened
                </div>
                <hr>
                <div class="stats">
                  <i class="fa fa-calendar"></i> Number of emails sent
                </div>
              </div>
            </div>
          </div>
          <div class="col-md-8">
            <div class="card card-chart">
              <div class="card-header">
                <h5 class="card-title">NASDAQ: AAPL</h5>
                <p class="card-category">Line Chart with Points</p>
              </div>
              <div class="card-body">
                <canvas id="speedChart" width="400" height="100"></canvas>
              </div>
              <div class="card-footer">
                <div class="chart-legend">
                  <i class="fa fa-circle text-info"></i> Tesla Model S
                  <i class="fa fa-circle text-warning"></i> BMW 5 Series
                </div>
                <hr />
                <div class="card-stats">
                  <i class="fa fa-check"></i> Data information certified
                </div>
              </div>
            </div>
          </div>-->
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
  <script>
    $(document).ready(function() {
      // Javascript method's body can be found in assets/assets-for-demo/js/demo.js
      demo.initChartsPages();
    });
  </script>
</body>

</html>
