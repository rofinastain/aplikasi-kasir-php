<?php
session_start();
include 'config.php';
include 'authcheckkasir.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

if (isset($_POST['kode_barang']) && isset($_POST['qty']) && is_numeric($_POST['qty'])) {
    $kode_barang = $_POST['kode_barang'];
    $qty = intval($_POST['qty']);  // Mengonversi jumlah yang diinput menjadi bilangan bulat

    // Menampilkan data barang
    $data = mysqli_query($dbconnect, "SELECT * FROM barang WHERE kode_barang='$kode_barang'");
    $b = mysqli_fetch_assoc($data);

    if ($b['jumlah'] < $qty) {
        // Jika stok tidak cukup
        $_SESSION['error'] = "Stok untuk '{$b['nama']}' tidak mencukupi, tersedia hanya {$b['jumlah']} unit.";
        header('location: kasir.php');
        exit;
    }

    // Cek jika barang sudah ada di keranjang
    $key = array_search($b['id_barang'], array_column($_SESSION['cart'], 'id'));

    if ($key !== false) {
        // Jika barang sudah ada, tambahkan jumlahnya
        $_SESSION['cart'][$key]['qty'] += $qty;
    } else {
        // Jika barang baru, tambahkan ke keranjang
        $barang = [
            'id' => $b['id_barang'],
            'nama' => $b['nama'],
            'harga' => $b['harga'],
            'qty' => $qty
        ];

        $_SESSION['cart'][] = $barang;
    }

    header('location: kasir.php');
} else {
    // Jika jumlah tidak di-set atau tidak valid
    $_SESSION['error'] = 'Jumlah yang dimasukkan tidak valid. Harap masukkan angka yang benar.';
    header('location: kasir.php');
}
?>
