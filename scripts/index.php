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
    <title>Jago Library | Dashboard</title>
    <link rel="stylesheet" href="/css/styles.css">
    <script src="/scripts/scroll.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
      integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>
    <header>
        <?php include "header.php"?>
    </header>

    <div class="hero">
        <div class="hero-content">
            <h1>Explore the World of Books</h1>
            <p>Discover, Learn, 
                and Grow with the best collection of books Enjoy to Jago Library.</p>
            <a href="#" class="hero-button">Discover Now</a>
        </div>
    </div>

    <div class="featured-container" id="container">
    <h2>Featured Books</h2>
    <div class="book-slider">
        <?php while($row = $buku->fetch_assoc()) { ?>
            <div class="book-card">
                <div class="book-image">
                    <img src="/images/<?php echo $row['cover_buku']; ?>" alt="Harry Potter">
                </div>
                <div class="book-info-index">
                    <h3><?php echo $row['nama_buku']; ?></h3>
                    <p><?php echo $row['pengarang']; ?></p>
                    <p class="rating">⭐ 5/5</p>
                    <p class="description"><?php echo $row['deskripsi_buku']; ?></p>
                    <a href="<?php echo "pinjam.php?kode_buku=" . $row['kode_buku']; ?>"><button class="pinjam-button"> Pinjam </button></a>
                </div>
            </div>
        <?php } ?>
    </div>

    <div class="slider-controls">
        <button onclick="prevSlide()" class="slider-btn left-btn">◀</button>
        <button onclick="nextSlide()" class="slider-btn right-btn">▶</button>
    </div>
</div>

    <section id="categories" class="pt-0">
    <div class="container-category">
        <div class="section-title overflow-hidden mb-4">
            <h2 class="d-flex align-items-center">Categories</h2>
        </div>
        <div class="row">
            <div class="col-md-4">
                <div class="card category-card mb-4 rounded-4 position-relative overflow-hidden">
                    <a href="index.html" class="text-decoration-none">
                        <img src="/images/category1.jpg" class="img-fluid rounded-3" alt="Romance">
                        <h6 class="position-absolute bottom-0 m-4 py-2 px-3 rounded-3 text-white">Romance</h6>
                    </a>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card category-card mb-4 rounded-4 position-relative overflow-hidden">
                    <a href="index.html" class="text-decoration-none">
                        <img src="/images/category2.jpg" class="img-fluid rounded-3" alt="Lifestyle">
                        <h6 class="position-absolute bottom-0 m-4 py-2 px-3 rounded-3 text-white">Lifestyle</h6>
                    </a>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card category-card mb-4 rounded-4 position-relative overflow-hidden">
                    <a href="index.html" class="text-decoration-none">
                        <img src="/images/category3.jpg" class="img-fluid rounded-3" alt="Recipe">
                        <h6 class="position-absolute bottom-0 m-4 py-2 px-3 rounded-3 text-white">Recipe</h6>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>


    <footer>
        <?php include "footer.php"; ?>
    </footer>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
      integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</body>
</html>
