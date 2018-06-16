<?php require_once("../includes/session.php") ?>
<?php require_once("../includes/db_connection.php");?>
<?php require_once("../includes/functions.php"); ?>
<?php require_once("../includes/validation_functions.php");?>
<?php confirm_logged_in(); ?>
<?php
	$pass_wrong = false;
?>
<?php 
	if(isset($_GET["id"])) {
		$current_id = find_admin_by_id($_GET["id"]);
		$id = $current_id["id"];
	} else {
		$current_id = null;
	}
	//$id = $_GET["id"]; ?>
<?php// $current_id = find_admin_by_id($id); ?>
<?php
	if(!$current_id) {
	// redirects if no admin id was passed through the query string or the id couldn't be found in database	
		redirect_to("manage_admins.php");
	}
?>

<?php
	if(isset($_POST['submit'])) {
		
		// Validations
		$required_fields = ["username", "password"];
		validate_presences($required_fields);
		$fields_with_max_lengths = ["username" => 30];
		validate_max_lengths($fields_with_max_lengths);
		$unencrypted_password = $_POST["password"];
		if(!$_POST["password"] == "") {
			validate_password($unencrypted_password);
			$pass_wrong = true;
		}
		
		
		if(empty($errors)) {
			$username = escape_string($_POST["username"]);
			$password = password_encrypt($_POST["password"]);
			$admin_id = $current_id["id"];
			if ($admin_id == 1) {
				$_SESSION["message"] = "Sorry, you do not have permissions to edit that admin.";
				redirect_to("manage_admins.php");
			} else {		
				$query = "UPDATE admins SET ";
				$query .= "username = '{$username}', ";
				$query .= "hashed_password = '{$password}' ";
				$query .= "WHERE id = {$id} ";
				$query .= "LIMIT 1";
				$result = mysqli_query($db, $query);
			
				if($result && mysqli_affected_rows($db) >= 0){
					$_SESSION["message"] = "Admin was successfully updated.";
					redirect_to("manage_admins.php");
				} else {
					$message = "Unable to update admin.  Database query failed.";
				}
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
		<h2>Edit Admin: <?php echo htmlentities($current_id["username"]); ?></h2>
		
		<form action="edit_admin.php?id=<?php echo urlencode($current_id["id"]); ?>" method="post">
			<p>
				<label for="username">Username: </label>
				<input id="username" type="text" name="username" value="<?php echo htmlentities($current_id["username"]); ?>">
			</p>
			<p>
				<label for="password">Password: </label>
				<input id="password" type="password" name="password" value="">
			</p>
			<input type="submit" name="submit" value="Edit Admin">
		</form>
		<br>
		<a href="manage_admins.php">Cancel</a>
		&nbsp;
		&nbsp;
		<a href="delete_admin.php?id=<?php echo urlencode($current_id["id"]);?>" onclick="return confirm('Are you sure you want to delete this admin?');">Delete Admin</a>

	</div>
</div>
<?php
	if($pass_wrong) {
		echo set_focus("password");
	} else {
		echo set_focus("username");
	}
?>
<?php include("../includes/layouts/footer.php"); ?>