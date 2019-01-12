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
session_start();      // memulai session

// fungsi untuk pengecekan status login user 
// jika user belum login, alihkan ke halaman "login-error"
if (empty($_SESSION['username']) && empty($_SESSION['password'])){
    echo "<meta http-equiv='refresh' content='0; url=login-error'>";
}
// jika user sudah login
else {
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
            <!-- DataTables CSS -->
            <link rel="stylesheet" type="text/css" href="assets/plugins/DataTables/css/dataTables.bootstrap4.min.css">
            <!-- Datepicker CSS -->
            <link rel="stylesheet" type="text/css" href="assets/plugins/datepicker/css/datepicker.min.css">
            <!-- Font Awesome CSS -->
            <link rel="stylesheet" type="text/css" href="assets/plugins/fontawesome-free-5.5.0-web/css/all.min.css">
            <!-- Sweetalert CSS -->
            <link rel="stylesheet" type="text/css" href="assets/plugins/sweetalert/css/sweetalert.css">
            <!-- Chosen CSS -->
            <link rel="stylesheet" type="text/css" href="assets/plugins/chosen-bootstrap-4/css/chosen.css">
            <!-- Custom CSS -->
            <link rel="stylesheet" type="text/css" href="assets/css/style.css">
            <!-- jQuery -->
            <script type="text/javascript" src="assets/js/jquery-3.3.1.js"></script>

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
            
            <!-- Navbar Brand -->
            <nav class="navbar navbar-expand-md fixed-top navbar-light bg-purple">
                <div class="container">
                    <!-- Logo dan Nama Konter -->
                    <a class="navbar-brand" href="main">
                        <img src="assets/img/<?php echo $logo; ?>" class="d-inline-block brand align-top title-icon" alt="ToroLab">
                        <span class="title-brand"><?php echo $nama_konter; ?></span>
                    </a>
                    <!-- Profil User -->
                    <div>
                        <ul class="navbar-nav ml-auto">
                            <li class="nav-item dropdown">
                                <a class="dropdown-toggle username" href="javascript:void(0);" id="navbarUser" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fas fa-user title-icon"></i> <?php echo $_SESSION['nama_user']; ?>
                                </a>
                                <!-- Menu User -->
                                <div class="dropdown-menu" aria-labelledby="navbarUser">
                                    <a class="dropdown-item menu" id="password" href="#password">
                                        <i class="fas fa-angle-right title-icon"></i> Ubah Password
                                    </a>

                                    <div class="dropdown-divider"></div>

                                    <a class="dropdown-item" id="logout" href="javascript:void(0);">
                                        <i class="fas fa-angle-right title-icon"></i> Logout
                                    </a>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>
            
            <!-- Navbar Menu -->
            <nav class="navbar navbar-expand-md fixed-top navbar-light navbar-menu bg-light shadow-sm">
                <div class="container">
                    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <!-- Menu Aplikasi -->
                    <div class="collapse navbar-collapse" id="navbarCollapse">
                        <ul class="navbar-nav">
                        <?php 
                        // mengecek hak akses user untuk menampilkan menu sesuai dengan hak akses
                        // jika "hak_akses = Administrator", tampilkan menu
                        if ($_SESSION['hak_akses']=='Administrator') { ?>
                            <li class="nav-item">
                                <a class="nav-link mr-2 menu" id="beranda" href="#beranda">
                                    <i class="fas fa-home title-icon"></i> Beranda 
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link mr-2 menu" id="pelanggan" href="#pelanggan">
                                    <i class="fas fa-user-friends title-icon"></i> Pelanggan
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link mr-2 menu" id="pulsa" href="#pulsa">
                                    <i class="fas fa-tablet-alt title-icon"></i> Pulsa
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link mr-2 menu" id="penjualan" href="#penjualan">
                                    <i class="fas fa-shopping-cart title-icon"></i> Penjualan
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link mr-2 menu" id="laporan" href="#laporan">
                                    <i class="fas fa-file-alt title-icon"></i> Laporan
                                </a>
                            </li>
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle mr-2" href="javascript:void(0);" id="navbarUtility" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fas fa-user-cog title-icon"></i> Utility
                                </a>
                                <!-- User Menu -->
                                <div class="dropdown-menu" aria-labelledby="navbarUtility">
                                    <a class="dropdown-item menu" id="konfigurasi" href="#konfigurasi">
                                        <i class="fas fa-angle-right title-icon"></i> Konfigurasi Aplikasi
                                    </a>

                                    <div class="dropdown-divider"></div>

                                    <a class="dropdown-item menu" id="user" href="#manajemen-user">
                                        <i class="fas fa-angle-right title-icon"></i> Manajemen User
                                    </a>

                                    <div class="dropdown-divider"></div>

                                    <a class="dropdown-item menu" id="backupdb" href="#backup-database">
                                        <i class="fas fa-angle-right title-icon"></i> Backup Database
                                    </a>

                                    <div class="dropdown-divider"></div>

                                    <a class="dropdown-item menu" id="audit" href="#audit-trail">
                                        <i class="fas fa-angle-right title-icon"></i> Audit Trail
                                    </a>
                                </div>
                            </li>
                        <?php 
                        } 
                        // jika "hak_akses = Operator", tampilkan menu
                        else { ?>
                            <li class="nav-item">
                                <a class="nav-link mr-2 menu" id="beranda" href="#beranda">
                                    <i class="fas fa-home title-icon"></i> Beranda 
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link mr-2 menu" id="pelanggan" href="#pelanggan">
                                    <i class="fas fa-user-friends title-icon"></i> Pelanggan
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link mr-2 menu" id="penjualan" href="#penjualan">
                                    <i class="fas fa-shopping-cart title-icon"></i> Penjualan
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link mr-2 menu" id="laporan" href="#laporan">
                                    <i class="fas fa-file-alt title-icon"></i> Laporan
                                </a>
                            </li>
                        <?php  
                        }
                        ?>
                        </ul>
                    </div>
                </div>
            </nav>
            
            <main role="main" class="container">
                <!-- Menampilkan isi halaman pada class "content" -->
                <div class="content"></div>
            </main>

            <!-- Footer -->
            <div class="container">
                <footer class="pt-4 my-md-4 pt-md-3 border-top">
                    <div class="row">
                        <div class="col-12 col-md center">
                            &copy; 2018 - <a class="text-info" href="https://rsudpbari.palembang.go.id/">https://rsudpbari.palembang.go.id</a>
                        </div>
                    </div>
                </footer>
            </div>

            <!-- Optional JavaScript -->
            <!-- Bootstrap JS -->
            <script type="text/javascript" src="assets/plugins/bootstrap-4.1.3/js/bootstrap.min.js"></script>
            <!-- Fontawesome Plugin JS -->
            <script type="text/javascript" src="assets/plugins/fontawesome-free-5.5.0-web/js/all.min.js"></script>
            <!-- DataTables Plugin JS -->
            <script type="text/javascript" src="assets/plugins/DataTables/js/jquery.dataTables.min.js"></script>
            <script type="text/javascript" src="assets/plugins/DataTables/js/dataTables.bootstrap4.min.js"></script>
            <!-- datepicker Plugin JS -->
            <script type="text/javascript" src="assets/plugins/datepicker/js/bootstrap-datepicker.min.js"></script>
            <!-- SweetAlert Plugin JS -->
            <script type="text/javascript" src="assets/plugins/sweetalert/js/sweetalert.min.js"></script>
            <!-- Chosen Plugin JS -->
            <script type="text/javascript" src="assets/plugins/chosen-bootstrap-4/js/chosen.jquery.js"></script>
            
            <script type="text/javascript">
            $(document).ready(function() {
                // ==================================== Menu dan Halaman Aplikasi =====================================
                // halaman yang diload default pertama kali saat aplikasi dijalankan
                $(".content").load("beranda");

                // fungsi untuk pemanggilan halaman tanpa reload/refresh
                $(".menu").click(function(){
                    // tampilkan preloader
                    $('.preloader').fadeIn('slow');
                    // membuat variabel untuk menampung "id" dari class "menu" yang diklik
                    var menu = $(this).attr('id');
                    // jika "id = beranda", maka load halaman view beranda
                    if (menu=="beranda") {
                        // tutup preloader
                        $('.preloader').fadeOut('slow');
                        // tampilkan halaman view beranda
                        setTimeout('$(".content").load("beranda");', 1000);
                    } 
                    // jika "id = pelanggan", maka load halaman view pelanggan
                    else if (menu=="pelanggan") {
                        // tutup preloader
                        $('.preloader').fadeOut('slow');
                        // tampilkan halaman view pelanggan
                        setTimeout('$(".content").load("pelanggan");', 1000);                     
                    }
                    // jika "id = pulsa", maka load halaman view pulsa 
                    else if (menu=="pulsa") {
                        // tutup preloader
                        $('.preloader').fadeOut('slow');
                        // tampilkan halaman view pulsa
                        setTimeout('$(".content").load("pulsa");', 1000);         
                    } 
                    // jika "id = penjualan", maka load halaman view penjualan
                    else if (menu=="penjualan") {
                        // tutup preloader
                        $('.preloader').fadeOut(1000);
                        // tampilkan halaman view penjualan
                        setTimeout('$(".content").load("penjualan");', 1000);
                    } 
                    // jika "id = laporan", maka load halaman view laporan
                    else if (menu=="laporan") {
                        // tutup preloader
                        $('.preloader').fadeOut(1000);
                        // tampilkan halaman view laporan
                        setTimeout('$(".content").load("laporan");', 1000);
                    } 
                    // jika "id = konfigurasi", maka load halaman view konfigurasi
                    else if (menu=="konfigurasi") {
                        // tutup preloader
                        $('.preloader').fadeOut(1000);
                        // tampilkan halaman view konfigurasi
                        setTimeout('$(".content").load("konfigurasi");', 1000);
                    } 
                    // jika "id = user", maka load halaman view user
                    else if (menu=="user") {
                        // tutup preloader
                        $('.preloader').fadeOut(1000);
                        // tampilkan halaman view user
                        setTimeout('$(".content").load("user");', 1000);
                    } 
                    // jika "id = backupdb", maka load halaman view backup database
                    else if (menu=="backupdb") {
                        // tutup preloader
                        $('.preloader').fadeOut(1000);
                        // tampilkan halaman view backup database
                        setTimeout('$(".content").load("backupdb");', 1000);
                    } 
                    // jika "id = audit", maka load halaman view audit trail
                    else if (menu=="audit") {
                        // tutup preloader
                        $('.preloader').fadeOut(1000);
                        // tampilkan halaman view audit trail
                        setTimeout('$(".content").load("audit");', 1000);           
                    } 
                    // jika "id = password", maka load halaman view password
                    else if (menu=="password") {
                        // tutup preloader
                        $('.preloader').fadeOut(1000);
                        // tampilkan halaman view password
                        setTimeout('$(".content").load("password");', 1000);
                    }
                }); 
                // ====================================================================================================
                
                // ============================================= Logout ===============================================
                $("#logout").click(function(){
                    // tampilkan notifikasi saat akan logout
                    swal({
                        title: "Apakah Anda Yakin?",
                        text: "Anda akan keluar dari aplikasi.",
                        type: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#DD6B55",
                        confirmButtonText: "Ya",
                        closeOnConfirm: false
                    }, 
                    // jika confirm button dipilih
                    function () {
                        // alihkan ke halaman "logout"
                        window.location = "logout";
                    });
                });
                // ====================================================================================================
            } );

            // ========================================== Validasi Form ===========================================
            // Validasi karakter yang boleh diinpukan pada form
            function getkey(e) {
                if (window.event)
                    return window.event.keyCode;
                else if (e)
                    return e.which;
                else
                    return null;
            }

            function goodchars(e, goods, field) {
                var key, keychar;
                key = getkey(e);
                if (key == null) return true;

                keychar = String.fromCharCode(key);
                keychar = keychar.toLowerCase();
                goods   = goods.toLowerCase();

                // check goodkeys
                if (goods.indexOf(keychar) != -1)
                    return true;
                // control keys
                if ( key==null || key==0 || key==8 || key==9 || key==27 )
                    return true;
                  
                if (key == 13) {
                    var i;
                    for (i = 0; i < field.form.elements.length; i++)
                        if (field == field.form.elements[i])
                            break;
                            i = (i + 1) % field.form.elements.length;
                            field.form.elements[i].focus();
                        return false;
                    };
                // else return false
                return false;
            }
            // ====================================================================================================
            </script>
        </body>
    </html>
<?php } ?>