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
    			<!-- judul halaman ubah password -->
    			<i class="fas fa-lock title-icon"></i> Ubah Password
    		</h5>
    	</div>
    </div>

    <div class="border mb-4"></div>

    <div class="row">
        <div class="col-md-12">
        	<div class="card">
				<!-- Form Ubah Password -->
	            <form id="formPassword">
				  	<div class="card-body">
				    	<div class="form-group col-md-5">
	                        <label>Password Lama</label>
			                <input type="password" class="form-control" id="old_pass" name="old_pass" autocomplete="off">
			            </div>

			            <div class="form-group col-md-5">
	                        <label>Password Baru</label>
			                <input type="password" class="form-control" id="new_pass" name="new_pass" autocomplete="off">
			            </div>

			            <div class="form-group col-md-5">
	                        <label>Ulangi Password Baru</label>
			                <input type="password" class="form-control" id="retype_pass" name="retype_pass" autocomplete="off">
			            </div>
						
						<div class="form-group col-md-5">
				            <div class="form-check form-check-inline">
				                <input type="checkbox" class="form-check-input" id="tampil_password">
				                <label class="form-check-label">Tampilkan Password</label>
				            </div>
				        </div>
				  	</div>
				  	<div class="card-footer">
				  		<div class="form-group col-md-6 mt-2">
				    		<button type="button" class="btn btn-primary" id="btnSimpan">Simpan</button>
			            </div>
				  	</div>
	            </form>
			</div>
        </div>
    </div>

    <script type="text/javascript">
    $(document).ready(function(){
    	// ===================================== Tampilkan Password =====================================
        $('#tampil_password').click(function(){
            // jika diceklis, maka ubah atribut "type=text" untuk menampilkan password
            if ($(this).is(':checked')){
                $('#old_pass').attr('type','text');
                $('#new_pass').attr('type','text');
                $('#retype_pass').attr('type','text');
            } 
            // jika tidak diceklis, maka ubah atribut "type=password" untuk menyembunyikan password
            else {
                $('#old_pass').attr('type','password');
                $('#new_pass').attr('type','password');
                $('#retype_pass').attr('type','password');
            }
        });
        // ==============================================================================================
                
        // ======================================= Update Password =======================================
        // Proses Update Data
        $('#btnSimpan').click(function(){
            // validasi form input
            // jika "password lama" kosong
            if ($('#old_pass').val()==""){
                // focus ke input "password lama"
                $( "#old_pass" ).focus();
                // tampilkan peringatan data tidak boleh kosong
                swal("Peringatan!", "Password Lama tidak boleh kosong.", "warning");
            }
            // jika "password lama" sudah terisi, jalankan perintah untuk mengecek password lama
            else {
                // mengambil value dari "password lama"
                var password_lama = $('#old_pass').val();

                $.ajax({
                    type : "POST",                                  	// mengirim data dengan method POST 
                    url  : "modules/password/get_password_lama.php",    // proses pengecekan password lama
                    data : {password_lama:password_lama},               // data yang dikirim
                    success: function(result){                      	// ketika selesai melakukan pengecekan password lama
                    	// jika password lama salah
                        if (result==="salah") {
                            // reset form
                            $('#formPassword')[0].reset();
                            // tampilkan pesan password salah
                            swal("Peringatan!", "Paswword Lama yang Anda masukan salah.", "warning");
                        } 
                        // jika password lama benar, jalankan pengecekan selanjutnya
                        else if (result==="benar") {
                            // jika "password baru" kosong
		                    if ($('#new_pass').val()==""){
		                        // focus ke input "password baru"
		                        $( "#new_pass" ).focus();
		                        // tampilkan peringatan data tidak boleh kosong
		                        swal("Peringatan!", "Password Baru tidak boleh kosong.", "warning");
		                    }
		                    // jika "ulangi password baru" kosong
		                    else if ($('#retype_pass').val()==""){
		                        // focus ke input "ulangi password baru"
		                        $( "#retype_pass" ).focus();
		                        // tampilkan peringatan data tidak boleh kosong
		                        swal("Peringatan!", "Ulangi Password Baru tidak boleh kosong.", "warning");
		                    }
		                    // jika "password baru" dan "ulangi password baru" tidak sama
		                    else if ($('#new_pass').val() != $('#retype_pass').val()){
		                        // focus ke input "ulangi password baru"
		                        $( "#retype_pass" ).focus();
		                        // tampilkan peringatan password tidak sama
		                        swal("Peringatan!", "Password Baru dan Ulangi Password Baru tidak cocok.", "warning");
		                    }
		                    // jika semua data sudah terisi, jalankan perintah update data
		                    else {
		                        // mengambil value dari "password baru"
                				var password_baru = $('#new_pass').val();

		                        $.ajax({
		                            type : "POST",                                  // mengirim data dengan method POST 
		                            url  : "modules/password/update.php",          	// proses update data
		                            data : {password_baru:password_baru},           // data yang dikirim
		                            success: function(result){                      // ketika proses update data selesai
		                            	// jika berhasil
		                                if (result==="sukses") {
		                                    // reset form
		                                    $('#formPassword')[0].reset();
		                                    // tampilkan pesan sukses update data
		                                    swal("Sukses!", "Password berhasil diubah.", "success");
		                                } 
                                        // jika gagal
                                        else {
		                                    // tampilkan pesan gagal update data dan error result
		                                    swal("Gagal!", "Password gagal diubah. Script error : "+ result, "error");
		                                }
		                            }
		                        });
		                        return false;
		                    }
                        }
                        // jika error
                        else {
                            // tampilkan pesan error
                            swal("Gagal!", "Query Error : "+ result, "error");
                        }
                    }
                });
                return false;
            }
        });
        // ===============================================================================================
    });
    </script>
<?php } ?>