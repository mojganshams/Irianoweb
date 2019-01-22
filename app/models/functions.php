<?php

function strip_zeros_from_date( $marked_string="" ) {
  // first remove the marked zeros
  $no_zeros = str_replace('*0', '', $marked_string);
  // then remove any remaining marks
  $cleaned_string = str_replace('*', '', $no_zeros);
  return $cleaned_string;
}

function redirect_to( $location = NULL ) {
  if ($location != NULL) {
    header("Location: {$location}");
    exit;
  }
}

function output_message($message="") {
  if (!empty($message)) { 
    return "<div class=\"message rtl\">{$message}</div>";
  } else {
    return "";
  }
}

function __autoload($class_name) {
	$class_name = strtolower($class_name);
  $path = LIB_PATH.DS."{$class_name}.php";
  if(file_exists($path)) {
    require_once($path);
  } else {
		die("The file {$class_name}.php could not be found.");
	}
}

function include_layout_template($template="") {
	include(SITE_ROOT.DS.'public'.DS.'layouts'.DS.$template);
}

function log_action($action, $message="") {
	$logfile = SITE_ROOT.DS.'logs'.DS.'log.txt';
	$new = file_exists($logfile) ? false : true;
  if($handle = fopen($logfile, 'a')) { // append
    $timestamp = strftime("%Y-%m-%d %H:%M:%S", time());
		$content = "{$timestamp} | {$action}: {$message}\n";
    fwrite($handle, $content);
    fclose($handle);
    if($new) { chmod($logfile, 0755); }
  } else {
    echo "Could not open log file for writing.";
  }
}

function datetime_to_text($datetime="") {
  $unixdatetime = strtotime($datetime);
  return strftime("%B %d, %Y at %I:%M %p", $unixdatetime);
}
function password_encrypt($password) {
    $hash_format = "$2y$10$";
    $salt_length = 22;
    $salt = generate_salt($salt_length);
    $format_and_salt = $hash_format . $salt;
    $hash = crypt($password, $format_and_salt);
    return $hash;
}
function generate_salt($length) {
    $unique_random_string = md5(uniqid(mt_rand(), true));
    $base64_string = base64_encode($unique_random_string);
    $modified_base64_string = str_replace('+', '.', $base64_string);
    $salt = substr($modified_base64_string, 0, $length);
    return $salt;
}
function password_check($password, $existing_hash) {
    $hash = crypt($password, $existing_hash);
    if($hash === $existing_hash) {
        return $existing_hash;
    }
    else {
        return false;
    }
}
function confirm_query($result_set) {
    if(!$result_set) {
        die("Database query failed.");
    }
}
function find_admin_by_username($username) {
    global $database;
    //$safe_username = mysqli_real_escape_string($database, $username);

    $query = "SELECT * ";
    $query .= "FROM users ";
    $query .= "WHERE username = '{$username}' ";
    $query .= "LIMIT 1";
    $admin_set = $database->query($query);
    if($admin = mysqli_fetch_assoc($admin_set)) {
        return $admin;
    }
    else {
        return null;
    }
}
function attempt_login($username, $password) {
    $admin = find_admin_by_username($username);
    if ($admin) {
        if (password_check($password, $admin["hashed_password"])) {
            return $admin;
        }
        else {
            return false;
        }
    }
    else {
        return false;
    }
}

$errors = array();
function fieldname_as_text($fieldname) {
    $fieldname = str_replace("_", " ", $fieldname);
    $fieldname = ucfirst($fieldname);
    return $fieldname;
}
function has_presence($value) {
    return isset($value) && $value !== "";
}
function validate_presences($required_fields) {
    global $errors;
    foreach ($required_fields as $field) {
        $value = trim($_POST[$field]);
        if(!has_presence($value)) {
            $errors[$field] = fieldname_as_text($field) . " نمی تواند خالی باشد.";
        }
    }
}
function validate_max_lengths($fields_with_max_lengths) {
    global $errors;
    foreach ($fields_with_max_lengths as $field => $max) {
        $value = trim($_POST[$field]);
        if(!has_max_length($value, $max)) {
            $errors[$field] = fieldname_as_text($field) . " is too long";
        }
    }
}
function has_max_length($value, $max) {
    return strlen($value) <= $max;
}
function has_inclusion_in($value, $set) {
    return in_array($value, $set);
}
function form_errors($errors = array()) {
    $output = "";
    if(!empty($errors)) {
        $output .= "<div class='message'>";
        $output .= "لطفا خطا های زیر را بررسی نمایید:";
        $output .= "<ul>";
        foreach ($errors as $key => $error) {
            $output .= "<li>";
            $output .= htmlentities($error);
            $output .= "</li>";
        }
        $output .= "</ul>";
        $output .= "</div>";
    }
    return $output;
}