<?php
  include 'layout/sidebar.php';
  include('../database/connection.php');

  $username = '';
  $email = '';
  $nama = '';
  $kode = '';
  $jumlah = '';

  $query = "SELECT * FROM users WHERE role = 'admin'";
  $stmt = $conn->prepare($query);
  $stmt->execute();
  $daftar_admin = $stmt->get_result();

$query = "SELECT MAX(CAST(nrp_nidn AS UNSIGNED)) AS max_nrp_nidn FROM users WHERE role = 'admin'";
$stmt = $conn->prepare($query);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

$last_admin = $row['max_nrp_nidn'] ?? 0;
$next_admin = (string)($last_admin + 1);

  if (isset($_POST['search_admin']) && !empty($_SERVER['HTTP_X_REQUESTED_WITH'])) {
    $search = $_POST['search_admin'];
    $query = "SELECT * FROM users WHERE 
                (username LIKE ? OR 
                nama LIKE ?) AND
                role = 'admin'";
    $stmt = $conn->prepare($query);
    $param = "%" . $search . "%";
    $stmt->bind_param("ss", $param, $param);
    $stmt->execute();
    $daftar_admin = $stmt->get_result();

    while ($row = $daftar_admin->fetch_assoc()) {
        echo "<tr>
                <td>{$row['username']}</td>
                <td>{$row['nama']}</td>
                <td>{$row['kode_autentikasi']}</td>
              </tr>";
    }
    exit;
  }

  $admin_baru_ditambahkan = false;
  $email = $_POST['email'] ?? '';
  $username = $_POST['username'] ?? '';
  $password = $_POST['password'] ?? '';
  $nama = $_POST['nama'] ?? '';
  $kode = $_POST['kode-autentikasi'] ?? '';

  function kodeAutentikasi($length = 6) {
      return str_pad(random_int(0, 999999), $length, '0', STR_PAD_LEFT);
  }

  if (isset($_POST['generate'])) {
      $kode = kodeAutentikasi();
  }

  if (isset($_POST['tambah'])) {
    $foto = $_FILES['foto'];
    $uploadDir = "../images/user/";
    $nama_file = basename($foto['name']);
    $filePath = $uploadDir . $nama_file;

    $successTambah = false;

    if (move_uploaded_file($foto['tmp_name'], $filePath)) {
        $stmt = $conn->prepare("INSERT INTO users (nrp_nidn, role, email, username, password, kode_autentikasi, nama, foto) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $role = 'admin';
        $stmt->bind_param("ssssssss", $next_admin, $role, $email, $username, $password, $kode, $nama, $nama_file);
        if ($stmt->execute()) {
            $admin_baru_ditambahkan = true;
            $successTambah = true;
            $email = '';
            $username = '';
            $password = '';
            $nama = '';
            $kode = '';
        }
    } else {
        echo "Gagal mengupload file foto.";
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
  <title>Manajemen Admin</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0, shrink-to-fit=no" />

  <!-- Fonts and icons -->
  <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700,200" rel="stylesheet" />
  <link href="https://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css" rel="stylesheet" />

  <!-- Bootstrap and Paper Dashboard CSS -->
  <link href="assets/css/bootstrap.min.css" rel="stylesheet" />
  <link href="assets/css/paper-dashboard.css?v=2.0.1" rel="stylesheet" />

  <!-- Custom Style -->
  <link rel="stylesheet" type="text/css" href="style.css" />

  <!-- DataTables CSS -->
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css" />
</head>

<body class="">
  <div class="wrapper ">
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
            <a class="navbar-brand" href="javascript:;">Manajemen Admin</a>
          </div>
          <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navigation" aria-controls="navigation-index" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-bar navbar-kebab"></span>
            <span class="navbar-toggler-bar navbar-kebab"></span>
            <span class="navbar-toggler-bar navbar-kebab"></span>
          </button>
        </div>
      </nav>

    <div class="content">
      <div class="tabs">
        <button class="tab active" onclick="switchTab('admin')">Daftar Admin</button>
        <button class="tab" onclick="switchTab('tambah')">Tambah Admin</button>
      </div>

      <!-- Daftar Admin -->
      <div id="admin" class="tab-content active">
        <div class="row">
          <div class="col-md-12">
            <div class="card">
              <div class="card-header">
                <h4 class="card-title">Daftar Admin</h4>
              </div>
              <form method="POST">
                <div class="form-group mb-0 w-100">
                  <input type="text" id="search-admin" name="search_admin"
                    class="form-control w-100"
                    placeholder="Search..."
                    value="<?= htmlspecialchars($_POST['search_admin'] ?? '') ?>">
                </div>
              </form>
              <div class="card-body">
                <div class="table-responsive">
                  <table class="table" id="daftar-admin">
                    <thead class=" text-primary">
                      <th>
                        Username Admin
                      </th>  
                      <th>
                        Nama Admin
                      </th>
                      <th>
                        Kode Autentikasi
                      </th>
                    </thead>
                    <tbody>
                      <?php if ($daftar_admin && $daftar_admin->num_rows > 0): while($row = $daftar_admin->fetch_assoc()) { ?>
                        <tr>
                          <td>
                            <?php echo $row['username']; ?>
                          </td>
                          <td>
                            <?php echo $row['nama']; ?>
                          </td>
                          <td>
                            <?php echo $row['kode_autentikasi']; ?>
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

    <div id="tambah" class="tab-content">
       <div class="row">
          <div class="col-md-12">
            <div class="card card-user">
              <div class="card-header">
                <h5 class="card-title">Tambah Admin</h5>
              </div>
              <div class="card-body">
                <form method="POST" action="<?= $_SERVER['PHP_SELF'] . '?tab=tambah' ?>" enctype="multipart/form-data">
                  <div class="row">
                    <div class="col-md-5 pr-1">
                      <div class="form-group">
                        <label>Username</label>
                        <input type="text" name="username" class="form-control" placeholder="Masukkan username" value="<?= htmlspecialchars($username) ?>" required>
                      </div>
                    </div>
                    <div class="col-md-4 pl-1">
                      <div class="form-group">
                        <label for="exampleInputEmail1">Email address</label>
                        <input type="email" name="email" class="form-control" placeholder="Masukkan email" value="<?= htmlspecialchars($email) ?>" required>
                      </div>
                    </div>
                    <div class="col-md-3 px-1">
                      <div class="form-group">
                        <label>Password</label>
                        <input type="text" name="password" class="form-control" placeholder="Masukkan password" value="<?= htmlspecialchars($password) ?>" required>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-12">
                      <div class="form-group">
                        <label>Nama</label>
                        <input type="text" name="nama" class="form-control" placeholder="Masukkan nama" value="<?= htmlspecialchars($nama) ?>" required>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-12">
                      <div class="form-group">
                        <label>Kode Autentikasi</label>
                        <input type="text" name="kode-autentikasi" class="form-control" value="<?= htmlspecialchars($kode) ?>" readonly>
                        <button type="submit" name="generate" class="btn btn-secondary">Generate</button>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-12">
                      <label>Foto</label>
                      <input type="file" name="foto" class="form-control">
                    </div>
                  </div>
                  <div class="row">
                    <div class="update ml-auto mr-auto">
                      <button type="submit" name="tambah" class="btn btn-primary">Tambah Admin</button>
                    </div>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
    </div>

    <div class="modal fade" id="tambahModal" tabindex="-1" role="dialog" aria-labelledby="tambahModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header bg-light">
            <h5 class="modal-title" id="tambahModalLabel">Sukses</h5>
          </div>
          <div class="modal-body">
            Tambah Admin Berhasil!
          </div>
        </div>
      </div>
    </div>

  <script>
    function switchTab(tabName) {
      localStorage.setItem('activeTab', tabName);

      document.querySelectorAll('.tab').forEach(tab => tab.classList.remove('active'));
      document.querySelectorAll('.tab-content').forEach(content => content.classList.remove('active'));

      document.querySelector(`.tab[onclick="switchTab('${tabName}')"]`).classList.add('active');
      document.getElementById(tabName).classList.add('active');
    }

    document.addEventListener('DOMContentLoaded', function () {
    const urlParams = new URLSearchParams(window.location.search);
    const tabParam = urlParams.get('tab');

    if (tabParam) {
      switchTab(tabParam);
      localStorage.setItem('activeTab', tabParam);
    } else {
      const activeTab = localStorage.getItem('activeTab') || 'admin';
      switchTab(activeTab);
    }

    if (history.replaceState) {
      const cleanUrl = window.location.origin + window.location.pathname;
      history.replaceState(null, '', cleanUrl);
    }
  });
  </script>


    <script>
      document.getElementById("search-admin").addEventListener("keyup", function () {
        var search_admin = this.value;
        var xhr = new XMLHttpRequest();
        xhr.open("POST", "<?= $_SERVER['PHP_SELF']; ?>", true);
        xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhr.setRequestHeader("X-Requested-With", "XMLHttpRequest");
        xhr.onreadystatechange = function () {
          if (xhr.readyState == 4 && xhr.status == 200) {
            document.querySelector("#daftar-admin tbody").innerHTML = xhr.responseText;
          }
        };
        xhr.send("search_admin=" + encodeURIComponent(search_admin));
      });
    </script>

    <script>
      const searchInputs = [
        document.getElementById("search_peminjaman"),
        document.getElementById("search_tanggal_peminjaman"),
        document.getElementById("search_tanggal_pengembalian")
      ];

      searchInputs.forEach(input => {
        input.addEventListener("input", function () {
          const search_peminjaman = document.getElementById("search_peminjaman").value;
          const search_tanggal_peminjaman = document.getElementById("search_tanggal_peminjaman").value;
          const search_tanggal_pengembalian = document.getElementById("search_tanggal_pengembalian").value;

          const params = new URLSearchParams();
          params.append("search_peminjaman", search_peminjaman);
          params.append("search_tanggal_peminjaman", search_tanggal_peminjaman);
          params.append("search_tanggal_pengembalian", search_tanggal_pengembalian);

          const xhr = new XMLHttpRequest();
          xhr.open("POST", "<?= $_SERVER['PHP_SELF']; ?>", true);
          xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
          xhr.setRequestHeader("X-Requested-With", "XMLHttpRequest");
          xhr.onreadystatechange = function () {
            if (xhr.readyState == 4 && xhr.status == 200) {
              document.querySelector("#daftar-peminjaman tbody").innerHTML = xhr.responseText;
            }
          };
          xhr.send(params.toString());
        });
      });
    </script>

    <script>
      const searchKonfirmasi = [
        document.getElementById("search_konfirmasi"),
        document.getElementById("search_tanggal_peminjaman"),
        document.getElementById("search_tanggal_pengembalian")
      ];

      searchKonfirmasi.forEach(input => {
        input.addEventListener("input", function () {
          const search_konfirmasi = document.getElementById("search_konfirmasi").value;
          const search_tanggal_peminjaman = document.getElementById("search_tanggal_peminjaman").value;
          const search_tanggal_pengembalian = document.getElementById("search_tanggal_pengembalian").value;

          const params = new URLSearchParams();
          params.append("search_konfirmasi", search_konfirmasi);
          params.append("search_tanggal_peminjaman", search_tanggal_peminjaman);
          params.append("search_tanggal_pengembalian", search_tanggal_pengembalian);

          const xhr = new XMLHttpRequest();
          xhr.open("POST", "<?= $_SERVER['PHP_SELF']; ?>", true);
          xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
          xhr.setRequestHeader("X-Requested-With", "XMLHttpRequest");
          xhr.onreadystatechange = function () {
            if (xhr.readyState == 4 && xhr.status == 200) {
              document.querySelector("#daftar-konfirmasi tbody").innerHTML = xhr.responseText;
            }
          };
          xhr.send(params.toString());
        });
      });
    </script>

      <div class="content">
        
      </div>

      <!-- End Navbar -->
      <div class="content">
        
      </div>
      
    </div>
  </div>

  <!-- Core JS Files -->
  <script src="assets/js/core/jquery.min.js"></script> <!-- jQuery harus di-load dulu -->
  <script src="assets/js/core/popper.min.js"></script>
  <script src="assets/js/core/bootstrap.min.js"></script>

  <!-- DataTables CSS & JS (Load setelah jQuery) -->
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
  <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>

  <!-- Perfect Scrollbar Plugin -->
  <script src="assets/js/plugins/perfect-scrollbar.jquery.min.js"></script>

  <!-- Google Maps Plugin (Opsional, hanya aktifkan jika kamu butuh peta) -->
  <!-- Ganti 'YOUR_KEY_HERE' dengan API Key Google Maps-mu jika digunakan -->
  <script src="https://maps.googleapis.com/maps/api/js?key=YOUR_KEY_HERE"></script>

  <!-- Chart JS (Opsional jika kamu pakai chart) -->
  <script src="assets/js/plugins/chartjs.min.js"></script>

  <!-- Notifications Plugin (Opsional untuk notifikasi bootstrap-notify) -->
  <script src="assets/js/plugins/bootstrap-notify.js"></script>

  <!-- Paper Dashboard JS -->
  <script src="assets/js/paper-dashboard.min.js?v=2.0.1" type="text/javascript"></script>

  <!-- Demo script (sebaiknya tidak digunakan di produksi) -->
  <script src="assets/demo/demo.js"></script>

  
  <?php if (isset($successTambah) && $successTambah): ?>
      <script>
        $(document).ready(function() {
          $('#tambahModal').modal('show');
          setTimeout(function() {
            window.location.href = 'manajemen-admin.php?tab=tambah';
          }, 2000);
        });
      </script>
  <?php endif; ?>
</body>

</html>