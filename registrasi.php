<?php
session_name('session_fpgrowth');
session_start();
require_once 'config.php';
require_once 'function.php';

if(isset($_POST["register"])){
	if(empty($_POST['nama']) or empty($_POST['username']) or empty($_POST['password1']) or empty($_POST['password2'])){
		exit("<script>window.alert('Lengkapi seluruh data.');window.history.back();</script>");
	} else {
		$nama = strtolower($_POST['nama']);
		$username = $_POST['username'];
		$password1 = $_POST['password1'];
		$password2 = $_POST['password2'];

		echo "<script>
				alert('berhasil!');
		</script>";
	}


	//cek konfirmasi password
	if ($password1 !== $password2) {
		echo "<script>
				alert('konfirmasi password tidak sama!');
		</script>";

		return false;
	}

	//enkripsi password
	$password = md5($password1);

	//tambahkan user baru ke database
	$q=mysqli_query($con, "INSERT INTO tb_user VALUES('', '$nama','$username','$password')");

	$h=mysqli_affected_rows($q);

	$_SESSION['LOGIN_ID']=$h['id_user'];
	//jika user berhasil ditambahkan akan langsung redirect ke menu login
	header('location:index.php?=login');
}
?>