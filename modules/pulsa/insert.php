<?php
session_start();      // memulai session

// Mengecek AJAX Request
if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && ( $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest' )) {
	// panggil file "config.php" untuk koneksi ke database
	require_once "../../config/config.php";

	try {
		// ambil "data" hasil post dari ajax
		$provider     = trim($_POST['provider']);
		$nominal      = trim($_POST['nominal']);
		$harga        = trim($_POST['harga']);
		// ambil "data" dari session
		$created_user = $_SESSION['id_user'];

		// sql statement untuk insert data ke tabel "pulsa"
		$query = "INSERT INTO pulsa(provider, nominal, harga, created_user) VALUES (:provider, :nominal, :harga, :created_user)";
		// membuat prepared statements
		$stmt = $pdo->prepare($query);

		// hubungkan "data" dengan prepared statements
		$stmt->bindParam(':provider', $provider);
		$stmt->bindParam(':nominal', $nominal);
		$stmt->bindParam(':harga', $harga);
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