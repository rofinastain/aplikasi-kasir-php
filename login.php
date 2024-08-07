
<?php
include 'config.php';
session_start();
// remove all session variables
// session_unset();

// print_r($_SESSION);

if (isset($_POST['masuk'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $query = mysqli_query($dbconnect, "SELECT * FROM user WHERE username='$username' and password='$password'");

    //mendapatkan hasil dari data
    $data = mysqli_fetch_assoc($query);
    // return var_dump($data);

    //mendapatkan nilai jumlah data
    $check = mysqli_num_rows($query);
    // return var_dump($check);

    if (!$check) {
        $_SESSION['error'] = 'Username & password salah';
    } else {
        $_SESSION['userid'] = $data['id_user'];
        $_SESSION['nama'] = $data['nama'];
        $_SESSION['role_id'] = $data['role_id'];

        header('location:index.php');
    }
}

?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="Mark Otto, Jacob Thornton, and Bootstrap contributors">
    <meta name="generator" content="Jekyll v4.0.1">
    <title>Masuk</title>

    <!-- Bootstrap core CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" crossorigin="anonymous">

    <style>
        body {
            background-image: url('image/kasir.png'); /* Memastikan path benar */
            background-size: cover;
            background-position: center;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh; /* Menggunakan full-screen height */
            margin: 0;
        }
        .form-container {
            background-color: rgba(255, 255, 255, 0.9); /* Latar belakang transparan putih */
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.15);
            width: 100%;
            max-width: 400px;
        }
        .bd-placeholder-img {
            font-size: 1.125rem;
            text-anchor: middle;
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
        }
        @media (min-width: 768px) {
            .bd-placeholder-img-lg {
                font-size: 3.5rem;
            }
        }
    </style>
</head>
<body class="text-center">
    <div class="form-container"> <!-- Container untuk form -->
        <form method="post" class="form-signin">
        <h1 class="h3 mb-3 font-weight-normal">Toko Sya'adah</h1>
            <!-- Alert -->
            <?php if (isset($_SESSION['error']) && $_SESSION['error'] != '') { ?>
                <div class="alert alert-danger" role="alert">
                    <?= $_SESSION['error'] ?>
                </div>
            <?php }
                $_SESSION['error'] = '';
            ?>
            <h4>Silakan Masuk</h4>
            <label for="inputEmail" class="sr-only">Username</label>
            <input type="text" class="form-control" name="username" placeholder="Username">
            <label for="inputPassword" class="sr-only">Password</label>
            <input type="password" class="form-control" name="password" placeholder="Password">
            <div class="checkbox mb-3">
                <!-- <label>
                    <input type="checkbox" value="remember-me"> Remember me
                </label> -->
            </div>
            <input type="submit" name="masuk" value="Sign in" class="btn btn-lg btn-primary btn-block"/>
            <a href="register.php" class="btn btn-lg btn-secondary btn-block">Daftar</a>
            <p class="mt-5 mb-3 text-muted">&copy; 2024</p>
        </form>
    </div>
</body>
</html>
