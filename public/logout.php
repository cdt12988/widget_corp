<?php require_once("../includes/session.php"); ?>
<?php require_once("../includes/functions.php"); ?>

<?php
// There are two ways to logout
	// v1: Simple Logout (commonly used)
		// make sure your session is started/included/required
		// just resets the session variables you are using to null and redirects wherever you would like them to
		$_SESSION["admin_id"] = null;
		$_SESSION["username"] = null;
		redirect_to("login.php");
?>
<?php
/*	
	// v2: Destroy the Session
		// this version assumes there is nothing left in the session to keep and completely destroys the session.
		// make sure your session is started/included/required:
		session_start();
		// completely empty your session array:
		$_SESSION = [];
		// check to see if the COOKIE for the session name is there:
		if (isset($_COOKIE[session_name()])) {
			// set the cookie session name equal to nothing and give it a time in the past so that it expires:
			setcookie(session_name(), '', time()-42000, '/');
		}
		// we then destroy the session completely, destroying the session file on the server:
		session_destroy();
		// and lastly, we can redirect the user wherever we'd like:
		redirect_to("login.php");
*/		
?>