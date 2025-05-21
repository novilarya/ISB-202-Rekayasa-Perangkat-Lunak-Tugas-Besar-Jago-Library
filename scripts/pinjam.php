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
                <div class="pinjam-section">
                <p class="availability">Tersisa 5 buku lagi</p>
                <div class="purchase-controls">
                    <button class="pinjam-peminjaman">Pinjam</button>
                </div>
                </div>
            </div>
            </div>

    <footer>
        <?php include "footer.php"; ?>
    </footer>
</body>
</html>