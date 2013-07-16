<?php

define('DB_SERVER', '124.124.218.98');
define('DB_USERNAME', 'vignesh');
define('DB_PASSWORD', 'php123');
define('DB_DATABASE', 'login');
$connection = mysql_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD) or die(mysql_error());
$database = mysql_select_db(DB_DATABASE) or die(mysql_error());
?>
