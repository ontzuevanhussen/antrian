<?php
session_start();      // memulai session

// Mengecek AJAX Request
if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && ( $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest' )) {
    // panggil file "config.php" untuk koneksi ke database
    require_once "../../config/config.php";
    
    // mengecek data post dari ajax
    if (isset($_POST['id_pulsa'])) {
        try {
            // ambil "data" hasil post dari ajax
            $id_pulsa     = trim($_POST['id_pulsa']);
            $provider     = trim($_POST['provider']);
            $nominal      = trim($_POST['nominal']);
            $harga        = trim($_POST['harga']);
            // ambil "data" dari session
            $updated_user = $_SESSION['id_user'];
            // ambil waktu sekarang
            $updated_date = gmdate("Y-m-d H:i:s", time()+60*60*7);

            // sql statement untuk update data di tabel "pulsa"
            $query = "UPDATE pulsa SET provider     = :provider, 
                                       nominal      = :nominal, 
                                       harga        = :harga,
                                       updated_user = :updated_user, 
                                       updated_date = :updated_date
                                 WHERE id_pulsa     = :id_pulsa";
            // membuat prepared statements
            $stmt = $pdo->prepare($query);
            
            // hubungkan "data" dengan prepared statements
            $stmt->bindParam(':id_pulsa', $id_pulsa);
            $stmt->bindParam(':provider', $provider);
            $stmt->bindParam(':nominal', $nominal);
            $stmt->bindParam(':harga', $harga);
            $stmt->bindParam(':updated_user', $updated_user);
            $stmt->bindParam(':updated_date', $updated_date);

            // eksekusi query
            $stmt->execute();

            // cek query
            if ($stmt) {
                // jika berhasil tampilkan pesan "sukses"
                echo "sukses";
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