<?php
session_name('session_fpgrowth');
session_start();
include 'config.php';

session_destroy();
header('location:'.$urlaplikasi);
?>