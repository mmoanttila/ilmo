<?php
/*** PREVENT THE PAGE FROM BEING CACHED BY THE WEB BROWSER ***/
header("Cache-Control: no-cache, must-revalidate");
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");

require_once("wp-authenticate.php");

/*** REQUIRE USER AUTHENTICATION ***/
login();

/*** RETRIEVE LOGGED IN USER INFORMATION ***/
$user = wp_get_current_user();
?>
<!DOCTYPE html>
<html lang="en">
<head>
   <title>Login</title>
</head>
<body>
<p>Welcome <?php echo $user->user_firstname . " " . $user->user_lastname; ?></p>
<p><a href="/ilmo/login.php?logout=true">Click here to log out</a></p>
</body>
</html>
