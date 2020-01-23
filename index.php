<?php
session_name('session_fpgrowth');
session_start();
define('fpgrowthdagadu',true);

require_once 'config.php';
require_once 'function.php';

require_once 'page.php';
require_once 'template.php';
mysqli_close($con);
?>