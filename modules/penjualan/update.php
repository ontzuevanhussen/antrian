<?php
session_start();      // memulai session

// Mengecek AJAX Request
if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && ( $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest' )) {
    // panggil file "config.php" untuk koneksi ke database
    require_once "../../config/config.php";
    
    // mengecek data post dari ajax
    if (isset($_POST['id_penjualan'])) {
        try {
            // ambil "data" hasil post dari ajax
            $id_penjualan = trim($_POST['id_penjualan']);
            $tanggal      = trim(date('Y-m-d', strtotime($_POST['tanggal'])));
            $pelanggan    = trim($_POST['id_pelanggan']);
            $pulsa        = trim($_POST['id_pulsa']);
            $jumlah_bayar = trim($_POST['harga']);
            // ambil "data" dari session
            $updated_user = $_SESSION['id_user'];
            // ambil waktu sekarang
            $updated_date = gmdate("Y-m-d H:i:s", time()+60*60*7);

            // sql statement untuk update data di tabel "penjualan"
            $query = "UPDATE penjualan SET tanggal      = :tanggal, 
                                           pelanggan    = :pelanggan,
                                           pulsa        = :pulsa,
                                           jumlah_bayar = :jumlah_bayar,
                                           updated_user = :updated_user, 
                                           updated_date = :updated_date
                                     WHERE id_penjualan = :id_penjualan";
            // membuat prepared statements
            $stmt = $pdo->prepare($query);
            
            // hubungkan "data" dengan prepared statements
            $stmt->bindParam(':id_penjualan', $id_penjualan);
            $stmt->bindParam(':tanggal', $tanggal);
            $stmt->bindParam(':pelanggan', $pelanggan);
            $stmt->bindParam(':pulsa', $pulsa);
            $stmt->bindParam(':jumlah_bayar', $jumlah_bayar);
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