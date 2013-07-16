<?php

require 'facebook/facebook.php';
require 'config/fbconfig.php';
require 'config/functions.php';

$facebook = new Facebook(array(
	'appId' => APP_ID,
	'secret' => APP_SECRET,
	'status'   => true,                                                                                                               
	'cookie'   => true
	));

	if(isset($_SESSION['id'])){
		header("Location: home.php");
	}
	
	$user = $facebook->getUser();
	
	if ($user) {
      $logoutUrl = $facebook->getLogoutUrl(
        array(
            'next'      => "http://fadev.dyndns.org/login/logout.php?logout",
        )
      );
    } else {
      $loginUrl = $facebook->getLoginUrl(
        array(
            'display'   => 'popup',
            'next'      => $config['baseurl'] . '?loginsucc=1',
            'cancel_url'=> $config['baseurl'] . '?cancel=1',
			'scope' => 'email',
        )
      );
	 
    }
	
	 // if user click cancel in the popup window
    if (isset($_REQUEST['error_reason'])){

        echo "<script>
            window.close();
			window.opener.location.href='http://124.124.218.98/login/index.php';
            </script>";
    }
	
	if($user) {
		try {
			// Proceed knowing you have a logged in user who's authenticated.
			$user_profile = $facebook->api('/me','GET');
		} catch (FacebookApiException $e) {
			error_log($e);
			$user = null;
		}
	  $sortArray = get_object_vars(json_decode($_GET['session']));
        ksort($sortArray);
 
        $strCookie  =   "";
        $flag       =   false;
        foreach($sortArray as $key=>$item){
            if ($flag) $strCookie .= '&';
            $strCookie .= $key . '=' . $item;
            $flag = true;
        }
 
        //now set the cookie so that next time user don't need to click login again
        setCookie('fbs_' . "{$fbconfig['appid']}", $strCookie);
 
    echo "<script>window.close();window.opener.location.reload();</script>";
	
	$_SESSION['logout_url'] = $logoutUrl;
	
	if (!empty($user_profile )) {
		$username = $user_profile['username'];
		$fullname = $user_profile['name'];
		$uid = $user_profile['id'];
		$email = $user_profile['email'];
		$personMarkup = "<div><img src='http://graph.facebook.com/$uid/picture'></div>";
		$user = new User();
		$userdata = $user->checkUser($uid, 'facebook', $username,$fullname,$email);
		if(!empty($userdata)){
		session_start();
		$_SESSION['id'] = $userdata['id'];
		$_SESSION['oauth_id'] = $uid;
		$_SESSION['username'] = $userdata['username'];		$_SESSION['email'] = $email;
		$_SESSION['name'] = $fullname;
		$_SESSION['oauth_provider'] = $userdata['oauth_provider'];
		$_SESSION['image'] = $personMarkup;
		}
	} else {
		# For testing purposes, if there was an error, let's kill the script
		die("There was an error.");
	}
}

?>
  <script type="text/javascript">
        <?php if ($loginUrl) { ?>
        var newwindow;
        var intId;
        function login(){
            var  screenX    = typeof window.screenX != 'undefined' ? window.screenX : window.screenLeft,
                 screenY    = typeof window.screenY != 'undefined' ? window.screenY : window.screenTop,
                 outerWidth = typeof window.outerWidth != 'undefined' ? window.outerWidth : document.body.clientWidth,
                 outerHeight = typeof window.outerHeight != 'undefined' ? window.outerHeight : (document.body.clientHeight - 22),
                 width    = 500,
                 height   = 270,
                 left     = parseInt(screenX + ((outerWidth - width) / 2), 10),
                 top      = parseInt(screenY + ((outerHeight - height) / 2.5), 10),
                 features = (
                    'width=' + width +
                    ',height=' + height +
                    ',left=' + left +
                    ',top=' + top
                  );
 
            newwindow=window.open('<?php echo $loginUrl?>','Login_by_facebook',features);
			if(!newwindow) {
				alert("Please enabled popups for this site and login again to continue.");
				//window.close();
				window.location.href='http://fadev.dyndns.org/login/index.php';
			}
           if (window.focus) {newwindow.focus()}
          return false;
        }
 
        <?php } ?>
		window.onload = login;
    </script>