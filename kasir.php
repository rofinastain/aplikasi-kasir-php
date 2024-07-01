<?php
session_start();
include 'config.php';
include 'authcheckkasir.php';

$barang = mysqli_query($dbconnect, 'SELECT * FROM barang');

$sum = 0;
if (isset($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $key => $value) {
        $sum += ($value['harga'] * $value['qty']);
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Kasir</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" crossorigin="anonymous">
</head>
<body>
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h1>Kasir</h1>
            <h2>Hai <?=$_SESSION['nama']?></h2>
            <?php if (isset($_SESSION['error']) && $_SESSION['error'] != ''): ?>
            <div class="alert alert-danger" role="alert">
                <?= $_SESSION['error']; ?>
            </div>
            <?php $_SESSION['error'] = ''; endif; ?>
            <a href="logout.php">Logout</a> |
            <a href="keranjang_reset.php">Reset Keranjang</a> |
            <a href="riwayat.php">Riwayat Transaksi</a>
        </div>
    </div>
    <hr>
    <div class="row">
        <div class="col-md-8">
            <form method="post" action="keranjang_act.php" class="form-inline">
                <div class="input-group">
                    <select class="form-control" name="kode_barang">
                        <option value="">Pilih Barang</option>
                        <?php while ($row = mysqli_fetch_array($barang)) { ?>
                            <option value="<?=$row['kode_barang']?>"><?=$row['nama']?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="input-group">
                    <input type="number" name="qty" class="form-control" placeholder="Jumlah">
                    <span class="input-group-btn">
                        <button class="btn btn-primary" type="submit">Tambah</button>
                    </span>
                </div>
            </form>
            <br>
            <form method="post" action="keranjang_update.php">
                <table class="table table-bordered">
                    <tr>
                        <th>Nama</th>
                        <th>Harga</th>
                        <th>Qty</th>
                        <th>Sub Total</th>
                        <th>Hapus</th>
                    </tr>
                    <?php if (isset($_SESSION['cart'])): ?>
                    <?php foreach ($_SESSION['cart'] as $key => $value) { ?>
                        <tr>
                            <td><?=$value['nama']?></td>
                            <td align="right"><?=number_format($value['harga'])?></td>
                            <td class="col-md-2">
                                <input type="number" name="qty[<?=$key?>]" value="<?=$value['qty']?>" class="form-control">
                            </td>
                            <td align="right"><?=number_format($value['qty']*$value['harga'])?></td>

                            <td><a href="keranjang_hapus.php?id=<?=$value['id']?>" class="btn btn-danger"><i class="glyphicon glyphicon-remove"></i></a></td>
                        </tr>
                    <?php } ?>
                    <?php endif; ?>
                </table>
                <button type="submit" class="btn btn-success">Perbaruhi</button>
            </form>
        </div>
        <div class="col-md-4">
            <h3>Total Rp <?=number_format($sum)?></h3>
            <form action="transaksi_act.php" method="POST">
                <input type="hidden" name="total" value="<?=$sum?>">
                <div class="form-group">
                    <label>Bayar</label>
                    <input type="text" id="bayar" name="bayar" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Kembalian</label>
                    <input type="text" id="kembalian" class="form-control" disabled>
                </div>
                <button type="submit" class="btn btn-primary">Selesai</button>
            </form>
        </div>
    </div>
</div>
<script type="text/javascript">
    var bayar = document.getElementById('bayar');
    var kembalian = document.getElementById('kembalian');
    var total = <?= $sum ?>;

    bayar.addEventListener('keyup', function (e) {
        var bayarValue = cleanRupiah(this.value); // Membersihkan format sebelum melakukan perhitungan
        var kembaliValue = bayarValue - total;
        kembalian.value = kembaliValue > 0 ? formatRupiah(kembaliValue.toString(), 'Rp ') : 'Tidak cukup';
        this.value = formatRupiah(bayarValue.toString(), 'Rp '); // Memformat ulang nilai input bayar
    });

    function formatRupiah(angka, prefix) {
        var number_string = angka.replace(/[^,\d]/g, '').toString(),
            split = number_string.split(','),
            sisa = split[0].length % 3,
            rupiah = split[0].substr(0, sisa),
            ribuan = split[0].substr(sisa).match(/\d{3}/gi);

        if (ribuan) {
            separator = sisa ? '.' : '';
            rupiah += separator + ribuan.join('.');
        }

        rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
        return prefix == undefined ? rupiah : (prefix + rupiah);
    }

    function cleanRupiah(rupiah) {
        return rupiah.replace(/[^,\d]/g, '').replace(',', '.');
    }
</script>

