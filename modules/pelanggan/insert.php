<?php
session_start();      // memulai session

// Mengecek AJAX Request
if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && ( $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest' )) {
	// panggil file "config.php" untuk koneksi ke database
	require_once "../../config/config.php";

	try {
		// ambil "data" hasil post dari ajax
		$nama         = trim($_POST['nama']);
		$no_hp        = trim($_POST['no_hp']);
		// ambil "data" dari session
		$created_user = $_SESSION['id_user'];

		// sql statement untuk insert data ke tabel "pelanggan"
		$query = "INSERT INTO pelanggan(nama, no_hp, created_user) VALUES (:nama, :no_hp, :created_user)";
		// membuat prepared statements
		$stmt = $pdo->prepare($query);

		// hubungkan "data" dengan prepared statements
		$stmt->bindParam(':nama', $nama);
		$stmt->bindParam(':no_hp', $no_hp);
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