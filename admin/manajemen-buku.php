<?php
  include 'layout/sidebar.php';
  include('../database/connection.php');
  $buku = null;
  $buku_terlambat = null;
  $jenis_buku = '';

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
                    WHERE 1=1";
                  
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

  if (isset($_POST['search_peminjaman']) && !empty($_SERVER['HTTP_X_REQUESTED_WITH'])) {
    $search_username = $_POST['search_peminjaman'] ?? '';
    $search_peminjaman = $_POST['search_tanggal_peminjaman'] ?? '';
    $search_pengembalian = $_POST['search_tanggal_pengembalian'] ?? '';

    if (!empty($search_peminjaman) || !empty($search_peminjaman) || !empty($search_pengembalian)) {
            $query = "SELECT users.nama, buku.nama_buku, peminjaman.tanggal_peminjaman, peminjaman.tanggal_pengembalian 
                      FROM users
                      INNER JOIN peminjaman ON users.nrp_nidn = peminjaman.nrp_nidn
                      INNER JOIN buku ON peminjaman.kode_buku = buku.kode_buku
                      WHERE 1=1";
                    
            
            $params = [];
            $types = '';

            if (!empty($search_username)) {
                $query .= " AND users.nama LIKE ?";
                $params[] = "%$search_username%";
                $types .= 's';
            }

            if (!empty($search_peminjaman)) {
                $query .= " AND peminjaman.tanggal_peminjaman >= ?";
                $params[] = $search_peminjaman;
                $types .= 's';
            }

            if (!empty($search_pengembalian)) {
                $query .= " AND peminjaman.tanggal_pengembalian <= ?";
                $params[] = $search_pengembalian;
                $types .= 's';
            }

            $stmt = $conn->prepare($query);
            if ($params) {
                $stmt->bind_param($types, ...$params);
            }
            $stmt->execute();
            $buku = $stmt->get_result();
        } else {
            $stmt = $conn->prepare($query);
            $stmt->execute();
            $buku = $stmt->get_result();
        }


      while ($row = $buku->fetch_assoc()) {
          echo "<tr>
                  <td>{$row['nama_buku']}</td>
                  <td>{$row['nama']}</td>
                  <td>{$row['tanggal_peminjaman']}</td>
                  <td>{$row['tanggal_pengembalian']}</td>
                </tr>";
      }
    exit;
  }

  if (isset($_POST['search_buku']) && !empty($_SERVER['HTTP_X_REQUESTED_WITH'])) {
    $search = $_POST['search_buku'];
    $query = "SELECT * FROM buku WHERE 
                kode_buku LIKE ? OR 
                nama_buku LIKE ? OR 
                jenis_buku LIKE ? OR 
                pengarang LIKE ? OR 
                penerbit LIKE ? OR 
                jumlah_buku LIKE ? OR 
                status LIKE ?";
    $stmt = $conn->prepare($query);
    $param = "%" . $search . "%";
    $stmt->bind_param("sssssss", $param, $param, $param, $param, $param, $param, $param);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>{$row['kode_buku']}</td>
                <td>{$row['nama_buku']}</td>
                <td>{$row['jenis_buku']}</td>
                <td>{$row['pengarang']}</td>
                <td>{$row['penerbit']}</td>
                <td>{$row['jumlah_buku']}</td>
                <td>{$row['status']}</td>
              </tr>";
    }
    exit;
  }


  if (isset($_POST['tambah'])) {
    $kode_buku = $_POST['kode_buku'] ?? '';
    $jenis_buku = $_POST['jenis_buku'] ?? '';
    $nama_buku = $_POST['nama_buku'] ?? '';
    $pengarang = $_POST['pengarang'] ?? '';
    $penerbit = $_POST['penerbit'] ?? '';
    $jumlah_halaman = $_POST['jumlah_halaman'] ?? '';
    $tahun_terbit = $_POST['tahun_terbit'] ?? '';
    $deskripsi_buku = $_POST['deskripsi_buku'] ?? '';
    $status = $_POST['status'] ?? 'Tersedia';
    $jumlah_buku = $_POST['jumlah_buku'] ?? '';
    $penyumbang = $_POST['penyumbang'] ?? '';
    $cover_buku = $_FILES['cover_buku'];
    $uploadDir = "../images/buku/";
    $nama_file = basename($cover_buku['name']);
    $filePath = $uploadDir . $nama_file;

    if (move_uploaded_file($cover_buku['tmp_name'], $filePath)) {
        $stmt = $conn->prepare("INSERT INTO buku (
            kode_buku, jenis_buku, nama_buku, pengarang, penerbit, jumlah_halaman, 
            tahun_terbit, deskripsi_buku, status, penyumbang, cover_buku, jumlah_buku
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

        if (!$stmt) {
            echo "Prepare failed: " . $conn->error;
            exit;
        }

        $stmt->bind_param(
            "ssssssssssss",
            $kode_buku, $jenis_buku, $nama_buku, $pengarang, $penerbit,
            $jumlah_halaman, $tahun_terbit, $deskripsi_buku, $status,
            $penyumbang, $nama_file, $jumlah_buku
        );

        if ($stmt->execute()) {
            echo "Data berhasil ditambahkan.";
        } else {
            echo "Gagal menambahkan data: " . $stmt->error;
        }
    } else {
        echo "Gagal mengupload file cover. Error code: " . $cover_buku['error'];
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
        <button class="tab" onclick="switchTab('tambah')">Tambah Buku</button>
      </div>

      <!-- Daftar Buku -->
      <div id="buku" class="tab-content active">
        <div class="row">
          <div class="col-md-12">
            <div class="card">
              <div class="card-header">
                <h4 class="card-title">Daftar Peminjaman Buku</h4>
              </div>
              <form method="POST">
                <div class="form-group mb-0 w-100">
                  <input type="text" id="search-buku" name="search_buku"
                    class="form-control w-100"
                    placeholder="Search..."
                    value="<?= htmlspecialchars($_POST['search_buku'] ?? '') ?>">
                </div>
              </form>
              <div class="card-body">
                <div class="table-responsive">
                  <table class="table" id="daftar-buku">
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
                      <?php if ($daftar_buku && $daftar_buku->num_rows > 0): while($row = $daftar_buku->fetch_assoc()) { ?>
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
              <div class="card-header">
                <h4 class="card-title">Daftar Peminjaman Buku</h4>
                <div class="row">
                  <form method="POST" class="row mb-3">
                    <div class="col-md-4">
                      <input type="text" name="search_peminjaman" id="search_peminjaman"
                        class="form-control" placeholder="Search..."
                        value="<?= htmlspecialchars($_POST['search_peminjaman'] ?? '') ?>">
                    </div>
                    <div class="col-md-3">
                      <input type="date" name="search_tanggal_peminjaman" id="search_tanggal_peminjaman"
                        class="form-control"
                        value="<?= htmlspecialchars($_POST['search_tanggal_peminjaman'] ?? '') ?>">
                    </div>
                    <div class="col-md-3">
                      <input type="date" name="search_tanggal_pengembalian" id="search_tanggal_pengembalian"
                        class="form-control"
                        value="<?= htmlspecialchars($_POST['search_tanggal_pengembalian'] ?? '') ?>">
                    </div>
                  </form>
                </div>
              </div>
              <div class="card-body">
                <div class="table-responsive">
                  <table class="table" id="daftar-peminjaman">
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
                      <?php if ($buku && $buku->num_rows > 0): while($row = $buku->fetch_assoc()): ?>
                        <tr>
                          <td><?= htmlspecialchars($row['nama_buku']) ?></td>
                          <td><?= htmlspecialchars($row['nama']) ?></td>
                          <td><?= htmlspecialchars($row['tanggal_peminjaman']) ?></td>
                          <td><?= htmlspecialchars($row['tanggal_pengembalian']) ?></td>
                        </tr>
                      <?php endwhile; else: ?>
                        <tr><td colspan="4">Tidak ada data ditemukan.</td></tr>
                      <?php endif; ?>
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

    <div id="tambah" class="tab-content">
       <div class="row">
          <div class="col-md-12">
            <div class="card card-user">
              <div class="card-header">
                <h5 class="card-title">Tambah Buku</h5>
              </div>
              <div class="card-body">
                <form method="POST" action="" enctype="multipart/form-data">
                  <div class="row">
                    <div class="col-md-6 pr-1">
                      <div class="form-group">
                        <label>Kode Buku</label>
                        <input type="text" name="kode_buku" class="form-control" placeholder="Masukkan kode buku" required>
                      </div>
                    </div>
                    <div class="col-md-6 pl-1">
                      <div class="form-group">
                        <label>Jenis Buku</label>
                        <select class="form-control" name="jenis_buku" required>
                            <option value="TA" <?= $jenis_buku == 'TA' ? 'selected' : '' ?>>Tugas Akhir</option>
                            <option value="KP" <?= $jenis_buku == 'KP' ? 'selected' : '' ?>>Kuliah Praktik</option>
                            <option value="SIP" <?= $jenis_buku == 'SIP' ? 'selected' : '' ?>>SIP</option>
                            <option value="MBKM" <?= $jenis_buku == 'MBKM' ? 'selected' : '' ?>>MBKM</option>
                            <option value="Umum" <?= $jenis_buku == 'Umum' ? 'selected' : '' ?>>Umum</option>
                        </select>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-12">
                      <div class="form-group">
                        <label>Nama Buku</label>
                        <input type="text" name="nama_buku" class="form-control" placeholder="Masukkan nama buku" required>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-6 pr-1">
                      <div class="form-group">
                        <label>Pengarang</label>
                        <input type="text" name="pengarang" class="form-control" placeholder="Masukkan pengarang buku" required>
                      </div>
                    </div>
                    <div class="col-md-6 pl-1">
                      <div class="form-group">
                        <label>Penerbit</label>
                        <input type="text" name="penerbit" class="form-control" placeholder="Masukkan penerbit buku" required>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-6 pr-1">
                      <div class="form-group">
                        <label>Jumlah Halaman</label>
                        <input type="text" name="jumlah_halaman" class="form-control" placeholder="Masukkan jumlah halaman buku" required>
                      </div>
                    </div>
                    <div class="col-md-6 pl-1">
                      <div class="form-group">
                        <label>Tahun Terbit</label>
                        <input type="text" name="tahun_terbit" class="form-control" placeholder="Masukkan tahun terbit buku" required>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-12">
                      <div class="form-group">
                        <label>Deskripsi Buku</label>
                        <textarea type="text" name="deskripsi_buku" class="form-control textarea"></textarea>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-6 pr-1">
                      <div class="form-group">
                        <label>Jumlah Buku</label>
                        <input type="text" name="jumlah_buku" class="form-control" placeholder="Masukkan jumlah buku" required>
                      </div>
                    </div>
                    <div class="col-md-6 pl-1">
                      <div class="form-group">
                        <label>Penyumbang</label>
                        <input type="text" name="penyumbang" class="form-control" placeholder="Masukkan siapa yang menyumbang" required>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-6 pr-1">
                      <label>Cover Buku</label>
                      <input type="file" name="cover_buku" class="form-control" required>
                    </div>  
                    <div class="col-md-6 pr-1">
                      <div class="update ml-auto mr-auto">
                      <button type="submit" name="tambah" class="btn btn-primary">Tambah Buku</button>
                      </div>
                    </div>                       
                  </div>
                  <div class="row">
                    
                  </div>
                </form>
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


    <script>
      document.getElementById("search-buku").addEventListener("keyup", function () {
        var search_buku = this.value;
        var xhr = new XMLHttpRequest();
        xhr.open("POST", "<?= $_SERVER['PHP_SELF']; ?>", true);
        xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhr.setRequestHeader("X-Requested-With", "XMLHttpRequest");
        xhr.onreadystatechange = function () {
          if (xhr.readyState == 4 && xhr.status == 200) {
            // Pastikan target tbody benar
            document.querySelector("#daftar-buku tbody").innerHTML = xhr.responseText;
          }
        };
        xhr.send("search_buku=" + encodeURIComponent(search_buku));
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