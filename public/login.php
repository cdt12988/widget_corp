<?php require_once("../includes/session.php") ?>
<?php require_once("../includes/db_connection.php");?>
<?php require_once("../includes/functions.php"); ?>
<?php require_once("../includes/validation_functions.php");?>
<?php $layout_context = "admin"; ?>

<?php
	if(isset($_POST['submit'])) {
		$username = ($_POST["username"]);
		$password = ($_POST["password"]);
		
		// Validations
		$required_fields = ["username", "password"];
		validate_presences($required_fields);
		
		if(empty($errors)) {
		// Attempt login
			$found_admin = attempt_login($username, $password);



			if($found_admin) {
		// Success
		// Mark user as logged in:
				$_SESSION["admin_id"] = $found_admin["id"]; // Stores the user id (from attempt_login()) to verify/mark/stamp the user as logged in
				$_SESSION["username"] = $found_admin["username"]; // Stores the username for our convenience to use while the user navigates the admin areas of our site; Could use it in the header or to say "hi" or anything
				redirect_to("admin.php");
			} else {
		// Failure
				$message = "Username/Password combination not found.";
			}			
		}	
	} else {
		$username = "";
	}
?>

<?php include("../includes/layouts/header.php"); ?>
<div id="main">
	<div id="navigation">
		<br>
		<?php if(logged_in()) { ?>
		<a href="admin.php">&laquo; Admin Menu</a><br>
		<?php } ?>
		<a href="index.php">&laquo; Public Page</a><br>
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
		<h2>Login</h2>
		
		<form action="login.php" method="post">
			<p>
				<label for="username">Username: </label>
				<input id="username" type="text" name="username" value="<?php echo htmlentities($username); ?>">
			</p>
			<p>
				<label for="password">Password: </label>
				<input id="password" type="password" name="password" value="">
			</p>
			<input type="submit" name="submit" value="Login">
		</form>
	</div>
</div>

<?php include("../includes/layouts/footer.php"); ?>