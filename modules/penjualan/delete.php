<?php
session_start();      // memulai session

// Mengecek AJAX Request
if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && ( $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest' )) {
    // panggil file "config.php" untuk koneksi ke database
    require_once "../../config/config.php";
    
    // mengecek data post dari ajax
    if (isset($_POST['id_penjualan'])) {
        try {
            // ambil "data" post dari ajax 
            $id_penjualan = $_POST['id_penjualan'];
            $tanggal      = $_POST['tanggal'];
            $nama         = $_POST['nama'];
            $no_hp        = $_POST['no_hp'];
            $provider     = $_POST['provider'];
            $nominal      = $_POST['nominal'];

            // sql statement untuk delete data dari tabel "penjualan"
            $query = "DELETE FROM penjualan WHERE id_penjualan=:id_penjualan";
            // membuat prepared statements
            $stmt = $pdo->prepare($query);

            // hubungkan "data" dengan prepared statements
            $stmt->bindParam(':id_penjualan', $id_penjualan);

            // eksekusi query
            $stmt->execute();

            // cek hasil query
            // jika data "penjualan" berhasil dihapus, jalankan perintah untuk insert data audit
            if ($stmt) {
                // siapkan "data"
                $username   = $_SESSION['id_user'];
                $aksi       = "Delete";
                $keterangan = "<b>Delete</b> data penjualan pada tabel <b>penjualan</b>. <br> <b>[ID Penjualan : </b>".$id_penjualan."<b>][Tanggal : </b>".$tanggal."<b>][Pelanggan : </b>".$nama." - ".$no_hp."<b>][Pulsa : </b>".$provider." - ".$nominal."<b>]";

                // sql statement untuk insert data ke tabel "sys_audit_trail"
                $query = "INSERT INTO sys_audit_trail(username, aksi, keterangan) VALUES (:username, :aksi, :keterangan)";
                // membuat prepared statements
                $stmt = $pdo->prepare($query);

                // hubungkan "data" dengan prepared statements
                $stmt->bindParam(':username', $username);
                $stmt->bindParam(':aksi', $aksi);
                $stmt->bindParam(':keterangan', $keterangan);

                // eksekusi query
                $stmt->execute();

                // cek query
                if ($stmt) {
                    // jika insert data audit berhasil tampilkan pesan "sukses"
                    echo "sukses";
                }
            }

            // tutup koneksi
            $pdo = null;
        } catch (PDOException $e) {
            // tampilkan pesan kesalahan
            echo $e->getMessage();
        }
    }
} else {
    // jika tidak ada ajax request, maka alihkan ke halaman "login-error"
    echo '<script>window.location="../../login-error"</script>';
}
?>