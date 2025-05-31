<?php
    session_start();
    include('../database/connection.php');
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

      $stmt = $conn->prepare("UPDATE peminjaman SET status = 'kembali' WHERE kode_buku = ? AND nrp_nidn = ? AND status = 'dipinjam'");
      $stmt->bind_param("ss", $kode_buku, $nrp_nidn);
      $stmt->execute();

      $stmtUpdateBuku = $conn->prepare("UPDATE buku SET status = 'Tersedia' WHERE kode_buku = ?");
      $stmtUpdateBuku->bind_param("s", $kode_buku);
      
      if ($stmtUpdateBuku->execute()) {
          $_SESSION['success'] = true; // simpan status sukses di session
          header("Location: " . $_SERVER['PHP_SELF']); // redirect untuk menghentikan POST
          exit();
      }
    }
?>

<!DOCTYPE html>
<html lang="en">
  <head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title>Daftar Pinjaman | Jago_library</title>
  </head>
  <header>
        <?php include "header.php"; ?>
  </header>
  <body>
    <div class="dashboard">
      <aside class="sidebar">
        <h1 class="logo">Books</h1>
        <nav class="menu">
          <ul>
            <li class="active">My Books</li>
            <a class="active" href="sumbang.php"><li>Sumbang buku</li></a>
            <li> Sumbang</li>
            <!-- <li> </li> -->
          </ul>
        </nav>
        <!-- <div class="book-types">
          <h3>Books Types</h3>
          <ul>
            <li>Biography</li>
            <li>Kids</li>
            <li>Sports</li>
          </ul>
        </div> -->
      </aside>

      <main class="main-content">
        <section class="books-section">
          <div class="section-header">
            <h2>My Books</h2>
          </div>
          <div class="book-grid">
            <?php while($row = $buku->fetch_assoc()) { ?>
              <div class="book-card-daftar">
                <img src="/images/<?php echo $row['cover_buku']; ?>" alt="Harry Potter Book" />
                <div class="book-info-daftar">
                  <h3><?php echo $row['nama_buku']; ?></h3>
                  <div class="book-description">
                    <div class="row">
                      <span class="detail-label">Kode buku</span>
                      <span class="detail-value">: <?php echo $row['kode_buku']; ?></span>
                    </div>
                    <div class="row">
                      <span class="detail-label">Pengarang</span>
                      <span class="detail-value">: <?php echo $row['pengarang']; ?></span>
                    </div>
                    <div class="row">
                      <span class="detail-label">Jumlah halaman</span>
                      <span class="detail-value">: <?php echo $row['jumlah_halaman']; ?></span>
                    </div>
                    <div class="row">
                      <span class="detail-label">Tahun terbit</span>
                      <span class="detail-value">: <?php echo $row['tahun_terbit']; ?></span>
                    </div>
                    <div class="row">
                      <span class="detail-label">Kode buku</span>
                      <span class="detail-value">: <?php echo $row['kode_buku']; ?></span>
                    </div>
                  </div>
                  <form method="POST" action="">
                    <input type="hidden" name="kode_buku" value="<?php echo $row['kode_buku']; ?>">
                    <input type="hidden" name="nrp_nidn" value="<?php echo $row['nrp_nidn']; ?>">
                    <button type="submit" name="kembalikan" class="kembalikan">Kembalikan</button>
                  </form>
                </div>
              </div>
            <?php } ?>
          </div>
        </section>
      </main>
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  </body>
</html>