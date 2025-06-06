<?php
include 'header.php';
include('./database/connection.php');
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
							<div class="banner-content">
								<h2 class="banner-title">Life of the Wild</h2>
								<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed eu feugiat amet, libero
									ipsum enim pharetra hac. Urna commodo, lacus ut magna velit eleifend. Amet, quis
									urna, a eu.</p>
								<!-- <div class="btn-wrap">
									<a href="#" class="btn btn-outline-accent btn-accent-arrow">Read More<i
											class="icon icon-ns-arrow-right"></i></a>
								</div> -->
							</div><!--banner-content-->
							<img src="images/harry-potter51.jpg" alt="banner" class="banner-image">
						</div><!--slider-item-->

						<div class="slider-item">
							<div class="banner-content">
								<h2 class="banner-title">Birds gonna be Happy</h2>
								<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed eu feugiat amet, libero
									ipsum enim pharetra hac. Urna commodo, lacus ut magna velit eleifend. Amet, quis
									urna, a eu.</p>
								<!-- <div class="btn-wrap">
									<a href="#" class="btn btn-outline-accent btn-accent-arrow">Read More<i
											class="icon icon-ns-arrow-right"></i></a>
								</div> -->
							</div><!--banner-content-->
							<img src="images/main-banner2.jpg" alt="banner" class="banner-image">
						</div><!--slider-item-->

					</div><!--slider-->

					<button class="next slick-arrow">
						<i class="icon icon-arrow-right"></i>
					</button>

				</div>
			</div>
		</div>

	</section>

	<section id="client-holder" data-aos="fade-up">
		<div class="container">
			<div class="row">
				<div class="inner-content">
					<div class="logo-wrap">
						<div class="grid">
							<a href="#"><img src="images/client-image1.png" alt="client"></a>
							<a href="#"><img src="images/client-image2.png" alt="client"></a>
							<a href="#"><img src="images/client-image3.png" alt="client"></a>
							<a href="#"><img src="images/client-image4.png" alt="client"></a>
							<a href="#"><img src="images/client-image5.png" alt="client"></a>
						</div>
					</div><!--image-holder-->
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
						<a href="#" class="btn-accent-arrow">Lihat Daftar Buku <i
								class="icon icon-ns-arrow-right"></i></a>
					</div>

				</div>
			</div>
		</div>
	</section>

	<div class="container my-5">
		<div class="row g-4 justify-content-center">
			<!-- Daftar Pinjam -->
			<div class="col-md-3">
				<a href="daftar-pinjam.php" class="card-menu text-decoration-none text-dark">
					<div class="icon-circle">
						<i class="fas fa-book"></i>
					</div>
					<h5 class="mt-3">Daftar Pinjam</h5>
					<p class="text-muted small">Lihat semua buku yang sedang kamu pinjam.</p>
				</a>
			</div>

			<!-- Sumbang Buku -->
			<div class="col-md-3">
				<a href="sumbang.php" class="card-menu text-decoration-none text-dark">
					<div class="icon-circle">
						<i class="fas fa-upload"></i>
					</div>
					<h5 class="mt-3">Sumbang Buku</h5>
					<p class="text-muted small">Bantu pustaka dengan menyumbang buku.</p>
				</a>
			</div>

			<section id="best-selling" class="leaf-pattern-overlay">
				<div class="corner-pattern-overlay"></div>
				<div class="container">
					<div class="row justify-content-center">

						<div class="col-md-8">

							<div class="row">

								<div class="col-md-6">
									<figure class="products-thumb">
										<img src="images/single-image.jpg" alt="book" class="single-image">
									</figure>
								</div>

								<div class="col-md-6">
									<div class="product-entry">
										<h2 class="section-title divider">Best Selling Book</h2>

										<div class="products-content">
											<div class="author-name">By Timbur Hood</div>
											<h3 class="item-title">Birds gonna be happy</h3>
											<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed eu feugiat amet,
												libero ipsum enim pharetra hac.</p>
											<div class="item-price">$ 45.00</div>
											<div class="btn-wrap">
												<a href="#" class="btn-accent-arrow">shop it now <i
														class="icon icon-ns-arrow-right"></i></a>
											</div>
										</div>

									</div>
								</div>

							</div>
							<!-- / row -->

						</div>

					</div>
				</div>
			</section>

			<section id="popular-books" class="bookshelf py-5 my-5">
				<div class="container">
					<div class="row">
						<div class="col-md-12">

							<div class="section-header align-center">
								<div class="title">
									<span>Some quality items</span>
								</div>
								<h2 class="section-title">Popular Books</h2>
							</div>

							<ul class="tabs">
								<li data-tab-target="#all-genre" class="active tab">All Genre</li>
								<li data-tab-target="#business" class="tab">Business</li>
								<li data-tab-target="#technology" class="tab">Technology</li>
								<li data-tab-target="#romantic" class="tab">Romantic</li>
								<li data-tab-target="#adventure" class="tab">Adventure</li>
								<li data-tab-target="#fictional" class="tab">Fictional</li>
							</ul>

							<div class="tab-content">
								<div id="all-genre" data-tab-content class="active">
									<div class="row">

										<div class="col-md-3">
											<div class="product-item">
												<figure class="product-style">
													<img src="images/tab-item1.jpg" alt="Books" class="product-item">
													<button type="button" class="add-to-cart" data-product-tile="add-to-cart">Add to
														Cart</button>
												</figure>
												<figcaption>
													<h3>Portrait photography</h3>
													<span>Adam Silber</span>
													<div class="item-price">$ 40.00</div>
												</figcaption>
											</div>
										</div>

										<div class="col-md-3">
											<div class="product-item">
												<figure class="product-style">
													<img src="images/tab-item2.jpg" alt="Books" class="product-item">
													<button type="button" class="add-to-cart" data-product-tile="add-to-cart">Add to
														Cart</button>
												</figure>
												<figcaption>
													<h3>Once upon a time</h3>
													<span>Klien Marry</span>
													<div class="item-price">$ 35.00</div>
												</figcaption>
											</div>
										</div>

										<div class="col-md-3">
											<div class="product-item">
												<figure class="product-style">
													<img src="images/tab-item3.jpg" alt="Books" class="product-item">
													<button type="button" class="add-to-cart" data-product-tile="add-to-cart">Add to
														Cart</button>
												</figure>
												<figcaption>
													<h3>Tips of simple lifestyle</h3>
													<span>Bratt Smith</span>
													<div class="item-price">$ 40.00</div>
												</figcaption>
											</div>
										</div>

										<div class="col-md-3">
											<div class="product-item">
												<figure class="product-style">
													<img src="images/tab-item4.jpg" alt="Books" class="product-item">
													<button type="button" class="add-to-cart" data-product-tile="add-to-cart">Add to
														Cart</button>
												</figure>
												<figcaption>
													<h3>Just felt from outside</h3>
													<span>Nicole Wilson</span>
													<div class="item-price">$ 40.00</div>
												</figcaption>
											</div>
										</div>

									</div>
									<div class="row">

										<div class="col-md-3">
											<div class="product-item">
												<figure class="product-style">
													<img src="images/tab-item5.jpg" alt="Books" class="product-item">
													<button type="button" class="add-to-cart" data-product-tile="add-to-cart">Add to
														Cart</button>
												</figure>
												<figcaption>
													<h3>Peaceful Enlightment</h3>
													<span>Marmik Lama</span>
													<div class="item-price">$ 40.00</div>
												</figcaption>
											</div>
										</div>

										<div class="col-md-3">
											<div class="product-item">
												<figure class="product-style">
													<img src="images/tab-item6.jpg" alt="Books" class="product-item">
													<button type="button" class="add-to-cart" data-product-tile="add-to-cart">Add to
														Cart</button>
												</figure>
												<figcaption>
													<h3>Great travel at desert</h3>
													<span>Sanchit Howdy</span>
													<div class="item-price">$ 40.00</div>
												</figcaption>
											</div>
										</div>

										<div class="col-md-3">
											<div class="product-item">
												<figure class="product-style">
													<img src="images/tab-item7.jpg" alt="Books" class="product-item">
													<button type="button" class="add-to-cart" data-product-tile="add-to-cart">Add to
														Cart</button>
												</figure>
												<figcaption>
													<h3>Life among the pirates</h3>
													<span>Armor Ramsey</span>
													<div class="item-price">$ 40.00</div>
												</figcaption>
											</div>
										</div>

										<div class="col-md-3">
											<div class="product-item">
												<figure class="product-style">
													<img src="images/tab-item8.jpg" alt="Books" class="product-item">
													<button type="button" class="add-to-cart" data-product-tile="add-to-cart">Add to
														Cart</button>
												</figure>
												<figcaption>
													<h3>Simple way of piece life</h3>
													<span>Armor Ramsey</span>
													<div class="item-price">$ 40.00</div>
												</figcaption>
											</div>
										</div>

									</div>

								</div>
								<div id="business" data-tab-content>
									<div class="row">
										<div class="col-md-3">
											<div class="product-item">
												<figure class="product-style">
													<img src="images/tab-item2.jpg" alt="Books" class="product-item">
													<button type="button" class="add-to-cart" data-product-tile="add-to-cart">Add to
														Cart</button>
												</figure>
												<figcaption>
													<h3>Peaceful Enlightment</h3>
													<span>Marmik Lama</span>
													<div class="item-price">$ 40.00</div>
												</figcaption>
											</div>
										</div>

										<div class="col-md-3">
											<div class="product-item">
												<figure class="product-style">
													<img src="images/tab-item4.jpg" alt="Books" class="product-item">
													<button type="button" class="add-to-cart" data-product-tile="add-to-cart">Add to
														Cart</button>
												</figure>
												<figcaption>
													<h3>Great travel at desert</h3>
													<span>Sanchit Howdy</span>
													<div class="item-price">$ 40.00</div>
												</figcaption>
											</div>
										</div>

										<div class="col-md-3">
											<div class="product-item">
												<figure class="product-style">
													<img src="images/tab-item6.jpg" alt="Books" class="product-item">
													<button type="button" class="add-to-cart" data-product-tile="add-to-cart">Add to
														Cart</button>
												</figure>
												<figcaption>
													<h3>Life among the pirates</h3>
													<span>Armor Ramsey</span>
													<div class="item-price">$ 40.00</div>
												</figcaption>
											</div>
										</div>

										<div class="col-md-3">
											<div class="product-item">
												<figure class="product-style">
													<img src="images/tab-item8.jpg" alt="Books" class="product-item">
													<button type="button" class="add-to-cart" data-product-tile="add-to-cart">Add to
														Cart</button>
												</figure>
												<figcaption>
													<h3>Simple way of piece life</h3>
													<span>Armor Ramsey</span>
													<div class="item-price">$ 40.00</div>
												</figcaption>
											</div>
										</div>

									</div>
								</div>

								<div id="technology" data-tab-content>
									<div class="row">
										<div class="col-md-3">
											<div class="product-item">
												<figure class="product-style">
													<img src="images/tab-item1.jpg" alt="Books" class="product-item">
													<button type="button" class="add-to-cart" data-product-tile="add-to-cart">Add to
														Cart</button>
												</figure>
												<figcaption>
													<h3>Peaceful Enlightment</h3>
													<span>Marmik Lama</span>
													<div class="item-price">$ 40.00</div>
												</figcaption>
											</div>
										</div>

										<div class="col-md-3">
											<div class="product-item">
												<figure class="product-style">
													<img src="images/tab-item3.jpg" alt="Books" class="product-item">
													<button type="button" class="add-to-cart" data-product-tile="add-to-cart">Add to
														Cart</button>
												</figure>
												<figcaption>
													<h3>Great travel at desert</h3>
													<span>Sanchit Howdy</span>
													<div class="item-price">$ 40.00</div>
												</figcaption>
											</div>
										</div>

										<div class="col-md-3">
											<div class="product-item">
												<figure class="product-style">
													<img src="images/tab-item5.jpg" alt="Books" class="product-item">
													<button type="button" class="add-to-cart" data-product-tile="add-to-cart">Add to
														Cart</button>
												</figure>
												<figcaption>
													<h3>Life among the pirates</h3>
													<span>Armor Ramsey</span>
													<div class="item-price">$ 40.00</div>
												</figcaption>
											</div>
										</div>

										<div class="col-md-3">
											<div class="product-item">
												<figure class="product-style">
													<img src="images/tab-item7.jpg" alt="Books" class="product-item">
													<button type="button" class="add-to-cart" data-product-tile="add-to-cart">Add to
														Cart</button>
												</figure>
												<figcaption>
													<h3>Simple way of piece life</h3>
													<span>Armor Ramsey</span>
													<div class="item-price">$ 40.00</div>
												</figcaption>
											</div>
										</div>
									</div>
								</div>

								<div id="romantic" data-tab-content>
									<div class="row">
										<div class="col-md-3">
											<div class="product-item">
												<figure class="product-style">
													<img src="images/tab-item1.jpg" alt="Books" class="product-item">
													<button type="button" class="add-to-cart" data-product-tile="add-to-cart">Add to
														Cart</button>
												</figure>
												<figcaption>
													<h3>Peaceful Enlightment</h3>
													<span>Marmik Lama</span>
													<div class="item-price">$ 40.00</div>
												</figcaption>
											</div>
										</div>

										<div class="col-md-3">
											<div class="product-item">
												<figure class="product-style">
													<img src="images/tab-item3.jpg" alt="Books" class="product-item">
													<button type="button" class="add-to-cart" data-product-tile="add-to-cart">Add to
														Cart</button>
												</figure>
												<figcaption>
													<h3>Great travel at desert</h3>
													<span>Sanchit Howdy</span>
													<div class="item-price">$ 40.00</div>
												</figcaption>
											</div>
										</div>

										<div class="col-md-3">
											<div class="product-item">
												<figure class="product-style">
													<img src="images/tab-item5.jpg" alt="Books" class="product-item">
													<button type="button" class="add-to-cart" data-product-tile="add-to-cart">Add to
														Cart</button>
												</figure>
												<figcaption>
													<h3>Life among the pirates</h3>
													<span>Armor Ramsey</span>
													<div class="item-price">$ 40.00</div>
												</figcaption>
											</div>
										</div>

										<div class="col-md-3">
											<div class="product-item">
												<figure class="product-style">
													<img src="images/tab-item7.jpg" alt="Books" class="product-item">
													<button type="button" class="add-to-cart" data-product-tile="add-to-cart">Add to
														Cart</button>
												</figure>
												<figcaption>
													<h3>Simple way of piece life</h3>
													<span>Armor Ramsey</span>
													<div class="item-price">$ 40.00</div>
												</figcaption>
											</div>
										</div>
									</div>
								</div>

								<div id="adventure" data-tab-content>
									<div class="row">
										<div class="col-md-3">
											<div class="product-item">
												<figure class="product-style">
													<img src="images/tab-item5.jpg" alt="Books" class="product-item">
													<button type="button" class="add-to-cart" data-product-tile="add-to-cart">Add to
														Cart</button>
												</figure>
												<figcaption>
													<h3>Life among the pirates</h3>
													<span>Armor Ramsey</span>
													<div class="item-price">$ 40.00</div>
												</figcaption>
											</div>
										</div>

										<div class="col-md-3">
											<div class="product-item">
												<figure class="product-style">
													<img src="images/tab-item7.jpg" alt="Books" class="product-item">
													<button type="button" class="add-to-cart" data-product-tile="add-to-cart">Add to
														Cart</button>
												</figure>
												<figcaption>
													<h3>Simple way of piece life</h3>
													<span>Armor Ramsey</span>
													<div class="item-price">$ 40.00</div>
												</figcaption>
											</div>
										</div>
									</div>
								</div>

								<div id="fictional" data-tab-content>
									<div class="row">
										<div class="col-md-3">
											<div class="product-item">
												<figure class="product-style">
													<img src="images/tab-item5.jpg" alt="Books" class="product-item">
													<button type="button" class="add-to-cart" data-product-tile="add-to-cart">Add to
														Cart</button>
												</figure>
												<figcaption>
													<h3>Life among the pirates</h3>
													<span>Armor Ramsey</span>
													<div class="item-price">$ 40.00</div>
												</figcaption>
											</div>
										</div>

										<div class="col-md-3">
											<div class="product-item">
												<figure class="product-style">
													<img src="images/tab-item7.jpg" alt="Books" class="product-item">
													<button type="button" class="add-to-cart" data-product-tile="add-to-cart">Add to
														Cart</button>
												</figure>
												<figcaption>
													<h3>Simple way of piece life</h3>
													<span>Armor Ramsey</span>
													<div class="item-price">$ 40.00</div>
												</figcaption>
											</div>
										</div>
									</div>
								</div>

							</div>

						</div><!--inner-tabs-->

					</div>
				</div>
			</section>

			<section id="quotation" class="align-center pb-5 mb-5">
				<div class="inner-content">
					<h2 class="section-title divider">Quote of the day</h2>
					<blockquote data-aos="fade-up">
						<q>“The more that you read, the more things you will know. The more that you learn, the more places
							you’ll go.”</q>
						<div class="author-name">Dr. Seuss</div>
					</blockquote>
				</div>
			</section>

			<section id="special-offer" class="bookshelf pb-5 mb-5">

				<div class="section-header align-center">
					<div class="title">
						<span>Grab your opportunity</span>
					</div>
					<h2 class="section-title">Books with offer</h2>
				</div>

				<div class="container">
					<div class="row">
						<div class="inner-content">
							<div class="product-list" data-aos="fade-up">
								<div class="grid product-grid">
									<div class="product-item">
										<figure class="product-style">
											<img src="images/product-item5.jpg" alt="Books" class="product-item">
											<button type="button" class="add-to-cart" data-product-tile="add-to-cart">Add to
												Cart</button>
										</figure>
										<figcaption>
											<h3>Simple way of piece life</h3>
											<span>Armor Ramsey</span>
											<div class="item-price">
												<span class="prev-price">$ 50.00</span>$ 40.00
											</div>
									</div>
									</figcaption>

									<div class="product-item">
										<figure class="product-style">
											<img src="images/product-item6.jpg" alt="Books" class="product-item">
											<button type="button" class="add-to-cart" data-product-tile="add-to-cart">Add to
												Cart</button>
										</figure>
										<figcaption>
											<h3>Great travel at desert</h3>
											<span>Sanchit Howdy</span>
											<div class="item-price">
												<span class="prev-price">$ 30.00</span>$ 38.00
											</div>
									</div>
									</figcaption>

									<div class="product-item">
										<figure class="product-style">
											<img src="images/product-item7.jpg" alt="Books" class="product-item">
											<button type="button" class="add-to-cart" data-product-tile="add-to-cart">Add to
												Cart</button>
										</figure>
										<figcaption>
											<h3>The lady beauty Scarlett</h3>
											<span>Arthur Doyle</span>
											<div class="item-price">
												<span class="prev-price">$ 35.00</span>$ 45.00
											</div>
									</div>
									</figcaption>

									<div class="product-item">
										<figure class="product-style">
											<img src="images/product-item8.jpg" alt="Books" class="product-item">
											<button type="button" class="add-to-cart" data-product-tile="add-to-cart">Add to
												Cart</button>
										</figure>
										<figcaption>
											<h3>Once upon a time</h3>
											<span>Klien Marry</span>
											<div class="item-price">
												<span class="prev-price">$ 25.00</span>$ 35.00
											</div>
									</div>
									</figcaption>

									<div class="product-item">
										<figure class="product-style">
											<img src="images/product-item2.jpg" alt="Books" class="product-item">
											<button type="button" class="add-to-cart" data-product-tile="add-to-cart">Add to
												Cart</button>
										</figure>
										<figcaption>
											<h3>Simple way of piece life</h3>
											<span>Armor Ramsey</span>
											<div class="item-price">$ 40.00</div>
										</figcaption>
									</div>
								</div><!--grid-->
							</div>
						</div><!--inner-content-->
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
										<h2 class="section-title divider">Kritik anda sangat berarti untuk kami</h2>
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