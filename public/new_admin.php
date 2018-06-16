<?php require_once("../includes/session.php") ?>
<?php require_once("../includes/db_connection.php");?>
<?php require_once("../includes/functions.php"); ?>
<?php require_once("../includes/validation_functions.php");?>
<?php confirm_logged_in(); ?>
<?php $pass_wrong = false; ?>
<?php
	if(isset($_POST['submit'])) {
		$username = escape_string($_POST["username"]);
		$password = password_encrypt($_POST["password"]);
		
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
			$query = "INSERT INTO admins (";
			$query .= "username, hashed_password";
			$query .= ") VALUES (";
			$query .= " '{$username}', '{$password}'";
			$query .= ")";
			$result = mysqli_query($db, $query);
			
			if($result) {
				$_SESSION["message"] = "Admin successfully created.";
				redirect_to("manage_admins.php");
			} else {
				$message = "Admin could not be created.  Database query failed.";
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
		<?php echo form_errors($errors); ?>
		<h2>Create Admin</h2>
		
		<form action="new_admin.php" method="post">
			<p>
				<label for="username">Username: </label>
				<input id="username" type="text" name="username" value="<?php if(isset($username)) { echo htmlentities($username); } ?>">
			</p>
			<p>
				<label for="password">Password: </label>
				<input id="password" type="password" name="password" value="">
			</p>
			<input type="submit" name="submit" value="Create Admin">
		</form>
		<br>
		<a href="manage_admins.php">Cancel</a>
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