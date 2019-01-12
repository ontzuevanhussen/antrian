<?php
session_start();      // memulai session

// Mengecek AJAX Request
if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && ( $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest' )) {
	// panggil file "config.php" untuk koneksi ke database
	require_once "../../config/config.php";

	try {
	    // ambil "data" hasil post dari ajax
		$nama_user    = trim($_POST['nama_user']);
		$username     = trim($_POST['username']);
		$password     = sha1(md5(trim($_POST['pass'])));
		$hak_akses    = trim($_POST['hak_akses']);
		$blokir       = trim($_POST['blokir']);
		// ambil "data" dari session
		$created_user = $_SESSION['id_user'];

		// sql statement untuk menampilkan data "username" dari tabel "sys_users"
	    $query = "SELECT username FROM sys_users WHERE username=:username";
	    // membuat prepared statements
	    $stmt = $pdo->prepare($query);

	    // hubungkan "data" dengan prepared statements
	    $stmt->bindParam(':username', $username);

	    // eksekusi query
	    $stmt->execute();

	    // cek hasil query
	    // jika "username" sudah ada di tabel "sys_users"
	    if ($stmt->rowCount() <> 0) {
	        // tampilkan pesan gagal insert data
	        echo "gagal";
	    }
	    // jika "username" belum ada, jalankan perintah insert
	    else {
	    	// sql statement untuk insert data ke tabel "sys_users"
			$query = "INSERT INTO sys_users(nama_user, username, password, hak_akses, blokir, created_user) 
					  VALUES (:nama_user, :username, :password, :hak_akses, :blokir, :created_user)";
			// membuat prepared statements
			$stmt = $pdo->prepare($query);
			
			// hubungkan "data" dengan prepared statements
			$stmt->bindParam(':nama_user', $nama_user);
			$stmt->bindParam(':username', $username);
			$stmt->bindParam(':password', $password);
			$stmt->bindParam(':hak_akses', $hak_akses);
			$stmt->bindParam(':blokir', $blokir);
			$stmt->bindParam(':created_user', $created_user);

			// eksekusi query
			$stmt->execute();

			// cek query
			if ($stmt) {
			    // jika berhasil tampilkan pesan "sukses"
			    echo "sukses";
			}
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