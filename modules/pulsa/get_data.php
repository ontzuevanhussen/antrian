<?php
// Mengecek AJAX Request
if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && ( $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest' )) {
	// panggil file "config.php" untuk koneksi ke database
	require_once "../../config/config.php";

	// mengecek data get dari ajax
    if (isset($_GET['id_pulsa'])) { 
        try {
            // ambil "data" get dari ajax
            $id_pulsa = $_GET['id_pulsa'];

        	// sql statement untuk menampilkan data dari tabel "pulsa" berdasarkan "id_pulsa"
            $query = "SELECT id_pulsa, provider, nominal, harga FROM pulsa WHERE id_pulsa=:id_pulsa";
            // membuat prepared statements
            $stmt = $pdo->prepare($query);

        	// hubungkan "data" dengan prepared statements
            $stmt->bindParam(':id_pulsa', $id_pulsa);
            
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