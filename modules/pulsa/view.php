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
    			<!-- judul halaman tampil data pulsa -->
    			<i class="fas fa-tablet-alt title-icon"></i> Data Pulsa
    			<!-- tombol tambah, dan export data pulsa -->
                <div class="float-right">
                    <a class="btn btn-primary mr-2" id="btnTambah" href="javascript:void(0);" data-toggle="modal" data-target="#modalPulsa" role="button">
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
            <!-- Tabel pulsa untuk menampilkan data pulsa dari database -->
            <table id="tabel-pulsa" class="table table-striped table-bordered" style="width:100%">
    			<!-- judul kolom pada bagian kepala (atas) tabel -->
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Provider</th>
                        <th>Nominal</th>
                        <th>Harga</th>
                        <th></th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>

    <!-- Modal form untuk entri dan ubah data pulsa -->
    <div class="modal fade" id="modalPulsa" tabindex="-1" role="dialog" aria-labelledby="modalPulsa" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
            	<!-- judul form data pulsa -->
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-edit title-icon"></i><span id="modalLabel"></span></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
    			<!-- isi form data pulsa -->
                <form id="formPulsa">
                    <div class="modal-body">
                        <input type="hidden" id="id_pulsa" name="id_pulsa">

                        <div class="form-group">
                            <label>Provider</label>
                            <input type="text" class="form-control" id="provider" name="provider" autocomplete="off">
                        </div>

                        <div class="form-group">
                            <label>Nominal</label>
                            <input type="text" class="form-control" id="nominal" name="nominal" onKeyPress="return goodchars(event,'0123456789.',this)" autocomplete="off">
                        </div>

                        <div class="form-group">
                            <label>Harga</label>
                            <div class="input-group mb-2">
                                <div class="input-group-prepend"><div class="input-group-text">Rp.</div></div>
                                <input type="text" class="form-control" id="harga" name="harga" onKeyPress="return goodchars(event,'0123456789.',this)" autocomplete="off">
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
        var table = $('#tabel-pulsa').DataTable( {
            "processing": true,                         // tampilkan loading saat proses data
            "serverSide": true,                         // server-side processing
            "ajax": 'modules/pulsa/data.php',           // panggil file "data.php" untuk menampilkan data pulsa dari database
            // menampilkan data
            "columnDefs": [ 
                { "targets": 0, "data": null, "orderable": false, "searchable": false, "width": '30px', "className": 'center' },
                { "targets": 1, "width": '200px' },
                { "targets": 2, "width": '80px', "className": 'right' },
                { "targets": 3, "width": '80px', "className": 'right' },
                {
                  "targets": 4, "data": null, "orderable": false, "searchable": false, "width": '70px', "className": 'center',
                  // tombol ubah dan hapus
                  "render": function(data, type, row) {
                      var btn = "<a style=\"margin-right:7px\" title=\"Ubah\" class=\"btn btn-primary btn-sm getUbah\" href=\"javascript:void(0);\"><i class=\"fas fa-edit\"></i></a><a title=\"Hapus\" class=\"btn btn-danger btn-sm btnHapus\" href=\"javascript:void(0);\"><i class=\"fas fa-trash\"></i></a>";
                      return btn;
                  } 
                } 
            ],
            "order": [[ 1, "asc" ]],            // urutkan data berdasarkan "provider" secara ascending
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
            $('#formPulsa')[0].reset();
            // judul form
            $('#modalLabel').text('Entri Data Pulsa');
        });

        // Tampilkan Modal Form Ubah Data
        $('#tabel-pulsa tbody').on( 'click', '.getUbah', function (){
            // judul form
            $('#modalLabel').text('Ubah Data Pulsa');
            // ambil data dari datatables 
            var data = table.row( $(this).parents('tr') ).data();
            // membuat variabel untuk menampung data "id_pulsa"
            var id_pulsa = data[ 4 ];
            
            $.ajax({
                type     : "GET",                                   // mengirim data dengan method GET 
                url      : "modules/pulsa/get_data.php",            // proses get data pulsa berdasarkan "id_pulsa"
                data     : {id_pulsa:id_pulsa},                     // data yang dikirim
                dataType : "JSON",                                  // tipe data JSON
                success: function(result){                          // ketika proses get data selesai
                    // tampilkan modal ubah data pulsa
                    $('#modalPulsa').modal('show');
                    // tampilkan data pulsa
                    $('#id_pulsa').val(result.id_pulsa);
                    $('#provider').val(result.provider);
                    $('#nominal').val(result.nominal);
                    $('#harga').val(result.harga);
                }
            });
        });
        // ===============================================================================================

        // ====================================== Insert dan Update ======================================
        // Proses Simpan Data
        $('#btnSimpan').click(function(){
            // Validasi form input
            // jika provider kosong
            if ($('#provider').val()==""){
                // focus ke input provider pulsa
                $( "#provider" ).focus();
                // tampilkan peringatan data tidak boleh kosong
                swal("Peringatan!", "Provider tidak boleh kosong.", "warning");
            }
            // jika nominal kosong atau 0 (nol)
            else if ($('#nominal').val()=="" || $('#nominal').val()==0){
                // focus ke input nominal
                $( "#nominal" ).focus();
                // tampilkan peringatan data tidak boleh kosong atau 0 (nol)
                swal("Peringatan!", "Nominal tidak boleh kosong atau 0 (nol).", "warning");
            }
            // jika harga kosong atau 0 (nol)
            else if ($('#harga').val()=="" || $('#harga').val()==0){
                // focus ke input harga
                $( "#harga" ).focus();
                // tampilkan peringatan data tidak boleh kosong atau 0 (nol)
                swal("Peringatan!", "Harga tidak boleh kosong atau 0 (nol).", "warning");
            }
            // jika semua data sudah terisi, jalankan perintah insert / update data
            else {
                // jika form entri data pulsa yang ditampilkan, jalankan perintah insert
                if ($('#modalLabel').text()=="Entri Data Pulsa") {
                    // membuat variabel untuk menampung data dari form entri data pulsa
                    var data = $('#formPulsa').serialize();

                    $.ajax({
                        type : "POST",                              // mengirim data dengan method POST 
                        url  : "modules/pulsa/insert.php",          // proses insert data
                        data : data,                                // data yang dikirim
                        success: function(result){                  // ketika sukses menyimpan data
                            // jika berhasil
                            if (result==="sukses") {
                                // reset form
                                $('#formPulsa')[0].reset();
                                // tutup modal entri data pulsa
                                $('#modalPulsa').modal('hide');
                                // tampilkan pesan sukses insert data
                                swal("Sukses!", "Data Pulsa berhasil disimpan.", "success");
                                // tampilkan view data pulsa
                                var table = $('#tabel-pulsa').DataTable(); 
                                table.ajax.reload( null, false );
                            }
                            // jika gagal
                            else {
                                // tampilkan pesan gagal insert data dan error result
                                swal("Gagal!", "Data Pulsa tidak bisa disimpan. Query Error : "+ result, "error");
                            }
                        }
                    });
                    return false;
                }
                // jika form ubah data pulsa yang ditampilkan, jalankan perintah update 
                else if ($('#modalLabel').text()=="Ubah Data Pulsa") {
                    // membuat variabel untuk menampung data dari form ubah data pulsa
                    var data = $('#formPulsa').serialize();

                    $.ajax({
                        type : "POST",                              // mengirim data dengan method POST 
                        url  : "modules/pulsa/update.php",          // proses update data
                        data : data,                                // data yang dikirim
                        success: function(result){                  // ketika proses update data selesai
                            // jika berhasil
                            if (result==="sukses") {
                                // reset form
                                $('#formPulsa')[0].reset();
                                // tutup modal ubah data pulsa
                                $('#modalPulsa').modal('hide');
                                // tampilkan pesan sukses ubah data
                                swal("Sukses!", "Data Pulsa berhasil diubah.", "success");
                                // tampilkan view data pulsa
                                var table = $('#tabel-pulsa').DataTable(); 
                                table.ajax.reload( null, false );
                            }
                            // jika gagal
                            else {
                                // tampilkan pesan gagal update data dan error result
                                swal("Gagal!", "Data Pulsa tidak bisa diubah. Query Error : "+ result, "error");
                            }
                        }
                    });
                    return false;
                }
            }
        });      
        // ===============================================================================================
        
        // =========================================== Delete ============================================
        $('#tabel-pulsa tbody').on( 'click', '.btnHapus', function (){
            // ambil data dari datatables
            var data = table.row( $(this).parents('tr') ).data();
            // tampilkan notifikasi saat akan menghapus data
            swal({
                title: "Apakah Anda Yakin?",
                text: "Anda akan menghapus data pulsa Provider : "+ data[ 1 ] +" - Nominal : "+ data[ 2 ],
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Ya, Hapus!",
                closeOnConfirm: false
            }, 
            // jika dipilih ya, maka jalankan perintah delete data
            function () {
                // membuat variabel untuk menampung data pulsa
                var id_pulsa = data[ 4 ];
                var provider = data[ 1 ];
                var nominal  = data[ 2 ];
                var harga    = data[ 3 ];

                $.ajax({
                    type : "POST",                                                                  // mengirim data dengan method POST 
                    url  : "modules/pulsa/delete.php",                                              // proses delete data 
                    data : {id_pulsa:id_pulsa, provider:provider, nominal:nominal, harga:harga},    // data yang dikirim
                    success: function(result){                                                      // ketika proses delete data selesai
                        // jika berhasil
                        if (result==="sukses") {
                            // tampilkan pesan sukses delete data
                            swal("Sukses!", "Data Pulsa berhasil dihapus.", "success");
                            // tampilkan view data pulsa
                            var table = $('#tabel-pulsa').DataTable(); 
                            table.ajax.reload( null, false );
                        }
                        // jika gagal karena data "pulsa" sudah ada di tabel "penjualan"
                        else if (result==="gagal") {
                            // tampilkan pesan gagal delete data
                            swal("Gagal!", "Data Pulsa "+ data[ 1 ] +" - "+ data[ 2 ]  +" tidak bisa dihapus karena data Pulsa tersebut sudah tercatat pada data Penjualan.", "error");
                        } 
                        // jika gagal karena script error
                        else {
                            // tampilkan pesan gagal delete data dan error result
                            swal("Gagal!", "Data Pulsa tidak bisa dihapus. Query Error : "+ result, "error");
                        }
                    }
                });
            });
        });
        // ===============================================================================================
        
        // =========================================== Export ============================================
        $('#btnExport').click(function(){
            // arahkan ke file "export.php"
            location.href = "modules/pulsa/export.php";
            // tampilkan pesan sukses export data
            setTimeout('swal("Sukses!", "Data Pulsa berhasil diexport.", "success");', 1500);
        });
        // ===============================================================================================
    });
    </script>
<?php } ?>