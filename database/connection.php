<?php
    $host = "localhost";
    $dbname = "202_ta_silib";
    $username = "root";
    $password = ""; 

    $conn = new mysqli($host, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Koneksi dengan database gagal: " . $conn->connect_error);
    }
?>
