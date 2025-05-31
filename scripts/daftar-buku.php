<?php
    include('../database/connection.php');
    session_start();

    $jenis_buku = '';
    $jenis_buku_dipilih = '';
    $status_buku = '';
    $search = '';

    $query = "SELECT * FROM buku";
    $stmt = $conn->prepare($query);
    $stmt->execute();
    $buku = $stmt->get_result();

    $email = $_SESSION['email'];
    $query = "SELECT * FROM users WHERE email = '$email'";
    $stmtUser = $conn->prepare($query);
    $stmtUser->execute();
    $user = $stmtUser->get_result();
    $kode_user = $user->fetch_assoc();
    $semester = $kode_user['semester'];

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
                            <option value="TA" <?= $jenis_buku == 'TA' ? 'selected' : '' ?>>Tugas Akhir</option>
                            <option value="KP" <?= $jenis_buku == 'KP' ? 'selected' : '' ?>>Kuliah Project</option>
                            <option value="SIP" <?= $jenis_buku == 'SIP' ? 'selected' : '' ?>>Sistem Informasi Pengabdian</option>
                            <option value="MBKM" <?= $jenis_buku == 'MBKM' ? 'selected' : '' ?>>Merdeka Belajar Kampus Merdeka</option>
                            <option value="Umum" <?= $jenis_buku == 'Umum' ? 'selected' : '' ?>>Umum</option>
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
                                    $isTersedia = ($status === 'Tersedia');
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
                                    <?php if ($isTersedia) { ?>
                                        <input type="hidden" name="jenis_buku_dipilih" value="<?php echo $row['jenis_buku']; ?>">
                                        <?php
                                            $jenis_buku_dipilih = $row['jenis_buku'];
                                        ?>
                                        <?php if ($semester >= 7 && $jenis_buku_dipilih == 'TA') { ?>
                                            <a href="pinjam.php?kode_buku=<?php echo $row['kode_buku']; ?>">
                                                <button class="pinjam-button">Pinjam</button>
                                            </a>
                                        <?php } else if ($semester <= 7 && $jenis_buku_dipilih == 'TA') { ?>
                                            <button class="pinjam-button" onclick="showModalTA()">Pinjam</button>
                                        <?php } else if ($jenis_buku_dipilih != 'TA'){ ?>
                                            <a href="pinjam.php?kode_buku=<?php echo $row['kode_buku']; ?>">
                                                <button class="pinjam-button">Pinjam</button>
                                            </a>
                                        <?php } ?>
                                    <?php } else { ?>
                                        <button class="pinjam-button" onclick="showModal()">Pinjam</button>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </section>
        </main>
    </div>
    <div id="unavailableModal" class="modal" style="display:none;">
        <div class="modal-content">
            <span class="close" onclick="hideModal()">&times;</span>
            <p>Buku ini tidak tersedia untuk dipinjam saat ini.</p>
        </div>
    </div>
    <div id="unavailableModalTA" class="modal" style="display:none;">
        <div class="modal-content">
            <span class="close" onclick="hideModalTA()">&times;</span>
            <p>Buku ini tidak tersedia untuk dipinjam saat ini dikarenakan Anda belum memenuhi semester untuk meminjam buku ini.</p>
        </div>
    </div>
    <footer>
        <?php include "footer.php"; ?>
    </footer>
    <script>
        function showModal() {
            document.getElementById('unavailableModal').style.display = 'flex';
        }

        function hideModal() {
            document.getElementById('unavailableModal').style.display = 'none';
        }

        function showModalTA() {
            document.getElementById('unavailableModalTA').style.display = 'flex';
        }

        function hideModalTA() {
            document.getElementById('unavailableModalTA').style.display = 'none';
        }
    </script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</body>
</html>