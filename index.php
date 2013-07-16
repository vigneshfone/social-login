<?php
//Always place this code at the top of the Page
session_start();
if (isset($_SESSION['id'])) {
    // Redirection to login page twitter or facebook
    header("location: home.php");
}

if (array_key_exists("login", $_GET)) {
    $oauth_provider = $_GET['oauth_provider'];
    if ($oauth_provider == 'twitter') {
        header("Location: login-twitter.php");
    } else if ($oauth_provider == 'facebook') {
        header("Location: login-facebook.php");
    }else if ($oauth_provider == 'google') {
//        header("Location: login-google.php");
		$loginUrl = "login-google.php"
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
                 height   = 470,
                 left     = parseInt(screenX + ((outerWidth - width) / 2), 10),
                 top      = parseInt(screenY + ((outerHeight - height) / 2.5), 10),
                 features = (
                    'width=' + width +
                    ',height=' + height +
                    ',left=' + left +
                    ',top=' + top
                  );
 
            newwindow=window.open('<?php echo $loginUrl?>','Login_by_Google',features);
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
	<?php
    }
}
?>
<title>Fonearena Login</title>
<style type="text/css">
    #buttons
	{
	text-align:center
	}
    #buttons img,
    #buttons a img
    { border: none;}
	h1
	{
	font-family:Arial, Helvetica, sans-serif;
	color:#999999;
	}
	#overlay {
    position: fixed; 
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
	z-index:-1;
}
#modal {
    position:absolute;
    border-radius:14px;
    padding:8px;
	 left: 28%;
     top: 25%;
	 visibility:hidden;
	 background:white;
	 z-index:1000;
}

#content {
    border-radius:8px;
    background:#fff;
    padding:20px;
}
#close {
    position:absolute;
    background:url(close.png) 0 0 no-repeat;
    width:24px;
    height:27px;
    display:block;
    text-indent:-9999px;
    top:0px;
    right:5px;
}
</style>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>


<div id="buttons">
<h1>Twitter Facebook Google Login </h1>
    <a href="?login&oauth_provider=twitter"><img src="images/tw_login.png"></a>&nbsp;&nbsp;&nbsp;
    <a href="?login&oauth_provider=facebook"><img src="images/fb_login.png"></a> <br />    <a href="?login&oauth_provider=google"><img src="images/gplus.png"></a>
</div>
<?php
if($_GET['msg']=="error")
{
?>
<div style="padding:6px; background:#FDE0CE; color:#FD5E5E; font-weight:bold; border:solid 1px #FE9592; text-align:center;">Error!! Wrong username or password!!</div>
<div style="padding:6px; background:#FDE0CE; color:#FD5E5E; font-weight:bold; border:solid 1px #FE9592; text-align:center;"><a href="index.php">Login Again</a></div>
<?php
}elseif($_GET['msg']=="logout")
{
?><div style="background:#DCFDC8; border:solid 1px #A0EB70; color:#030; text-align:center; font-weight:bold; padding:4px;">Logged out successfully!!</div>
<?php
}else{
?>
<div style="margin:0 auto; padding:8px 4px; text-align:center; width:320px;">
<form method="post" action="login-normal.php">
<table width="100%" border="0" align="center" cellpadding="4" cellspacing="4">
  <tr>
    <td width="20%" align="right" valign="middle">Username</td>
    <td width="3%" align="left" valign="middle">:</td>
    <td width="77%" align="left" valign="middle"><label>
      <input name="login" type="text" id="login" placeholder="Enter Name" required />
    </label></td>
  </tr>
  <tr>
    <td align="right" valign="middle">Password</td>
    <td align="left" valign="middle">:</td>
    <td align="left" valign="middle"><label>
      <input name="password" type="password" id="password" placeholder="Enter your password" required />
    </label></td>
  </tr>
  <tr>
    <td align="left" valign="middle">&nbsp;</td>
    <td align="left" valign="middle">&nbsp;</td>
    <td align="left" valign="middle"><label>
      <input type="submit" name="button" id="button" value="Login" />
    </label><label>
      <input type="button" name="register" id="register_button" value="Register" />
    </label></td>
	 
  </tr>
</table>

</form>
</div>
<div id='overlay'></div>
<div id='modal'>
<form method="post" action="register.php">
<table width="500" border="0" align="center" cellpadding="4" cellspacing="4">
  <tr>
    <td width="20%" align="right" valign="middle">Username</td>
    <td width="3%" align="left" valign="middle">:</td>
    <td width="77%" align="left" valign="middle"><label>
      <input name="register" type="text" id="register" required /><span id="availability_status"></span>
    </label></td>
  </tr>
   <tr>
    <td width="20%" align="right" valign="middle">Email</td>
    <td width="3%" align="left" valign="middle">:</td>
    <td width="77%" align="left" valign="middle"><label>
      <input name="email" type="email" id="email" value="" required />
    </label></td>
  </tr>
  <tr>
    <td align="right" valign="middle">Password</td>
    <td align="left" valign="middle">:</td>
    <td align="left" valign="middle"><label>
      <input name="password" type="password" id="password1" value="" required />
    </label></td>
  </tr>
  <tr>
    <td align="right" valign="middle">Re-Enter Password</td>
    <td align="left" valign="middle">:</td>
    <td align="left" valign="middle"><label>
      <input name="password" type="password" id="password2" value="" required />
    </label></td>
  </tr>
  <tr>
    <td align="left" valign="middle">&nbsp;</td>
    <td align="left" valign="middle">&nbsp;</td>
    <td align="left" valign="middle"><label>
      <input type="submit" name="button" id="button_reg" value="register" required />
    </label></td>
  </tr>
</table>

</form>
<a href="#" name="close" id="close">close</a>
</div>

<script>
$(document).ready(function(){
$('#register_button').click(function(){
	//alert("sdad");
	$('#modal').css("visibility","visible");
	$("#overlay").css({"z-index":10,"background":"rgba(0,0,0,0.5)"});
});
$('#close').click(function(){
	$('#modal').css("visibility","hidden");
	$("#overlay").css({"z-index":-1,"background":"none"});
});

$('#button_reg').click(function(){
var pwd1= $('#password1').val();
var pwd2= $('#password2').val();
if(pwd1=== pwd2){
//alert(pwd1+'---'+pwd2);
return true;
}else{
	alert("password donot match");
	return false;
}
});

$("#register").change(function()
{ //if theres a change in the username textbox

var username = $("#register").val();//Get the value in the username textbox
if(username.length > 3)//if the lenght greater than 3 characters
{
$("#availability_status").html('Checking availability...');

$.ajax({  //Make the Ajax Request
 type: "POST",
 url: "check-username.php",  //file name
 data: "username="+ username,  //data
 success: function(server_response){

 $("#availability_status").ajaxComplete(function(event, request){

 if(server_response == '0')
 {
 $("#availability_status").html('<font color="Green"> Available </font>  ');
 }
 else  if(server_response == '1')
 {
 $("#availability_status").html('<font color="red">Not Available </font>');
 }
 });
 }
 });
}
else
{
$("#availability_status").html('<font color="#cc0000">Username too short</font>');
}
return false;
});
});
</script>
<?php
session_start();
?><script type="text/javascript" src="jquery.oauthpopup.js"></script><script type="text/javascript">// <![CDATA[
$(document).ready(function(){
    $('#connect').click(function(){
        $.oauthpopup({
            path: 'connect.php',
            callback: function(){
                window.location.reload();
            }
        });
    });
});
// ]]></script>
<?php } ?>