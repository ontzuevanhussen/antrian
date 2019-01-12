<?php
session_start();      // memulai session

// Mengecek AJAX Request
if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && ( $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest' )) {
    // panggil file "config.php" untuk koneksi ke database
    require_once "../../config/config.php";
    
    // mengecek data post dari ajax
    if (isset($_POST['id_user'])) {
        try {
            // ambil "data" post dari ajax 
            $id_user   = trim($_POST['id_user']);
            $nama_user = trim($_POST['nama_user']);
            $username  = trim($_POST['username']);
            $hak_akses = trim($_POST['hak_akses']);

            // sql statement untuk menampilkan data "user" dari tabel "sys_audit_trail" berdasarkan "id_user"
            $query = "SELECT username FROM sys_audit_trail WHERE username=:id_user";
            // membuat prepared statements
            $stmt = $pdo->prepare($query);

            // hubungkan "data" dengan prepared statements
            $stmt->bindParam(':id_user', $id_user);

            // eksekusi query
            $stmt->execute();

            // cek hasil query
            // jika data "user" sudah ada di tabel "sys_audit_trail"
            if ($stmt->rowCount() <> 0) {
                // tampilkan pesan gagal delete data
                echo "gagal";
            }
            // jika data tidak ada, jalankan perintah delete
            else {
                // sql statement untuk delete data dari tabel "sys_users"
                $query = "DELETE FROM sys_users WHERE id_user=:id_user";
                // membuat prepared statements
                $stmt = $pdo->prepare($query);

                // hubungkan "data" dengan prepared statements
                $stmt->bindParam(':id_user', $id_user);

                // eksekusi query
                $stmt->execute();

                // cek hasil query
                // jika data "user" berhasil dihapus, jalankan perintah untuk insert data audit
                if ($stmt) {
                    // siapkan "data"
                    $username_login = $_SESSION['id_user'];
                    $aksi           = "Delete";
                    $keterangan     = "<b>Delete</b> data user pada tabel <b>sys_users</b>. <br> <b>[ID User : </b>".$id_user."<b>][Nama User : </b>".$nama_user."<b>][Username : </b>".$username."<b>][Hak Akses : </b>".$hak_akses."<b>]";

                    // sql statement untuk insert data ke tabel "sys_audit_trail"
                    $query = "INSERT INTO sys_audit_trail(username, aksi, keterangan) VALUES (:username, :aksi, :keterangan)";
                    // membuat prepared statements
                    $stmt = $pdo->prepare($query);

                    // hubungkan "data" dengan prepared statements
                    $stmt->bindParam(':username', $username_login);
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