<?php
    include('../database/connection.php');

    $query = "SELECT * FROM buku";
    $stmt = $conn->prepare($query);
    $stmt->execute();
    $buku = $stmt->get_result();

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
                  <button class="kembalikan">Kembalikan</button>
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
  </body>
</html>