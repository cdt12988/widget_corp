<?php
	session_start();
	
	// returns any "message" key saved to the $_SESSION global array
	function display_message() {
		if(isset($_SESSION["message"])) {
			$message = "<div class=\"message\">";
			$message .= htmlentities($_SESSION["message"]);
			$message .= "</div>";
			
			$_SESSION["message"] = null;
			
			return $message;
		}
	}
	
	//
	function display_errors() {
		if(isset($_SESSION["errors"])) {
			$errors = $_SESSION["errors"];
			
			$_SESSION["errors"] = null;
			
			return $errors;
		}
	}
	
?>