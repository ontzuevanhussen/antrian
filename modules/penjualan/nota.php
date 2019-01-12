<?php  
session_start();      // memulai session

// fungsi untuk pengecekan status login user 
// jika user belum login, alihkan ke halaman "login-error"
if (empty($_SESSION['username']) && empty($_SESSION['password'])){
    echo "<meta http-equiv='refresh' content='0; url=../../login-error'>";
}
// jika user sudah login
else { ?>
    <!doctype html>
    <html lang="en">
        <head>
            <!-- Required meta tags -->
            <meta charset="utf-8">
            <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
            <!-- Title -->
            <title>Nota Pembelian Pulsa</title>
        </head>
        <!-- Tampilkan "windows print" saat file "nota.php" dijalankan -->
        <body onload="window.print()">
            <table>
                <?php  
                // panggil file "config.php" untuk koneksi ke database
                require_once "../../config/config.php";

                try {
                    // siapkan "data" query
                    $id = 1;

                    // sql statement untuk menampilkan data dari tabel "sys_config" berdasarkan "id"
                    $query = "SELECT nama_konter FROM sys_config WHERE id=:id";
                    // membuat prepared statements
                    $stmt = $pdo->prepare($query);

                    // hubungkan "data" dengan prepared statements
                    $stmt->bindParam(':id', $id);

                    // eksekusi query
                    $stmt->execute(); 

                    // ambil data hasil query
                    $data = $stmt->fetch(PDO::FETCH_ASSOC);
                } catch (PDOException $e) {
                    // tampilkan pesan kesalahan
                    echo "Query Error : ".$e->getMessage();
                }
                ?>
                <tr>
                    <td align="center" colspan="3"><strong><?php echo $data['nama_konter']; ?></strong></td>
                </tr>
                <tr>
                    <td align="center" colspan="3">==============================</td>
                </tr>

                <?php  
                try {
                    // ambil "data" get
                    $id_penjualan = $_GET['id'];

                    // sql statement untuk menampilkan data dari tabel "penjualan" berdasarkan "id_penjualan"
                    $query = "SELECT a.id_penjualan,a.tanggal,a.jumlah_bayar,b.nama,b.no_hp,c.provider,c.nominal
                              FROM penjualan as a INNER JOIN pelanggan as b INNER JOIN pulsa as c 
                              ON a.pelanggan=b.id_pelanggan AND a.pulsa=c.id_pulsa WHERE id_penjualan=:id_penjualan";
                    // membuat prepared statements
                    $stmt = $pdo->prepare($query);

                    // hubungkan "data" dengan prepared statements
                    $stmt->bindParam(':id_penjualan', $id_penjualan);

                    // eksekusi query
                    $stmt->execute();

                    // ambil data hasil query
                    $data = $stmt->fetch(PDO::FETCH_ASSOC);

                    // tutup koneksi
                    $pdo = null;
                } catch (PDOException $e) {
                    // tampilkan pesan kesalahan
                    echo "Query Error : ".$e->getMessage();
                }
                ?>
                <tr>
                    <td>Tanggal</td>
                    <td>:</td>
                    <td><?php echo date('d-m-Y', strtotime($data['tanggal'])); ?></td>
                </tr>
                <tr>
                    <td align="center" colspan="3">--------------------------------------------------</td>
                </tr>
                <tr>
                    <td colspan="3"><strong>Pelanggan :</strong></td>
                </tr>
                <tr>
                    <td colspan="" rowspan="" headers="">Nama</td>
                    <td colspan="" rowspan="" headers="">:</td>
                    <td colspan="" rowspan="" headers=""><?php echo $data['nama']; ?></td>
                </tr>
                <tr>
                    <td colspan="" rowspan="" headers="">No. HP</td>
                    <td colspan="" rowspan="" headers="">:</td>
                    <td colspan="" rowspan="" headers=""><?php echo $data['no_hp']; ?></td>
                </tr>
                <tr>
                    <td align="center" colspan="3">--------------------------------------------------</td>
                </tr>
                <tr>
                    <td colspan="3"><strong>Pulsa :</strong></td>
                </tr>
                <tr>
                    <td colspan="" rowspan="" headers="">Provider</td>
                    <td colspan="" rowspan="" headers="">:</td>
                    <td colspan="" rowspan="" headers=""><?php echo $data['provider']; ?></td>
                </tr>
                <tr>
                    <td colspan="" rowspan="" headers="">Nominal</td>
                    <td colspan="" rowspan="" headers="">:</td>
                    <td colspan="" rowspan="" headers=""><?php echo number_format($data['nominal']); ?></td>
                </tr>
                <tr>
                    <td align="center" colspan="3">--------------------------------------------------</td>
                </tr>
                <tr>
                    <td colspan="" rowspan="" headers=""><strong>Jumlah Bayar</strong></td>
                    <td colspan="" rowspan="" headers="">:</td>
                    <td colspan="" rowspan="" headers=""><strong>Rp. <?php echo number_format($data['jumlah_bayar']); ?></strong></td>
                </tr>
                <tr>
                    <td align="center" colspan="3">--------------------------------------------------</td>
                </tr>
            </table>
        </body>
    </html>
<?php } ?>