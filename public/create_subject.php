<?php require_once("../includes/session.php") ?>
<?php require_once("../includes/db_connection.php");?>
<?php require_once("../includes/functions.php"); ?>
<?php require_once("../includes/validation_functions.php") ?>
<?php confirm_logged_in(); ?>

<?php
	if(isset($_POST['submit'])) {
		$menu_name = escape_string($_POST["menu_name"]);
		$position = (int) $_POST["position"];
		$visible = isset($_POST["visible"]) ? (int) $_POST["visible"] : "";
	//	$visible = (int) $_POST["visible"];
		
		// Validations
		$required_fields = ["menu_name", "position", "visible"];
		validate_presences($required_fields);
		$fields_with_max_lengths = ["menu_name" => 30];
		validate_max_lengths($fields_with_max_lengths);
		
		if(!empty($errors)) {
			$_SESSION["errors"] = $errors;
			redirect_to("new_subject.php");
			
		} else {
		
			$query = "INSERT INTO subjects (";
			$query .= "menu_name, position, visible";
			$query .= ") VALUES (";
			$query .= " '{$menu_name}', {$position}, {$visible}";
			$query .= ")";
			$result = mysqli_query($db, $query);
			
			if($result){
				$_SESSION["message"] = "Subject successfully created.";
				redirect_to("manage_content.php");
			} else {
				$_SESSION["message"] = "Subject creation failed.  Database query failed.";
				redirect_to("new_subject.php");
			}
		}	
	} else {
		redirect_to("new_subject.php");
	}
?>



<?php
	if(isset($db)) { mysqli_close($db); }
?>