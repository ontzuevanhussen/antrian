<?php
session_start();      // memulai session

// Mengecek AJAX Request
if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && ( $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest' )) {
    // panggil file "config.php" untuk koneksi ke database
    require_once "../../config/config.php";
    
    // mengecek data post dari ajax
    if (isset($_POST['id_pelanggan'])) {
        try {
            // ambil "data" post dari ajax 
            $id_pelanggan = $_POST['id_pelanggan'];
            $nama         = $_POST['nama'];
            $no_hp        = $_POST['no_hp'];

            // sql statement untuk menampilkan data "pelanggan" dari tabel "penjualan" berdasarkan "id_pelanggan"
            $query = "SELECT pelanggan FROM penjualan WHERE pelanggan=:id_pelanggan";
            // membuat prepared statements
            $stmt = $pdo->prepare($query);

            // hubungkan "data" dengan prepared statements
            $stmt->bindParam(':id_pelanggan', $id_pelanggan);

            // eksekusi query
            $stmt->execute();

            // cek hasil query
            // jika data "pelanggan" sudah ada di tabel "penjualan"
            if ($stmt->rowCount() <> 0) {
                // tampilkan pesan gagal delete data
                echo "gagal";
            }
            // jika data tidak ada, jalankan perintah delete
            else {
                // sql statement untuk delete data dari tabel "pelanggan"
                $query = "DELETE FROM pelanggan WHERE id_pelanggan=:id_pelanggan";
                // membuat prepared statements
                $stmt = $pdo->prepare($query);

                // hubungkan "data" dengan prepared statements
                $stmt->bindParam(':id_pelanggan', $id_pelanggan);

                // eksekusi query
                $stmt->execute();

                // cek hasil query
                // jika data "pelanggan" berhasil dihapus, jalankan perintah untuk insert data audit
                if ($stmt) {
                    // siapkan "data"
                    $username   = $_SESSION['id_user'];
                    $aksi       = "Delete";
                    $keterangan = "<b>Delete</b> data pelanggan pada tabel <b>pelanggan</b>. <br> <b>[ID Pelanggan : </b>".$id_pelanggan."<b>][Nama Pelanggan : </b>".$nama."<b>][No. HP : </b>".$no_hp."<b>]";

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