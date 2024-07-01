<?php
$host = 'localhost';
$user = 'root';
$pass = '';
$db = 'kasirdb';

// Set zona waktu ke Asia/Jakarta
date_default_timezone_set('Asia/Jakarta');

$dbconnect = new mysqli("$host", "$user", "$pass", "$db");

if ($dbconnect-> connect_error) {
    echo 'Koneksi gagal -> ' . $dbconnect->connect_error;
}
