<?php
    $host = "localhost";
    $dbname = "Esilib";
    $username = "root";
    $password = ""; 

    $conn = new mysqli($host, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Koneksi dengan database gagal: " . $conn->connect_error);
    }
?>
