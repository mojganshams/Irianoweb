<?php

$view = \NOMVC\Core\View::toView(getAppServices()['view']);
$db = \NOMVC\Core\SQLDatabase::toSQLDatabase(getAppServices()['database'])->db;
$currentView = 'front/admin/login.html.twig';

global $session;

if(isGetRequest())
{
    $msg = null;
    if(isset($_GET['redirect']))
    {
        $msg = [
            'type' => null, // success, failure
            'class' => 'alert-warning',
            'text' => 'برای دسترسی به بخش مورد نظر لطفا وارد حساب خود شوید.' // body of message
        ];
    }

    if(isset($_SESSION['userid']))
    {
        autoRedirectTo(AUTO_SITE_HOME_URL . '/admin');
    }

    return $view->render($currentView, ['msg' => $msg]);
}
else
{
    $msg = [
        'type' => null, // success, failure
        'text' => null // body of message
    ];
    if(isset($_SESSION['userid'])) {
        autoRedirectTo(AUTO_SITE_HOME_URL . '/admin');
    }
    if (isset($_POST['submit']))
    {

        // Login
        $username_email = stripslashes(strip_tags($_POST['username_email']));
        $password = stripslashes(strip_tags($_POST['password']));

        if(!filter_var($username_email, FILTER_VALIDATE_EMAIL) === false)
        {
            // a valid email detected
            $query = "SELECT * FROM users WHERE email = ?";
        }
        else
        {
            // probably it's a username
            $query = "SELECT * FROM users WHERE username = ?";
        }

        $stmt = $db->prepare($query);
        $res = $stmt->execute([$username_email]);
        $user = $stmt->fetch();
        
        if(\NOMVC\Utils\PasswordV1::passwordVerify($password, $user['password']))
        {
            // Successful login
            $_SESSION['loggedin'] = true;
            $_SESSION['userid'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['avatar'] = $user['avatar'];
            
            if($user['is_admin'] == 1 && $user['is_admin'] != 0)
            {
                $_SESSION['admin'] = true;
            }

            if(isset($_GET['redirect']) && !empty($_GET['redirect']))
            {
                autoRedirectTo(AUTO_SITE_HOME_URL . $_GET['redirect']);
            }
            else
            {
                autoRedirectTo(AUTO_SITE_HOME_URL . '/admin');
            }
        }
        else
        {
            $msg = ['type' => 'failure', 'class' => 'alert-danger', 'text' => 'ایمیل یا نام کاربری اشتباه است یا وجود ندارد.'];
            return $view->render($currentView, ['msg' => $msg]);
        }

        /*// Form has been submitted.
        $username = trim($_POST['username']);
        $password = trim($_POST['password']);
        $admin = find_admin_by_username($username);
        $found_user = null;

        if ($admin)
        {
            if ($hashed_password = password_check($password, $admin["password"]))
            {
                // Check database to see if username/password exist.
                $found_user = User::authenticate($username, $hashed_password);
            }
            else
            {
                $msg = [
                    'type' => null, // success, failure
                    'class' => 'alert-danger',
                    'text' => 'نام کاربری یا رمز عبور اشتباه می باشد.' // body of message
                ];
            }
        }
        else
        {
            $msg = [
                'type' => null, // success, failure
                'class' => 'alert-danger',
                'text' => 'شما اجازه دسترسی به این بخش را ندارید.' // body of message
            ];
        }


        if ($found_user)
        {
            $session->login($found_user);
            log_action('Login', "{$found_user->username} logged in.");
            $_SESSION["username"] = $found_user->username;
            $_SESSION["userid"] = $found_user->id;
            $_SESSION["avatar"] = $found_user->avatar;
            autoRedirectTo(AUTO_SITE_HOME_URL . '/admin');
        }
        else
        {
            // username/password combo was not found in the database
            $msg = [
                'type' => null, // success, failure
                'class' => 'alert-danger',
                'text' => 'نام کاربری یا رمز عبور اشتباه می باشد.' // body of message
            ];
        }
        return $view->render($currentView, ['msg' => $msg]);*/
    }
}


/*$title = "ورود به بخش مدیریت";
require_once("../models/initialize.php");

if($session->is_logged_in()) {
    redirect_to("/websites/dashboard.php");
}

// Remember to give your form's submit tag a name="submit" attribute!
if (isset($_POST['submit'])) { // Form has been submitted.

    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $admin = find_admin_by_username($username);
    if ($admin) {
        if ($hashed_password = password_check($password, $admin["password"])) {
            // Check database to see if username/password exist.
            $found_user = User::authenticate($username, $hashed_password);
        } else {
            redirect_to("/websites/panel.login.php");
        }
    } else {
        redirect_to("/websites/panel.login.php");
    }


    if ($found_user) {
        $session->login($found_user);
        log_action('Login', "{$found_user->username} logged in.");
        $_SESSION["username"] = $found_user->username;
        redirect_to("/websites/dashboard.php");
    } else {
        // username/password combo was not found in the database
        $message = "Username/password combination incorrect.";
    }

} else { // Form has not been submitted.
    $username = "";
    $password = "";
}*/
