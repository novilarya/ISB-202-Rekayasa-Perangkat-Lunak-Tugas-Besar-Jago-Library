<?php
    session_start();
    include('database/connection.php');
    $success ='';
    $email = $_SESSION['email'];
    $stmt = $conn->prepare("SELECT * FROM peminjaman 
                  INNER JOIN users ON peminjaman.nrp_nidn = users.nrp_nidn
                  INNER JOIN buku ON peminjaman.kode_buku = buku.kode_buku
                  WHERE users.email = ? AND peminjaman.status = 'dipinjam'");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $buku = $stmt->get_result();

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['kembalikan'])){
        $kode_buku = $_POST['kode_buku'];
        $nrp_nidn = $_POST['nrp_nidn'];
        $tanggal_kembali = $_POST['tanggal_kembali'];
        $today = date('Y-m-d');
        $denda = 0;
        $lama_terlambat = 0;

        if ($today > $tanggal_kembali) {
            $datetime1 = new DateTime($tanggal_kembali);
            $datetime2 = new DateTime($today);
            $interval = $datetime1->diff($datetime2);
            $lama_terlambat = $interval->days;
            $denda = $lama_terlambat * 5000;

            $_SESSION['denda'] = [
                'kode_buku' => $kode_buku,
                'nrp_nidn' => $nrp_nidn,
                'lama' => $lama_terlambat,
                'total' => $denda,
                'metode' => $metode
            ];
            header("Location: " . $_SERVER['PHP_SELF']);
            exit();
        } else {
            $stmt = $conn->prepare("UPDATE peminjaman SET status = 'kembali' WHERE kode_buku = ? AND nrp_nidn = ? AND status = 'dipinjam'");
            $stmt->bind_param("ss", $kode_buku, $nrp_nidn);
            $stmt->execute();

            $stmtUpdateBuku = $conn->prepare("UPDATE buku SET status = 'Tersedia' WHERE kode_buku = ?");
            $stmtUpdateBuku->bind_param("s", $kode_buku);

            if ($stmtUpdateBuku->execute()) {
                $_SESSION['success'] = true;
                header("Location: " . $_SERVER['PHP_SELF']);
                exit();
            }
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

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">

    <link rel="stylesheet" type="text/css" href="css/normalize.css">
    <link rel="stylesheet" type="text/css" href="icomoon/icomoon.css">
    <link rel="stylesheet" type="text/css" href="css/vendor.css">
    <link rel="stylesheet" type="text/css" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

</head>
<header>
    <?php include "header.php" ?>
</header>

<body>
    <main class="container my-5">
        <h2 class="mb-4">My Book</h2>
        <div class="row">
            <?php while ($row = $buku->fetch_assoc()) { ?>
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="card h-100 shadow-sm border-0">
                        <img src="/images/buku/<?php echo $row['cover_buku']; ?>"
                            class="card-img-top"
                            style="height: auto; max-height: 500px; object-fit: contain; margin-top: 40px;"
                            alt="<?php echo $row['nama_buku']; ?>">
                        <div class="card-body">
                            <h3 class="card-title"><?php echo $row['nama_buku']; ?></h3>
                            <p class="card-text mb-1"><strong>Kode Buku:</strong> <?php echo $row['kode_buku']; ?></p>
                            <p class="card-text mb-1"><strong>Pengarang:</strong> <?php echo $row['pengarang']; ?></p>
                            <p class="card-text mb-1"><strong>Halaman:</strong> <?php echo $row['jumlah_halaman']; ?></p>
                            <p class="card-text mb-3"><strong>Tahun Terbit:</strong> <?php echo $row['tahun_terbit']; ?></p>
                            <p class="card-text mb-3"><strong>Tanggal Pinjam:</strong> <?php echo $row['tanggal_peminjaman']; ?></p>
                            <p class="card-text mb-3"><strong>Tanggal Kembali:</strong> <?php echo $row['tanggal_pengembalian']; ?></p>

                            <form method="POST" action="">
                                <input type="hidden" name="kode_buku" value="<?php echo $row['kode_buku']; ?>">
                                <input type="hidden" name="nrp_nidn" value="<?php echo $row['nrp_nidn']; ?>">
                                <input type="hidden" name="tanggal_kembali" value="<?php echo $row['tanggal_pengembalian']; ?>">
                                <button type="submit" name="kembalikan" class="btn btn-outline-info btn-sm mt-3" style="height: 50px; width: 200px;">Kembalikan</button>
                            </form>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
    </main>
    <script src="js/jquery-1.11.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm"
        crossorigin="anonymous"></script>
    <script src="js/plugins.js"></script>
    <script src="js/script.js"></script>

    <?php
    include 'footer.php';
    ?>

    <div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="successModalLabel">Sukses</h5>
          </div>
          <div class="modal-body">
            Buku berhasil dikembalikan!
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-success rounded 4" data-bs-dismiss="modal">Tutup</button>
          </div>
        </div>
      </div>
    </div>
  
    <footer>
      <?php include "footer.php"; ?>
    </footer>

        
    <div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="successModalLabel">Sukses</h5>
          </div>
          <div class="modal-body">
            Buku berhasil dikembalikan!
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-success" data-bs-dismiss="modal">Tutup</button>
          </div>
        </div>
      </div>
    </div>

    <?php if (isset($_SESSION['success']) && $_SESSION['success'] === true): ?>
    <script>
      document.addEventListener("DOMContentLoaded", function () {
        const successModal = new bootstrap.Modal(document.getElementById('successModal'));
        successModal.show();

        const modalEl = document.getElementById('successModal');
        modalEl.addEventListener('hidden.bs.modal', function () {
          window.location.reload();
        });
      });
    </script>
    <?php unset($_SESSION['success']); endif; ?>

    <?php if (isset($_SESSION['denda'])): ?>
      <div class="modal fade" id="dendaModal" tabindex="-1" aria-labelledby="dendaModalLabel" aria-hidden="true">
        <div class="modal-dialog">
          <form method="POST" action="proses-denda.php">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title">Denda Terlambat</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
              </div>
              <div class="modal-body">
                <p>Buku terlambat dikembalikan selama <strong><?= $_SESSION['denda']['lama']; ?></strong> hari.</p>
                <p>Total denda: <strong>Rp <?= number_format($_SESSION['denda']['total'], 0, ',', '.'); ?></strong></p>
                <p>Pilih metode pembayaran:</p>

                <div class="form-check">
                  <input class="form-check-input" type="radio" name="metode" id="cash" value="Cash" required>
                  <label class="form-check-label" for="cash">Cash</label>
                </div>
                <div class="form-check">
                  <input class="form-check-input" type="radio" name="metode" id="va" value="Virtual Account" required>
                  <label class="form-check-label" for="va">Virtual Account</label>
                </div>

                <input type="hidden" name="kode_buku" value="<?= $_SESSION['denda']['kode_buku'] ?>">
                <input type="hidden" name="nrp_nidn" value="<?= $_SESSION['denda']['nrp_nidn'] ?>">
                <input type="hidden" name="denda" value="<?= $_SESSION['denda']['total'] ?>">
              </div>
              <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Bayar Denda</button>
              </div>
            </div>
          </form>
        </div>
      </div>

      <script>
        const modal = new bootstrap.Modal(document.getElementById('dendaModal'));
        window.addEventListener('load', () => {
          modal.show();
        });
      </script>
    <?php unset($_SESSION['denda']); endif; ?>

</body>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  </body>

</html>