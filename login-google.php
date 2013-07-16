<?php
require_once 'google/Google_Client.php';
require_once 'google/Google_Oauth2Service.php';
require_once 'config/googleconfig.php';
require 'config/functions.php';
session_start();

$client = new Google_Client();
$client->setApplicationName("Google UserInfo PHP Starter Application");

 $client->setClientId(CLIENT_ID);
 $client->setClientSecret(CLIENT_SECRET);
 $client->setRedirectUri(OAUTH_CALLBACK);
 $client->setDeveloperKey(DEVELOPER_KEY);
 $client->setApprovalPrompt(auto);
 //$client->setScopes(array('https://www.googleapis.com/auth/userinfo.email')); 
$oauth2 = new Google_Oauth2Service($client);

if (isset($_GET['code'])) {
  $client->authenticate($_GET['code']);
  $_SESSION['token'] = $client->getAccessToken();
  $redirect = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'];
  header('Location: ' . filter_var($redirect, FILTER_SANITIZE_URL));
  return;
}

if (isset($_SESSION['token'])) {
 $client->setAccessToken($_SESSION['token']);
}

if (isset($_REQUEST['logout'])) {
  unset($_SESSION['token']);
  $client->revokeToken();
}

if ($client->getAccessToken()) {
  $user = $oauth2->userinfo->get();
if (!empty($user )) {

	$email = filter_var($user['email'], FILTER_SANITIZE_EMAIL);
	$img = filter_var($user['picture'], FILTER_VALIDATE_URL);
	$name= $user['name'];
	$uid= $user['id'];
	$personMarkup = "<div><img src='$img?sz=50'></div>";
	$userInsert = new User();
	$userdata = $userInsert->checkUser($uid, 'google', '', $name, $email);
  // The access token may have been updated lazily.
	$_SESSION['token'] = $client->getAccessToken();
  	$_SESSION['id'] = $userdata['id'];
	$_SESSION['oauth_id'] = $userdata['oauth_uid'];
	$_SESSION['username'] = $userdata['full_name'];
	$_SESSION['email'] =  $userdata['email'];
	$_SESSION['oauth_provider'] = $userdata['oauth_provider'];
	$_SESSION['image'] = $personMarkup;
	$_SESSION["logout_url"] = "http://fadev.dyndns.org/login/logout.php?logout";
	echo "<script>window.close();window.opener.location.href='home.php';</script>";
	//header("Location: home.php");
  }else{
  die("There was an error.");
  }
}else{
  $authUrl = $client->createAuthUrl('https://www.googleapis.com/auth/userinfo.email');
}

  if(isset($authUrl)) {
    print "<a class='login' href='$authUrl'>Connect Me!</a>";
        header("Location: $authUrl");
  } else {
   print "<a class='logout' href='http://fadev.dyndns.org/login/logout.php?logout'>Logout</a>";
  }
?>

