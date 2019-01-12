<?php
session_start();      // memulai session

// Mengecek AJAX Request
if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && ( $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest' )) {
    // panggil file "config.php" untuk koneksi ke database
    require_once "../../config/config.php";

    // mengecek data post dari ajax
    if (isset($_POST['password_lama'])) {
        try {
            // ambil "data" post dari ajax
            $password_lama = sha1(md5(trim($_POST['password_lama'])));
            // ambil "data" dari session
            $id_user = $_SESSION['id_user'];

            // sql statement untuk menampilkan data password dari tabel "sys_users" berdasarkan "id_user"
            $query = "SELECT password FROM sys_users WHERE id_user=:id_user";
            // membuat prepared statements
            $stmt = $pdo->prepare($query);

            // hubungkan "data" dengan prepared statements
            $stmt->bindParam(':id_user', $id_user);

            // eksekusi query
            $stmt->execute();

            // ambil data hasil query
            $data = $stmt->fetch(PDO::FETCH_ASSOC);

            // mengecek password sebelum diubah 
            // jika value "password lama" tidak sama dengan "password" di database
            if ($password_lama != $data['password']) {
                // tampilkan pesan salah
                echo "salah";
            }
            // jika password sama
            else {
                // tampilkan pesan benar
                echo "benar";
            }

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