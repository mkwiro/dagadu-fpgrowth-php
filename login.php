<?php
session_name('session_fpgrowth');
session_start();
require_once 'config.php';
require_once 'function.php';

if(isset($_POST["submit"])){
	if(empty($_POST['username']) or empty($_POST['password'])){
		exit("<script>window.alert('Lengkapi username dan password.');window.history.back();</script>");
	}
	$username=$_POST['username'];
	$password=$_POST['password'];
	$q=mysqli_query($con, "SELECT * FROM tb_user WHERE username='".escape($username)."' AND password='".escape($password)."'");
	if(mysqli_num_rows($q)==0){
		exit("<script>window.alert('Username dan password yang anda masukkan salah');window.history.back();</script>");
	}
	$h=mysqli_fetch_array($q);
	$_SESSION['LOGIN_ID']=$h['id_user'];
	header('location:index.php?=home');
}

?>