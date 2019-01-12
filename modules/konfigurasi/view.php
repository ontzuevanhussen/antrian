<?php  
session_start();      // memulai session

// fungsi untuk pengecekan status login user 
// jika user belum login, alihkan ke halaman "login-error"
if (empty($_SESSION['username']) && empty($_SESSION['password'])){
    echo "<meta http-equiv='refresh' content='0; url=../../login-error'>";
}
// jika user sudah login
else { ?>
    <div class="row mb-3">
        <div class="col-md-12">
            <h5>
                <!-- judul halaman konfigurasi aplikasi -->
                <i class="fas fa-cog title-icon"></i> Konfigurasi Aplikasi
            </h5>
        </div>
    </div>

    <div class="border mb-4"></div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <!-- Form Konfigurasi Aplikasi -->
                <form id="formConfig" enctype="multipart/form-data">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-7">
                                <input type="hidden" id="id" name="id">

                                <div class="form-group col-md-12">
                                    <label>Nama Konter</label>
                                    <input type="text" class="form-control" id="nama_konter" name="nama_konter" autocomplete="off" readonly>
                                </div>

                                <div class="form-group col-md-12">
                                    <label>Alamat</label>
                                    <textarea class="form-control" id="alamat" name="alamat" rows="2" readonly></textarea>
                                </div>

                                <div class="form-group col-md-12">
                                    <label>Telepon</label>
                                    <input type="text" class="form-control" id="telepon" name="telepon" maxlength="13" onKeyPress="return goodchars(event,'0123456789',this)" autocomplete="off" readonly>
                                </div>

                                <div class="form-group col-md-12">
                                    <label>Email</label>
                                    <input type="email" class="form-control" id="email" name="email" autocomplete="off" readonly>
                                </div>

                                <div class="form-group col-md-12">
                                    <label>Website</label>
                                    <input type="text" class="form-control" id="website" name="website" autocomplete="off" readonly>
                                </div>
                            </div>
                            <div class="col-md-5">
                                <div class="form-group col-md-12">
                                    <label>Logo</label>
                                    <input type="hidden" class="form-control-file mb-3" id="logo" name="logo" autocomplete="off">
                                    <!-- logo preview -->
                                    <div class="logo-preview-border mb-3" id="imagePreview">
                                        <img class="logo-preview" alt="Logo">
                                    </div>
                                    <!-- keterangan unggah file logo -->
                                    <div id="logoHelp">
                                        <small class="form-text text-danger">*Harap unggah file logo yang memiliki tipe *.jpg atau *.png.</small>
                                        <small class="form-text text-danger">*Ukuran file logo yang bisa diunggah maksimal 1 Mb.</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="form-group col-md-6 mt-2">
                            <button type="button" class="btn btn-primary" id="btnUbah">Ubah</button>
                            <button type="button" class="btn btn-primary mr-2" id="btnSimpan">Simpan</button>
                            <button type="button" class="btn btn-secondary" id="btnBatal">Batal</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script type="text/javascript">
    $(document).ready(function(){
        // =========================================== Onload ===========================================
        // panggil fungsi tampil data konfigurasi
        tampil_data();
        // sembunyikan tombol "simpan"
        $('#btnSimpan').hide();
        // sembunyikan tombol "batal"
        $('#btnBatal').hide();
        // sembunyikan keterangan unggah file logo
        $('#logoHelp').hide();
        // ==============================================================================================
        
        // ======================================== Tampil Data =========================================
        // tampilkan data konfigurasi aplikasi
        function tampil_data() {
            // siapkan data "id" konfigurasi
            var id = 1;
            
            $.ajax({
                type     : "GET",                                       // mengirim data dengan method GET 
                url      : "modules/konfigurasi/get_data.php",          // proses get data konfigurasi berdasarkan "id"
                data     : {id:id},                                     // data yang dikirim
                dataType : "JSON",                                      // tipe data JSON
                success: function(result){                              // ketika proses get data selesai
                    // tampilkan data konfigurasi ke form
                    $('#id').val(result.id);
                    $('#nama_konter').val(result.nama_konter);
                    $('#alamat').val(result.alamat);
                    $('#telepon').val(result.telepon);
                    $('#email').val(result.email);
                    $('#website').val(result.website);
                    $('.logo-preview').attr('src','assets/img/'+result.logo);  
                }
            });
        }
        // ==============================================================================================
        
        // ======================================= Validasi Form ========================================
        // validasi email
        function validasi_email(email) {
            // regular expression pattern untuk pola penulisan email
            var regex = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;

            // jika input email tidak sesuai dengan pola regex
            if (!regex.test(email)) {
                // email tidak valid
                return false;
            } 
            // jika input email sesuai dengan pola regex
            else {
                // email valid
                return true;
            }
        }

        // validasi url (website)
        function validasi_url(website) {
            // regular expression pattern untuk pola penulisan url
            var regex = /^(http(s)?:\/\/)?(www\.)?[a-z0-9]+([\-\.]{1}[a-z0-9]+)*\.[a-z]{2,5}(:[0-9]{1,5})?(\/.*)?$/;

            // jika input website tidak sesuai dengan pola regex
            if (!regex.test(website)) {
                // website tidak valid
                return false;
            } 
            // jika input website sesuai dengan pola regex
            else {
                // website valid
                return true;
            }
        }

        // validasi file dan preview logo sebelum diunggah
        $('#logo').change(function(){
            // mengambil value dari "logo"
            var filePath          = $('#logo').val();
            var fileSize          = $('#logo')[0].files[0].size;
            // tentukan extension file yang diperbolehkan
            var allowedExtensions = /(\.jpg|\.jpeg|\.png)$/i;

            // Jika tipe file yang diunggah tidak sesuai dengan "allowedExtensions"
            if (!allowedExtensions.exec(filePath)) {
                // tampilkan pesan peringatan tipe file tidak sesuai
                swal("Peringatan!", "Tipe file logo tidak sesuai. Harap unggah file logo yang memiliki tipe *.jpg atau *.png.", "warning");
                // reset input file logo
                $('#logo').val('');
                // preview logo
                $('.logo-preview').attr('src','assets/img/no_image_300x300.gif');  

                return false;
            } 
            // jika ukuran file yang diunggah lebih dari 1 Mb
            else if (fileSize > 1000000) {
                // tampilkan pesan peringatan ukuran file tidak sesuai
                swal("Peringatan!", "Ukuran file logo lebih dari 1 Mb. Harap unggah file logo yang memiliki ukuran maksimal 1 Mb.", "warning");
                // reset input file logo
                $('#logo').val('');
                // preview logo
                $('.logo-preview').attr('src','assets/img/no_image_300x300.gif');

                return false;
            }
            // jika file yang diunggah sudah sesuai, tampilkan preview logo
            else {
                var fileInput = document.getElementById('logo');

                if (fileInput.files && fileInput.files[0]) {
                    var reader = new FileReader();
                    
                    reader.onload = function(e) {
                        // preview logo
                        $('.logo-preview').attr('src',e.target.result);
                    };
                };
                reader.readAsDataURL(fileInput.files[0]);
            }
        });
        // ==============================================================================================
        
        // ====================================== Enable Form Update ====================================
        $('#btnUbah').click(function(){
            // sembunyikan tombol "ubah"
            $('#btnUbah').hide();
            // tampilkan tombol "simpan"
            $('#btnSimpan').show();
            // tampilkan tombol "batal"
            $('#btnBatal').show();
            // aktifkan form input
            $('#nama_konter').removeAttr('readonly');
            $('#alamat').removeAttr('readonly');
            $('#telepon').removeAttr('readonly');
            $('#email').removeAttr('readonly');
            $('#website').removeAttr('readonly');
            $('#logo').attr('type','file');
            // tampilkan keterangan unggah file logo
            $('#logoHelp').show();
        });
        // ==============================================================================================
                
        // =========================================== Update ===========================================
        // Proses Update Data        
        $('#btnSimpan').click(function(){
            // validasi form input
            // jika nama konter kosong
            if ($('#nama_konter').val()==""){
                // focus ke input nama konter
                $( "#nama_konter" ).focus();
                // tampilkan peringatan data tidak boleh kosong
                swal("Peringatan!", "Nama Konter tidak boleh kosong.", "warning");
            }
            // jika alamat kosong
            else if ($('#alamat').val()==""){
                // focus ke input alamat
                $( "#alamat" ).focus();
                // tampilkan peringatan data tidak boleh kosong
                swal("Peringatan!", "Alamat tidak boleh kosong.", "warning");
            }
            // jika telepon kosong
            else if ($('#telepon').val()==""){
                // focus ke input telepon
                $( "#telepon" ).focus();
                // tampilkan peringatan data tidak boleh kosong
                swal("Peringatan!", "Telepon tidak boleh kosong.", "warning");
            }
            // jika email kosong
            else if ($('#email').val()==""){
                // focus ke input email
                $( "#email" ).focus();
                // tampilkan peringatan data tidak boleh kosong
                swal("Peringatan!", "Email tidak boleh kosong.", "warning");
            }
            // jika input email tidak sesuai dengan pola regex
            else if (validasi_email($('#email').val())==false){
                // focus ke input email
                $( "#email" ).focus();
                // tampilkan peringatan data tidak valid
                swal("Peringatan!", "Alamat email yang Anda masukan tidak valid.", "warning");
            }
            // jika website kosong
            else if ($('#website').val()==""){
                // focus ke input website
                $( "#website" ).focus();
                // tampilkan peringatan data tidak boleh kosong
                swal("Peringatan!", "Website tidak boleh kosong.", "warning");
            }
            // jika input website tidak sesuai dengan pola regex
            else if (validasi_url($('#website').val())==false){
                // focus ke input website
                $( "#website" ).focus();
                // tampilkan peringatan data tidak valid
                swal("Peringatan!", "Alamat website yang Anda masukan tidak valid.", "warning");
            }
            // jika semua data sudah terisi, jalankan perintah update data
            else {
                // siapkan data dengan "FormData"
                var data = new FormData();
                data.append('id', $('#id').val());
                data.append('nama_konter', $('#nama_konter').val());
                data.append('alamat', $('#alamat').val());
                data.append('telepon', $('#telepon').val());
                data.append('email', $('#email').val());
                data.append('website', $('#website').val());
                data.append('logo', $('#logo')[0].files[0]);

                $.ajax({
                    type        : "POST",                                   // mengirim data dengan method POST 
                    url         : "modules/konfigurasi/update.php",         // proses update data
                    data        : data,                                     // data yang dikirim
                    contentType : false,
                    processData : false,
                    success: function(result){                              // ketika proses update data selesai
                     // jika berhasil
                        if (result==="sukses") {
                            // tampilkan pesan sukses update data
                            swal("Sukses!", "Data konfigurasi aplikasi berhasil diubah.", "success");
                            // tampilkan data konfigurasi
                            tampil_data();
                            // non-aktifkan form input
                            disable_form();
                        } 
                        // jika gagal
                        else {
                            // tampilkan pesan gagal update data dan error result
                            swal("Gagal!", "Data konfigurasi aplikasi gagal diubah. Query Error : "+ result, "error");
                        }
                    }
                });
                return false;
            }            
        });
        // ==============================================================================================

        // ====================================== Disable Form Update ====================================
        $('#btnBatal').click(function(){
            // tampilkan data konfigurasi
            tampil_data();
            // non-aktifkan form input
            disable_form();
        });

        // fungsi untuk non-aktifkan form input
        function disable_form() {
            // tampilkan tombol "ubah"
            $('#btnUbah').show();
            // sembunyikan tombol "simpan"
            $('#btnSimpan').hide();
            // sembunyikan tombol "batal"
            $('#btnBatal').hide();
            // non-aktifkan form input
            $('#nama_konter').attr('readonly','true');
            $('#alamat').attr('readonly','true');
            $('#telepon').attr('readonly','true');
            $('#email').attr('readonly','true');
            $('#website').attr('readonly','true');
            $('#logo').attr('type','hidden');
            // sembunyikan keterangan unggah file logo
            $('#logoHelp').hide();
        }
        // ==============================================================================================
    });
    </script>
<?php } ?>