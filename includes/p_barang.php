<?php if(!defined('fpgrowthdagadu')){ exit(); }?>
<?php
$link_list=$urlaplikasi.'index.php?=barang';
$link_update=$urlaplikasi.'index.php?=update_barang';

$error='';
$daftar='';

$no=0;
					
// Buat query untuk menampilkan data barang sesuai limit yang ditentukan
$q=mysqli_query($con,"SELECT * FROM tb_barang_fpgrowth order by id_brg"); //memberikan perintah query ke mysqlserver
if(mysqli_num_rows($q) > 0){ //mendapatkan jumlah baris query yg dihasilkan oleh mysql_query
	while($h=mysqli_fetch_array($q)){ //menampilkan data mysql
		$no++; // Untuk penomoran tabel
		$id=$h['id_brg'];

		$daftar.='
		<tr>
			<td class="text-center">'.$no.'</td>
			<td>'.htmlspecialchars($h['kode_barang']).'</td>
			<td>'.htmlspecialchars($h['nama_barang']).'</td>
		</tr>';
	}
}
?>

<div class="row">
	<div class="col-lg-12">
		<h3 class="page-header" style="margin-top:0;">Data Barang</h3>
	</div>
</div>
<div class="row">
	<div class="col-lg-12">

		<div class="form-inline">
		<div class="form-group"><a href="<?php echo $link_update;?>" class="btn btn-sm btn-primary"><span class="fas fa-plus fa-sm"></span> Tambah Data</a></div>
		</div>

		<div style="height:10px;clear:both;"></div>
		<table class="table table-striped table-hover table-bordered">
			<thead>
              <tr>
                <th class="text-center" scope="col">No</th>
                <th scope="col">Kode Barang</th>
                <th scope="col">Nama Barang</th>
              </tr>
            </thead>
			<tbody>
				<?php echo $daftar;?>
			</tbody>
		</table>
	</div>
</div>