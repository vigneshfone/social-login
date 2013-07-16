<?php

/* Start session and load library. */
session_start();
require_once('twitter/twitteroauth.php');
require_once('config/twconfig.php');

/* Build TwitterOAuth object with client credentials. */
$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET);
 
/* Get temporary credentials. */
$request_token = $connection->getRequestToken(OAUTH_CALLBACK);

/* Save temporary credentials to session. */
$_SESSION['oauth_token'] = $token = $request_token['oauth_token'];
$_SESSION['oauth_token_secret'] = $request_token['oauth_token_secret'];
 
/* If last connection failed don't display authorization link. */
switch ($connection->http_code) {
  case 200:
    /* Build authorize URL and redirect user to Twitter. */
    $loginUrl = $connection->getAuthorizeURL($token);
    //header('Location: ' . $url); 
    break;
  default:
    /* Show notification if something went wrong. */
    echo 'Could not connect to Twitter. Refresh the page or try again later.';
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
 
            newwindow=window.open('<?php echo $loginUrl?>','Login_by_Twitter',features);
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