
<?php
include 'authcheck.php';

function generateKodeBarang($namaBarang) {
    $inisial = '';
    $kata = explode(' ', $namaBarang);
    foreach ($kata as $k) {
        if (!empty($k)) {
            $inisial .= strtoupper($k[0]);
        }
    }
    $unik = rand(100, 999);
    return $inisial . $unik;
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $data = mysqli_query($dbconnect, "SELECT * FROM barang where id_barang='$id'");
    $data = mysqli_fetch_assoc($data);
}

if (isset($_POST['update'])) {
    $id = $_GET['id'];
    $nama = $_POST['nama'];
    $harga = $_POST['harga'];
    $jumlah = $_POST['jumlah'];

    // Cek apakah nama barang diubah, jika iya, buat kode barang baru
    if ($nama != $data['nama']) {
        $kode_barang = generateKodeBarang($nama);
    } else {
        $kode_barang = $_POST['kode_barang'];
    }

    mysqli_query($dbconnect, "UPDATE barang SET nama='$nama', harga='$harga', jumlah='$jumlah', kode_barang='$kode_barang' where id_barang='$id'");
    $_SESSION['success'] = 'Berhasil memperbaruhi data';
    header('location: index.php?page=barang');
}
?>

<div class="container">
    <h1>Edit Barang</h1>
    <form method="post">
        <div class="form-group">
            <label>Nama Barang</label>
            <input type="text" name="nama" class="form-control" placeholder="Nama barang" value="<?=$data['nama']?>" required>
        </div>
        <div class="form-group">
            <label>Kode Barang</label>
            <input type="text" name="kode_barang" class="form-control" placeholder="Kode barang" value="<?=$data['kode_barang']?>" disabled>
        </div>
        <div class="form-group">
            <label>Harga</label>
            <input type="number" name="harga" class="form-control" placeholder="Harga Barang" value="<?=$data['harga']?>" required>
        </div>
        <div class="form-group">
            <label>Jumlah Stock</label>
            <input type="number" name="jumlah" class="form-control" placeholder="Jumlah Stock" value="<?=$data['jumlah']?>" required>
        </div>
        <input type="hidden" name="kode_barang" value="<?=$data['kode_barang']?>">
		<input type="submit" name="update" value="Perbaruhi" class="btn btn-primary">
        <a href="index.php?page=barang" class="btn btn-warning">Kembali</a>
    </form>
</div>
