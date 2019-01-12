<?php
// Mengecek AJAX Request
if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && ( $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest' )) {
    // panggil file "config.php" untuk koneksi ke database
    require_once "config/config.php";

    // mengecek data post dari ajax
    if (isset($_POST['username']) && isset($_POST['password'])) {
        try {
            // ambil "data" post dari ajax
            $username = trim($_POST['username']);
            $password = sha1(md5(trim($_POST['password'])));
            // siapkan "data" blokir
            $blokir   = "Tidak";

            // sql statement untuk menampilkan data dari tabel "sys_users" berdasarkan username, password, dan blokir
            $query = "SELECT * FROM sys_users WHERE username=:username AND password=:password AND blokir=:blokir";
            // membuat prepared statements
            $stmt = $pdo->prepare($query);

            // hubungkan "data" dengan prepared statements
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':password', $password);
            $stmt->bindParam(':blokir', $blokir);

            // eksekusi query
            $stmt->execute();

            // cek hasil query
            // jika data ada, jalankan perintah untuk membuat session
            if ($stmt->rowCount() <> 0) {
                // ambil data hasil query
                $data = $stmt->fetch(PDO::FETCH_ASSOC);
                // membuat session
                session_start();
                $_SESSION['id_user']   = $data['id_user'];
                $_SESSION['nama_user'] = $data['nama_user'];
                $_SESSION['username']  = $data['username'];
                $_SESSION['password']  = $data['password'];
                $_SESSION['hak_akses'] = $data['hak_akses'];

                // tampilkan pesan "sukses"
                echo "sukses";
            }
            // jika data tidak ada
            else {
                // tampilkan pesan "gagal"
                echo "gagal";
            }
        } catch (Exception $e) {
            // tampilkan pesan kesalahan
            echo $e->getMessage();
        }
    }
    // tutup koneksi
    $pdo = null;
} else {
    // jika tidak ada ajax request, maka alihkan ke halaman "login-error"
    echo '<script>window.location="login-error"</script>';
}
?>