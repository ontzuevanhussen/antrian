<?php
session_start();      // memulai session

// Mengecek AJAX Request
if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && ( $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest' )) {
    // panggil file "config.php" untuk koneksi ke database
    require_once "../../config/config.php";
    
    // mengecek data post dari ajax
    if (isset($_POST['id_pelanggan'])) {
        try {
            // ambil "data" hasil post dari ajax
            $id_pelanggan = trim($_POST['id_pelanggan']);
            $nama         = trim($_POST['nama']);
            $no_hp        = trim($_POST['no_hp']);
            // ambil "data" dari session
            $updated_user = $_SESSION['id_user'];
            // ambil waktu sekarang
            $updated_date = gmdate("Y-m-d H:i:s", time()+60*60*7);

            // sql statement untuk update data di tabel "pelanggan"
            $query = "UPDATE pelanggan SET nama         = :nama, 
                                           no_hp        = :no_hp,
                                           updated_user = :updated_user, 
                                           updated_date = :updated_date 
                                     WHERE id_pelanggan = :id_pelanggan";
            // membuat prepared statements
            $stmt = $pdo->prepare($query);

            // hubungkan "data" dengan prepared statements
            $stmt->bindParam(':id_pelanggan', $id_pelanggan);
            $stmt->bindParam(':nama', $nama);
            $stmt->bindParam(':no_hp', $no_hp);
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