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
    			<!-- judul halaman tampil data pelanggan -->
    			<i class="fas fa-user-friends title-icon"></i> Data Pelanggan
    			<!-- tombol tambah, dan export data pelanggan -->
                <div class="float-right">
                    <a class="btn btn-primary mr-2" id="btnTambah" href="javascript:void(0);" data-toggle="modal" data-target="#modalPelanggan" role="button">
                        <i class="fas fa-plus"></i> Tambah 
                    </a>
                    <a class="btn btn-success" id="btnExport" href="javascript:void(0);" role="button">
                        <i class="fas fa-file-export title-icon"></i> Export 
                    </a>
                </div>
    		</h5>
    	</div>
    </div>

    <div class="border mb-4"></div>

    <div class="row">
        <div class="col-md-12">
            <!-- Tabel pelanggan untuk menampilkan data pelanggan dari database -->
            <table id="tabel-pelanggan" class="table table-striped table-bordered" style="width:100%">
    			<!-- judul kolom pada bagian kepala (atas) tabel -->
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>ID Pelanggan</th>   <!-- kolom "ID Pelanggan" disembunyikan -->
                        <th>Nama Pelanggan</th>
                        <th>No. HP</th>
                        <th></th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>

    <!-- Modal form untuk entri dan ubah data pelanggan -->
    <div class="modal fade" id="modalPelanggan" tabindex="-1" role="dialog" aria-labelledby="modalPelanggan" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
            	<!-- judul form data pelanggan -->
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-edit title-icon"></i><span id="modalLabel"></span></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
    			<!-- isi form data pelanggan -->
                <form id="formPelanggan">
                    <div class="modal-body">
                        <input type="hidden" id="id_pelanggan" name="id_pelanggan">

                        <div class="form-group">
                            <label>Nama Pelanggan</label>
                            <input type="text" class="form-control" id="nama" name="nama" autocomplete="off">
                        </div>

                        <div class="form-group">
                            <label>No. HP</label>
                            <input type="text" class="form-control" id="no_hp" name="no_hp" maxlength="13" onKeyPress="return goodchars(event,'0123456789',this)" autocomplete="off">
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
        var table = $('#tabel-pelanggan').DataTable( {
            "processing": true,                         // tampilkan loading saat proses data
            "serverSide": true,                         // server-side processing
            "ajax": 'modules/pelanggan/data.php',       // panggil file "data.php" untuk menampilkan data pelanggan dari database
            // menampilkan data
            "columnDefs": [ 
                { "targets": 0, "data": null, "orderable": false, "searchable": false, "width": '30px', "className": 'center' },
                { "targets": 1, "visible": false },     // kolom "id_pelanggan" disembunyikan
                { "targets": 2, "width": '180px' },
                { "targets": 3, "width": '100px', "className": 'center' },
                {
                  "targets": 4, "data": null, "orderable": false, "searchable": false, "width": '70px', "className": 'center',
                  // tombol ubah dan hapus
                  "render": function(data, type, row) {
                      var btn = "<a style=\"margin-right:7px\" title=\"Ubah\" class=\"btn btn-primary btn-sm getUbah\" href=\"javascript:void(0);\"><i class=\"fas fa-edit\"></i></a><a title=\"Hapus\" class=\"btn btn-danger btn-sm btnHapus\" href=\"javascript:void(0);\"><i class=\"fas fa-trash\"></i></a>";
                      return btn;
                  } 
                } 
            ],
            "order": [[ 1, "desc" ]],           // urutkan data berdasarkan "id_pelanggan" secara descending
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
            $('#formPelanggan')[0].reset();
            // judul form
            $('#modalLabel').text('Entri Data Pelanggan');
        });

        // Tampilkan Modal Form Ubah Data
        $('#tabel-pelanggan tbody').on( 'click', '.getUbah', function (){
            // judul form
            $('#modalLabel').text('Ubah Data Pelanggan');
            // ambil data dari datatables 
            var data = table.row( $(this).parents('tr') ).data();
            // membuat variabel untuk menampung data "id_pelanggan"
            var id_pelanggan = data[ 1 ];
            
            $.ajax({
                type     : "GET",                                       // mengirim data dengan method GET 
                url      : "modules/pelanggan/get_data.php",            // proses get data pelanggan berdasarkan "id_pelanggan"
                data     : {id_pelanggan:id_pelanggan},                 // data yang dikirim
                dataType : "JSON",                                      // tipe data JSON
                success: function(result){                              // ketika proses get data selesai
                    // tampilkan modal ubah data pelanggan
                    $('#modalPelanggan').modal('show');
                    // tampilkan data pelanggan
                    $('#id_pelanggan').val(result.id_pelanggan);
                    $('#nama').val(result.nama);
                    $('#no_hp').val(result.no_hp);
                }
            });
        });
        // ===============================================================================================

        // ====================================== Insert dan Update ======================================
        // Proses Simpan Data
        $('#btnSimpan').click(function(){
            // validasi form input
            // jika nama pelanggan kosong
            if ($('#nama').val()==""){
                // focus ke input nama pelanggan
                $( "#nama" ).focus();
                // tampilkan peringatan data tidak boleh kosong
                swal("Peringatan!", "Nama Pelanggan tidak boleh kosong.", "warning");
            }
            // jika no. hp kosong
            else if ($('#no_hp').val()==""){
                // focus ke input no. hp
                $( "#no_hp" ).focus();
                // tampilkan peringatan data tidak boleh kosong
                swal("Peringatan!", "No. HP tidak boleh kosong.", "warning");
            }
            // jika semua data sudah terisi, jalankan perintah insert / update data
            else {
                // jika form entri data pelanggan yang ditampilkan, jalankan perintah insert
                if ($('#modalLabel').text()=="Entri Data Pelanggan") {
                    // membuat variabel untuk menampung data dari form entri data pelanggan
                    var data = $('#formPelanggan').serialize();

                    $.ajax({
                        type : "POST",                                  // mengirim data dengan method POST 
                        url  : "modules/pelanggan/insert.php",          // proses insert data
                        data : data,                                    // data yang dikirim
                        success: function(result){                      // ketika proses insert data selesai
                            // jika berhasil
                            if (result==="sukses") {
                                // reset form
                                $('#formPelanggan')[0].reset();
                                // tutup modal entri data pelanggan
                                $('#modalPelanggan').modal('hide');
                                // tampilkan pesan sukses insert data
                                swal("Sukses!", "Data Pelanggan berhasil disimpan.", "success");
                                // tampilkan view data pelanggan
                                var table = $('#tabel-pelanggan').DataTable(); 
                                table.ajax.reload( null, false );
                            } 
                            // jika gagal
                            else {
                                // tampilkan pesan gagal insert data dan error result
                                swal("Gagal!", "Data Pelanggan tidak bisa disimpan. Query Error : "+ result, "error");
                            }
                        }
                    });
                    return false;
                }
                // jika form ubah data pelanggan yang ditampilkan, jalankan perintah update 
                else if ($('#modalLabel').text()=="Ubah Data Pelanggan") {
                    // membuat variabel untuk menampung data dari form ubah data pelanggan
                    var data = $('#formPelanggan').serialize();

                    $.ajax({
                        type : "POST",                                  // mengirim data dengan method POST 
                        url  : "modules/pelanggan/update.php",          // proses update data
                        data : data,                                    // data yang dikirim
                        success: function(result){                      // ketika proses update data selesai
                            // jika berhasil
                            if (result==="sukses") {
                                // reset form
                                $('#formPelanggan')[0].reset();
                                // tutup modal ubah data pelanggan
                                $('#modalPelanggan').modal('hide');
                                // tampilkan pesan sukses update data
                                swal("Sukses!", "Data Pelanggan berhasil diubah.", "success");
                                // tampilkan view data pelanggan
                                var table = $('#tabel-pelanggan').DataTable(); 
                                table.ajax.reload( null, false );
                            } 
                            // jika gagal
                            else {
                                // tampilkan pesan gagal update data dan error result
                                swal("Gagal!", "Data Pelanggan tidak bisa diubah. Query Error : "+ result, "error");
                            }
                        }
                    });
                    return false;
                }   
            }
        });
        // ===============================================================================================
        
        // =========================================== Delete ============================================
        $('#tabel-pelanggan tbody').on( 'click', '.btnHapus', function (){
            // ambil data dari datatables 
            var data = table.row( $(this).parents('tr') ).data();
            // tampilkan notifikasi saat akan menghapus data
            swal({
                title: "Apakah Anda Yakin?",
                text: "Anda akan menghapus data Pelanggan : "+ data[ 2 ],
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Ya, Hapus!",
                closeOnConfirm: false
            }, 
            // jika dipilih ya, maka jalankan perintah delete data
            function () {
                // membuat variabel untuk menampung data pelanggan
                var id_pelanggan = data[ 1 ];
                var nama         = data[ 2 ];
                var no_hp        = data[ 3 ];

                $.ajax({
                    type : "POST",                                              // mengirim data dengan method POST 
                    url  : "modules/pelanggan/delete.php",                      // proses delete data 
                    data : {id_pelanggan:id_pelanggan, nama:nama, no_hp:no_hp}, // data yang dikirim
                    success: function(result){                                  // ketika proses delete data selesai
                        // jika berhasil
                        if (result==="sukses") {
                            // tampilkan pesan sukses delete data
                            swal("Sukses!", "Data Pelanggan berhasil dihapus.", "success");
                            // tampilkan view data pelanggan
                            var table = $('#tabel-pelanggan').DataTable(); 
                            table.ajax.reload( null, false );
                        } 
                        // jika gagal karena data "pelanggan" sudah ada di tabel "penjualan"
                        else if (result==="gagal") {
                            // tampilkan pesan gagal delete data
                            swal("Gagal!", "Data Pelanggan "+ data[ 2 ] +" tidak bisa dihapus karena data Pelanggan tersebut sudah tercatat pada data Penjualan.", "error");
                        } 
                        // jika gagal karena script error
                        else {
                            // tampilkan pesan gagal delete data dan error result
                            swal("Gagal!", "Data Pelanggan tidak bisa dihapus. Query Error : "+ result, "error");
                        }
                    }
                });
            });
        });
        // ===============================================================================================
        
        // =========================================== Export ============================================
        $('#btnExport').click(function(){
            // arahkan ke file "export.php"
            location.href = "modules/pelanggan/export.php";
            // tampilkan pesan sukses export data
            setTimeout('swal("Sukses!", "Data Pelanggan berhasil diexport.", "success");', 1500);
        });
        // ===============================================================================================
    });
    </script>
<?php } ?>