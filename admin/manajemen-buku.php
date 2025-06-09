<?php
  include 'layout/sidebar.php';
  include('../database/connection.php');
  $buku = null;
  $buku_terlambat = null;

  $query = "SELECT users.nama, buku.nama_buku, peminjaman.tanggal_peminjaman, peminjaman.tanggal_pengembalian, peminjaman.kode_buku, peminjaman.nrp_nidn
            FROM users
            INNER JOIN peminjaman ON users.nrp_nidn = peminjaman.nrp_nidn
            INNER JOIN buku ON peminjaman.kode_buku = buku.kode_buku
            WHERE peminjaman.status = 'dipinjam'";

  $query2 = "SELECT users.nama, buku.nama_buku, peminjaman.tanggal_peminjaman, peminjaman.tanggal_pengembalian, peminjaman.denda, peminjaman.kode_buku, peminjaman.nrp_nidn
            FROM users
            INNER JOIN peminjaman ON users.nrp_nidn = peminjaman.nrp_nidn
            INNER JOIN buku ON peminjaman.kode_buku = buku.kode_buku
            WHERE peminjaman.status = 'pembayaran'";

  $query3 = "SELECT * FROM buku";
  $stmt = $conn->prepare($query3);
  $stmt->execute();
  $daftar_buku = $stmt->get_result();          

  if ($_SERVER["REQUEST_METHOD"] == "GET") {
      $search_username = $_GET['search_username'] ?? '';
      $search_peminjaman = $_GET['search_tanggal_peminjaman'] ?? '';
      $search_pengembalian = $_GET['search_tanggal_pengembalian'] ?? '';

      if (!empty($search_username) || !empty($search_peminjaman) || !empty($search_pengembalian)) {
          $query = "SELECT users.nama, buku.nama_buku, peminjaman.tanggal_peminjaman, peminjaman.tanggal_pengembalian 
                    FROM users
                    INNER JOIN peminjaman ON users.nrp_nidn = peminjaman.nrp_nidn
                    INNER JOIN buku ON peminjaman.kode_buku = buku.kode_buku
                    WHERE peminjaman.status = 'dipinjam'";
                  
          $query2 = "SELECT users.nama, buku.nama_buku, peminjaman.tanggal_peminjaman, peminjaman.tanggal_pengembalian 
                    FROM users
                    INNER JOIN peminjaman ON users.nrp_nidn = peminjaman.nrp_nidn
                    INNER JOIN buku ON peminjaman.kode_buku = buku.kode_buku
                    WHERE peminjaman.status = 'pembayaran'";

          $params = [];
          $types = '';

          if (!empty($search_username)) {
              $query .= " AND users.nama LIKE ?";
              $params[] = "%$search_username%";
              $types .= 's';
              
              $query2 .= " AND users.nama LIKE ?";
              $params[] = "%$search_username%";
              $types .= 's';
          }

          if (!empty($search_peminjaman)) {
              $query .= " AND peminjaman.tanggal_peminjaman >= ?";
              $params[] = $search_peminjaman;
              $types .= 's';
              
              $query2 .= " AND peminjaman.tanggal_peminjaman >= ?";
              $params[] = $search_peminjaman;
              $types .= 's';
          }

          if (!empty($search_pengembalian)) {
              $query .= " AND peminjaman.tanggal_pengembalian <= ?";
              $params[] = $search_pengembalian;
              $types .= 's';
              
              $query2 .= " AND peminjaman.tanggal_pengembalian <= ?";
              $params[] = $search_pengembalian;
              $types .= 's';
          }

          $stmt = $conn->prepare($query);
          if ($params) {
              $stmt->bind_param($types, ...$params);
          }
          $stmt->execute();
          $buku = $stmt->get_result();

          $stmt2 = $conn->prepare($query2);
          if ($params) {
              $stmt2->bind_param($types, ...$params);
          }
          $stmt2->execute();
          $buku_terlambat = $stmt2->get_result();
      } else {
          $stmt = $conn->prepare($query);
          $stmt->execute();
          $buku = $stmt->get_result();
          $stmt2 = $conn->prepare($query2);
          $stmt2->execute();
          $buku_terlambat = $stmt2->get_result();
      }
  }

  if (isset($_POST['update'])){
    $kode_buku = $_POST['kode_buku'];
    $nrp_nidn = $_POST['nrp_nidn'];

    $stmt = $conn->prepare("UPDATE peminjaman SET status = 'lunas' WHERE kode_buku = ? AND nrp_nidn = ?");
    $stmt->bind_param("ss", $kode_buku, $nrp_nidn);
    $stmt->execute();
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
  <link rel="stylesheet" type="text/css" href="style.css">
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
            <a class="navbar-brand" href="javascript:;">Manajemen Buku</a>
          </div>
          <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navigation" aria-controls="navigation-index" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-bar navbar-kebab"></span>
            <span class="navbar-toggler-bar navbar-kebab"></span>
            <span class="navbar-toggler-bar navbar-kebab"></span>
          </button>
          <div class="collapse navbar-collapse justify-content-end" id="navigation">
            <form>
              <div class="input-group no-border">
                <input type="text" value="" class="form-control" placeholder="Search...">
                <div class="input-group-append">
                  <div class="input-group-text">
                    <i class="nc-icon nc-zoom-split"></i>
                  </div>
                </div>
              </div>
            </form>
            <ul class="navbar-nav">
              <li class="nav-item">
                <a class="nav-link btn-magnify" href="javascript:;">
                  <i class="nc-icon nc-layout-11"></i>
                  <p>
                    <span class="d-lg-none d-md-block">Stats</span>
                  </p>
                </a>
              </li>
              <li class="nav-item btn-rotate dropdown">
                <a class="nav-link dropdown-toggle" href="http://example.com" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  <i class="nc-icon nc-bell-55"></i>
                  <p>
                    <span class="d-lg-none d-md-block">Some Actions</span>
                  </p>
                </a>
                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownMenuLink">
                  <a class="dropdown-item" href="#">Action</a>
                  <a class="dropdown-item" href="#">Another action</a>
                  <a class="dropdown-item" href="#">Something else here</a>
                </div>
              </li>
              <li class="nav-item">
                <a class="nav-link btn-rotate" href="javascript:;">
                  <i class="nc-icon nc-settings-gear-65"></i>
                  <p>
                    <span class="d-lg-none d-md-block">Account</span>
                  </p>
                </a>
              </li>
            </ul>
          </div>
        </div>
      </nav>


    <div class="content">
      <div class="tabs">
        <button class="tab active" onclick="switchTab('buku')">Daftar Buku</button>
        <button class="tab" onclick="switchTab('peminjaman')">Daftar Peminjaman Buku</button>
        <button class="tab" onclick="switchTab('konfirmasi')">Daftar Konfirmasi Buku</button>
      </div>

      <!-- Daftar Buku -->
      <div id="buku" class="tab-content active">
        <div class="row">
          <div class="col-md-12">
            <div class="card">
              <div class="card-header">
                <h4 class="card-title">Daftar Peminjaman Buku</h4>
              </div>
              <div class="card-body">
                <div class="table-responsive">
                  <table class="table">
                    <thead class=" text-primary">
                      <th>
                        Kode Buku
                      </th>  
                      <th>
                        Nama Buku
                      </th>
                      <th>
                        Jenis Buku
                      </th>
                      <th>
                        Pengarang
                      </th>
                      <th>
                        Penerbit
                      </th>
                      <th>
                        Jumlah Buku
                      </th>
                      <th>
                        Status
                      </th>
                    </thead>
                    <tbody>
                      <?php if ($buku): while($row = $daftar_buku->fetch_assoc()) { ?>
                        <tr>
                          <td>
                            <?php echo $row['kode_buku']; ?>
                          </td>
                          <td>
                            <?php echo $row['nama_buku']; ?>
                          </td>
                          <td>
                            <?php echo $row['jenis_buku']; ?>
                          </td>
                          <td>
                            <?php echo $row['pengarang']; ?>
                          </td>
                          <td>
                            <?php echo $row['penerbit']; ?>
                          </td>
                          <td>
                            <?php echo $row['jumlah_buku']; ?>
                          </td>
                          <td>
                            <?php echo $row['status']; ?>
                          </td>
                        </tr>
                      <?php } endif; ?>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Daftar Peminjaman -->
      <div id="peminjaman" class="tab-content">
        <div class="row">
          <div class="col-md-12">
            <div class="card">
              <div class="card-body">
                <div class="row">
                  <form method="GET">
                    <div class="row">
                      <div class="col-md-3 pr-1">
                        <div class="form-group">
                          <label>Username Peminjam</label>
                          <input type="text" name="search_username" class="form-control"
                                placeholder="Masukkan username"
                                value="<?= htmlspecialchars($_GET['search_username'] ?? '') ?>">
                        </div>
                      </div>
                      <div class="col-md-3 pl-1">
                        <div class="form-group">
                          <label for="tanggal_pinjam" class="form-label">Tanggal Pinjam</label>
                          <input type="date" name="search_tanggal_peminjaman" id="tanggal_pinjam"
                                class="form-control"
                                value="<?= htmlspecialchars($_GET['search_tanggal_peminjaman'] ?? '') ?>">
                        </div>
                      </div>
                      <div class="col-md-3 px-1">
                        <div class="form-group">
                          <label for="tanggal_kembali" class="form-label">Tanggal Kembali</label>
                          <input type="date" name="search_tanggal_pengembalian" id="tanggal_kembali"
                                class="form-control"
                                value="<?= htmlspecialchars($_GET['search_tanggal_pengembalian'] ?? '') ?>">
                        </div>
                      </div>
                      <div class="col-md-3">
                        <div class="form-group">
                          <label>&nbsp;</label>
                          <button type="submit" class="btn btn-primary d-block w-100">Cari</button>
                        </div>
                      </div>
                    </div>
                  </form>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-md-12">
            <div class="card">
              <div class="card-header">
                <h4 class="card-title">Daftar Peminjaman Buku</h4>
              </div>
              <div class="card-body">
                <div class="table-responsive">
                  <table class="table">
                    <thead class=" text-primary">
                      <th>
                        Nama Buku
                      </th>
                      <th>
                        Nama Peminjam
                      </th>
                      <th>
                        Tanggal Peminjaman
                      </th>
                      <th>
                        Tanggal Pengembalian
                      </th>
                    </thead>
                    <tbody>
                      <?php if ($buku): while($row = $buku->fetch_assoc()) { ?>
                        <tr>
                          <td>
                            <?php echo $row['nama_buku']; ?>
                          </td>
                          <td>
                            <?php echo $row['nama']; ?>
                          </td>
                          <td>
                            <?php echo $row['tanggal_peminjaman']; ?>
                          </td>
                          <td>
                            <?php echo $row['tanggal_pengembalian']; ?>
                          </td>
                        </tr>
                      <?php } endif; ?>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Daftar Konfirmasi -->
      <div id="konfirmasi" class="tab-content">
        <div class="row">
          <div class="col-md-12">
            <div class="card">
              <div class="card-header">
                <h4 class="card-title">Daftar Konfirmasi Buku</h4>
              </div>
              <div class="card-body">
                <div class="table-responsive">
                  <table class="table">
                    <thead class=" text-primary">
                      <th>
                        Nama Buku
                      </th>
                      <th>
                        Nama Peminjam
                      </th>
                      <th>
                        Tanggal Peminjaman
                      </th>
                      <th>
                        Tanggal Pengembalian
                      </th>
                      <th>
                        Denda
                      </th>
                      <th>
                        Status
                      </th>
                    </thead>
                    <tbody>
                      <form method="POST">
                        <?php while($row = $buku_terlambat->fetch_assoc()) { ?>
                          <tr>
                            <td>
                              <?php echo $row['nama_buku']; ?>
                            </td>
                            <td>
                              <?php echo $row['nama']; ?>
                            </td>
                            <td>
                              <?php echo $row['tanggal_peminjaman']; ?>
                            </td>
                            <td>
                              <?php echo $row['tanggal_pengembalian']; ?>
                            </td>
                            <td>
                              <?php echo $row['denda']; ?>
                            </td>
                            <td>
                                <input type="hidden" name="kode_buku" value="<?php echo $row['kode_buku']; ?>">
                                <input type="hidden" name="nrp_nidn" value="<?php echo $row['nrp_nidn']; ?>">
                                <button type="submit" name="update" class="btn btn-primary d-block w-100">Terima</button>
                            </td>
                          </tr>
                        <?php } ?>
                      </form>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>
    </div>

    <script>
      function switchTab(tabName) {
        document.querySelectorAll('.tab').forEach(tab => tab.classList.remove('active'));
        document.querySelectorAll('.tab-content').forEach(content => content.classList.remove('active'));

        document.querySelector(`.tab[onclick="switchTab('${tabName}')"]`).classList.add('active');
        document.getElementById(tabName).classList.add('active');
      }
    </script>



      <div class="content">
        
      </div>

      <!-- End Navbar -->
      <div class="content">
        
      </div>
      <footer class="footer footer-black  footer-white ">
        <div class="container-fluid">
          <div class="row">
            <nav class="footer-nav">
              <ul>
                <li><a href="https://www.creative-tim.com" target="_blank">Creative Tim</a></li>
                <li><a href="https://www.creative-tim.com/blog" target="_blank">Blog</a></li>
                <li><a href="https://www.creative-tim.com/license" target="_blank">Licenses</a></li>
              </ul>
            </nav>
            <div class="credits ml-auto">
              <span class="copyright">
                © <script>
                  document.write(new Date().getFullYear())
                </script>, made with <i class="fa fa-heart heart"></i> by Creative Tim
              </span>
            </div>
          </div>
        </div>
      </footer>
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
</body>

</html>