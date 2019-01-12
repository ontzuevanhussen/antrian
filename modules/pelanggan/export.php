<?php
session_start();      // memulai session

// fungsi untuk pengecekan status login user 
// jika user belum login, alihkan ke halaman "login-error"
if (empty($_SESSION['username']) && empty($_SESSION['password'])){
    echo "<meta http-equiv='refresh' content='0; url=../../login-error'>";
}
// jika user sudah login
else {
    // fungsi untuk export data
    header("Content-Type: application/force-download");
    header("Cache-Control: no-cache, must-revalidate");
    // nama file hasil export
    header("content-disposition: attachment;filename=DATA-PELANGGAN.xls");
?>
    <!-- Judul file -->
    <h3>DATA PELANGGAN</h3>
    <!-- Table untuk di Export Ke Excel -->
    <table border='1'>
        <h3>
            <thead>
                <tr>
                    <th align="center" valign="middle">No.</th>
                    <th align="center" valign="middle">Nama Pelanggan</th>
                    <th align="center" valign="middle">No. HP</th>
                </tr>
            </thead>
        </h3>

        <tbody>
        <?php  
        // panggil file "config.php" untuk koneksi ke database
        require_once "../../config/config.php";

        try {
            // variabel untuk nomor urut tabel
            $no = 1;
            // sql statement untuk menampilkan data dari tabel "pelanggan"
            $query = "SELECT * FROM pelanggan ORDER BY nama ASC";
            // membuat prepared statements
            $stmt = $pdo->prepare($query);

            // eksekusi query
            $stmt->execute();

            // tampilkan hasil query
            while ($data = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo "<tr>
                        <td width='30' align='center'>".$no."</td>
                        <td width='170'>".$data['nama']."</td>
                        <td width='90' align='center'>".$data['no_hp']."</td>
                    </tr>";
                $no++;
            };
            
            // tutup koneksi
            $pdo = null;
        } catch (PDOException $e) {
            // tampilkan pesan kesalahan
            echo "Query Error : ".$e->getMessage();
        }
        ?>
        </tbody>
    </table>
<?php } ?>