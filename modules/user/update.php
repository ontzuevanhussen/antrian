<?php
session_start();      // memulai session

// Mengecek AJAX Request
if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && ( $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest' )) {
    // panggil file "config.php" untuk koneksi ke database
    require_once "../../config/config.php";
    
    // mengecek data post dari ajax
    if (isset($_POST['id_user'])) {
        try {
            // ambil "data" hasil post dari ajax
            $id_user      = trim($_POST['id_user']);
            $nama_user    = trim($_POST['nama_user']);
            $username     = trim($_POST['username']);
            $hak_akses    = trim($_POST['hak_akses']);
            $blokir       = trim($_POST['blokir']);
            // ambil "data" dari session
            $updated_user = $_SESSION['id_user'];
            // ambil waktu sekarang
            $updated_date = gmdate("Y-m-d H:i:s", time()+60*60*7);

            // sql statement untuk menampilkan data "username" dari tabel "sys_users"
            $query = "SELECT username FROM sys_users WHERE username=:username AND id_user!=:id_user";
            // membuat prepared statements
            $stmt = $pdo->prepare($query);

            // hubungkan "data" dengan prepared statements
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':id_user', $id_user);

            // eksekusi query
            $stmt->execute();

            // cek hasil query
            // jika "username" sudah ada di tabel "sys_users"
            if ($stmt->rowCount() <> 0) {
                // tampilkan pesan gagal update data
                echo "gagal";
            }
            // jika "username" belum ada, jalankan perintah update
            else {
                // jika password tidak diubah
                if (empty($_POST['pass'])) {
                    // sql statement untuk update data di tabel "sys_users"
                    $query = "UPDATE sys_users SET nama_user    = :nama_user, 
                                                   username     = :username,
                                                   hak_akses    = :hak_akses, 
                                                   blokir       = :blokir,
                                                   updated_user = :updated_user, 
                                                   updated_date = :updated_date
                                             WHERE id_user      = :id_user";
                    // membuat prepared statements
                    $stmt = $pdo->prepare($query);

                    // hubungkan "data" dengan prepared statements
                    $stmt->bindParam(':id_user', $id_user);
                    $stmt->bindParam(':nama_user', $nama_user);
                    $stmt->bindParam(':username', $username);
                    $stmt->bindParam(':hak_akses', $hak_akses);
                    $stmt->bindParam(':blokir', $blokir);
                    $stmt->bindParam(':updated_user', $updated_user);
                    $stmt->bindParam(':updated_date', $updated_date);

                    // eksekusi query
                    $stmt->execute();
                }
                else {
                    // ambil "data" password hasil post dari ajax
                    $password = sha1(md5(trim($_POST['pass'])));

                    // sql statement untuk update data di tabel "sys_users"
                    $query = "UPDATE sys_users SET nama_user    = :nama_user, 
                                                   username     = :username,
                                                   password     = :password, 
                                                   hak_akses    = :hak_akses, 
                                                   blokir       = :blokir,
                                                   updated_user = :updated_user, 
                                                   updated_date = :updated_date
                                             WHERE id_user      = :id_user";
                    // membuat prepared statements
                    $stmt = $pdo->prepare($query);
                    
                    // hubungkan "data" dengan prepared statements
                    $stmt->bindParam(':id_user', $id_user);
                    $stmt->bindParam(':nama_user', $nama_user);
                    $stmt->bindParam(':username', $username);
                    $stmt->bindParam(':password', $password);
                    $stmt->bindParam(':hak_akses', $hak_akses);
                    $stmt->bindParam(':blokir', $blokir);
                    $stmt->bindParam(':updated_user', $updated_user);
                    $stmt->bindParam(':updated_date', $updated_date);

                    // eksekusi query
                    $stmt->execute();
                }
                    
                // cek query
                if ($stmt) {
                    // jika berhasil tampilkan pesan "sukses"
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