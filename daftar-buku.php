<?php
include('database/connection.php');
session_start();
if (!isset($_SESSION['email'])) {
	header('Location: login.php');
	exit();
}

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

// Default query
$query = "SELECT * FROM buku WHERE 1=1";
$params = [];
$types = "";

// Ambil parameter dari GET
$search = $_GET['cariBuku'] ?? '';
$status_buku = $_GET['status'] ?? '';
$jenis_buku = $_GET['jenis_buku'] ?? '';

// Pencarian judul atau pengarang
if (isset($_POST['search'])) {
	include('database/connection.php');
	$search = $_POST['search'];
	$query = "SELECT * FROM buku WHERE nama_buku LIKE ? OR pengarang LIKE ?";
	$stmt = $conn->prepare($query);
	$likeSearch = "%$search%";
	$stmt->bind_param("ss", $likeSearch, $likeSearch);
	$stmt->execute();
	$result = $stmt->get_result();

	while ($row = $result->fetch_assoc()) {
		echo '<div class="col-md-3 mb-4">
			<div class="product-item-buku">
				<figure class="product-style-buku">
					<img src="/images/buku/' . htmlspecialchars($row['cover_buku']) . '" alt="' . htmlspecialchars($row['nama_buku']) . '" class="product-item">';
		if ($row['status'] === 'Tersedia') {
			echo '<a href="pinjam.php?kode_buku=' . $row['kode_buku'] . '">
					<button type="button" class="add-to-cart">Pinjam</button>
				  </a>';
		} else {
			echo '<button class="add-to-cart" onclick="showModal()">Pinjam</button>';
		}
		echo '</figure>
			<figcaption>
				<h3>' . htmlspecialchars($row['nama_buku']) . '</h3>
				<span>' . htmlspecialchars($row['pengarang']) . '</span>
				<div class="item-price">
					<span class="' . ($row['status'] === 'Tersedia' ? 'badge-green' : 'badge-red') . '">' . $row['status'] . '</span>
				</div>
			</figcaption>
		</div>
	</div>';
	}
	exit();
}

// Filter status
if (!empty($status_buku)) {
	$query .= " AND status = ?";
	$params[] = $status_buku;
	$types .= "s";
}

// Filter jenis buku
if (!empty($jenis_buku)) {
	$query .= " AND jenis_buku = ?";
	$params[] = $jenis_buku;
	$types .= "s";
}

// Eksekusi query filter
$stmt2 = $conn->prepare($query);
if (!empty($params)) {
	$stmt2->bind_param($types, ...$params);
}
$stmt2->execute();
$buku = $stmt2->get_result();


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
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
	<link rel="stylesheet" type="text/css" href="css/normalize.css">
	<link rel="stylesheet" type="text/css" href="icomoon/icomoon.css">
	<link rel="stylesheet" type="text/css" href="css/vendor.css">
	<link rel="stylesheet" type="text/css" href="style.css">


</head>
<header>
	<?php include "header.php" ?>
</header>

