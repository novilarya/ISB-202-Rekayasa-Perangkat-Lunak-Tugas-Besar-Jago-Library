<?php
  include 'layout/sidebar.php';
  include('../database/connection.php');

  $buku = null;
  $buku_terlambat = null;
  $buku_konfirmasi = null;
  $jenis_buku = '';

  $query = "SELECT users.nama, buku.nama_buku, peminjaman.tanggal_peminjaman, peminjaman.tanggal_pengembalian, peminjaman.kode_buku, peminjaman.nrp_nidn
            FROM users
            INNER JOIN peminjaman ON users.nrp_nidn = peminjaman.nrp_nidn
            INNER JOIN buku ON peminjaman.kode_buku = buku.kode_buku
            WHERE peminjaman.status = 'dipinjam'";

  $query2 = "SELECT users.nama, buku.nama_buku, peminjaman.tanggal_peminjaman, peminjaman.tanggal_pengembalian, peminjaman.denda, peminjaman.kode_buku, peminjaman.nrp_nidn, peminjaman.status
            FROM users
            INNER JOIN peminjaman ON users.nrp_nidn = peminjaman.nrp_nidn
            INNER JOIN buku ON peminjaman.kode_buku = buku.kode_buku
            WHERE peminjaman.status = 'pembayaran' OR peminjaman.status = 'konfirmasi'";

  $stmt = $conn->prepare($query2);
  $stmt->execute();
  $buku_konfirmasi = $stmt->get_result();             

  $query3 = "SELECT * FROM buku";
  $stmt = $conn->prepare($query3);
  $stmt->execute();
  $daftar_buku = $stmt->get_result();          

  if (isset($_POST['update_pembayaran'])){
    $kode_buku = $_POST['kode_buku'];
    $nrp_nidn = $_POST['nrp_nidn'];

    $stmt = $conn->prepare("UPDATE peminjaman SET status = 'dikembalikan' WHERE kode_buku = ? AND nrp_nidn = ?");
    $stmt->bind_param("ss", $kode_buku, $nrp_nidn);
    $stmt->execute();
    header("Location: " . $_SERVER['PHP_SELF'] . "?tab=konfirmasi");
  }

  if (isset($_POST['update_konfirmasi'])){
    $kode_buku = $_POST['kode_buku'];
    $nrp_nidn = $_POST['nrp_nidn'];

    $stmt = $conn->prepare("UPDATE peminjaman SET status = 'dikembalikan' WHERE kode_buku = ? AND nrp_nidn = ?");
    $stmt->bind_param("ss", $kode_buku, $nrp_nidn);
    $stmt->execute();
    
    header("Location: " . $_SERVER['PHP_SELF'] . "?tab=konfirmasi");
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
      $buku_peminjaman = $stmt->get_result();
      } else {
        $stmt = $conn->prepare($query);
        $stmt->execute();
        $buku_peminjaman = $stmt->get_result();
      }
      while ($row = $buku_peminjaman->fetch_assoc()) {
        echo "<tr>
          <td>{$row['nama_buku']}</td>
          <td>{$row['nama']}</td>
          <td>{$row['tanggal_peminjaman']}</td>
          <td>{$row['tanggal_pengembalian']}</td>
        </tr>";
      }
    exit;
  }

  if (isset($_POST['search_konfirmasi']) && !empty($_SERVER['HTTP_X_REQUESTED_WITH'])) {
    $search_username = $_POST['search_konfirmasi'] ?? '';
    $search_peminjaman = $_POST['search_tanggal_peminjaman'] ?? '';
    $search_pengembalian = $_POST['search_tanggal_pengembalian'] ?? '';

    if (!empty($search_peminjaman) || !empty($search_peminjaman) || !empty($search_pengembalian)) {
      $query2 = "SELECT users.nama, buku.nama_buku, peminjaman.tanggal_peminjaman, peminjaman.tanggal_pengembalian, peminjaman.status
        FROM users
        INNER JOIN peminjaman ON users.nrp_nidn = peminjaman.nrp_nidn
        INNER JOIN buku ON peminjaman.kode_buku = buku.kode_buku
        WHERE peminjaman.status = 'pembayaran' OR peminjaman.status = 'konfirmasi'";
                      
      $params = [];
      $types = '';

      if (!empty($search_username)) {
        $query2 .= " AND users.nama LIKE ?";
        $params[] = "%$search_username%";
        $types .= 's';
      }

      if (!empty($search_peminjaman)) {
        $query2 .= " AND peminjaman.tanggal_peminjaman >= ?";
        $params[] = $search_peminjaman;
        $types .= 's';
      }

      if (!empty($search_pengembalian)) {
        $query2 .= " AND peminjaman.tanggal_pengembalian <= ?";
        $params[] = $search_pengembalian;
        $types .= 's';
      }

      $stmt = $conn->prepare($query2);
      if ($params) {
        $stmt->bind_param($types, ...$params);
      }
      $stmt->execute();
      $buku_konfirmasi = $stmt->get_result();
      } else {
        $stmt = $conn->prepare($query2);
        $stmt->execute();
        $buku_konfirmasi = $stmt->get_result();
      }
      while ($row = $buku_konfirmasi->fetch_assoc()) {
        echo "<tr>
          <td>{$row['nama_buku']}</td>
          <td>{$row['nama']}</td>
          <td>{$row['tanggal_peminjaman']}</td>
          <td>{$row['tanggal_pengembalian']}</td>
          <td>{$row['denda']}</td>
          <td>{$row['status']}</td>
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
            header("Location: " . $_SERVER['PHP_SELF'] . "?tab=daftar-buku");
        } else {
            echo "Gagal menambahkan data: " . $stmt->error;
        }
    } else {
        echo "Gagal mengupload file cover. Error code: " . $cover_buku['error'];
    }
  }

 if (isset($_POST['update_detail_buku'])) {
    $kode_buku_lama = $_POST['kode_buku_lama'] ?? '';
    $kode_buku_baru = $_POST['kode_buku_baru'] ?? '';
    $jenis_buku = $_POST['jenis_buku_baru'] ?? '';
    $nama_buku = $_POST['nama_buku_baru'] ?? '';
    $pengarang = $_POST['pengarang_baru'] ?? '';
    $penerbit = $_POST['penerbit_baru'] ?? '';
    $jumlah_halaman = $_POST['jumlah_halaman_baru'] ?? '';
    $tahun_terbit = $_POST['tahun_terbit_baru'] ?? '';
    $deskripsi_buku = $_POST['deskripsi_buku_baru'] ?? '';
    $status = $_POST['status'] ?? 'Tersedia';
    $jumlah_buku = $_POST['jumlah_buku_baru'] ?? '';
    $penyumbang = $_POST['penyumbang_baru'] ?? '';
    $cover_buku = $_FILES['cover_buku_baru'];

    $uploadDir = "../images/buku/";
    $stmt_select = $conn->prepare("SELECT cover_buku FROM buku WHERE kode_buku = ?");
    $stmt_select->bind_param("s", $kode_buku_lama);
    $stmt_select->execute();
    $stmt_select->bind_result($nama_file_lama);
    $stmt_select->fetch();
    $stmt_select->close();

    $nama_file_final = $nama_file_lama;

    if (!empty($cover_buku['name'])) {

        $filePathLama = $uploadDir . $nama_file_lama;
        if (file_exists($filePathLama)) {
            unlink($filePathLama);
        }

        $nama_file_baru = basename($cover_buku['name']);
        $filePathBaru = $uploadDir . $nama_file_baru;

        if (move_uploaded_file($cover_buku['tmp_name'], $filePathBaru)) {
            $nama_file_final = $nama_file_baru;
        } else {
            echo "Gagal mengupload file cover baru. Error code: " . $cover_buku['error'];
            exit;
        }
    }

    $stmt = $conn->prepare("UPDATE buku SET
        kode_buku = ?, jenis_buku = ?, nama_buku = ?, pengarang = ?, penerbit = ?, jumlah_halaman = ?, 
        tahun_terbit = ?, deskripsi_buku = ?, status = ?, penyumbang = ?, cover_buku = ?, jumlah_buku = ?
        WHERE kode_buku = ?");

    if (!$stmt) {
        echo "Prepare failed: " . $conn->error;
        exit;
    }

    $stmt->bind_param(
        "sssssssssssss",
        $kode_buku_baru, $jenis_buku, $nama_buku, $pengarang, $penerbit,
        $jumlah_halaman, $tahun_terbit, $deskripsi_buku, $status,
        $penyumbang, $nama_file_final, $jumlah_buku, $kode_buku_lama
    );

    if ($stmt->execute()) {
        header("Location: " . $_SERVER['PHP_SELF'] . "?tab=daftar-buku");
    } else {
        echo "Gagal memperbarui data: " . $stmt->error;
    }

    $stmt->close();
  }


 if (isset($_POST['delete_detail_buku'])) {
    $kode_buku_lama = $_POST['kode_buku_lama'] ?? '';

    $stmt_select = $conn->prepare("SELECT cover_buku FROM buku WHERE kode_buku = ?");
    $stmt_select->bind_param("s", $kode_buku_lama);
    $stmt_select->execute();
    $stmt_select->bind_result($nama_file_cover);
    $stmt_select->fetch();
    $stmt_select->close();

    if (!empty($nama_file_cover)) {
        $filePath = "../images/buku/" . $nama_file_cover;
        if (file_exists($filePath)) {
            unlink($filePath);
        }
    }

    $stmt_delete = $conn->prepare("DELETE FROM buku WHERE kode_buku = ?");
    if (!$stmt_delete) {
        echo "Prepare failed: " . $conn->error;
        exit;
    }

    $stmt_delete->bind_param("s", $kode_buku_lama);

    if ($stmt_delete->execute()) {
        header("Location: " . $_SERVER['PHP_SELF'] . "?tab=daftar-buku");
    } else {
        echo "Gagal menghapus data: " . $stmt_delete->error;
    }

    $stmt_delete->close();
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
                      <th>
                        Detail
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
                          <td>
                            <button 
                              type="button" 
                              class="btn btn-primary btn-detail" 
                              data-toggle="modal" 
                              data-target="#detailModalBuku"
                              data-kode="<?php echo $row['kode_buku']; ?>"
                              data-nama="<?php echo $row['nama_buku']; ?>"
                              data-jenis="<?php echo $row['jenis_buku']; ?>"
                              data-pengarang="<?php echo $row['pengarang']; ?>"
                              data-penerbit="<?php echo $row['penerbit']; ?>"
                              data-jumlah_halaman="<?php echo $row['jumlah_halaman']; ?>"
                              data-tahun_terbit="<?php echo $row['tahun_terbit']; ?>"
                              data-deskripsi_buku="<?php echo $row['deskripsi_buku']; ?>"
                              data-jumlah_buku="<?php echo $row['jumlah_buku']; ?>"
                              data-penyumbang="<?php echo $row['penyumbang']; ?>"
                              data-cover_buku="<?php echo $row['cover_buku']; ?>"
                            >
                              Details
                            </button>
                          </td>
                        </tr>
                      <?php } endif; ?>
                    </tbody>
                  </table>
                  <div class="modal fade" id="detailModalBuku" tabindex="-1" role="dialog" aria-labelledby="detailModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                      <div class="modal-content">
                        <div class="modal-header">
                          <h5 class="modal-title" id="detailModalLabel">Detail Buku</h5>
                          <button type="button" class="close" data-dismiss="modal" aria-label="Tutup">
                            <span aria-hidden="true">&times;</span>
                          </button>
                        </div>
                        <div class="modal-body">

                        <form method="POST" action="" enctype="multipart/form-data">
                          <div class="row">
                            <div class="col-md-6 pr-1">
                              <div class="form-group">
                                <label>Kode Buku</label>
                                <input type="text" name="kode_buku_baru" class="form-control" id="input-kode-baru">
                              </div>
                            </div>
                            <div class="col-md-6 pl-1">
                              <div class="form-group">
                                <label>Jenis Buku</label>
                                <select class="form-control" name="jenis_buku_baru" id="inut-jenis">
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
                                <input type="text" name="nama_buku_baru" class="form-control" id="input-nama">
                              </div>
                            </div>
                          </div>
                          <div class="row">
                            <div class="col-md-6 pr-1">
                              <div class="form-group">
                                <label>Pengarang</label>
                                <input type="text" name="pengarang_baru" class="form-control" id="input-pengarang">
                              </div>
                            </div>
                            <div class="col-md-6 pl-1">
                              <div class="form-group">
                                <label>Penerbit</label>
                                <input type="text" name="penerbit_baru" class="form-control" id="input-penerbit">
                              </div>
                            </div>
                          </div>
                          <div class="row">
                            <div class="col-md-6 pr-1">
                              <div class="form-group">
                                <label>Jumlah Halaman</label>
                                <input type="text" name="jumlah_halaman_baru" class="form-control" id="input-jumlah-halaman">
                              </div>
                            </div>
                            <div class="col-md-6 pl-1">
                              <div class="form-group">
                                <label>Tahun Terbit</label>
                                <input type="text" name="tahun_terbit_baru" class="form-control" id="input-tahun-terbit">
                              </div>
                            </div>
                          </div>
                          <div class="row">
                            <div class="col-md-12">
                              <div class="form-group">
                                <label>Deskripsi Buku</label>
                                <textarea type="text" name="deskripsi_buku_baru" class="form-control textarea" id="input-deskripsi_buku"></textarea>
                              </div>
                            </div>
                          </div>
                          <div class="row">
                            <div class="col-md-6 pr-1">
                              <div class="form-group">
                                <label>Jumlah Buku</label>
                                <input type="text" name="jumlah_buku_baru" class="form-control" id="input-jumlah-buku">
                              </div>
                            </div>
                            <div class="col-md-6 pl-1">
                              <div class="form-group">
                                <label>Penyumbang</label>
                                <input type="text" name="penyumbang_baru" class="form-control" id="input-penyumbang">
                              </div>
                            </div>
                          </div>
                          <div class="row">
                            <div class="col-md-6 pr-1">
                              <label>Cover Buku</label>
                              <input type="file" name="cover_buku_baru" class="form-control" id="input-cover">
                            </div>                  
                          </div>
                          <div class="row">
                            <input type="text" name="kode_buku_lama" class="form-control" id="input-kode-lama" hidden>
                            <div class="col-md-6 pr-1">
                              <div class="update ml-auto mr-auto">
                                <button type="submit" name="update_detail_buku" class="btn btn-primary">Update</button>
                              </div>
                            </div> 
                            <div class="col-md-6 pr-1">
                              <div class="update ml-auto mr-auto">
                                <button type="submit" name="delete_detail_buku" class="btn btn-primary">Delete</button>
                              </div>
                            </div>       
                          </div>
                        </form>
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

      <!-- Daftar Peminjaman -->
      <div id="peminjaman" class="tab-content">
        <div class="row">
          <div class="col-md-12">
            <div class="card">
              <div class="card-header">
                <h4 class="card-title">Daftar Peminjaman Buku</h4>
                <form method="POST" class="mb-3">
                  <div class="row">
                    <div class="col-md-6">
                      <input type="text" name="search_peminjaman" class="form-control w-100"
                        placeholder="Search..."
                        value="<?= htmlspecialchars($_POST['search_peminjaman'] ?? '') ?>">
                    </div>
                    <div class="col-md-3">
                      <input type="date" name="search_tanggal_peminjaman" class="form-control w-100"
                        value="<?= htmlspecialchars($_POST['search_tanggal_peminjaman'] ?? '') ?>">
                    </div>
                    <div class="col-md-3">
                      <input type="date" name="search_tanggal_pengembalian" class="form-control w-100"
                        value="<?= htmlspecialchars($_POST['search_tanggal_pengembalian'] ?? '') ?>">
                    </div>
                  </div>
                </form>
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
                      <?php if ($buku_peminjaman && $buku_peminjaman->num_rows > 0): while($row = $buku_peminjaman->fetch_assoc()): ?>
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
                <form method="POST" class="mb-3">
                  <div class="row">
                    <div class="col-md-6">
                      <input type="text" name="search_konfirmasi" id="search_konfirmasi"
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
                  </div>
                </form>
              </div>
              <div class="card-body">
                <div class="table-responsive">
                  <table class="table" id="daftar-konfirmasi">
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
                      <?php if ($buku_konfirmasi && $buku_konfirmasi->num_rows > 0): ?>
                        <?php while($row = $buku_konfirmasi->fetch_assoc()): ?>
                          <tr>
                            <td><?= htmlspecialchars($row['nama_buku']) ?></td>
                            <td><?= htmlspecialchars($row['nama']) ?></td>
                            <td><?= htmlspecialchars($row['tanggal_peminjaman']) ?></td>
                            <td><?= htmlspecialchars($row['tanggal_pengembalian']) ?></td>
                            <td>
                              Rp. <?= number_format((float)($row['denda'] ?? 0), 0, ',', '.') ?>
                            </td>
                            <td>
                              <form method="POST">
                                <input type="hidden" name="kode_buku" value="<?= htmlspecialchars($row['kode_buku']) ?>">
                                <input type="hidden" name="nrp_nidn" value="<?= htmlspecialchars($row['nrp_nidn']) ?>">
                                <?php if (htmlspecialchars($row['status']) === 'konfirmasi') : ?>
                                  <button type="submit" name="update_konfirmasi" class="btn btn-primary d-block w-100">Konfirmasi Pengembalian</button>
                                <?php elseif (htmlspecialchars($row['status']) === 'pembayaran') : ?>
                                  <button type="submit" name="update_pembayaran" class="btn btn-primary d-block w-100">Terima Pembayaran</button>
                                <?php endif; ?>
                              </form>
                            </td>
                          </tr>
                        <?php endwhile; ?>
                      <?php else: ?>
                        <tr><td colspan="6">Tidak ada data ditemukan.</td></tr>
                      <?php endif; ?>
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
        $('.btn-detail').on('click', function() {
        const kode = $(this).data('kode');
        const nama = $(this).data('nama');
        const jenis = $(this).data('jenis');
        const pengarang = $(this).data('pengarang');
        const penerbit = $(this).data('penerbit');
        const jumlah_halaman = $(this).data('jumlah_halaman');
        const tahun_terbit = $(this).data('tahun_terbit');
        const deskripsi_buku = $(this).data('deskripsi_buku');
        const jumlah_buku = $(this).data('jumlah_buku');
        const penyumbang = $(this).data('penyumbang');
        const cover_buku = $(this).data('cover_buku');

        $('#input-kode-baru').val(kode);
        $('#input-kode-lama').val(kode);
        $('#input-nama').val(nama);
        $('#input-jenis').val(jenis);
        $('#input-pengarang').val(pengarang);
        $('#input-penerbit').val(penerbit);
        $('#input-jumlah-halaman').val(jumlah_halaman);
        $('#input-tahun-terbit').val(tahun_terbit);
        $('#input-deskripsi_buku').val(deskripsi_buku);
        $('#input-jumlah-buku').val(jumlah_buku);
        $('#input-penyumbang').val(penyumbang);
        $('#input-cover').val(cover_buku);

      });
      });
    </script>

</body>

</html>