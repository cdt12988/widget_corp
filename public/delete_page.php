<?php require_once("../includes/session.php") ?>
<?php require_once("../includes/db_connection.php");?>
<?php require_once("../includes/functions.php"); ?>
<?php confirm_logged_in(); ?>

<?php
	$current_page = find_page_by_id($_GET["page"], false);
	if(!$current_page) {
	//page id was not found or invalid or
	//page couldn't be found in database
		redirect_to("manage_content.php");
	}
		
	$id = $current_page["id"];
	$subject_id = $current_page["subject_id"];
	$query = "DELETE FROM pages WHERE id = '{$id}' LIMIT 1";
	$result = mysqli_query($db, $query);
	if($result && mysqli_affected_rows($db) == 1) {
		$_SESSION["message"] = "Page was successfully deleted.";
		redirect_to("manage_content.php?subject={$subject_id}");
	} else {
		$_SESSION["message"] = "Unable to delete page.  Database query failed.";
		redirect_to("manage_content.php?subject={$id}");
	}
?>