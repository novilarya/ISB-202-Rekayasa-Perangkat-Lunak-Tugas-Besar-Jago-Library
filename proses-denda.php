<?php
    session_start();
    include('database/connection.php');

    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $kode_buku = $_POST['kode_buku'];
        $nrp_nidn = $_POST['nrp_nidn'];
        $metode = $_POST['metode'];
        $denda = $_POST['denda']; 

        $stmt = $conn->prepare("UPDATE peminjaman SET status = 'pembayaran', denda = ?, metode_pembayaran = ? WHERE kode_buku = ? AND nrp_nidn = ?");
        $stmt->bind_param("ssss", $denda, $metode, $kode_buku, $nrp_nidn);
        $stmt->execute();

        $stmtUpdateBuku = $conn->prepare("UPDATE buku SET status = 'Tersedia' WHERE kode_buku = ?");
        $stmtUpdateBuku->bind_param("s", $kode_buku);
        $stmtUpdateBuku->execute();

        $_SESSION['success'] = "Pengembalian berhasil dan denda sudah dibayar.";
        unset($_SESSION['denda']); 
        header("Location: daftar-pinjam.php");
        exit();
    }
?>