<?php
$db_host = 'localhost';
$db_user = 'root';
$db_password = '';
$db_name = 'test';

$urlaplikasi = 'http://localhost/dagadu-fpgrowth-php/';

$con = @mysqli_connect($db_host,$db_user,$db_password,$db_name,3306) or die('<link href="'.$urlaplikasi.'assets/css/sb-admin-2.min.css" rel="stylesheet">
	   <center><div class="alert alert-danger"><strong>Error!</strong> Koneksi ke server database gagal.</div></center>');

?>