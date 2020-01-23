<?php if(!defined('fpgrowthdagadu')){ exit(); }?>
<?php

$url_tmp = str_replace($urlaplikasi,'','http://'.$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI']);
$url_tmp = explode('/',$url_tmp);
switch($url_tmp[0]){
	case 'index.php?=home':
	case 'index.php?=barang':
	case 'index.php?=update_barang':
	case 'index.php?=transaksi':
	case 'index.php?=update_transaksi':
	case 'index.php?=analisa':
	case 'index.php?=registrasi':

		$_GET['hal'] = $url_tmp[0];
		break;
	default:
		$_GET['hal'] = '';
		
		break;
}

$page='';
if(isset($_GET['hal'])){
	$page=$_GET['hal'];
}
$current_page=$page;

switch($page){
	case 'index.php?=home':
		$page="include 'includes/p_home.php';";
		break;
	case 'index.php?=barang':
		$page="include 'includes/p_barang.php';";
		break;
	case 'index.php?=update_barang':
		$page="include 'includes/p_barang_update.php';";
		break;
	case 'index.php?=transaksi':
		$page="include 'includes/p_transaksi.php';";
		break;
	case 'index.php?=update_transaksi':
		$page="include 'includes/p_transaksi_update.php';";
		break;
	case 'index.php?=analisa':
		$page="include 'includes/p_analisa.php';";
		break;
	case 'index.php?=registrasi':
		$page="include 'includes/p_registrasi.php';";
		break;
		
	default :
		$page="include 'includes/p_login.php';";
		break;
}

$content=$page;

?>