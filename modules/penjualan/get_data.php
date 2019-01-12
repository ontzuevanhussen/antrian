<?php
// Mengecek AJAX Request
if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && ( $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest' )) {
	// panggil file "config.php" untuk koneksi ke database
	require_once "../../config/config.php";

	// mengecek data get dari ajax
    if (isset($_GET['id_penjualan'])) {
        try {
            // ambil "data" get dari ajax
            $id_penjualan = $_GET['id_penjualan'];

        	// sql statement untuk menampilkan data dari tabel "penjualan" berdasarkan "id_penjualan"
            $query = "SELECT a.id_penjualan,a.tanggal,a.pelanggan,a.pulsa,a.jumlah_bayar,b.nama,b.no_hp,c.provider,c.nominal
                      FROM penjualan as a INNER JOIN pelanggan as b INNER JOIN pulsa as c 
                      ON a.pelanggan=b.id_pelanggan AND a.pulsa=c.id_pulsa WHERE id_penjualan=:id_penjualan";
            // membuat prepared statements
            $stmt = $pdo->prepare($query);

        	// hubungkan "data" dengan prepared statements
            $stmt->bindParam(':id_penjualan', $id_penjualan);
            
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