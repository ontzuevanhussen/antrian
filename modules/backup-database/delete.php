<?php
session_start();      // memulai session

// Mengecek AJAX Request
if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && ( $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest' )) {
    // panggil file "config.php" untuk koneksi ke database
    require_once "../../config/config.php";
    
    // mengecek data post dari ajax
    if (isset($_POST['id'])) {
        try {
            // ambil "data" hasil post dari ajax
            $id          = trim($_POST['id']);
            $nama_file   = trim($_POST['nama_file']);
            $ukuran_file = trim($_POST['ukuran_file']);

            // hapus file backup database dari folder "database"
            $hapus_file = unlink("../../database/$nama_file");

            // mengecek proses hapus file
            // jika berhasil hapus file, jalankan perintah delete
            if ($hapus_file) {
                // sql statement untuk delete data dari tabel "sys_database"
                $query = "DELETE FROM sys_database WHERE id=:id";
                // membuat prepared statements
                $stmt = $pdo->prepare($query);

                // hubungkan "data" dengan prepared statements
                $stmt->bindParam(':id', $id);

                // eksekusi query
                $stmt->execute();

                // cek hasil query
                // jika data "backup database" berhasil dihapus, jalankan perintah untuk insert data audit
                if ($stmt) {
                    // siapkan "data"
                    $username   = $_SESSION['id_user'];
                    $aksi       = "Delete";
                    $keterangan = "<b>Delete</b> data backup database pada tabel <b>sys_database</b>. <br> <b>[ID : </b>".$id."<b>][Nama File : </b>".$nama_file."<b>][Ukuran File : </b>".$ukuran_file."<b>]";

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