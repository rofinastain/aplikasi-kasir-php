<?php
// session_start();

//auth check kasir
if (isset($_SESSION['userid'])) {
    if ($_SESSION['role_id'] == 1) {
        //redirect ke halaman index.php
        header('Location:index.php');
    }
} else {
    $_SESSION['error'] = 'Anda harus login dahulu';
    header('location:login.php');
}
