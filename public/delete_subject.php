<?php require_once("../includes/session.php") ?>
<?php require_once("../includes/db_connection.php");?>
<?php require_once("../includes/functions.php"); ?>
<?php confirm_logged_in(); ?>

<?php
	$current_subject = find_subject_by_id($_GET["subject"], false);
	if(!$current_subject) {
	//subject id was not found or invalid or
	//subject couldn't be found in database
		redirect_to("manage_content.php");
	}
	
	//this will disallow the deletion if the subject has pages assigned to it:
	$pages_set = find_pages_for_subject($current_subject["id"]);
	if(mysqli_num_rows($pages_set) > 0) {
		$_SESSION["message"] = "Unable to delete subjects with pages assigned to them.";
		redirect_to("manage_content.php?subject={$current_subject["id"]}");
	}
	
	$id = $current_subject["id"];
	$query = "DELETE FROM subjects WHERE id = '{$id}' LIMIT 1";
	$result = mysqli_query($db, $query);
	if($result && mysqli_affected_rows($db) == 1) {
		$_SESSION["message"] = "Subject successfully deleted.";
		redirect_to("manage_content.php");
	} else {
		$_SESSION["message"] = "Failed to delete subject.  Database query failed.";
		redirect_to("manage_content.php?subject={$id}");
	}
?>