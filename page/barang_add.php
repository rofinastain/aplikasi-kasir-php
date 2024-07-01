<?php
include 'authcheck.php';

// Fungsi untuk menghasilkan kode barang
// Fungsi untuk menghasilkan kode barang
function generateKodeBarang($namaBarang) {
    // Mengambil huruf pertama dari setiap kata dalam nama barang
    $inisial = '';
    $kata = explode(' ', $namaBarang); // Memecah nama barang menjadi kata-kata berdasarkan spasi
    foreach ($kata as $k) {
        if (!empty($k)) {
            $inisial .= strtoupper($k[0]); // Mengambil huruf pertama dari setiap kata dan membuatnya kapital
        }
    }
    
    // Menggunakan tiga digit angka acak
    $unik = rand(100, 999);  // Menghasilkan tiga digit acak

    // Menggabungkan inisial dengan kode unik
    return $inisial . $unik;
}




if (isset($_POST['simpan'])) {
    $nama = $_POST['nama'];
    $harga = $_POST['harga'];
    $jumlah = $_POST['jumlah'];

    // Memanggil fungsi untuk menghasilkan kode barang
    $kode_barang = generateKodeBarang($nama);

    // Menyimpan ke database;
    mysqli_query($dbconnect, "INSERT INTO barang VALUES (NULL,'$nama','$harga','$jumlah','$kode_barang')");

    $_SESSION['success'] = 'Berhasil menambahkan data';

    // Mengalihkan halaman ke list barang
    header('location: index.php?page=barang');
}
?>

<div class="container">
    <h1>Tambah Barang</h1>
    <form method="post">
        <div class="form-group">
            <label>Nama Barang</label>
            <input type="text" name="nama" class="form-control" placeholder="Nama barang" required>
        </div>
        <div class="form-group">
            <label>Harga</label>
            <input type="number" name="harga" class="form-control" placeholder="Harga Barang" required>
        </div>
        <div class="form-group">
            <label>Jumlah Stock</label>
            <input type="number" name="jumlah" class="form-control" placeholder="Jumlah Stock" required>
        </div>
        <input type="submit" name="simpan" value="Simpan" class="btn btn-primary">
        <a href="?page=barang" class="btn btn-warning">Kembali</a>
    </form>
</div>
