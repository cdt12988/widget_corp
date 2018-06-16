<?php require_once("../includes/session.php") ?>
<?php require_once("../includes/db_connection.php");?>
<?php require_once("../includes/functions.php"); ?>
<?php require_once("../includes/validation_functions.php");?>
<?php confirm_logged_in(); ?>
<?php find_selected_page(); ?>

<?php
	if(!$current_subject) {
	// redirects if no subject id was passed through the query string or the subject couldn't be found in database	
		redirect_to("manage_content.php");
	}
?>

<?php
	if(isset($_POST['submit'])) {
		
		// Validations
		$required_fields = ["menu_name", "position", "visible"];
		validate_presences($required_fields);
		$fields_with_max_lengths = ["menu_name" => 30];
		validate_max_lengths($fields_with_max_lengths);
		
		if(empty($errors)) {
						
			$id = $current_subject["id"];
			$menu_name = escape_string($_POST["menu_name"]);
			$position = (int) $_POST["position"];
			$visible = isset($_POST["visible"]) ? (int) $_POST["visible"] : "";
		//	$visible = (int) $_POST["visible"];
		
			$query = "UPDATE subjects SET ";
			$query .= "menu_name = '{$menu_name}', ";
			$query .= "position = '{$position}', ";
			$query .= "visible = '{$visible}' ";
			$query .= "WHERE id = '{$id}' ";
			$query .= "LIMIT 1";
			$result = mysqli_query($db, $query);
			
			if($result && mysqli_affected_rows($db) >= 0){
				$_SESSION["message"] = "Subject successfully updated.";
				redirect_to("manage_content.php?subject={$id}");
			} else {
				$message = "Subject update failed.  Database query failed.";
			}
		}	
	}
?>

<?php $layout_context = "admin"; ?>
<?php include("../includes/layouts/header.php"); ?>

<div id="main">
	<div id="navigation">
		<br>
		<a href="admin.php">&laquo; Main menu</a><br>
		<?php echo navigation($current_subject, $current_page); ?>
	</div>
	<div id="page">
		<?php
		if(!empty($message)) {
			echo "<div class = \"message\">";
			echo htmlentities($message);
			echo "</div>";
		}	
		?>
		<?php
			echo form_errors($errors);
		?>
		<h2>Edit Subject: <?php echo htmlentities($current_subject["menu_name"]); ?></h2>
		
		<form action="edit_subject.php?subject=<?php echo urlencode($current_subject["id"]); ?>" method="post">
			<p>
				<label for="menu_name">Menu name: </label>
				<input id="menu_name" type="text" name="menu_name" value="<?php echo htmlentities($current_subject["menu_name"]); ?>">
			</p>
			<p>
				<label for="position">Position: </label>
				<select id="position" name="position">
				<?php
					$subject_set = find_all_subjects(false);
					$subject_count = mysqli_num_rows($subject_set);
					for($i=1; $i <= $subject_count; $i++) {
						echo "<option value=\"{$i}\"";
						if($current_subject["position"] == $i) {
							echo " selected";
						}
						echo ">{$i}</option>";
					}
				?>
				</select>
			</p>
			<p>
				<label for="visible">Visible: </label>
				<input id="visible" type="radio" name="visible" value="0" <?php if($current_subject["visible"] == 0) {echo "checked";} ?>> No
				&nbsp;
				<input id="visible" type="radio" name="visible" value="1"<?php if($current_subject["visible"] == 1) {echo "checked";} ?>> Yes
			</p>
			<input type="submit" name="submit" value="Edit Subject">
		</form>
		<br>
		<a href="manage_content.php?subject=<?php echo urlencode($current_subject["id"]);?>">Cancel</a>
		&nbsp;
		&nbsp;
		<a href="delete_subject.php?subject=<?php echo urlencode($current_subject["id"]);?>" onclick="return confirm('Are you sure you want to delete this subject?');">Delete Subject</a>

	</div>
</div>

<?php include("../includes/layouts/footer.php"); ?>