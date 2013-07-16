<?php

require 'dbconfig.php';

class User {

	const SALT =  'dfsd$%#RFdgfvefew4@!Yhjnjnyi';

    function checkUser($uid, $oauth_provider, $username,$fullname,$email) 
	{
        $query = mysql_query("SELECT * FROM `users` WHERE oauth_uid = '$uid' and oauth_provider = '$oauth_provider'") or die(mysql_error());
		$ip = $_SERVER['REMOTE_ADDR'];
        $result = mysql_fetch_array($query);
        if (!empty($result)) {
            # User is already present
        } else {
            #user not present. Insert a new Record
            $query = mysql_query("INSERT INTO `users` (oauth_provider, oauth_uid, username, full_name,email, ip_address, created, modified) VALUES ('$oauth_provider', $uid, '$username','$fullname','$email', '$ip', NOW(), NOW())") or die(mysql_error());
            $query = mysql_query("SELECT * FROM `users` WHERE oauth_uid = '$uid' and oauth_provider = '$oauth_provider'");
            $result = mysql_fetch_array($query);
            return $result;
        }
        return $result;
    }

    function insertUser($username, $emailId, $password){
		$query= mysql_query("INSERT INTO `users` (`email`, `oauth_provider`, `username`, `password`, `created`, `modified`) VALUES ('$emailId', 'normal', '$username', '$password', NOW(), NOW());");
		
		if($query)
		{		
			$query = mysql_query("SELECT * FROM `users` WHERE username = '$username' and password = '$password'");
			$result = mysql_fetch_array($query);
		}else{
			echo "Error!";
		}
        return $result;
	}
	
    function clean($str) {
		$str = @trim($str);
		if(get_magic_quotes_gpc()) {
			$str = stripslashes($str);
		}
		return mysql_real_escape_string($str);
	}
	
	function checkUserNormal($username, $password){
        $query = mysql_query("SELECT * FROM `users` WHERE username = '$username' and password = '$password'") or die(mysql_error());
        $result = mysql_fetch_assoc($query);
		if (!empty($result)) {
			return $result;
		}
	}
	
	function makeSaltedHash($password, $salt = '') {
		$password = self::clean($password);
		if (empty($salt)) {
			$salt = makeRandomSalt(mt_rand(64, 128));
		}
		$hash = hash('sha512', $password . $salt . self::SALT);
		for ($i = 0; $i < 50; $i++) {
			$hash = hash('sha512', $password . $salt . self::SALT . $hash);
		}
		return $hash . ':' . $salt;
	}

	function makeRandomSalt($length = 64) {
		$salt = '';
		for ($i = 0; $i < $lenght; $i++) {
			$salt .= chr(mt_rand(33, 126));
		}
		return $salt;
	}

}

?>
