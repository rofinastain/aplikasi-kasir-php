<?php
session_start();
include 'config.php';
include 'authcheckkasir.php';

// Inisialisasi keranjang jika belum ada
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Penanganan form submit
if (isset($_POST['kode_barang']) && isset($_POST['qty']) && is_numeric($_POST['qty'])) {
    $kode_barang = $_POST['kode_barang'];
    $qty = intval($_POST['qty']);  // Konversi kuantitas ke integer

    // Mengambil data barang dari database
    $data = mysqli_query($dbconnect, "SELECT * FROM barang WHERE kode_barang='$kode_barang'");
    $b = mysqli_fetch_assoc($data);

    // Memeriksa ketersediaan stok
    if ($b['jumlah'] < $qty) {
        $_SESSION['error'] = "Stok tidak mencukupi untuk '{$b['nama']}'. Tersedia hanya {$b['jumlah']} unit.";
        header('location: kasir.php');
        exit;
    }

    // Mencari barang di dalam keranjang
    $key = array_search($b['id_barang'], array_column($_SESSION['cart'], 'id'));

    if ($key !== false) {
        // Jika barang sudah ada, tambah jumlahnya
        $_SESSION['cart'][$key]['qty'] += $qty;
    } else {
        // Jika barang baru, tambahkan ke keranjang
        $_SESSION['cart'][] = [
            'id' => $b['id_barang'],
            'nama' => $b['nama'],
            'harga' => $b['harga'],
            'qty' => $qty
        ];
    }

    header('location: kasir.php');
} else {
    // Menetapkan error jika input tidak valid
    $_SESSION['error'] = 'Masukkan jumlah yang valid.';
    header('location: kasir.php');
}
?>
