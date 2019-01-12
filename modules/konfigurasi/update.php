<?php
session_start();      // memulai session

// Mengecek AJAX Request
if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && ( $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest' )) {
    // panggil file "config.php" untuk koneksi ke database
    require_once "../../config/config.php";
    
    try {
        // ambil "data" hasil post dari ajax
        $id           = trim($_POST['id']);
        $nama_konter  = trim($_POST['nama_konter']);
        $alamat       = trim($_POST['alamat']);
        $telepon      = trim($_POST['telepon']);
        $email        = trim($_POST['email']);
        $website      = trim($_POST['website']);
        // ambil "data" dari session
        $updated_user = $_SESSION['id_user'];
        // ambil waktu sekarang
        $updated_date = gmdate("Y-m-d H:i:s", time()+60*60*7);

        // jika logo tidak diubah
        if (empty($_FILES['logo'])) {
            // sql statement untuk update data di tabel "sys_config"
            $query = "UPDATE sys_config SET nama_konter     = :nama_konter,
                                            alamat          = :alamat,
                                            telepon         = :telepon,
                                            email           = :email,
                                            website         = :website,
                                            updated_user    = :updated_user, 
                                            updated_date    = :updated_date 
                                      WHERE id              = :id";
            // membuat prepared statements
            $stmt = $pdo->prepare($query);

            // hubungkan "data" dengan prepared statements
            $stmt->bindParam(':id', $id);
            $stmt->bindParam(':nama_konter', $nama_konter);
            $stmt->bindParam(':alamat', $alamat);
            $stmt->bindParam(':telepon', $telepon);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':website', $website);
            $stmt->bindParam(':updated_user', $updated_user);
            $stmt->bindParam(':updated_date', $updated_date);

            // eksekusi query
            $stmt->execute();

            // cek query
            if ($stmt) {
                // jika berhasil tampilkan pesan "sukses"
                echo "sukses";
            }
        }
        // jika logo diubah
        else {
            // ambil data file hasil post dari ajax
            $nama_file = $_FILES['logo']['name'];
            $tmp_file  = $_FILES['logo']['tmp_name'];
            // Set lokasi folder tempat menyimpan logo
            $path      = "../../assets/img/".$nama_file;

            // proses upload file
            // jika file berhasil diupload, jalankan perintah update data
            if (move_uploaded_file($tmp_file, $path)) {
                // sql statement untuk update data di tabel "sys_config"
                $query = "UPDATE sys_config SET nama_konter     = :nama_konter,
                                                alamat          = :alamat,
                                                telepon         = :telepon,
                                                email           = :email,
                                                website         = :website,
                                                logo            = :logo,
                                                updated_user    = :updated_user, 
                                                updated_date    = :updated_date 
                                          WHERE id              = :id";
                // membuat prepared statements
                $stmt = $pdo->prepare($query);

                // hubungkan "data" dengan prepared statements
                $stmt->bindParam(':id', $id);
                $stmt->bindParam(':nama_konter', $nama_konter);
                $stmt->bindParam(':alamat', $alamat);
                $stmt->bindParam(':telepon', $telepon);
                $stmt->bindParam(':email', $email);
                $stmt->bindParam(':website', $website);
                $stmt->bindParam(':logo', $nama_file);
                $stmt->bindParam(':updated_user', $updated_user);
                $stmt->bindParam(':updated_date', $updated_date);

                // eksekusi query
                $stmt->execute();

                // cek query
                if ($stmt) {
                    // jika berhasil tampilkan pesan "sukses"
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
} else {
    // jika tidak ada ajax request, maka alihkan ke halaman "login-error"
    echo '<script>window.location="../../login-error"</script>';
}
?>