<?php
// Mengecek AJAX Request
if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && ( $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest' )) {
	// panggil file "config.php" untuk koneksi ke database
	require_once "../../config/config.php";

    try {
    	// sql statement untuk menampilkan jumlah data dari tabel "penjualan"
        $query = "SELECT count(id_penjualan) as jumlah FROM penjualan";
        // membuat prepared statements
        $stmt = $pdo->prepare($query);
        // eksekusi query
        $stmt->execute(); 
        // ambil data hasil query
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        // tampilkan data
        echo number_format($data['jumlah']);

        // tutup koneksi
        $pdo = null;
    } catch (PDOException $e) {
        // tampilkan pesan kesalahan
        echo "Query Error : ".$e->getMessage();
    }
} else {
    // jika tidak ada ajax request, maka alihkan ke halaman "login-error"
    echo '<script>window.location="../../login-error"</script>';
}
?>