<?php require_once("../includes/session.php") ?>
<?php require_once("../includes/db_connection.php");?>
<?php require_once("../includes/functions.php"); ?>
<?php confirm_logged_in(); ?>

<?php
	$current_admin = find_admin_by_id($_GET["id"]);
	if(!$current_admin) {
	//admin id was not found or invalid or
	//admin couldn't be found in database
		redirect_to("manage_admins.php");
	}
	
	$id = $current_admin["id"];
	if ($id == 1) {
		$_SESSION["message"] = "Sorry, you do not have permissions to delete that admin.";
		redirect_to("manage_admins.php");
	} else {
		$query = "DELETE FROM admins WHERE id = '{$id}' LIMIT 1";
		$result = mysqli_query($db, $query);
		if($result && mysqli_affected_rows($db) == 1) {
			$_SESSION["message"] = "Admin was successfully deleted.";
			redirect_to("manage_admins.php");
		} else {
			$_SESSION["message"] = "Failed to delete admin.  Database query failed.";
			redirect_to("manage_admins.php");
		}
	}	
?>