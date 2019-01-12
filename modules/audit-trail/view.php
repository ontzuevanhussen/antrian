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
    			<!-- judul halaman audit trail -->
    			<i class="fas fa-history title-icon"></i> Audit Trail
    		</h5>
    	</div>
    </div>

    <div class="border mb-4"></div>

    <div class="row">
        <div class="col-md-12">
            <!-- Tabel audit trail untuk menampilkan data audit trail dari database -->
            <table id="tabel-audit" class="table table-striped table-bordered" style="width:100%">
    			<!-- judul kolom pada bagian kepala (atas) tabel -->
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>ID</th>             <!-- kolom "id" disembunyikan -->
                        <th>Tanggal</th>
                        <th>Username</th>
                        <th>Aksi</th>
                        <th>Keterangan</th>
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
        var table = $('#tabel-audit').DataTable( {
            "processing": true,                         // tampilkan loading saat proses data
            "serverSide": true,                         // server-side processing
            "ajax": 'modules/audit-trail/data.php',     // panggil file "data.php" untuk menampilkan data audit trail dari database
            // menampilkan data
            "columnDefs": [ 
                { "targets": 0, "data": null, "orderable": false, "searchable": false, "width": '30px', "className": 'center' },
                { "targets": 1, "visible": false },                         // kolom "id" disembunyikan
                { "targets": 2, "width": '100px', "className": 'center' },
                { "targets": 3, "width": '140px', "className": 'center' },
                { "targets": 4, "width": '90px', "className": 'center' },
                { "targets": 5, "width": '250px' }
            ],
            "order": [[ 1, "desc" ]],           // urutkan data berdasarkan "id" secara ascending
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
    });
    </script>
<?php } ?>