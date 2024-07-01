<?php
include 'config.php';
session_start();
include "authcheckkasir.php";

// Menghilangkan non-digit dari input bayar untuk menghindari error format
$bayar = preg_replace('/\D/', '', $_POST['bayar']);

$tanggal_waktu = date('Y-m-d H:i:s');
$nomor = rand(111111,999999);
$total = $_POST['total'];
$nama = $_SESSION['nama'];
$kembali = $bayar - $total;

// Cek stok cukup untuk setiap barang di keranjang sebelum proses transaksi
foreach ($_SESSION['cart'] as $item) {
    $cekStok = mysqli_query($dbconnect, "SELECT jumlah FROM barang WHERE id_barang = '{$item['id']}'");
    $dataStok = mysqli_fetch_assoc($cekStok);

    if ($dataStok['jumlah'] < $item['qty']) {
        $_SESSION['error'] = "Stok untuk barang '{$item['nama']}' tidak mencukupi.";
        header('location: kasir.php');
        exit;
    }
}

// Lanjutkan dengan proses transaksi jika stok cukup
$insertTransaksi = mysqli_query($dbconnect, "INSERT INTO transaksi (tanggal_waktu, nomor, total, nama, bayar, kembali) VALUES ('$tanggal_waktu', '$nomor', '$total', '$nama', '$bayar', '$kembali')");

if ($insertTransaksi) {
    $id_transaksi = mysqli_insert_id($dbconnect);

    // Insert ke detail transaksi dan update stok barang
    foreach ($_SESSION['cart'] as $key => $value) {
        $id_barang = $value['id'];
        $harga = $value['harga'];
        $qty = $value['qty'];
        $total = $harga * $qty;
        $diskon = $value['diskon'] ?? 0;  // Asumsikan diskon sudah di-set dalam cart

        $insertDetail = mysqli_query($dbconnect, "INSERT INTO transaksi_detail (id_transaksi, id_barang, harga, qty, total, diskon) VALUES ('$id_transaksi', '$id_barang', '$harga', '$qty', '$total', '$diskon')");
        
        if ($insertDetail) {
            // Update stok barang
            $updateStok = mysqli_query($dbconnect, "UPDATE barang SET jumlah = jumlah - $qty WHERE id_barang = '$id_barang'");
            if (!$updateStok) {
                $_SESSION['error'] = 'Gagal mengupdate stok barang.';
                header('location: kasir.php');
                exit;
            }
        } else {
            $_SESSION['error'] = 'Gagal menyimpan detail transaksi.';
            header('location: kasir.php');
            exit;
        }
    }

    $_SESSION['cart'] = [];  // Kosongkan keranjang setelah transaksi selesai
    $_SESSION['success'] = "Transaksi berhasil, kembali: Rp " . number_format($kembali);
    header("location:transaksi_selesai.php?idtrx=" . $id_transaksi);
    exit;
} else {
    $_SESSION['error'] = 'Gagal menyimpan transaksi.';
    header('location: kasir.php');
    exit;
}
?>
