<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Pinjaman | Jago_library</title>
</head>
<header>
        <?php include "header.php"; ?>
    </header>
<body>

    <div class="dashboard">
    <aside class="sidebar">
      <h1 class="logo">Books</h1>
      <nav class="menu">
        <ul>
          <li class="active">My Books</li>
          <a class="active" href="sumbang.php"><li>Sumbang buku</li></a>
          <li> Sumbang</li>
          <!-- <li> </li> -->
        </ul>
      </nav>
      <!-- <div class="book-types">
        <h3>Books Types</h3>
        <ul>
          <li>Biography</li>
          <li>Kids</li>
          <li>Sports</li>
        </ul>
      </div> -->
    </aside>

    <main class="main-content">
      <section class="books-section">
        <div class="section-header">
          <h2>My Books</h2>
        </div>

        <div class="book-grid">
          <!-- Book Card -->
                <div class="book-card-daftar">
        <img src="/images/harry-potter3.jpg" alt="Harry Potter Book" />
        <div class="book-info-daftar">
  <h3>Harry Potter Chamber Of Secret</h3>

  <div class="book-description">
    <div class="row">
      <span class="detail-label">Format</span>
      <span class="detail-value">170x215 mm</span>
    </div>
    <div class="row">
      <span class="detail-label">Number of pages</span>
      <span class="detail-value">348</span>
    </div>
    <div class="row">
      <span class="detail-label">Year of issue</span>
      <span class="detail-value">2003</span>
    </div>
    <div class="row">
      <span class="detail-label">ISBN</span>
      <span class="detail-value">5-353-01339-5</span>
    </div>
    <div class="row">
      <span class="detail-label">Circulation</span>
      <span class="detail-value">11000</span>
    </div>
  </div>

  <button class="kembalikan">Kembalikan</button>
</div>
        </div>
      </section>
    </main>
  </div>

    <footer>
        <?php include "footer.php"; ?>
    </footer>
</body>
</html>