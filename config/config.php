<?php
// set default timezone
date_default_timezone_set("ASIA/JAKARTA");

// panggil file parameter koneksi database
require_once "database.php";

try {
	// PDO driver (database MySQL)
	$dsn = 'mysql:host='.$con['host'].';dbname='.$con['db'];
	// buat koneksi dengan database
	$pdo = new PDO($dsn, $con['user'], $con['pass']);
	// set error mode
	$pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
	// tampilkan pesan kesalahan jika koneksi gagal
	echo "Koneksi Database Gagal! : ".$e->getMessage();
}
?>