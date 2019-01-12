<?php
session_start();      // memulai session

// fungsi untuk pengecekan status login user 
// jika user belum login, alihkan ke halaman "login-error"
if (empty($_SESSION['username']) && empty($_SESSION['password'])){
    echo "<meta http-equiv='refresh' content='0; url=../../login-error'>";
}
// jika user sudah login
else {
    // mengecek data get
    if (isset($_GET['tgl_awal']) && isset($_GET['tgl_akhir'])) {
        // fungsi untuk export data
        header("Content-Type: application/force-download");
        header("Cache-Control: no-cache, must-revalidate");
        // jika "tgl_awal" sama dengan "tgl_akhir"
        if ($_GET['tgl_awal'] == $_GET['tgl_akhir']) {
            // nama file hasil export
            header("content-disposition: attachment;filename=LAPORAN-DATA-PENJUALAN-TANGGAL-".$_GET['tgl_awal'].".xls");
        } 
        // jika tgl_awal" tidak sama dengan "tgl_akhir"
        else {
            // nama file hasil export
            header("content-disposition: attachment;filename=LAPORAN-DATA-PENJUALAN-TANGGAL-".$_GET['tgl_awal']."-SD-".$_GET['tgl_akhir'].".xls");
        }
        
    ?>
        <!-- Judul laporan -->
        <center>
            <h3>
                LAPORAN DATA PENJUALAN <br>
            <?php 
            // jika "tgl_awal" sama dengan "tgl_akhir", tampilkan "tgl_awal"
            if ($_GET['tgl_awal'] == $_GET['tgl_akhir']) {
                echo "Tanggal ". $_GET['tgl_awal'];
            } 
            // jika tgl_awal" tidak sama dengan "tgl_akhir", tampilkan "tgl_awal" s.d. "tgl_akhir" 
            else {
                echo "Tanggal ". $_GET['tgl_awal'] ." s.d. ". $_GET['tgl_akhir'];
            }
            ?>
            </h3>
        </center>
        <!-- Table untuk di Export Ke Excel -->
        <table border='1'>
            <h3>
                <thead>
                    <tr>
                        <th align="center" valign="middle">No.</th>
                        <th align="center" valign="middle">Tanggal</th>
                        <th align="center" valign="middle">Nama Pelanggan</th>
                        <th align="center" valign="middle">No. HP</th>
                        <th align="center" valign="middle">Pulsa</th>
                        <th align="center" valign="middle">Jumlah Bayar</th>
                    </tr>
                </thead>
            </h3>

            <tbody>
            <?php  
            // panggil file "config.php" untuk koneksi ke database
            require_once "../../config/config.php";

            try {
                // ambil "data" get
                $tgl_awal  = date('Y-m-d', strtotime($_GET['tgl_awal']));
                $tgl_akhir = date('Y-m-d', strtotime($_GET['tgl_akhir']));
                // variabel untuk nomor urut tabel
                $no = 1;
                // variabel untuk total bayar
                $total = 0;

                // sql statement untuk menampilkan data dari tabel "penjualan" berdasarkan "tanggal"
                $query = "SELECT a.id_penjualan,a.tanggal,a.jumlah_bayar,b.nama,b.no_hp,c.provider,c.nominal
                          FROM penjualan as a INNER JOIN pelanggan as b INNER JOIN pulsa as c 
                          ON a.pelanggan=b.id_pelanggan AND a.pulsa=c.id_pulsa 
                          WHERE a.tanggal BETWEEN :tgl_awal AND :tgl_akhir ORDER BY a.id_penjualan ASC";
                // membuat prepared statements
                $stmt = $pdo->prepare($query);

                // hubungkan "data" dengan prepared statements
                $stmt->bindParam(':tgl_awal', $tgl_awal);
                $stmt->bindParam(':tgl_akhir', $tgl_akhir);

                // eksekusi query
                $stmt->execute();

                // tampilkan hasil query
                while ($data = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    echo "<tr>
                            <td width='30' align='center'>".$no."</td>
                            <td width='90' align='center'>".date('d-m-Y', strtotime($data['tanggal']))."</td>
                            <td width='170'>".$data['nama']."</td>
                            <td width='90' align='center'>".$data['no_hp']."</td>
                            <td width='170'>".$data['provider']." - ".number_format($data['nominal'])."</td>
                            <td width='100' align='right'>Rp.".number_format($data['jumlah_bayar'])."</td>
                        </tr>";
                    $no++;
                    $total += $data['jumlah_bayar'];
                };
                echo "<tr>
                        <td align='center' colspan='5'><strong>Total</strong></td>
                        <td align='right'><strong>Rp.".number_format($total)."</strong></td>
                    </tr>";
                    
                // tutup koneksi
                $pdo = null;
            } catch (PDOException $e) {
                // tampilkan pesan kesalahan
                echo "Query Error : ".$e->getMessage();
            }
            ?>
            </tbody>
        </table>
    <?php 
    }
}
?>