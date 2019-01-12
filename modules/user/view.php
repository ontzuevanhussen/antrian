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
    			<!-- judul halaman tampil data user -->
    			<i class="fas fa-user title-icon"></i> Manajemen User
    			<!-- tombol tambah data user -->
                <a class="btn btn-primary float-right" id="btnTambah" href="javascript:void(0);" data-toggle="modal" data-target="#modalUser" role="button">
                    <i class="fas fa-plus"></i> Tambah 
                </a>
    		</h5>
    	</div>
    </div>

    <div class="border mb-4"></div>

    <div class="row">
        <div class="col-md-12">
            <!-- Tabel user untuk menampilkan data user dari database -->
            <table id="tabel-user" class="table table-striped table-bordered" style="width:100%">
    			<!-- judul kolom pada bagian kepala (atas) tabel -->
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>ID User</th>   <!-- kolom "ID User" disembunyikan -->
                        <th>Nama User</th>
                        <th>Username</th>
                        <th>Hak Akses</th>
                        <th>Blokir</th>
                        <th></th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>

    <!-- Modal form untuk entri dan ubah data user -->
    <div class="modal fade" id="modalUser" tabindex="-1" role="dialog" aria-labelledby="modalUser" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
            	<!-- judul form data user -->
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-edit title-icon"></i><span id="modalLabel"></span></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
    			<!-- isi form data user -->
                <form id="formUser">
                    <div class="modal-body">
                        <input type="hidden" id="id_user" name="id_user">

                        <div class="form-group">
                            <label>Nama User</label>
                            <input type="text" class="form-control" id="nama_user" name="nama_user" autocomplete="off">
                        </div>

                        <div class="form-group">
                            <label>Username</label>
                            <input type="text" class="form-control" id="username" name="username" autocomplete="off">
                        </div>

                        <div class="form-group">
                            <label>Password</label>
                            <input type="password" class="form-control" id="pass" name="pass" autocomplete="off">
                        </div>

                        <div class="form-group">
                            <label>Hak Akses</label>
                            <select class="form-control" id="hak_akses" name="hak_akses" autocomplete="off">
                                <option value="">-- Pilih --</option>
                                <option value="Administrator">Administrator</option>
                                <option value="Operator">Operator</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Blokir User</label> <br>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="blokir" id="blokir" value="Ya">
                                <label class="form-check-label">Ya</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="blokir" id="blokir" value="Tidak" checked>
                                <label class="form-check-label">Tidak</label>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary mr-2" id="btnSimpan">Simpan</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script type="text/javascript">
    $(document).ready(function(){
        // ============================================ View =============================================
        // dataTables plugin untuk membuat nomor urut tabel
        $.fn.dataTableExt.oApi.fnPagingInfo = function (oSettings)
        {
            return {
                "iStart": oSettings._iDisplayStart,
                "iEnd": oSettings.fnDisplayEnd(),
                "iLength": oSettings._iDisplayLength,
                "iTotal": oSettings.fnRecordsTotal(),
                "iFilteredTotal": oSettings.fnRecordsDisplay(),
                "iPage": Math.ceil(oSettings._iDisplayStart / oSettings._iDisplayLength),
                "iTotalPages": Math.ceil(oSettings.fnRecordsDisplay() / oSettings._iDisplayLength)
            };
        };

        // datatables serverside processing
        var table = $('#tabel-user').DataTable( {
            "processing": true,                         // tampilkan loading saat proses data
            "serverSide": true,                         // server-side processing
            "ajax": 'modules/user/data.php',            // panggil file "data.php" untuk menampilkan data user dari database
            // menampilkan data
            "columnDefs": [ 
                { "targets": 0, "data": null, "orderable": false, "searchable": false, "width": '30px', "className": 'center' },
                { "targets": 1, "visible": false },     // kolom "id_user" disembunyikan
                { "targets": 2, "width": '180px' },
                { "targets": 3, "width": '180px' },
                { "targets": 4, "width": '100px', "className": 'center' },
                { "targets": 5, "width": '80px', "className": 'center' },
                {
                  "targets": 6, "data": null, "orderable": false, "searchable": false, "width": '70px', "className": 'center',
                  // tombol ubah dan hapus
                  "render": function(data, type, row) {
                      var btn = "<a style=\"margin-right:7px\" title=\"Ubah\" class=\"btn btn-primary btn-sm getUbah\" href=\"javascript:void(0);\"><i class=\"fas fa-edit\"></i></a><a title=\"Hapus\" class=\"btn btn-danger btn-sm btnHapus\" href=\"javascript:void(0);\"><i class=\"fas fa-trash\"></i></a>";
                      return btn;
                  } 
                } 
            ],
            "order": [[ 1, "desc" ]],           // urutkan data berdasarkan "id_user" secara descending
            "iDisplayLength": 10,               // tampilkan 10 data per halaman
            // membuat nomor urut tabel
            "rowCallback": function (row, data, iDisplayIndex) {
                var info   = this.fnPagingInfo();
                var page   = info.iPage;
                var length = info.iLength;
                var index  = page * length + (iDisplayIndex + 1);
                $('td:eq(0)', row).html(index);
            }
        } );
        // ===============================================================================================

        // ============================================ Form =============================================
        // Tampilkan Modal Form Entri Data
        $('#btnTambah').click(function(){
            // reset form
            $('#formUser')[0].reset();
            $('#pass').attr('placeholder', '');
            // judul form
            $('#modalLabel').text('Entri Data User');
        });

        // Tampilkan Modal Form Ubah Data
        $('#tabel-user tbody').on( 'click', '.getUbah', function (){
            // judul form
            $('#modalLabel').text('Ubah Data User');
            // ambil data dari datatables 
            var data = table.row( $(this).parents('tr') ).data();
            // membuat variabel untuk menampung data "id_user"
            var id_user = data[ 1 ];
            
            $.ajax({
                type     : "GET",                                   // mengirim data dengan method GET 
                url      : "modules/user/get_data.php",             // proses get data user berdasarkan "id_user"
                data     : {id_user:id_user},                       // data yang dikirim
                dataType : "JSON",                                  // tipe data JSON
                success: function(result){                          // ketika proses get data selesai
                    // tampilkan modal ubah data user
                    $('#modalUser').modal('show');
                    // tampilkan data user
                    $('#id_user').val(result.id_user);
                    $('#nama_user').val(result.nama_user);
                    $('#username').val(result.username);
                    $('#hak_akses').val(result.hak_akses);
                    // tampilkan keterangan pada password
                    $('#pass').attr('placeholder', 'Kosongkan password jika tidak diubah');
                    // checked radio button blokir user sesuai data dari database 
                    var blokir = $('input:radio[name=blokir]');
                    // jika "blokir user = Ya"
                    if (result.blokir == 'Ya'){
                        blokir.filter('[value=Ya]').prop('checked', true);
                    } 
                    // jika "blokir user = Tidak"
                    else {
                        blokir.filter('[value=Tidak]').prop('checked', true);
                    }
                }
            });
        });
        // ===============================================================================================

        // ====================================== Insert dan Update ======================================
        // Proses Simpan Data
        $('#btnSimpan').click(function(){
            // jika form entri data user yang ditampilkan
            if ($('#modalLabel').text()=="Entri Data User") {
                // validasi form input
                // jika nama user kosong
                if ($('#nama_user').val()==""){
                    // focus ke input nama user
                    $( "#nama_user" ).focus();
                    // tampilkan peringatan data tidak boleh kosong
                    swal("Peringatan!", "Nama User tidak boleh kosong.", "warning");
                }
                // jika username kosong
                else if ($('#username').val()==""){
                    // focus ke input username
                    $( "#username" ).focus();
                    // tampilkan peringatan data tidak boleh kosong
                    swal("Peringatan!", "Username tidak boleh kosong.", "warning");
                }
                // jika password kosong
                else if ($('#pass').val()==""){
                    // focus ke input password
                    $( "#pass" ).focus();
                    // tampilkan peringatan data tidak boleh kosong
                    swal("Peringatan!", "Password tidak boleh kosong.", "warning");
                }
                // jika hak akses kosong
                else if ($('#hak_akses').val()==""){
                    // focus ke input hak akses
                    $( "#hak_akses" ).focus();
                    // tampilkan peringatan data tidak boleh kosong
                    swal("Peringatan!", "Hak Akses tidak boleh kosong.", "warning");
                }
                // jika semua data sudah terisi, jalankan perintah insert
                else {
                    // membuat variabel untuk menampung data dari form entri data user
                    var data = $('#formUser').serialize();

                    $.ajax({
                        type : "POST",                                  // mengirim data dengan method POST 
                        url  : "modules/user/insert.php",               // proses insert data
                        data : data,                                    // data yang dikirim
                        success: function(result){                      // ketika proses insert data selesai
                            // jika berhasil
                            if (result==="sukses") {
                                // reset form
                                $('#formUser')[0].reset();
                                // tutup modal entri data user
                                $('#modalUser').modal('hide');
                                // tampilkan pesan sukses insert data
                                swal("Sukses!", "Data User berhasil disimpan.", "success");
                                // tampilkan view data user
                                var table = $('#tabel-user').DataTable(); 
                                table.ajax.reload( null, false );
                            } 
                            // jika gagal karena data "username" sudah ada
                            else if (result==="gagal") {
                                // tampilkan pesan gagal insert data
                                swal("Gagal!", "Username "+ $('#username').val() +" sudah ada. Silahkan ganti Username Anda.", "error");
                            } 
                            // jika gagal karena script error
                            else {
                                // tampilkan pesan gagal insert data dan error result
                                swal("Gagal!", "Data User tidak bisa disimpan. Script error : "+ result, "error");
                            }
                        }
                    });
                    return false;
                }
            }
            // jika form ubah data user yang ditampilkan 
            else if ($('#modalLabel').text()=="Ubah Data User") {
                // validasi form input
                // jika nama user kosong
                if ($('#nama_user').val()==""){
                    // focus ke input nama user
                    $( "#nama_user" ).focus();
                    // tampilkan peringatan data tidak boleh kosong
                    swal("Peringatan!", "Nama User tidak boleh kosong.", "warning");
                }
                // jika username kosong
                else if ($('#username').val()==""){
                    // focus ke input username
                    $( "#username" ).focus();
                    // tampilkan peringatan data tidak boleh kosong
                    swal("Peringatan!", "Username tidak boleh kosong.", "warning");
                }
                // jika hak akses kosong
                else if ($('#hak_akses').val()==""){
                    // focus ke input hak akses
                    $( "#hak_akses" ).focus();
                    // tampilkan peringatan data tidak boleh kosong
                    swal("Peringatan!", "Hak Akses tidak boleh kosong.", "warning");
                }
                // jika semua data sudah terisi, jalankan perintah update
                else {
                    // membuat variabel untuk menampung data dari form ubah data user
                    var data = $('#formUser').serialize();

                    $.ajax({
                        type : "POST",                                  // mengirim data dengan method POST 
                        url  : "modules/user/update.php",               // proses update data
                        data : data,                                    // data yang dikirim
                        success: function(result){                      // ketika proses update data selesai
                            // jika berhasil
                            if (result==="sukses") {
                                // reset form
                                $('#formUser')[0].reset();
                                // tutup modal ubah data user
                                $('#modalUser').modal('hide');
                                // tampilkan pesan sukses update data
                                swal("Sukses!", "Data User berhasil diubah.", "success");
                                // tampilkan view data user
                                var table = $('#tabel-user').DataTable(); 
                                table.ajax.reload( null, false );
                            } 
                            // jika gagal karena data "username" sudah ada
                            else if (result==="gagal") {
                                // tampilkan pesan gagal update data
                                swal("Gagal!", "Username "+ $('#username').val() +" sudah ada. Silahkan ganti Username Anda.", "error");
                            } 
                            // jika gagal karena script error
                            else {
                                // tampilkan pesan gagal update data dan error result
                                swal("Gagal!", "Data User tidak bisa diubah. Script error : "+ result, "error");
                            }
                        }
                    });
                    return false;
                }
            }  
        });
        // ===============================================================================================
        
        // =========================================== Delete ============================================
        $('#tabel-user tbody').on( 'click', '.btnHapus', function (){
            // ambil data dari datatables 
            var data = table.row( $(this).parents('tr') ).data();
            // tampilkan notifikasi saat akan menghapus data
            swal({
                title: "Apakah Anda Yakin?",
                text: "Anda akan menghapus username : "+ data[ 3 ],
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Ya, Hapus!",
                closeOnConfirm: false
            }, 
            // jika dipilih ya, maka jalankan perintah delete data
            function () {
                // membuat variabel untuk menampung data user
                var id_user   = data[ 1 ];
                var nama_user = data[ 2 ];
                var username  = data[ 3 ];
                var hak_akses = data[ 4 ];

                $.ajax({
                    type : "POST",                                                                          // mengirim data dengan method POST 
                    url  : "modules/user/delete.php",                                                       // proses delete data 
                    data : {id_user:id_user, nama_user:nama_user, username:username, hak_akses:hak_akses},  // data yang dikirim
                    success: function(result){                                                              // ketika proses delete data selesai
                        // jika berhasil
                        if (result==="sukses") {
                            // tampilkan pesan sukses delete data
                            swal("Sukses!", "Data User berhasil dihapus.", "success");
                            // tampilkan view data user
                            var table = $('#tabel-user').DataTable(); 
                            table.ajax.reload( null, false );
                        } 
                        // jika gagal karena data "user" sudah tercatat di tabel lain
                        else if (result==="gagal") {
                            // tampilkan pesan gagal delete data
                            swal("Gagal!", "Username "+ data[ 3 ] +" tidak bisa dihapus karena Username tersebut sudah tercatat pada data lain.", "error");
                        } 
                        // jika gagal karena script error
                        else {
                            // tampilkan pesan gagal delete data dan error result
                            swal("Gagal!", "Data User tidak bisa dihapus. Script error : "+ result, "error");
                        }
                    }
                });
            });
        });
        // ===============================================================================================
    });
    </script>
<?php } ?>