<?php
// Mengecek AJAX Request
if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && ( $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest' )) {
	// panggil file "config.php" untuk koneksi ke database
	require_once "../../config/config.php";

	// mengecek data get dari ajax
    if (isset($_GET['id_pelanggan'])) {
        try {
            // ambil "data" get dari ajax
            $id_pelanggan = $_GET['id_pelanggan'];

        	// sql statement untuk menampilkan data dari tabel "pelanggan" berdasarkan "id_pelanggan"
            $query = "SELECT id_pelanggan, nama, no_hp FROM pelanggan WHERE id_pelanggan=:id_pelanggan";
            // membuat prepared statements
            $stmt = $pdo->prepare($query);

        	// hubungkan "data" dengan prepared statements
            $stmt->bindParam(':id_pelanggan', $id_pelanggan);

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