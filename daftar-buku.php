<?php
    include('database/connection.php');
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


</head>
<header>
      <?php include "header.php" ?>
    </header>
	
<body data-bs-spy="scroll" data-bs-target="#header" tabindex="0">

	<div class="container">
		<h2 class="section-title text-center mb-4">Daftar Buku</h2>

		<!-- Search -->
		<form method="GET" class="row g-3 mb-4 align-items-end">
			<div class="col-md-4">
				<label for="searchInput" class="form-label">Cari Buku</label>
				<input type="text" class="form-control" name="cariBuku" placeholder="Judul atau Pengarang" value="<?= htmlspecialchars($search) ?>">
			</div>
			<div class="col-md-2">
				<button type="submit" class="btn btn-primary rounded 4" style="height: 50px; width: 150px; border-radius: 2;">Cari</button>
			</div>
		</form>

		<!-- Filter -->
		<form method="POST" class="row g-3 mb-4 align-items-end">
			<div class="col-md-3">
				<label for="jenis_buku" class="form-label">Jenis Buku</label>
				<select name="jenis_buku" id="jenis_buku" class="form-select">
					<option value="">Pilih Jenis</option>
					<option value="TA" <?= $jenis_buku == 'TA' ? 'selected' : '' ?>>Tugas Akhir</option>
					<option value="KP" <?= $jenis_buku == 'KP' ? 'selected' : '' ?>>Kuliah Praktik</option>
					<option value="SIP" <?= $jenis_buku == 'SIP' ? 'selected' : '' ?>>Sistem Informasi Perusahaan</option>
					<option value="MBKM" <?= $jenis_buku == 'MBKM' ? 'selected' : '' ?>>Merdeka Belajar Kampus Merdeka</option>
					<option value="Umum" <?= $jenis_buku == 'PAP' ? 'selected' : '' ?>>Umum</option>
				</select>
			</div>

			<div class="col-md-2">
				<label for="status" class="form-label">Status</label>
				<select name="status" id="status" class="form-select">
					<option value="">Pilih Status</option>
					<option value="Tersedia" <?= $status_buku == 'Tersedia' ? 'selected' : '' ?>>Tersedia</option>
					<option value="Tidak Tersedia" <?= $status_buku == 'Tidak Tersedia' ? 'selected' : '' ?>>Tidak Tersedia</option>
				</select>
			</div>

			<div class="col-md-2">
				<button type="submit" class="btn btn-primary rounded 4" style="height: 50px; width: 150px; border-radius: 2;">Filter</button>
			</div>
		</form>

		<!-- Buku -->
		<div class="row">
			<?php while ($row = $buku->fetch_assoc()) {
				$isTersedia = $row['status'] === 'Tersedia';
				$badgeClass = $isTersedia ? 'badge-green' : 'badge-red';
				$jenisBuku = $row['jenis_buku'];
			?>
				<div class="col-md-3 mb-4">
					<div class="product-item">
						<figure class="product-style">
							<img src="/images/buku/<?php echo $row['cover_buku']; ?>" alt="<?php echo $row['nama_buku']; ?>" class="product-item">
							<?php if ($isTersedia): ?>
								<?php if ($jenisBuku == 'TA' && $semester < 7): ?>
									<button type="button" class="add-to-cart" onclick="showModalTA()">Pinjam</button>
								<?php else: ?>
									<a href="pinjam.php?kode_buku=<?php echo $row['kode_buku']; ?>">
										<button type="button" class="add-to-cart">Pinjam</button>
									</a>
								<?php endif; ?>
							<?php else: ?>
								<button class="add-to-cart" onclick="showModal()">Pinjam</button>
							<?php endif; ?>
						</figure>
						<figcaption>
							<h3><?php echo $row['nama_buku']; ?></h3>
							<span><?php echo $row['pengarang']; ?></span>
							<div class="item-price">
								<span class="<?php echo $badgeClass; ?>"><?php echo $row['status']; ?></span>
							</div>
						</figcaption>
					</div>
				</div>
			<?php } ?>
		</div>
	</div>
	</section>

	<!-- Modal Tidak Tersedia -->
	<div id="unavailableModal" class="custom-modal" style="display: none;">
		<div class="custom-modal-content">
			<button class="custom-close" onclick="hideModal()">&times;</button>
			<div class="custom-modal-body">
				<h4 class="modal-title">Buku Tidak Tersedia</h4>
				<p>Buku ini tidak tersedia untuk dipinjam saat ini.</p>
			</div>
		</div>
	</div>

	<!-- Modal Syarat TA -->
	<div id="unavailableModalTA" class="custom-modal" style="display: none;">
		<div class="custom-modal-content">
			<button class="custom-close" onclick="hideModalTA()">&times;</button>
			<div class="custom-modal-body">
				<h4 class="modal-title">Syarat Belum Terpenuhi</h4>
				<p>Buku TA hanya dapat dipinjam oleh mahasiswa semester 7 ke atas.</p>
			</div>
		</div>
	</div>

	<script src="js/jquery-1.11.0.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"
		integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm"
		crossorigin="anonymous"></script>
	<script src="js/plugins.js"></script>
	<script src="js/script.js"></script>

	<footer>
		<?php
		include 'footer.php';
		?>
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
</body>

</html>