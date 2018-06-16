<?php
	
	$errors = [];
	
	//converts strings with "_" to " "
	function convert_underscores($string) {
		$output = str_replace("_", " ", $string);
		return $output;
	}
	
	//converts underscores to spaces in $fieldname argument and capitalizes each word
	function field_name_as_text($fieldname) {
		$output = ucwords(convert_underscores($fieldname));
		return $output;
	}
	
	//Returns Boolean (T/F) if a field/value isset and not equal to "" *would need to trim input value to exlude blank spaces
	function has_presence($value) {
		return isset($value) && $value !== "";
	}
	
	//Loop that checks whether each value of the argument array has presence
	function validate_presences($required_fields) {
		global $errors;
		
		foreach($required_fields as $field) {
			if(isset($_POST[$field])) {
				$value = trim($_POST[$field]);
			} else {
				$value = "";
			}
			if(!has_presence($value)) {
					$errors[$field] = field_name_as_text($field) . " required";
			}
		}
	}
	
	//Checks min length of a value
	function meets_min_length($value, $min) {
		return strlen($value) >= $min;
	}
	
	//Checks max length of a value
	function meets_max_length($value, $max) {
		return strlen($value) <= $max;
	}
	
	//Loop that checks the max length of all values in the argument array; returns new value to the $errors array
	function validate_max_lengths($fields_max_length){
		global $errors;	
		foreach ($fields_max_length as $field => $max) {
			$value = trim($_POST[$field]);
			if(!meets_max_length($value, $max)) {
				$errors[$field] = field_name_as_text($field) . " cannot be longer than {$max} characters";
				set_focus($field);
			}
		}
	}	
	
	//Checks whether a value is included within a set/array *may want to format the input value first (strtolower)
	function is_included_in($value, $set) {
		return in_array($value, $set);
	}
	
	//Checks whether an email meets the following format: text.text.text@text.text.text.domain.domain.domain
	function email_format($value) {
		return preg_match("/\w+(\.*\w*)*@\w+(\.*\w*)*\.\w{2,}+(\.*\w*)/", $value);
	}
	
	//uses javascript to set focus on whatever the $input id is
	function set_focus($input) {
		$output = "";
			$output .= "<script type=\"text/javascript\">";
			$output .= "document.getElementById(\"" . $input . "\").focus();";
		//	$output .= "document.getElementById(\"" . $input . "\").style.color = \"red\";";
			$output .= "</script>";
		return $output;
	}
	
	//
	function validate_password($password) {
		global $errors;
		
		$pattern = "/(?=.*[A-Z])(?=.*[a-z])(?=^\S*$)(?=.*\d)(?=.*[\`\~\!\@\#\$\%\^\&\*\(\)\-\_\=\+\[\]\{\}\\\|\;\:\'\"\,\.\<\>\/\?])(^.{8,15}$)/";
		if(!preg_match($pattern, $password)) {
			$errors["password_validation"] = "Password Requirements: <br>
			<ul>
				<li>Must be between 8 and 15 characters</li>
				<li>Must contain at least one capital and lowercase letter</li>
				<li>Must contain at least one number</li>
				<li>Must contain at least one non-alphanumeric character</li>
				<li>Can not contain any spaces</li>
			</ul>";
		}
	}
	
?>