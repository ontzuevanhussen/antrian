<?php
// Mengecek AJAX Request
if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && ( $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest' )) {

// sql statement untuk join table 
$table = <<<EOT
(   
    SELECT a.id, a.tanggal, a.aksi, a.keterangan, b.username
    FROM sys_audit_trail as a INNER JOIN sys_users as b ON a.username=b.id_user
) temp
EOT;

    // primary key tabel
    $primaryKey = 'id';
    
    // membuat array untuk menampilkan isi tabel.
    // parameter 'db' mewakili nama kolom dalam database.
    // parameter 'dt' mewakili pengenal kolom pada DataTable.
    $columns = array(
        array( 'db' => 'id', 'dt' => 1 ),
        array(
            'db' => 'tanggal',
            'dt' => 2,
            'formatter' => function( $d, $row ) {
                return date('d-m-Y H:i:s', strtotime($d));
            }
        ),
        array( 'db' => 'username', 'dt' => 3 ),
        array( 'db' => 'aksi', 'dt' => 4 ),
        array( 'db' => 'keterangan', 'dt' => 5 )
    );

    // memanggil file "database.php" untuk informasi koneksi ke server SQL
    require_once "../../config/database.php";
    // memanggil file "ssp.class.php" untuk menjalankan datatables server-side processing
    require '../../config/ssp.class.php';

    echo json_encode(
        SSP::simple( $_GET, $sql_details, $table, $primaryKey, $columns )
    );
} else {
    // jika tidak ada ajax request, maka alihkan ke halaman "login-error"
    echo '<script>window.location="../../login-error"</script>';
}
?>