<!-- Aplikasi Penjualan Pulsa dengan PHP PDO dan AJAX
*********************************************************
* Developer    : Indra Styawantoro
* Company      : Indra Studio
* Release Date : Januari 2019
* Update       : -
* Website      : www.indrasatya.com
* E-mail       : indra.setyawantoro@gmail.com
* Phone / WA   : +62-813-7778-3334
-->

<?php  
// panggil file "config.php" untuk koneksi ke database
require_once "config/config.php";

try {
    // siapkan "data"
    $id = 1;

    // sql statement untuk menampilkan data dari tabel "sys_config" berdasarkan "id"
    $query = "SELECT nama_konter, logo FROM sys_config WHERE id=:id";
    // membuat prepared statements
    $stmt = $pdo->prepare($query);

    // hubungkan "data" dengan prepared statements
    $stmt->bindParam(':id', $id);

    // eksekusi query
    $stmt->execute(); 

    // ambil data hasil query
    $data = $stmt->fetch(PDO::FETCH_ASSOC);
    // tampilkan data
    $nama_konter = $data['nama_konter'];
    $logo        = $data['logo'];

    // tutup koneksi
    $pdo = null;
} catch (PDOException $e) {
    // tampilkan pesan kesalahan
    echo "Query Error : ".$e->getMessage();
}
?>

