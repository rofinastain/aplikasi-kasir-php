
<?php

include 'config.php';
session_start();

include 'authcheckkasir.php';

$view = $dbconnect->query('SELECT * FROM transaksi');
// return var_dump($view);

?>

<!DOCTYPE html>
<html>
<head>
	<title>Riwayat Transaksi</title>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
</head>
<body>
<div class="container">

	<!-- <?php if (isset($_SESSION['success']) && $_SESSION['success'] != '') {?>

		<div class="alert alert-success" role="alert">
			<?=$_SESSION['success']?>
		</div>

	<?php
        }
        $_SESSION['success'] = '';
    ?> -->

    <h1>Riwayat Transaksi</h1>
    <a href="/">Kembali</a>
	<table class="table table-bordered">
		<tr>
			<th>#Nomor</th>
			<th>Tanggal</th>
			<th>Total</th>
			<th>Kasir</th>
			<th></th>
		</tr>
		<?php while ($row = $view->fetch_array()) : 
            $formatted_date = date('d-m-Y H:i:s', strtotime($row['tanggal_waktu'])); // Format tanggal di sini
        ?>
            <tr>
                <td><?= $row['nomor'] ?></td>
                <td><?= $formatted_date ?></td>
                <td><?= number_format($row['total']) ?></td>
                <td><?= $row['nama'] ?></td>
                <td>
                    <a href="/transaksi_selesai.php?idtrx=<?= $row['id_transaksi'] ?>" class="btn btn-primary" target="_blank">Lihat</a>
                </td>
            </tr>
        <?php endwhile; ?>

	</table>
</div>
</body>
</html>