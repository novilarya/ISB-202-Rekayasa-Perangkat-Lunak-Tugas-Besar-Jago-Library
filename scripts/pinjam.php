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
</head>
<body>
    <header>
        <?php include "header.php"; ?>
    </header>
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
                    <div class="purchase-controls">
                        <button class="pinjam-peminjaman">Pinjam</button>
                    </div>
                </div>
            </div>

        <?php } ?>
    </div>
    <footer>
        <?php include "footer.php"; ?>
    </footer>
</body>
</html>