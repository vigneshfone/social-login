<?php
require 'config/dbconfig.php';

if(isset($_POST['username']))//If a username has been submitted
{
$username = mysql_real_escape_string($_POST['username']);//Some clean up :)

$check_for_username = mysql_query("SELECT * FROM users WHERE username='$username' and oauth_provider = 'normal'");
//Query to check if username is available or not

if(mysql_num_rows($check_for_username))
{
echo '1';//If there is a  record match in the Database - Not Available
}
else
{
echo '0';//No Record Found - Username is available
}
}
?>