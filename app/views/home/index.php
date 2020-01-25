<div class="main" style=" margin-left: 200px">
<!-- PROSES PEMASUKAN DATA DARI TABEL SIPANDU KE TABEL FPGROWTH -->
<?php 
var_dump($_POST);
if(isset($_POST['submit'])){ 
    $servername = "localhost";
    $username = "root";
    $password = "";
    $database = "test";
    $conn= new mysqli($servername, $username, $password, $database);
        if (mysqli_connect_error()) {
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
    exit();
    }
    $q=mysqli_query($conn, "INSERT INTO tb_barang_fpgrowth(id_brg, kode_barang, nama_barang) SELECT b2_id, sing_b2, nama_b2 FROM b2");
    $q2=mysqli_query($conn, "INSERT INTO tb_transaksi_fpgrowth(kode_transaksi) SELECT DISTINCT transaksi_id FROM tb_stok WHERE tb_stok.tgl BETWEEN CAST('".$_POST['dari']."' AS DATE) AND CAST('".$_POST['sampai']."' AS DATE)");
    $q3=mysqli_query($conn, "INSERT INTO tb_transaksi_detail(id_transaksi, id_brg)
    SELECT tb_transaksi_fpgrowth.id_transaksi, LPAD(b2.b2_id, 2, 0)
    FROM tb_stok INNER JOIN tb_transaksi_fpgrowth ON tb_stok.transaksi_id = tb_transaksi_fpgrowth.kode_transaksi
    JOIN tb_barang ON tb_stok.kode_barang=tb_barang.kode_barang 
    JOIN b2 ON b2.b2_id=tb_barang.b2");
}
?>

<h1>Selamat datang</h1>
<p>Range data transaksi yang akan dianalisa</p>
<form action="<?= BASEURL;?>index" class="col-lg-5" method="POST">

<div class="form-group">
    <label for="dari">dari:</label>
    <input type="date" class="form-control" id="dari" name="dari">
</div>

<div class="form-group">
    <label for="sampai">sampai:</label>
    <input type="date" class="form-control" id="sampai" name="sampai">
</div>

<button type="submit" name="submit" class="btn btn-primary">Proses</button>
</form>
</div>