<?php
    include('../database/connection.php');
    
    if (isset($_GET['kode_buku'])) {
        $kode_buku = $_GET['kode_buku'];
        $query = "SELECT * FROM buku WHERE kode_buku = '$kode_buku'";
        $stmt = $conn->prepare($query);
        $stmt->execute();
        $buku = $stmt->get_result();
    } else {
        header('location: index.php');
    }
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pinjam | Jago_library</title>
    <link rel="stylesheet" href="/css/styles.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"></script>

</head>

<body>
    <header>
        <?php include "header.php"; ?>
    </header>
    <div class="book-image-container">
        <img src="/images/harry-potter5.jpg" alt="Harry Potter and The Sorcerer's Stone" />
        <span class="type-badge">Tersedia</span>
        <div class="book-info-wrapper">
            <div class="book-info">
                <h1 class="book-title">Harry Potter And The Sorcerer's Stone</h1>
                <p class="book-author">By JK Rowling</p>
                <div class="book-rating">
                    <span class="rating-stars">⭐⭐⭐⭐⭐</span>
                </div>
            </div>

            <div class="book-details">
                <div class="detail-row">
                    <span class="detail-label">Ukuran</span>
                    <span class="detail-value">170 x 215 mm</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Jumlah Halaman</span>
                    <span class="detail-value">348</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Tahun Terbit</span>
                    <span class="detail-value">2003</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">ISBN</span>
                    <span class="detail-value">5-353-01339-5</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Cetakan</span>
                    <span class="detail-value">11000</span>
                </div>
            </div>
            
    <div class="book-image-container">
        <?php while($row = $buku->fetch_assoc()) { ?>
            <img src="/images/<?php echo $row['cover_buku']; ?>" alt="Harry Potter and The Sorcerer's Stone" />
            <div class="book-info-wrapper">
                <?php
                    $status = $row['status'];
                    $badgeClass = ($status === 'Tersedia') ? 'badge-green' : 'badge-red';
                ?>
                <span class="type-badge <?php echo $badgeClass; ?>"><?php echo $status; ?></span>
                <div class="book-info">
                    <h1 class="book-title"><?php echo $row['nama_buku']; ?></h1>
                    <p class="book-author"><?php echo $row['pengarang']; ?></p>
                    <div class="book-rating">
                        <span class="rating-stars">⭐⭐⭐⭐⭐</span>
                    </div>
                </div>

                <div class="book-details">
                    <div class="detail-row">
                        <span class="detail-label">Jumlah Halaman</span>
                        <span class="detail-value"><?php echo $row['jumlah_halaman']; ?></span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Tahun Terbit</span>
                        <span class="detail-value"><?php echo $row['tahun_terbit']; ?></span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Kode Buku</span>
                        <span class="detail-value"><?php echo $row['kode_buku']; ?></span>
                    </div>
                </div>
                <div class="pinjam-section">
                <p class="availability">Tersisa 5 buku lagi</p>
                <button class="pinjam-peminjaman" data-bs-toggle="modal" data-bs-target="#exampleModal">Pinjam</button>
            </div>
        </div>
        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Input Tanggal Pinjam</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form method="POST" action="proses_pinjam.php" class="form-pinjam">
                            <div class="form-group">
                                <label for="tanggal_pinjam">Tanggal Pinjam</label>
                                <input type="date" name="tanggal_pinjam" id="tanggal_pinjam" required>
                            </div>

                            <div class="form-group">
                                <label for="tanggal_kembali">Tanggal Kembali</label>
                                <input type="date" name="tanggal_kembali" id="tanggal_kembali" required>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary">Pinjam</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>

        <?php } ?>
    </div>
    <footer>
        <?php include "footer.php"; ?>
    </footer>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</body>

</html>