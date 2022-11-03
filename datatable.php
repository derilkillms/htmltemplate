

<link rel="stylesheet" type="text/css" href="asset/jquery.dataTables.min.css">
<script type="text/javascript" src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.10.22/js/jquery.dataTables.min.js"></script>

<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.6.5/js/dataTables.buttons.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.6.5/js/buttons.flash.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.6.5/js/buttons.html5.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.6.5/js/buttons.print.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.6.5/js/buttons.colVis.min.js"></script>

<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>

<style type="text/css">
	div.dt-buttons {
		float: right;
	}
	#tfilterinput tr:nth-child(even){background-color: #f2f2f2}
	.tombolreset {
		font-size: 16px;
		border:0;
		background-color: #F13241;
		padding: 12px 25px !important;
		margin-top: 12px !important;
		border-radius: 7px;
		color: #fff !important;
	}

	.tombolreset:hover {
		font-size: 16px;
		border:0;
		background-color: #f6505d;
		padding: 12px 25px !important;
		margin-top: 12px !important;
		border-radius: 7px;
		color: #fff !important;
	}
</style>

<br>
<div class="container">
	<div class="card card-body">
		<table id="tfilterinput" class="table">
			
			<tr>
				
				<th >Nama</th>
				<th >Kursus</th>
				<th>Mulai Akses</th>
				<th>Akhir Akses</th>
				
				
			</tr>
			
			
			<tr>
				
				<td width="25%"><input type="text" id="nama" name="nama" class="form-control"></td>
				<td width="25%">
					<select id="kursus" name="kursus" class="form-control form-disable select2" >
						<option value="">--Pilih--</option>
						<?php
						$getkursus = $DB->get_records_sql("SELECT * FROM mdl_course");
						foreach ($getkursus as $key => $value) {
							echo "<option value='".$value->id."'>".$value->fullname."</option>";
						}


						?>
						
						
					</select>
				</td>
				<td width="25%"><input type="date" id="awal" name="awal" class="form-control"></td>
				<td width="25%"><input type="date" id="akhir" name="akhir" class="form-control"></td>
				
				
			</tr>
			
		</table>
		<div class="col col-3">
			<button id="tombolreset" class="tombolreset" >
				Reset
			</button>
		</div>
	</div>
</div>
<div class="container">
	<div class="card card-body">
		<div class="col col-6">
			<!-- <a href="export.php" class="btn btn-primary">Export</a> -->
		</div>
		<br>
		
		<form id="form-peserta">
			<table  id="example" border="1" class="display" style="width:100%">
				<thead align="center">
					<tr>
						<th>No</th>
						<th>Username</th>
						<th>NIK</th>
						<th>Nama Lengkap</th>
						<th>Departemen</th>
						
					</tr>
				</thead>

				<tbody >


					

				</tbody>
				<tfoot>
					
				</tfoot>

			</table>
		</form>
		<span class="loading"></span>
	</div>
</div>



<script type="text/javascript">

	$(document).ready(function() {
		document.getElementById("akhir").disabled = true;

		$('select').select2()
		var table =	$('#example').DataTable({
			"language": {
				"url": "//cdn.datatables.net/plug-ins/1.10.21/i18n/Indonesian.json"
			},
			"processing": true,
			"serverSide": true,
			"ordering":false,
			"scrollX": true,
			"lengthMenu": [[10, 25, 50, 100, 1000, 2000], [10, 25, 50, 100, 1000, 2000]],


			"ajax": {
				url: '<?=$CFG->wwwroot?>/laporankelas/api.php',
				type: 'post',
				data: function (data) {
					data.kursus = $('#kursus').val();
					data.awal = $('#awal').val();
					data.akhir = $('#akhir').val();
				}
			},
			"lengthMenu": [[10, 25, 50, 100, 500, 1000, 10000,10000000], [10, 25, 50, 100, 500, 1000, 10000,"All"]],

                // dom: 'Bfrltip',
                dom: '<"top"rl>Brt<"bottom"pi><"clear">',
                buttons: [

                {
                	extend: 'copyHtml5',
                	exportOptions: {
                		columns: [ 0, 1, 2, 3, 4 ]
                	}
                },
                {
                	extend: 'excelHtml5',
                	exportOptions: {
                		columns: [ 0, 1, 2, 3, 4 ]
                	}
                }
                ]
            } );

		$('#nama').on( 'keyup', function () {
			table.search( this.value ).draw();
		} );

		$('#kursus').on( 'change', function () {
			table.draw();
		} );
		$('#awal').on( 'change', function () {
			document.getElementById("akhir").disabled = false;

			table.draw();
		} );
		$('#akhir').on( 'change', function () {
			table.draw();
		} );
		$('#tombolreset').on( 'click', function () {
			$('#nama').val("");
			document.getElementById("akhir").disabled = true;

			$('#kursus').val(null).trigger('change');
			$('#awal').val(null).trigger('change');
			$('#akhir').val(null).trigger('change');

			table.search("").draw();
		} );

	} );
</script>

