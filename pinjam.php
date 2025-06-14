    <?php
    session_start();
    include('database/connection.php');
    $success = '';

    if (isset($_GET['kode_buku'])) {
      $kode_buku = $_GET['kode_buku'];
      $query = "SELECT * FROM buku WHERE kode_buku = ?";
      $stmt = $conn->prepare($query);
      $stmt->bind_param("s", $kode_buku);
      $stmt->execute();
      $buku = $stmt->get_result();

      if ($buku->num_rows === 0) {
        header("Location: daftar-buku.php");
        exit();
      }
    } else {
      header("Location: index.php");
      exit();
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
      $email = $_SESSION['email'];
      $stmt2 = $conn->prepare("SELECT * FROM users WHERE email = ?");
      $stmt2->bind_param("s", $email);
      $stmt2->execute();
      $user = $stmt2->get_result();
      $kode_user = $user->fetch_assoc();
      $nrp_nidn = $kode_user['nrp_nidn'];

      $tanggal_pinjam = $_POST['tanggal_pinjam'];
      $tanggal_kembali = $_POST['tanggal_kembali'];

      $stmtUpdate = $conn->prepare("UPDATE buku SET jumlah_buku = jumlah_buku - 1 WHERE kode_buku = '$kode_buku'");
      $stmtUpdate->execute();

      $query = "SELECT jumlah_buku FROM buku WHERE kode_buku = '$kode_buku'";
      $stmt = $conn->prepare($query);
      $stmt->execute();
      $result = $stmt->get_result();
      $row = $result->fetch_assoc();

      $jumlah_buku = $row['jumlah_buku'];
      if ((int)$jumlah_buku === 0) {
        $stmtUpdate = $conn->prepare("UPDATE buku SET status = 'Tidak Tersedia' WHERE kode_buku = ?");
        $stmtUpdate->bind_param("s", $kode_buku);
        $stmtUpdate->execute();
      }
      $stmtInsert = $conn->prepare("INSERT INTO peminjaman (kode_buku, nrp_nidn, tanggal_peminjaman, tanggal_pengembalian, status) VALUES (?, ?, ?, ?, 'menunggu diambil')");
      $stmtInsert->bind_param("ssss", $kode_buku, $nrp_nidn, $tanggal_pinjam, $tanggal_kembali);

      if ($stmtInsert->execute()) {
        $success = true;
      } else {
        $success = false;
      }
    }

    ?>


    <!DOCTYPE html>
    <html lang="en">

    <head>
      <title>BookSaw - Free Book Store HTML CSS Template</title>
      <meta charset="utf-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <meta name="format-detection" content="telephone=no">
      <meta name="apple-mobile-web-app-capable" content="yes">
      <meta name="author" content="">
      <meta name="keywords" content="">
      <meta name="description" content="">

      <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">

      <link rel="stylesheet" type="text/css" href="css/normalize.css">
      <link rel="stylesheet" type="text/css" href="icomoon/icomoon.css">
      <link rel="stylesheet" type="text/css" href="css/vendor.css">
      <link rel="stylesheet" type="text/css" href="style.css">
      <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
      <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"></script>
    </head>
    <header>
      <?php include "header.php" ?>
    </header>

    <body>
      <!-- Container untuk Detail Buku -->
      <div class="container py-5">
        <div class="row align-items-center">
          <?php while ($row = $buku->fetch_assoc()) { ?>
            <div class="col-md-4 text-center mb-4">
              <img src="/images/buku/<?php echo $row['cover_buku']; ?>" alt="<?php echo $row['nama_buku']; ?>" class="img-fluid rounded shadow-sm" style="max-height: 400px; object-fit: cover;">
            </div>
            <div class="col-md-8">
              <h1 class="display-5 fw-bold"><?php echo $row['nama_buku']; ?></h1>
              <p class="lead text-muted mb-1"><strong>Pengarang:</strong> <?php echo $row['pengarang']; ?></p>
              <p class="text-muted mb-1"><strong>Jumlah Halaman:</strong> <?php echo $row['jumlah_halaman']; ?></p>
              <p class="text-muted mb-1"><strong>Tahun Terbit:</strong> <?php echo $row['tahun_terbit']; ?></p>
              <p class="text-muted mb-3"><strong>Kode Buku:</strong> <?php echo $row['kode_buku']; ?></p>

              <?php
              $status = $row['status'];
              $badgeClass = ($status === 'Tersedia') ? 'bg-success' : 'bg-danger';
              ?>
              <span class="badge <?php echo $badgeClass; ?> py-3 px-3 mb-3"><?php echo $status; ?></span>

              <div>
                <p class="text-info-1">Tersisa <?php echo $row['jumlah_buku']; ?> buku lagi</p>
                <p class="text-info-1"><?php echo $row['deskripsi_buku']; ?></p>
                <button class="btn btn-primary rounded 4 px-4" style="height: 50px;" data-bs-toggle="modal" data-bs-target="#exampleModal">Pinjam Buku</button>
              </div>
            </div>

            <!-- Modal Form Peminjaman -->
            <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
              <div class="modal-dialog">
                <div class="modal-content shadow-sm">
                  <div class="modal-header bg-light">
                    <h4 class="modal-title" id="exampleModalLabel">Form Peminjaman</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                  </div>
                  <form method="POST" action="">
                    <div class="modal-body">
                      <div class="mb-3">
                        <label for="tanggal_pinjam" class="form-label">Tanggal Pinjam</label>
                        <input type="date" name="tanggal_pinjam" id="tanggal_pinjam" class="form-control" required min="<?= date('Y-m-d') ?>">
                      </div>
                      <div class="mb-3">
                        <label for="tanggal_kembali" class="form-label">Tanggal Kembali</label>
                        <input type="date" name="tanggal_kembali" id="tanggal_kembali" class="form-control" required min="<?= date('Y-m-d') ?>">
                      </div>
                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-outline-secondary rounded 4" style="height: 50px;" data-bs-dismiss="modal">Batal</button>
                      <button type="submit" class="btn btn-primary rounded 4 px-4" style="height: 50px;">Konfirmasi Pinjam</button>
                    </div>
                  </form>

                </div>
              </div>
            </div>
          <?php } ?>
        </div>
      </div>
      <div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header bg-light">
              <h5 class="modal-title" id="successModalLabel">Sukses</h5>
            </div>
            <div class="modal-body">
              Buku Berhasil Dipinjam, Silakan Ambil di Perpustakaan!
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-success rounded 4 px-4" style="height: 50px;" data-bs-dismiss="modal">Tutup</button>
            </div>
          </div>
        </div>
      </div>


      <script src="js/jquery-1.11.0.min.js"></script>
      <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm"
        crossorigin="anonymous"></script>
      <script src="js/plugins.js"></script>
      <script src="js/script.js"></script>

      <?php if ($success): ?>
        <script>
          const successModal = new bootstrap.Modal(document.getElementById('successModal'));
          successModal.show();
          
          setTimeout(() => {
            window.location.href = 'daftar-buku.php';
          }, 3000);
        </script>
      <?php endif; ?>

      <?php
      include 'footer.php';
      ?>

      <script>
        const tanggalPinjam = document.getElementById('tanggal_pinjam');
        const tanggalKembali = document.getElementById('tanggal_kembali');

        tanggalPinjam.addEventListener('change', function() {
          tanggalKembali.value = ''; // Kosongkan jika user ganti pinjam
          tanggalKembali.min = this.value; // Set min ke tanggal pinjam
        });
      </script>

      

    </body>

    </html>