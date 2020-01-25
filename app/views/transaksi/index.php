<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "test";
        $conn= new mysqli($servername, $username, $password, $database);
        if (mysqli_connect_error()) {
        echo "Failed to connect to MySQL: " . mysqli_connect_error();
        exit();
         }
$no=0;
$daftar='';
$q="SELECT * FROM tb_transaksi_fpgrowth ORDER BY id_transaksi";
$q=mysqli_query($conn,$q);
if(mysqli_num_rows($q) > 0){
	while($h=mysqli_fetch_array($q)){
		$no++;
		$id=$h['id_transaksi'];
		$barang=array();
		$qq=mysqli_query($conn, "SELECT tb_barang_fpgrowth.nama_barang FROM tb_transaksi_detail INNER JOIN tb_barang_fpgrowth ON tb_transaksi_detail.id_brg=tb_barang_fpgrowth.id_brg WHERE tb_transaksi_detail.id_transaksi='".$id."' ORDER BY tb_barang_fpgrowth.nama_barang");
		
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
<div class="main" style=" margin-left: 200px">
<div class="row">
	<div class="container">
    <h1>Daftar Transaksi</h1>
		<div style="height:5px;"></div>
		<table class="table table-striped table-hover table-bordered">
			<thead>
              <tr>
                <th class="text-center" scope="col">NO</th>
                <th scope="col">Kode Transaksi</th>
                <th scope="col">Nama Barang</th>
              </tr>
            </thead>
			<tbody>
            <?= $daftar;?>
			</tbody>
		</table>
	</div>
</div>
</div>