<?php
session_start();      // memulai session

// Mengecek AJAX Request
if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && ( $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest' )) {
    // panggil file "config.php" untuk koneksi ke database
    require_once "../../config/config.php";
    
    // mengecek data post dari ajax
    if (isset($_POST['id_pulsa'])) {
        try {
            // ambil "data" post dari ajax 
            $id_pulsa = $_POST['id_pulsa'];
            $provider = $_POST['provider'];
            $nominal  = $_POST['nominal'];
            $harga    = $_POST['harga'];

            // sql statement untuk menampilkan data "pulsa" dari tabel "penjualan" berdasarkan "id_pulsa"
            $query = "SELECT pulsa FROM penjualan WHERE pulsa=:id_pulsa";
            // membuat prepared statements
            $stmt = $pdo->prepare($query);

            // hubungkan "data" dengan prepared statements
            $stmt->bindParam(':id_pulsa', $id_pulsa);

            // eksekusi query
            $stmt->execute();

            // cek hasil query
            // jika data "pulsa" sudah ada di tabel "penjualan"
            if ($stmt->rowCount() <> 0) {
                // tampilkan pesan gagal delete data
                echo "gagal";
            }
            // jika data tidak ada, jalankan perintah delete
            else {
                // sql statement untuk delete data dari tabel "pulsa"
                $query = "DELETE FROM pulsa WHERE id_pulsa=:id_pulsa";
                // membuat prepared statements
                $stmt = $pdo->prepare($query);

                // hubungkan "data" dengan prepared statements
                $stmt->bindParam(':id_pulsa', $id_pulsa);

                // eksekusi query
                $stmt->execute();

                // cek hasil query
                // jika data "pulsa" berhasil dihapus, jalankan perintah untuk insert data audit
                if ($stmt) {
                    // siapkan "data"
                    $username   = $_SESSION['id_user'];
                    $aksi       = "Delete";
                    $keterangan = "<b>Delete</b> data pulsa pada tabel <b>pulsa</b>. <br> <b>[ID Pulsa : </b>".$id_pulsa."<b>][Provider : </b>".$provider."<b>][Nominal : </b>".$nominal."<b>][Harga : </b>".$harga."<b>]";

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