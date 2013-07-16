<?php
require 'config/functions.php';
	//Start session
	session_start();
	
	//Array to store validation errors
	$errmsg_arr = array();
	
	//Validation error flag
	$errflag = false;
	$user = new User();
	//Sanitize the POST values
	 $login = $user->clean($_POST['login']);
	 $password = $user->clean($_POST['password']);
	 $password = $user->makeSaltedHash($_POST['password'],'5');
	//Input Validations
	if($login == '') {
		$errmsg_arr[] = 'Login ID missing';
		$errflag = true;
	}
	if($password == '') {
		$errmsg_arr[] = 'Password missing';
		$errflag = true;
	}
	
	//If there are input validations, redirect back to the login form
	if($errflag) {
		$_SESSION['ERRMSG_ARR'] = $errmsg_arr;
		session_write_close();
		header("location:index.php?msg=error");
		exit();
	}
	
	
	//Check whether the query was successful or not
	if($result = $user->checkUserNormal($login, $password)) {

					
			//Set session
			$_SESSION['id'] = $result['id'];
			//Put name in session
			$_SESSION['username'] = $result['username'];
			$_SESSION['email'] = $result['email'];
			$_SESSION['oauth_provider'] = $result['oauth_provider'];
			session_write_close();
			//Redirect to user's page
			header("location: home.php");
			exit();
		}else {
			//If Login failed redirect to login page
 			header("location: index.php?msg=error");
			exit();
		}

?>