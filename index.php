<?php
include('./database/connection.php');

if (!isset($_SESSION['email'])) {
    header('Location: login.php');
    exit();
}
$query = "SELECT * FROM buku";
$stmt = $conn->prepare($query);
$stmt->execute();
$buku = $stmt->get_result();
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
	<link rel="stylesheet" type="text/css" href="styles.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

</head>
<header>
	<?php include "header.php" ?>
</header>
<body>

	<section id="billboard">

		<div class="container">
			<div class="row">
				<div class="col-md-12">

					<button class="prev slick-arrow">
						<i class="icon icon-arrow-left"></i>
					</button>

					<div class="main-slider pattern-overlay">
						<div class="slider-item">
							<div class="banner-content" style="margin-left: 100px;">
								<h2 class="banner-title">Pinjam Buku Online, #TanpaAntriTanpaRibet</h2>
								<p>Kemudahan meminjam buku tanpa mendatangi langsung ruang baca.</p>
								<div class="btn-wrap">
									<a href="daftar-buku.php" class="btn btn-outline-accent btn-accent-arrow rounded 4">Mulai Pinjam Sekarang<i
											class="icon icon-ns-arrow-right"></i></a>
								</div>
							</div><!--banner-content-->
							<img src="images/banner21.png" alt="banner" class="banner-image">
						</div><!--slider-item-->

						<div class="slider-item">
							<div class="banner-content" style="margin-left: 100px;">
								<h2 class="banner-title"> #TanpaAntriTanpaRibet</h2>
								<p>Kemudahan meminjam buku tanpa mendatangi langsung ruang baca.</p>
								<div class="btn-wrap">
									<a href="daftar-buku.php" class="btn btn-outline-accent btn-accent-arrow rounded 4">Mulai Pinjam Sekarang<i
											class="icon icon-ns-arrow-right"></i></a>
								</div>
							</div><!--banner-content-->
							<img src="images/banner3.png" alt="banner" class="banner-image">
						</div><!--slider-item-->

					</div><!--slider-->

					<button class="next slick-arrow">
						<i class="icon icon-arrow-right"></i>
					</button>

				</div>
			</div>
		</div>

	</section>

	<!-- Section: Daftar Pinjam Info -->
	<section class="daftar-pinjam-section py-5">
		<div class="container">
			<div class="row align-items-center">
				<!-- Ilustrasi (tempatkan aset gambar nanti) -->
				<div class="col-md-6">
					<img src="images/daftar-pinjam.png" alt="Ilustrasi Daftar Pinjam" class="img-fluid">
				</div>

				<!-- Deskripsi -->
				<div class="col-md-6 ps-5">
					<h2 class="fw-bold mb-4" style="font-size: 36px;">Pantau semua buku yang sedang kamu pinjam</h2>
					<p class="text-muted mb-4" style="font-size: 16px; line-height: 1.7;">
						Daftar pinjam membantu kamu mengelola buku yang sedang dipinjam, mengetahui status pengembalian,
						serta menghindari keterlambatan. Akses daftar pinjamanmu hanya dengan satu klik.
					</p>
					<a href="daftar-pinjam.php" class="btn btn-outline-accent rounded 4">
						Lihat Daftar Pinjam
					</a>
				</div>

			</div>
		</div>
	</section>

	<section id="featured-books" class="py-5 my-5">
		<div class="container">
			<div class="row">
				<div class="col-md-12">

					<div class="section-header align-center">
						<div class="title">
							<span>Some quality items</span>
						</div>
						<h2 class="section-title">Featured Books</h2>
					</div>
					<div class="position-relative">
						<!-- Tombol Kiri -->
						<button class="scroll-btn left btn btn-light position-absolute top-50 start-0 translate-middle-y z-3">
							❮
						</button>

						<!-- Container Scroll -->
						<div class="product-list overflow-auto px-5" data-aos="fade-up" id="scrollContainer" style="scroll-behavior: smooth;">
							<div class="d-flex flex-nowrap gap-4 py-2">
								<?php while ($row = $buku->fetch_assoc()) { ?>
									<div class="product-item text-center" style="min-width: 220px;">
										<figure class="product-style">
											<img src="images/buku/<?php echo $row['cover_buku']; ?>" alt="Books" class="product-item">
											<a href="<?php echo "pinjam.php?kode_buku=" . $row['kode_buku']; ?>">
												<button type="button" class="add-to-cart" data-product-tile="add-to-cart">Pinjam</button>
											</a>
										</figure>
										<figcaption>
											<h3><?php echo $row['nama_buku']; ?></h3>
											<span><?php echo $row['pengarang']; ?></span>
										</figcaption>
									</div>
								<?php } ?>
							</div>
						</div>

						<!-- Tombol Kanan -->
						<button class="scroll-btn right btn btn-light position-absolute top-50 end-0 translate-middle-y z-3">
							❯
						</button>
					</div>

					<!--grid-->


				</div><!--inner-content-->
			</div>

			<div class="row">
				<div class="col-md-12">

					<div class="btn-wrap align-right">
						<a href="daftar-buku.php" class="btn-accent-arrow">Lihat Daftar Buku <i
								class="icon icon-ns-arrow-right"></i></a>
					</div>

				</div>
			</div>
		</div>
	</section>



	<section id="subscribe">
		<div class="container">
			<div class="row justify-content-center">

				<div class="col-md-8">
					<div class="row">

						<div class="col-md-6">

							<div class="title-element">
								<h2 class="section-title divider">Kritik dan Saran anda sangat berarti untuk kami</h2>
							</div>

						</div>
						<div class="col-md-6">

							<div class="subscribe-content" data-aos="fade-up">
								<p>Berikan kritik dan saran dari pengalaman anda menggunakan website kami</p>
								<form id="form">
									<input type="text" name="text" placeholder="Masukan kritik anda">
									<button class="btn-subscribe">
										<span>kirim</span>
										<i class="icon icon-send"></i>
									</button>
								</form>
							</div>

						</div>

					</div>
				</div>

			</div>
		</div>
	</section>

	<?php
	include 'footer.php';
	?>

	<script>
		const scrollContainer = document.getElementById('scrollContainer');
		const scrollAmount = 300;

		document.querySelector('.scroll-btn.left').addEventListener('click', () => {
			scrollContainer.scrollLeft -= scrollAmount;
		});

		document.querySelector('.scroll-btn.right').addEventListener('click', () => {
			scrollContainer.scrollLeft += scrollAmount;
		});
	</script>

	<script src="js/jquery-1.11.0.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"
		integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm"
		crossorigin="anonymous"></script>
	<script src="js/plugins.js"></script>
	<script src="js/script.js"></script>

</body>

</html>