<body data-bs-spy="scroll" data-bs-target="#header" tabindex="0">
	<div>
	<div class="container">
		<h2 class="section-title text-center mb-4" style="font-family: Arial, Helvetica, sans-serif;">Daftar Buku</h2>

		<!-- Tabs -->
		<ul class="tabs">
			<li data-tab-target="#all" class="tab active">Semua</li>
			<li data-tab-target="#ta" class="tab">Tugas Akhir</li>
			<li data-tab-target="#kp" class="tab">Kuliah Praktik</li>
			<li data-tab-target="#sip" class="tab">SIP</li>
			<li data-tab-target="#mbkm" class="tab">MBKM</li>
			<li data-tab-target="#umum" class="tab">UMUM</li>
		</ul>

		<form method="GET" class="d-flex justify-content-center align-items-center mb-5">
			<div class="search-filter-wrapper d-flex align-items-center gap-3" style="width: 60%; margin-left: 80px;">
				<!-- Input Search -->
				<div class="input-icon-group">
					<input type="text" class="form-control custom-input" style="width: 300px;" id="searchInput" name="cariBuku" placeholder="Cari buku atau pengarang..." value="<?= htmlspecialchars($search ?? '') ?>">
				</div>

				<!-- Select Filter -->
				<div class="input-icon-group">
					<select name="status" id="status" style="width: 270px;" class="form-select custom-select">
						<option value="">Semua</option>
						<option value="Tersedia" <?= ($status_buku ?? '') === 'Tersedia' ? 'selected' : '' ?>>Tersedia</option>
						<option value="Tidak Tersedia" <?= ($status_buku ?? '') === 'Tidak Tersedia' ? 'selected' : '' ?>>Tidak Tersedia</option>
					</select>
				</div>

				<!-- Button Submit -->
				<button type="submit" class="btn search-btn">
					<i class="fas fa-search" style="height: 50px;"></i>
				</button>
			</div>
		</form>
		<!-- Tab Content -->
		<div class="tab-content">
			<?php
			$kategori = ['all' => 'Semua', 'TA' => 'Tugas Akhir', 'KP' => 'Kuliah Praktik', 'SIP' => 'SIP', 'MBKM' => 'MBKM', 'Umum' => 'Umum'];
			foreach ($kategori as $key => $label): ?>
				<div id="<?= strtolower($key) ?>" data-tab-content class="tab-pane <?= $key === 'all' ? 'active' : '' ?>">
					<div class="row">
						<?php
						$buku->data_seek(0); // reset pointer
						while ($row = $buku->fetch_assoc()):
							if ($key === 'all' || $row['jenis_buku'] === $key):
								$isTersedia = $row['status'] === 'Tersedia';
								$badgeClass = $isTersedia ? 'badge-green' : 'badge-red';
								$jenisBuku = $row['jenis_buku'];
						?>
								<div class="col-md-3 mb-4">
									<div class="product-item-buku">
										<figure class="product-style-buku">
											<img src="/images/buku/<?php echo $row['cover_buku']; ?>" alt="<?php echo $row['nama_buku']; ?>" class="product-item">
											<?php if ($isTersedia): ?>
												<?php if ($jenisBuku == 'TA' && $semester < 7): ?>
													<button type="button" class="add-to-cart" onclick="showModalTA()">Pinjam</button>
												<?php else: ?>
													<a href="pinjam.php?kode_buku=<?= $row['kode_buku']; ?>">
														<button type="button" class="add-to-cart">Pinjam</button>
													</a>
												<?php endif; ?>
											<?php else: ?>
												<button class="add-to-cart" onclick="showModal()">Pinjam</button>
											<?php endif; ?>
										</figure>
										<figcaption>
											<h3><?= $row['nama_buku']; ?></h3>
											<span><?= $row['pengarang']; ?></span>
											<div class="item-price">
												<span class="<?= $badgeClass; ?>"><?= $row['status']; ?></span>
											</div>
										</figcaption>
									</div>
								</div>
						<?php endif;
						endwhile; ?>
					</div>
				</div>
			<?php endforeach; ?>
		</div>
		</div>

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

		<script>
			document.querySelectorAll('.tab').forEach(tab => {
				tab.addEventListener('click', () => {
					// Tab toggle
					document.querySelectorAll('.tab').forEach(t => t.classList.remove('active'));
					tab.classList.add('active');

					// Content toggle
					const target = tab.dataset.tabTarget;
					document.querySelectorAll('[data-tab-content]').forEach(content => {
						content.classList.remove('active');
					});
					document.querySelector(target).classList.add('active');
				});
			});
		</script>

		<script>
			document.getElementById('searchInput').addEventListener('keyup', function() {
				const search = this.value;
				const xhr = new XMLHttpRequest();
				xhr.open('POST', window.location.href, true);
				xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
				xhr.onreadystatechange = function() {
					if (xhr.readyState === 4 && xhr.status === 200) {
						// Tampilkan hasil hanya di tab "Semua"
						const allTab = document.querySelector('#all .row');
						if (allTab) {
							allTab.innerHTML = xhr.responseText;
							// Aktifkan tab "Semua"
							document.querySelectorAll('.tab').forEach(t => t.classList.remove('active'));
							document.querySelector('[data-tab-target="#all"]').classList.add('active');
							document.querySelectorAll('[data-tab-content]').forEach(content => content.classList.remove('active'));
							document.querySelector('#all').classList.add('active');
						}
					}
				};
				xhr.send('search=' + encodeURIComponent(search));
			});
		</script>


</body>

</html>