<?php
session_start();      // memulai session

// Mengecek AJAX Request
if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && ( $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest' )) {
	// panggil file "config.php" untuk koneksi ke database
	require_once "../../config/config.php";

	try {
		// ambil "data" hasil post dari ajax
		$tanggal      = trim(date('Y-m-d', strtotime($_POST['tanggal'])));
		$pelanggan    = trim($_POST['id_pelanggan']);
		$pulsa        = trim($_POST['id_pulsa']);
		$jumlah_bayar = trim($_POST['harga']);
		// ambil "data" dari session
		$created_user = $_SESSION['id_user'];

		// sql statement untuk insert data ke tabel "penjualan"
		$query = "INSERT INTO penjualan(tanggal, pelanggan, pulsa, jumlah_bayar, created_user) 
				  VALUES (:tanggal, :pelanggan, :pulsa, :jumlah_bayar, :created_user)";
		// membuat prepared statements
		$stmt = $pdo->prepare($query);

		// hubungkan "data" dengan prepared statements
		$stmt->bindParam(':tanggal', $tanggal);
		$stmt->bindParam(':pelanggan', $pelanggan);
		$stmt->bindParam(':pulsa', $pulsa);
		$stmt->bindParam(':jumlah_bayar', $jumlah_bayar);
		$stmt->bindParam(':created_user', $created_user);

		// eksekusi query
		$stmt->execute();

		// cek query
		if ($stmt) {
		    // jika berhasil tampilkan pesan "sukses"
		    echo "sukses";
		}

		// tutup koneksi
        $pdo = null;
    } catch (PDOException $e) {
        // tampilkan pesan kesalahan
        echo $e->getMessage();
    }  
} else {
    // jika tidak ada ajax request, maka alihkan ke halaman "login-error"
    echo '<script>window.location="../../login-error"</script>';
}
?>