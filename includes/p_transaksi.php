<?php if(!defined('fpgrowthdagadu')){ exit(); }?>
<?php
if(!isset($_SESSION['LOGIN_ID'])){
	exit("<script>location.href='".$urlaplikasi."';</script>");
}
$link_list=$urlaplikasi.'index.php?=transaksi';
$link_update=$urlaplikasi.'index.php?=update_transaksi';
$error='';
$no=0;
$daftar='';
$q="select * from tb_transaksi_fpgrowth order by id_transaksi";
$q=mysqli_query($con,$q);
if(mysqli_num_rows($q) > 0){
	while($h=mysqli_fetch_array($q)){
		$no++;
		$id=$h['id_transaksi'];
		$barang=array();
		$qq=mysqli_query($con, "SELECT tb_barang_fpgrowth.nama_barang from tb_transaksi_detail inner join tb_barang_fpgrowth on tb_transaksi_detail.id_brg=tb_barang_fpgrowth.id_brg where tb_transaksi_detail.id_transaksi='".$id."' order by tb_barang_fpgrowth.nama_barang");
		while($hh=mysqli_fetch_array($qq)){
			$barang[]=$hh['nama_barang'];
		}
		$daftar.='
		<tr>
			<td class="text-center">'.$no.'</td>
			<td>'.htmlspecialchars($h['kode_transaksi']).'</td>
			<td>'.implode(', ', $barang).'</td>
		</tr>
		';
	}
}
?>
<div class="row">
	<div class="col-lg-12">
		<h3 class="page-header" style="margin-top:0;">Data Transaksi</h3>
	</div>
</div>
<div class="row">
	<div class="col-lg-12">
		<div style="height:20px;clear:both;"></div>
		<table id="table_browse" class="table table-striped table-hover table-bordered">
			<thead>
              <tr>
                <th class="text-center" scope="col">No</th>
                <th scope="col">Kode Transaksi</th>
                <th scope="col">Barang</th>
              </tr>
            </thead>
			<tbody>
				<?= $daftar;?>
			</tbody>
		</table>
			

	</div>
</div>