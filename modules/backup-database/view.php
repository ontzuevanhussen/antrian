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
    			<!-- judul halaman backup database -->
    			<i class="fas fa-database title-icon"></i> Backup Database
    		</h5>
    	</div>
    </div>

    <div class="border mb-4"></div>

    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="alert alert-info mb-4" role="alert">
                        <i class="fas fa-info-circle title-icon"></i> Klik tombol "Backup Database" untuk backup semua data pada database.
                    </div>
                    <a class="btn btn-primary" id="btnBackup" href="javascript:void(0);" role="button">
                        <i class="fas fa-download title-icon"></i> Backup Database 
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="border mb-4"></div>

    <div class="row">
        <div class="col-md-12">
            <!-- Tabel "backup database" untuk menampilkan data "backup database" dari database -->
            <table id="tabel-backupdb" class="table table-striped table-bordered" style="width:100%">
    			<!-- judul kolom pada bagian kepala (atas) tabel -->
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Nama File</th>
                        <th>Tanggal Backup</th>
                        <th>Ukuran File</th>
                        <th></th>
                    </tr>
                </thead>
            </table>
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
        var table = $('#tabel-backupdb').DataTable( {
            "processing": true,                         // tampilkan loading saat proses data
            "serverSide": true,                         // server-side processing
            "ajax": 'modules/backup-database/data.php', // panggil file "data.php" untuk menampilkan data "backup database" dari database
            // menampilkan data
            "columnDefs": [ 
                { "targets": 0, "data": null, "orderable": false, "searchable": false, "width": '30px', "className": 'center' },
                { "targets": 1, "width": '200px' },
                { "targets": 2, "width": '150px', "className": 'center' },
                { "targets": 3, "width": '120px', "className": 'center' },
                {
                  "targets": 4, "data": null, "orderable": false, "searchable": false, "width": '70px', "className": 'center',
                  // tombol ubah dan hapus
                  "render": function(data, type, row) {
                      var btn = "<a style=\"margin-right:7px\" title=\"Download\" class=\"btn btn-primary btn-sm\" href=\"database/"+data[ 1 ]+"\"><i class=\"fas fa-download\"></i></a><a title=\"Hapus\" class=\"btn btn-danger btn-sm btnHapus\" href=\"javascript:void(0);\"><i class=\"fas fa-trash\"></i></a>";
                      return btn;
                  } 
                } 
            ],
            "order": [[ 2, "desc" ]],           // urutkan data berdasarkan "tanggal backup" secara descending
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

        // ======================================= Backup Database =======================================
        // Proses Backup Database
        $('#btnBackup').click(function(){
            // tampilkan notifikasi saat akan backup database
            swal({
                title: "Apakah Anda Yakin?",
                text: "Anda akan membackup semua data pada database.",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Ya, Backup!",
                closeOnConfirm: false,
                showLoaderOnConfirm: true,
            // jika dipilih ya, maka jalankan perintah backup database
            }, function () {
                $.ajax({
                    url  : "modules/backup-database/backup.php",            // proses backup database
                    success: function(result){                              // ketika proses backup database selesai
                        // jika berhasil
                        if (result==="sukses") {
                            // set waktu 5 detik
                            setTimeout(function () {
                                // tampilkan pesan sukses backup database
                                swal("Sukses!", "Backup database berhasil.", "success");
                                // tampilkan view data backup database
                                var table = $('#tabel-backupdb').DataTable(); 
                                table.ajax.reload( null, false );
                            }, 5000);
                        } 
                        // jika gagal
                        else {
                            // tampilkan pesan gagal backup database dan error result
                            swal("Gagal!", "Backup database gagal. Script error : "+ result, "error");
                        }
                    }
                });
            });
        });
        // ===============================================================================================

        // =========================================== Delete ============================================
        $('#tabel-backupdb tbody').on( 'click', '.btnHapus', function (){
            // ambil data dari datatables 
            var data = table.row( $(this).parents('tr') ).data();
            // tampilkan notifikasi saat akan menghapus data
            swal({
                title: "Apakah Anda Yakin?",
                text: "Anda akan menghapus backup database : "+ data[ 1 ],
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Ya, Hapus!",
                closeOnConfirm: false
            }, 
            // jika dipilih ya, maka jalankan perintah delete data
            function () {
                // membuat variabel untuk menampung data "backup database"
                var id          = data[ 4 ];
                var nama_file   = data[ 1 ];
                var ukuran_file = data[ 3 ];

                $.ajax({
                    type : "POST",                                                  // mengirim data dengan method POST 
                    url  : "modules/backup-database/delete.php",                    // proses delete data 
                    data : {id:id, nama_file:nama_file, ukuran_file:ukuran_file},   // data yang dikirim
                    success: function(result){                                      // ketika proses delete data selesai
                        // jika berhasil
                        if (result==="sukses") {
                            // tampilkan pesan sukses delete data
                            swal("Sukses!", "Data backup database berhasil dihapus.", "success");
                            // tampilkan view data "backup database"
                            var table = $('#tabel-backupdb').DataTable(); 
                            table.ajax.reload( null, false );
                        }
                        // jika gagal
                        else {
                            // tampilkan pesan gagal delete data dan error result
                            swal("Gagal!", "Data backup database tidak bisa dihapus. Script error : "+ result, "error");
                        }
                    }
                });
            });
        });
        // ===============================================================================================
    });
    </script>
<?php } ?>