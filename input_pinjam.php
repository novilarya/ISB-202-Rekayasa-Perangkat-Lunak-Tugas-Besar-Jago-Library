<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form input pinjam | Jago_library</title>
    <link rel="stylesheet" href="/css/styles.css">
</head>

<body>
    <header>
        <?php include "header.php"; ?>
    </header>

    <form method="POST" action="pinjam.php" class="form-pinjam">
        <div class="form-group">
            <label for="tanggal_pinjam">Tanggal Pinjam</label>
            <input type="date" name="tanggal_pinjam" id="tanggal_pinjam" required>
        </div>

        <div class="form-group">
            <label for="tanggal_kembali">Tanggal Kembali</label>
            <input type="date" name="tanggal_kembali" id="tanggal_kembali" required>
        </div>

        <button type="submit" class="pinjam-button-tanggal">Pinjam</button>
    </form>


    <footer>
        <?php include "footer.php"; ?>
    </footer>
</body>

</html>