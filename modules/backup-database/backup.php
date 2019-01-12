<?php
session_start();      // memulai session

// Mengecek AJAX Request
if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && ( $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest' )) {
    // panggil file "config.php" untuk koneksi ke database
    require_once "../../config/config.php";
    // panggil file "fungsi_backup_import.php" untuk backup database
    require_once "../../config/fungsi_backup_import.php";
    // panggil file "fungsi_file_size.php" untuk mengetahui file size database
    require_once "../../config/fungsi_file_size.php";

    try {
        // parameter backup database
        $date = gmdate("Ymd_His", time()+60*60*7);  // waktu backup
        $dir  = "../../database";                   // direktori file hasil backup
        $name = $date.'_database';                  // nama file sql hasil backup
        
        // jalankan perintah backup database
        $backup = backup_database( $dir, $name, $con['host'], $con['user'], $con['pass'], $con['db']);

        // mengecek proses backup database
        // jika backup database berhasil
        if ($backup) {
            // siapkan "data"
            $nama_file    = $name.".sql.gz";
            $ukuran_file  = MakeReadable(filesize($dir."/".$nama_file));
            // ambil "data" dari session
            $created_user = $_SESSION['id_user'];

            // sql statement untuk insert data ke tabel "sys_database"
            $query = "INSERT INTO sys_database(nama_file, ukuran_file, created_user) VALUES (:nama_file, :ukuran_file, :created_user)";
            // membuat prepared statements
            $stmt = $pdo->prepare($query);

            // hubungkan "data" dengan prepared statements
            $stmt->bindParam(':nama_file', $nama_file);
            $stmt->bindParam(':ukuran_file', $ukuran_file);
            $stmt->bindParam(':created_user', $created_user);
            
            // eksekusi query
            $stmt->execute();

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
} else {
    // jika tidak ada ajax request, maka alihkan ke halaman "login-error"
    echo '<script>window.location="../../login-error"</script>';
}
?>