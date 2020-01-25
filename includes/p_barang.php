<?php if(!defined('fpgrowthdagadu')){ exit(); }?>
<?php
$link_list=$urlaplikasi.'index.php?=barang';
$error='';
$daftar='';

$no=0;					
// Buat query untuk menampilkan data barang sesuai limit yang ditentukan
$q=mysqli_query($con,"SELECT * FROM b2 order by b2_id"); //memberikan perintah query ke mysqlserver
// $q=mysqli_query($con,"SELECT * FROM tb_barang_fpgrowth order by id_brg"); 
if(mysqli_num_rows($q) > 0){ //mendapatkan jumlah baris query yg dihasilkan oleh mysql_query
	while($h=mysqli_fetch_array($q)){ //menampilkan data mysql
		$no++; // Untuk penomoran tabel
		$id=$h['b2_id'];

		$daftar.='
		<tr>
			<td class="text-center">'.($h['b2_id']).'</td>
			<td>'.htmlspecialchars($h['sing_b2']).'</td>
			<td>'.htmlspecialchars($h['nama_b2']).'</td>
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
		<div style="height:10px;clear:both;"></div>
		<table class="table table-striped table-hover table-bordered">
			<thead>
              <tr>
                <th class="text-center" scope="col">ID</th>
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