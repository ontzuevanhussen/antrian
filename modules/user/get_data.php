<?php
// Mengecek AJAX Request
if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && ( $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest' )) {
	// panggil file "config.php" untuk koneksi ke database
	require_once "../../config/config.php";

	// mengecek data get dari ajax
    if (isset($_GET['id_user'])) {
        try {
            // ambil "data" get dari ajax
            $id_user = $_GET['id_user'];

        	// sql statement untuk menampilkan data dari tabel "sys_users" berdasarkan "id_user"
            $query = "SELECT * FROM sys_users WHERE id_user=:id_user";
            // membuat prepared statements
            $stmt = $pdo->prepare($query);

        	// hubungkan "data" dengan prepared statements
            $stmt->bindParam(':id_user', $id_user);

            // eksekusi query
            $stmt->execute();

            // ambil data hasil query
            $data = $stmt->fetch(PDO::FETCH_ASSOC);
            // tampilkan data
            echo json_encode($data);

            // tutup koneksi
            $pdo = null;
        } catch (PDOException $e) {
            // tampilkan pesan kesalahan
            echo "Query Error : ".$e->getMessage();
        }
	}
} else {
    // jika tidak ada ajax request, maka alihkan ke halaman "login-error"
    echo '<script>window.location="../../login-error"</script>';
}
?>