<!doctype html>
<html lang="en">
    <head>
        <!-- Required meta tags -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="description" content="Aplikasi Penjualan Pulsa dengan PHP PDO dan AJAX">
        <meta name="keywords" content="Aplikasi Penjualan Pulsa dengan PHP PDO dan AJAX">
        <meta name="author" content="Indra Styawantoro">
        
        <!-- Favicon icon -->
        <link rel="shortcut icon" href="assets/img/favicon.png">
        <!-- Bootstrap CSS -->
        <link rel="stylesheet" type="text/css" href="assets/plugins/bootstrap-4.1.3/css/bootstrap.min.css">
        <!-- Font Awesome CSS -->
        <link rel="stylesheet" type="text/css" href="assets/plugins/fontawesome-free-5.5.0-web/css/all.min.css">
        <!-- Sweetalert CSS -->
        <link rel="stylesheet" type="text/css" href="assets/plugins/sweetalert/css/sweetalert.css">
        <!-- Custom CSS -->
        <link rel="stylesheet" type="text/css" href="assets/css/login.css">

        <!-- Title -->
        <title>Aplikasi Penjualan Pulsa</title>
    </head>
    <body>
        <!-- Preloader -->
        <div style="display:none" class="preloader">
            <div class="loading">
                <!-- Preloader Text -->
                <div id="loading-text"></div>
                <!-- Preloader Animasi -->
                <img src="assets/img/loading-inline.gif" alt="ToroLab" width="200">
            </div>
        </div>

        <!-- Form Login -->
        <form class="form-signin" id="formLogin">
            <div class="text-center mb-4">
                <!-- Logo -->
                <img class="brand mb-3" src="assets/img/<?php echo $logo; ?>" alt="ToroLab">
                <!-- Nama Konter -->
                <h1 class="h3 mb-4 font-weight-normal"><?php echo $nama_konter; ?></h1>
            </div>

            <?php  
            // fungsi untuk menampilkan pesan
            // jika alert = "" (kosong)
            // tampilkan pesan "" (kosong)
            if (empty($_GET['alert'])) { 
                echo "";
            }
            // jika alert = 1
            // tampilkan pesan Sukses "Anda telah berhasil logout"
            elseif ($_GET['alert'] == 1) { ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <strong><i class="fas fa-check-circle title-icon"></i> Sukses!</strong> <br> Anda telah berhasil logout.
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            <?php
            }
            // jika alert = 2
            // tampilkan pesan Peringatan "Anda harus login terlebih dahulu"
            elseif ($_GET['alert'] == 2) { ?>
                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                    <strong><i class="fas fa-exclamation-triangle title-icon"></i> Peringatan!</strong> <br> Anda harus login terlebih dahulu.
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            <?php } ?>

            <hr>
            <!-- isi form login -->
            <div class="form-group mt-4">
                <input type="text" class="form-control" id="username" name="username" placeholder="Username" autocomplete="off">
            </div>

            <div class="form-group">
                <input type="password" class="form-control" id="password" name="password" placeholder="Password" autocomplete="off">
            </div>

            <div class="form-check form-check-inline mb-4">
                <input type="checkbox" class="form-check-input" id="tampil_password">
                <label class="form-check-label">Tampilkan Password</label>
            </div>

            <button type="button" class="btn btn-lg btn-primary btn-block" id="btnLogin">
                <i class="fas fa-sign-in-alt title-icon"></i> Login
            </button>

            <p class="mt-5 mb-3 text-muted text-center">
                &copy; 2018 - <a class="text-primary" href="https://rsudpbari.palembang.go.id/">https://rsudpbari.palembang.go.id</a>
            </p>
        </form>

        <!-- Optional JavaScript -->
        <!-- jQuery -->
        <script type="text/javascript" src="assets/js/jquery-3.3.1.js"></script>
        <!-- Bootstrap JS -->
        <script type="text/javascript" src="assets/plugins/bootstrap-4.1.3/js/bootstrap.min.js"></script>
        <!-- Fontawesome Plugin JS -->
        <script type="text/javascript" src="assets/plugins/fontawesome-free-5.5.0-web/js/all.min.js"></script>
        <!-- SweetAlert Plugin JS -->
        <script type="text/javascript" src="assets/plugins/sweetalert/js/sweetalert.min.js"></script>

        <script type="text/javascript">
            $(document).ready(function(){   
                // ===================================== Tampilkan Password =====================================
                $('#tampil_password').click(function(){
                    // jika diceklis, maka ubah atribut "type=text" untuk menampilkan password
                    if ($(this).is(':checked')){
                        $('#password').attr('type','text');
                    } 
                    // jika tidak diceklis, maka ubah atribut "type=password" untuk menyembunyikan password
                    else {
                        $('#password').attr('type','password');
                    }
                });
                // ==============================================================================================

                // =========================================== Login ============================================
                $('#btnLogin').click(function(){
                    // validasi form input
                    // jika "username" kosong
                    if ($('#username').val()==""){
                        // focus ke input "username"
                        $( "#username" ).focus();
                        // tampilkan peringatan data tidak boleh kosong
                        swal("Peringatan!", "Username tidak boleh kosong.", "warning");
                    }
                    // jika "password" kosong
                    else if ($('#password').val()==""){
                        // focus ke input "password"
                        $( "#password" ).focus();
                        // tampilkan peringatan data tidak boleh kosong
                        swal("Peringatan!", "Password tidak boleh kosong.", "warning");
                    }
                    // jika semua data sudah terisi, jalankan perintah login
                    else {
                        // membuat variabel untuk menampung data dari form login
                        var data = $('#formLogin').serialize();

                        $.ajax({
                            type : "POST",                          // mengirim data dengan method POST 
                            url  : "login-check",                   // proses pengecekan login berdasakan username dan password
                            data : data,                            // data yang dikirim
                            beforeSend: function() {                // proses sebelum data dikirim
                                // tampilkan preloader
                                $('.preloader').fadeIn('slow');
                                $('#loading-text').html('<div class="alert alert-primary left" role="alert"><i class="fas fa-sync title-icon"></i> Memeriksa Username dan Password .....</div>');
                            },
                            success: function(result){              // ketika proses pengecekan login selesai
                                // jika login berhasil
                                if (result==="sukses") {
                                    // tampilkan pesan berhasil login
                                    setTimeout('$("#loading-text").html("<div class=\'alert alert-success left\' role=\'alert\'><h3><i class=\'fas fa-check-circle title-icon\'></i> Login Berhasil!</h3> <br> Anda akan diarahkan ke Halaman Admin .....</div>");', 1000);
                                    // alihkan ke halaman admin
                                    setTimeout('window.location = "main";', 2000);
                                } 
                                // jika login gagal
                                else if (result==="gagal") {
                                    // tutup preloader
                                    $('.preloader').fadeOut('fast', function() {
                                        swal("Gagal Login!", "Username atau Password salah. Cek kembali Username dan Password Anda.", "error");
                                    });
                                }
                                // jika error
                                else {
                                    // tutup preloader
                                    $('.preloader').fadeOut('fast', function() {
                                        // tampilkan pesan gagal login dan error result
                                        swal("Gagal Login!", "Query Error : "+ result, "error");
                                    });
                                }
                            }
                        });
                        return false;
                    }
                });
            });
            // ==============================================================================================
        </script>
    </body>
</html>