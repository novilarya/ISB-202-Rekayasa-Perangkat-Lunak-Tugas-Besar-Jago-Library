<?php
include('../database/connection.php');

$jenis_buku = '';
$status_buku = '';
$search = '';

$query = "SELECT * FROM buku";
$stmt = $conn->prepare($query);
$stmt->execute();
$buku = $stmt->get_result();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $jenis_buku = $_POST['jenis_buku'] ?? '';
    $status_buku = $_POST['status'] ?? '';

    $query = "SELECT * FROM buku WHERE 1=1";
    $params = [];
    $types = "";

    if (!empty($jenis_buku)) {
        $query .= " AND jenis_buku = ?";
        $params[] = $jenis_buku;
        $types .= "s";
    }

    if (!empty($status_buku)) {
        $query .= " AND status = ?";
        $params[] = $status_buku;
        $types .= "s";
    }

    $stmt2 = $conn->prepare($query);
    if (!empty($params)) {
        $stmt2->bind_param($types, ...$params);
    }
    $stmt2->execute();
    $buku = $stmt2->get_result();
}

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $search = $_GET['cariBuku'] ?? '';

    $query = "SELECT * FROM buku WHERE 1=1";
    $params = [];
    $types = "";

    if (!empty($search)) {
        $query .= " AND (nama_buku LIKE ? OR pengarang LIKE ?)";
        $params[] = "%$search%";
        $params[] = "%$search%";
        $types .= "ss";
    }

    $stmt2 = $conn->prepare($query);
    if (!empty($params)) {
        $stmt2->bind_param($types, ...$params);
    }
    $stmt2->execute();
    $buku = $stmt2->get_result();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Buku | Jago_library</title>
    <link rel="stylesheet" href="/css/styles.css">
    <script src="/scripts/daftar-buku.js"></script>
    <script> src = "/scripts/refresh-pencarian.js"</script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<header>
    <?php include "header.php"; ?>
</header>

<body>
    <div class="dashboard">
        <aside class="sidebar">
            <h1 class="logo">Kategori</h1>
            <nav class="menu">
                <form method="POST">
                    <div class="input-group">
                        <label for="jenis_buku">Jenis Buku</label>
                        <select name="jenis_buku" id="jenis_buku">
                            <option value="">-- Pilih Jenis --</option>
                            <option value="TA" <?= $jenis_buku == 'TA' ? 'selected' : '' ?>>TA</option>
                            <option value="KP" <?= $jenis_buku == 'KP' ? 'selected' : '' ?>>KP</option>
                            <option value="SIP" <?= $jenis_buku == 'SIP' ? 'selected' : '' ?>>SIP</option>
                            <option value="MBKM" <?= $jenis_buku == 'MBKM' ? 'selected' : '' ?>>MBKM</option>
                            <option value="PAP" <?= $jenis_buku == 'PAP' ? 'selected' : '' ?>>PAP</option>
                            <option value="PEM" <?= $jenis_buku == 'PEM' ? 'selected' : '' ?>>PEM</option>
                            <option value="DB" <?= $jenis_buku == 'DB' ? 'selected' : '' ?>>DB</option>
                        </select>
                    </div>

                    <div class="input-group">
                        <label for="status">Status Buku</label>
                        <select name="status" id="status">
                            <option value="">-- Pilih Status --</option>
                            <option value="Tersedia" <?= $status_buku == 'Tersedia' ? 'selected' : '' ?>>Tersedia</option>
                            <option value="Tidak Tersedia" <?= $status_buku == 'Tidak Tersedia' ? 'selected' : '' ?>>Tidak Tersedia</option>
                        </select>
                    </div>

                    <button type="submit" class="signup-button">Update</button>
                </form>
            </nav>
        </aside>

        <main class="main-content">
            <section class="books-section">
                <div class="section-header">
                    <h2>My Books</h2>
                    <form class="d-flex" role="search" method="GET" id="searcForm">
                        <input class="form-control me-2" type="search" name="cariBuku" id="searchInput"
                            placeholder="Cari judul atau pengarang" value="<?= htmlspecialchars($search) ?>" />
                        <button class="btn btn-outline-primary" type="submit">Cari</button>
                    </form>
                </div>
                <div class="book-grid">
                    <?php while ($row = $buku->fetch_assoc()) { ?>
                        <div class="book-card-daftar">
                            <img src="/images/<?php echo $row['cover_buku']; ?>" alt="Harry Potter Book" />
                            <div class="book-info-daftar">
                                <?php
                                $status = $row['status'];
                                $badgeClass = ($status === 'Tersedia') ? 'badge-green' : 'badge-red';
                                ?>
                                <span class="type-badge <?php echo $badgeClass; ?>"><?php echo $status; ?></span>

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
                                <div class="book-info-index">
                                    <a href="<?php echo "pinjam.php?kode_buku=" . $row['kode_buku']; ?>"><button class="pinjam-button"> Pinjam </button></a>
                                </div>
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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</body>
</html>