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
    			<!-- judul halaman laporan penjualan -->
    			<i class="fas fa-file-alt title-icon"></i> Laporan Penjualan
    		</h5>
    	</div>
    </div>

    <div class="border mb-4"></div>

    <div class="row">
        <div class="col-md-12">
            <!-- form filter data penjualan -->
            <form id="formFilter">
            	<div class="row">
    				<div class="col">
    					<div class="form-group mb-0">
    						<label>Filter : </label>
    					</div>
    				</div>
    			</div>

            	<div class="row">
    				<div class="col">
    		            <div class="form-group">
    		                <input type="text" class="form-control date-picker" data-date-format="dd-mm-yyyy" id="tgl_awal" name="tgl_awal" placeholder="Tanggal Awal" autocomplete="off" required>
    		            </div>
    				</div>

    				<div class="col">
    					<div class="form-group">
    		                <input type="text" class="form-control date-picker" data-date-format="dd-mm-yyyy" id="tgl_akhir" name="tgl_akhir" placeholder="Tanggal Akhir" autocomplete="off" required>
    		            </div>
    				</div>

    				<div class="col">
    					<div class="form-group">
    		                <button type="button" class="btn btn-primary" id="btnTampil">Tampilkan</button>
    			  		</div>
    				</div>

    				<div class="col">
    					<div class="form-group right">
    		                <!-- tombol cetak data ke format pdf -->
                            <a class="btn btn-warning mr-2 mb-3" id="btnCetak" href="javascript:void(0);" role="button">
                                <i class="fas fa-print title-icon"></i> Cetak 
                            </a>
                            <!-- tombol export data ke format pdf -->
                            <a class="btn btn-success mb-3" id="btnExport" href="javascript:void(0);" role="button">
                                <i class="fas fa-file-export title-icon"></i> Export 
                            </a>
    			  		</div>
    				</div>
    			</div>
            </form>
        </div>
    </div>

    <div class="border mt-2 mb-4"></div>

    <div class="row">
        <div id="tabelLaporan" class="col-md-12">
    		<!-- Tabel untuk menampilkan laporan data penjualan dari database -->
            <table class="table table-striped table-bordered" style="width:100%">
    			<!-- judul kolom pada bagian kepala (atas) tabel -->
                <thead>
                    <tr>
                        <th class="center">No.</th>
                        <th class="center">Tanggal</th>
                        <th class="center">Nama Pelanggan</th>
                        <th class="center">No. HP</th>
                        <th class="center">Pulsa</th>
                        <th class="center">Jumlah Bayar</th>
                    </tr>
                </thead>
                <!-- parameter untuk memuat isi tabel -->
                <tbody id="loadData"></tbody>
            </table>
        </div>
    </div>

    <script type="text/javascript">
    $(document).ready(function(){
        // ======================================= initiate plugin =======================================
        // datepicker plugin
        $('.date-picker').datepicker({
            autoclose: true,
            todayHighlight: true
        });
        // ===============================================================================================

        // =========================================== onload ============================================
    	// sembunyikan tabel laporan
        $('#tabelLaporan').hide();
        // sembunyikan tombol cetak
        $('#btnCetak').hide();
        // sembunyikan tombol export
        $('#btnExport').hide();
        // ===============================================================================================

        // ============================================ view =============================================
        // Tampilkan tabel laporan data penjualan
        $('#btnTampil').click(function(){
            // validasi form input
            // jika tanggal awal kosong
            if ($('#tgl_awal').val()==""){
                // focus ke input tanggal awal
                $( "#tgl_awal" ).focus();
                // tampilkan peringatan data tidak boleh kosong
                swal("Peringatan!", "Tanggal awal tidak boleh kosong.", "warning");
            }
            // jika tanggal akhir kosong
            else if ($('#tgl_akhir').val()==""){
                // focus ke input tanggal akhir
                $( "#tgl_akhir" ).focus();
                // tampilkan peringatan data tidak boleh kosong
                swal("Peringatan!", "Tanggal akhir tidak boleh kosong.", "warning");
            } 
            // jika semua tanggal sudah diisi, jalankan perintah untuk menampilkan data
            else {
            	// membuat variabel untuk menampung data dari form filter
            	var data = $('#formFilter').serialize();

            	$.ajax({
    				type : "GET",                           	// mengirim data dengan method GET 
    				url  : "modules/laporan/get_data.php",  	// proses get data penjualan berdasarkan tanggal
    				data : data,                 				// data yang dikirim
    	            success: function(data){                  	// ketika proses get data selesai
    			        // tampilkan tabel laporan
    			        $('#tabelLaporan').show();
    			        // tampilkan data penjualan
    			        $('#loadData').html(data);
                        // tampilkan tombol cetak
                        $('#btnCetak').show();
    				    // tampilkan tombol export
    				    $('#btnExport').show();
    	            }
    	        });
            }
        });
        // ===============================================================================================
         
        // =========================================== Cetak =============================================
        $('#btnCetak').click(function(){
            // ambil value "tgl_awal" dan "tgl_akhir" dari form filter
            var tgl_awal  = $('#tgl_awal').val();
            var tgl_akhir = $('#tgl_akhir').val();
            // buka file "cetak.php" dan kirim "value"
            window.open('cetak-'+ tgl_awal +'-sd-'+ tgl_akhir);
        });
        // ===============================================================================================
        
        // =========================================== Export ============================================
        $('#btnExport').click(function(){
            // ambil value "tgl_awal" dan "tgl_akhir" dari form filter
            var tgl_awal  = $('#tgl_awal').val();
            var tgl_akhir = $('#tgl_akhir').val();
            // arahkan ke file "export.php" dan kirim "value"
            location.href = "export-"+ tgl_awal +"-sd-"+ tgl_akhir;
            // tampilkan pesan sukses export data
            setTimeout('swal("Sukses!", "Laporan Data Penjualan berhasil diexport.", "success");', 1500);
        });
        // ===============================================================================================
    });
    </script>
<?php } ?>