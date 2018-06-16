<?php
	if(!isset($layout_context)) {
		$layout_context = "public";
	}
?>
<!DOCTYPE html PUBLIC>

<html lang="en-US">
	<head>
		<title>Widget Corp <?php if($layout_context == "admin") { echo "Admin"; } ?></title>
		<meta charset="utf-8">
		<meta name="description" content="">
		<link href="styles/public.css" media="all" rel="stylesheet" type="text/css">
	</head>
	<body>
	<div id="container">
		<div id="header">
			<h1><a href="<?php if(isset($_SESSION["admin_id"])) { echo "admin.php"; } else { echo "index.php"; } ?>">Widget Corp<?php if($layout_context == "admin") { echo " Admin"; } ?></a></h1>
			<div id="login">
				<?php if(isset($_SESSION["admin_id"])) { ?>
				<a href="logout.php">Logout</a>
				<?php } else { ?>
				<a href="login.php">Login</a>
				<?php } ?>
			</div>
		</div>
		
		