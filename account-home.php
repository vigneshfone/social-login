<?php
require_once('twitter/twitteroauth.php');
require_once('config/twconfig.php');
require 'config/functions.php';
session_start();

/* If access tokens are not available redirect to connect page. */
if (empty($_SESSION['access_token']) || empty($_SESSION['access_token']['oauth_token']) || empty($_SESSION['access_token']['oauth_token_secret'])) {
	header('Location: ./logout.php');
}
/* Get user access tokens out of the session. */
$access_token = $_SESSION['access_token'];

/* Create a TwitterOauth object with consumer/user tokens. */
$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $access_token['oauth_token'], $access_token['oauth_token_secret']);

/* If method is set change API call made. Test is called by default. */
$user_info = $connection->get('account/verify_credentials');
if(is_object($user_info)){

	if (isset($user_info->error)) {
		// Something's wrong, go back to square 1  
		header('Location: ./logout.php');
	}else {
		$twitter_otoken=$access_token['oauth_token'];
		$twitter_otoken_secret=$access_token['oauth_token_secret'];
		$email='';
		$uid = $user_info->id;
		$username = $user_info->screen_name;
		$fullname = $user_info->name;
		$user = new User();
		$image = "<div><img src='$user_info->profile_image_url' /></div>";
		$userdata = $user->checkUser($uid, 'twitter', $username,$fullname,$email);
		if(!empty($userdata)){
			$_SESSION['id'] = $userdata['id'];			$_SESSION['oauth_id'] = $uid;
			$_SESSION['name'] = $fullname;
			$_SESSION['username'] = $userdata['username'];
			$_SESSION['oauth_provider'] = $userdata['oauth_provider'];
			$_SESSION['email'] = '';
			$_SESSION['image'] = $image;
			$_SESSION["logout_url"] = "http://fadev.dyndns.org/login/logout.php?logout";
			header("Location: home.php");
		}
		
		echo "<br />";
		echo '<a href="logout.php">Logout</a></p>';
	}
}else
{
	if(isset($_SESSION['access_token']))
	{
		/* Build a Link to Goto to account home. */		
		echo '<a href="./redirect.php">Goto My Account</a>';	
	}else
	{
		/* Build an image link to start the redirect process. */
		echo '<a href="./redirect.php"><img src="./images/darker.png" alt="Sign in with Twitter"/></a>';		
	}
}
?>
