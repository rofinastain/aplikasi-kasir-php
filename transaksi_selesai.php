<?php
session_start();
require_once 'library/dompdf/autoload.inc.php';
use Dompdf\Dompdf;
include 'config.php';
include 'authcheckkasir.php';

// Check if cart is not empty and process stock reduction
if (!empty($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $item) {
        $id_barang = $item['id'];
        $qty = $item['qty'];

        // Reduce stock of the items
        $update = mysqli_query($dbconnect, "UPDATE barang SET jumlah = jumlah - $qty WHERE id_barang='$id_barang' AND jumlah >= $qty");

        if (!$update) {
            $_SESSION['error'] = 'Gagal mengupdate stok barang.';
            header('location: kasir.php');
            exit;
        }
    }

    // Clear the cart after processing
    $_SESSION['cart'] = [];
}

// Fetch transaction details
$id_trx = $_GET['idtrx'];

$data = mysqli_query($dbconnect, "SELECT * FROM transaksi WHERE id_transaksi='$id_trx'");
$trx = mysqli_fetch_assoc($data);

$detail = mysqli_query($dbconnect, "SELECT transaksi_detail.*, barang.nama FROM `transaksi_detail` INNER JOIN barang ON transaksi_detail.id_barang=barang.id_barang WHERE transaksi_detail.id_transaksi='$id_trx'");
// Membuat instance Dompdf
$dompdf = new Dompdf();

ob_start();  // Mulai output buffering
?>


<!DOCTYPE html>
<html>
<head>
	<title>Kasir Selesai</title>
	<style type="text/css">
		body{
			color: #000000;
		}
	</style>
</head>
<body>
	<div align="center">
		<table width="100%" border="0" cellpadding="1" cellspacing="0">
			<tr align="center">
			<th>Toko Sya'adah <br>
					Jl. Pertengahan Gg. Salam 4  <br>
				Cijantung, Pasar Rebo, Jakarta Timur</th>
			</tr>
			<tr align="center"><td><hr></td></tr>
			<tr align="center">
				<td>#<?=$trx['nomor']?> | <?=date('d-m-Y H:i:s',strtotime($trx['tanggal_waktu']))?> <?=$trx['nama']?></td>
			</tr>
			<tr><td><hr></td></tr>
		</table>
		<table width="100%" border="0" cellpadding="3" cellspacing="0">
			<?php while($row = mysqli_fetch_array($detail)){ ?>
			<tr>
				<td><?=$row['nama']?></td>
				<td><?=$row['qty']?></td>
				<td align="right"><?=number_format($row['harga'])?></td>
				<td align="right"><?=number_format($row['total'])?></td>
			</tr>
			<?php } ?>
			<tr>
				<td colspan="4"><hr></td>
			</tr>
			<tr>
				<td align="right" colspan="3">Total</td>
				<td align="right"><?=number_format($trx['total'])?></td>
			</tr>
			<tr>
				<td align="right" colspan="3">Bayar</td>
				<td align="right"><?=number_format($trx['bayar'])?></td>
			</tr>
			<tr>
				<td align="right" colspan="3">Kembali</td>
				<td align="right"><?=number_format($trx['kembali'])?></td>
			</tr>
		</table>
		<table width="100%" border="0" cellpadding="1" cellspacing="0">
			<tr><td><hr></td></tr>
			<tr align="center">
				<th>Terimakasih, Selamat Belanja Kembali</th>
			</tr>
			<tr align="center">
				<th>===== Layanan Konsumen ====</th>
			</tr>
			<tr align="center">
				<th>WhatsApp +6281932855057</th>
			</tr>
		</table>
	</div>
</body>
</html>
<?php
$html = ob_get_clean();  // Mendapatkan output dan membersihkan buffer

$dompdf->loadHtml($html);  // Memuat HTML ke Dompdf
$dompdf->setPaper('A4', 'portrait');  // Mengatur ukuran dan orientasi kertas
$dompdf->render();  // Merender dokumen ke format PDF

// Output PDF ke browser
$dompdf->stream("Invoice #{$trx['nomor']}.pdf", array("Attachment" => true));
exit;  // Pastikan tidak ada output lain yang mengganggu PDF