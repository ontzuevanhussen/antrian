<?php
session_start();      // memulai session
ob_start();

// fungsi untuk pengecekan status login user 
// jika user belum login, alihkan ke halaman "login-error"
if (empty($_SESSION['username']) && empty($_SESSION['password'])){
    echo "<meta http-equiv='refresh' content='0; url=../../login-error'>";
}
// jika user sudah login
else {
    // mengecek data get
    if (isset($_GET['tgl_awal']) && isset($_GET['tgl_akhir'])) { ?>
        <!-- Bagian halaman HTML yang akan konvert -->
        <!doctype html>
        <html lang="en">
            <head>
                <!-- Required meta tags -->
                <meta charset="utf-8">
                <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
                <!-- Custom CSS -->
                <link rel="stylesheet" type="text/css" href="../../assets/css/laporan.css" />
                <!-- Title -->
                <title>LAPORAN DATA PENJUALAN</title>
            </head>
            <body>
                <!-- Judul Laporan -->
                <div class="judul-laporan">
                    LAPORAN DATA PENJUALAN
                </div>

                <?php  
                // jika "tgl_awal" sama dengan "tgl_akhir"
                if ($_GET['tgl_awal'] == $_GET['tgl_akhir']) { ?>
                    <!-- tampilkan judul -->
                    <div class="judul-tanggal">
                        Tanggal <?php echo $_GET['tgl_awal']; ?>
                    </div>
                <?php
                } 
                // jika tgl_awal" tidak sama dengan "tgl_akhir"
                else { ?>
                    <!-- tampilkan judul -->
                    <div class="judul-tanggal">
                        Tanggal <?php echo $_GET['tgl_awal']; ?> s.d. <?php echo $_GET['tgl_akhir']; ?>
                    </div>
                <?php
                } 
                ?>

                <hr><br>
                <!-- Tabel untuk menampilkan laporan data penjualan dari database -->
                <table width="100%" border="0.5" cellpadding="0" cellspacing="0">
                    <!-- judul kolom pada bagian kepala (atas) tabel -->
                    <thead style="background:#e8ecee">
                        <tr class="tr-judul">
                            <th height="30" align="center" valign="middle">No.</th>
                            <th height="30" align="center" valign="middle">Tanggal</th>
                            <th height="30" align="center" valign="middle">Nama Pelanggan</th>
                            <th height="30" align="center" valign="middle">No. HP</th>
                            <th height="30" align="center" valign="middle">Pulsa</th>
                            <th height="30" align="center" valign="middle">Jumlah Bayar</th>
                        </tr>
                    </thead>
                    <!-- isi tabel -->
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
                            echo "<tr class='tr-isi'>
                                    <td width='30' height='20' align='center'>".$no."</td>
                                    <td width='80' height='20' align='center'>".date('d-m-Y', strtotime($data['tanggal']))."</td>
                                    <td style='padding-left:7px;' width='150' height='20'>".$data['nama']."</td>
                                    <td width='100' height='20' align='center'>".$data['no_hp']."</td>
                                    <td style='padding-left:7px;' width='175' height='20'>".$data['provider']." - ".number_format($data['nominal'])."</td>
                                    <td style='padding-right:7px;' width='110' height='20' align='right'>Rp.".number_format($data['jumlah_bayar'])."</td>
                                </tr>";
                            $no++;
                            $total += $data['jumlah_bayar'];
                        };
                        echo "<tr>
                                <td height='20' align='center' colspan='5'><strong>Total</strong></td>
                                <td style='padding-right:7px;' height='20' align='right'><strong>Rp.".number_format($total)."</strong></td>
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
            </body>
        </html> <!-- Akhir halaman HTML yang akan di konvert -->
        <?php
        // jika "tgl_awal" sama dengan "tgl_akhir"
        if ($_GET['tgl_awal'] == $_GET['tgl_akhir']) { 
            // nama file pdf yang dihasilkan
            $filename="LAPORAN-DATA-PENJUALAN-TANGGAL-".$_GET['tgl_awal'].".pdf"; 
        } 
        // jika tgl_awal" tidak sama dengan "tgl_akhir"
        else {
            // nama file pdf yang dihasilkan
            $filename="LAPORAN-DATA-PENJUALAN-TANGGAL-".$_GET['tgl_awal']."-SD-".$_GET['tgl_akhir'].".pdf"; 
        }
        
        // ====================================== Convert HTML ke PDF ======================================
        $content = ob_get_clean();
        $content = '<page style="font-family: freeserif">'.($content).'</page>';
        // panggil file library html2pdf
        require_once('../../assets/plugins/html2pdf_v4.03/html2pdf.class.php');
        try
        {
            $html2pdf = new HTML2PDF('P','A4','en', false, 'ISO-8859-15',array(10, 15, 10, 15));
            $html2pdf->setDefaultFont('Arial');
            $html2pdf->writeHTML($content, isset($_GET['vuehtml']));
            $html2pdf->Output($filename);
        }
        catch(HTML2PDF_exception $e) { echo $e; }
    }
}
?>