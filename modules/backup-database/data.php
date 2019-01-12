<?php
// Mengecek AJAX Request
if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && ( $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest' )) {

    // nama tabel
    $table = 'sys_database';
    // primary key tabel
    $primaryKey = 'id';
    
    // membuat array untuk menampilkan isi tabel.
    // Parameter 'db' mewakili nama kolom dalam database.
    // parameter 'dt' mewakili pengenal kolom pada DataTable.
    $columns = array(
        array( 'db' => 'nama_file', 'dt' => 1 ),
        array(
            'db' => 'created_date',
            'dt' => 2,
            'formatter' => function( $d, $row ) {
                return date('d-m-Y H:i:s', strtotime($d));
            }
        ),
        array( 'db' => 'ukuran_file', 'dt' => 3 ),
        array( 'db' => 'id', 'dt' => 4 )
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