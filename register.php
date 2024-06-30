<?php
session_start();
include 'config.php';  // Pastikan ini adalah file yang benar untuk koneksi database

if (isset($_POST['register'])) {
    $nama = $_POST['nama'];
    $username = $_POST['username'];
    $password = $_POST['password']; // Gunakan password hashing
    $role_id = 1; // Role ID '1' untuk admin

    $query = mysqli_query($dbconnect, "INSERT INTO user (nama, username, password, role_id) VALUES ('$nama', '$username', '$password', '$role_id')");

    if ($query) {
        $_SESSION['error'] = 'Pendaftaran berhasil. Silakan login sebagai admin.';
        header('location:login.php');
    } else {
        $_SESSION['error'] = 'Pendaftaran gagal. Username mungkin sudah digunakan.';
    }
}

?>
<!doctype html>
<html lang="en">
    <head>
        <title>Daftar</title>
        <style>
    body {
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
        background-color: #f5f5f5;
        font-family: 'Arial', sans-serif;
    }

    .form-signin {
        width: 100%;
        max-width: 330px;
        padding: 15px;
        margin: auto;
        background-color: white;
        border-radius: 8px;
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }

    .form-signin h1 {
        font-size: 24px;
        margin-bottom: 20px;
    }

    .form-control {
        position: relative;
        box-sizing: border-box;
        height: auto;
        padding: 10px;
        font-size: 16px;
        margin-bottom: 10px;
    }

    .btn-primary {
        color: #fff;
        background-color: #007bff;
        border-color: #007bff;
    }

    .btn-primary:hover {
        color: #fff;
        background-color: #0056b3;
        border-color: #004085;
    }

    .btn-block {
        display: block;
        width: 100%;
    }

    .mt-2 {
        margin-top: 8px;
    }

    .alert-danger {
        color: #721c24;
        background-color: #f8d7da;
        border-color: #f5c6cb;
        padding: 10px;
        border-radius: 4px;
        margin-bottom: 20px;
    }
</style>

    </head>
    <body class="text-center">
        <form method="post" class="form-signin">
            <h1 class="h3 mb-3 font-weight-normal">Daftar Akun Baru</h1>
            <?php if (isset($_SESSION['error']) && $_SESSION['error'] != '') { ?>
                <div class="alert alert-danger" role="alert">
                    <?= $_SESSION['error'] ?>
                </div>
            <?php }
                $_SESSION['error'] = ''; // Bersihkan pesan error setelah ditampilkan
            ?>
            <input type="text" class="form-control" name="nama" placeholder="Nama Lengkap" required autofocus>
            <input type="text" class="form-control" name="username" placeholder="Username" required>
            <input type="password" class="form-control" name="password" placeholder="Password" required>
            <button class="btn btn-lg btn-primary btn-block" type="submit" name="register">Daftar</button>
            <p class="mt-2">Sudah punya akun? <a href="login.php">Masuk</a></p>

        </form>
    </body>
</html>
