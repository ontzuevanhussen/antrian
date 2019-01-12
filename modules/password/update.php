<?php
session_start();      // memulai session

// Mengecek AJAX Request
if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && ( $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest' )) {
    // panggil file "config.php" untuk koneksi ke database
    require_once "../../config/config.php";
    
    // mengecek data post dari ajax
    if (isset($_POST['password_baru'])) {
        try {
            // ambil "data" hasil post dari ajax
            $password_baru = sha1(md5(trim($_POST['password_baru'])));
            // ambil "data" dari session
            $id_user = $_SESSION['id_user'];
            // ambil waktu sekarang
            $updated_date = gmdate("Y-m-d H:i:s", time()+60*60*7);

            // sql statement untuk update data "password" di tabel "sys_users"
            $query = "UPDATE sys_users SET password     = :password, 
                                           updated_user = :updated_user, 
                                           updated_date = :updated_date 
                                     WHERE id_user      = :id_user";
            // membuat prepared statements
            $stmt = $pdo->prepare($query);

            // hubungkan "data" dengan prepared statements
            $stmt->bindParam(':id_user', $id_user);
            $stmt->bindParam(':password', $password_baru);
            $stmt->bindParam(':updated_user', $id_user);
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