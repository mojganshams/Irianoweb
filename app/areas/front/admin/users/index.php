<?php

$view = \NOMVC\Core\View::toView(getAppServices()['view']);
$db = \NOMVC\Core\SQLDatabase::toSQLDatabase(getAppServices()['database'])->db;
$currentView = 'front/admin/users/index.html.twig';

if($_SERVER['REQUEST_METHOD'] !== 'POST')
{
    // Show the View

    if(!isset($_SESSION['userid']))
    {
        // Error, user must be logged in to use this feature
        autoRedirectTo(AUTO_SITE_HOME_URL . '/login?redirect=' . urlencode($_SERVER['REQUEST_URI']));
    }
    else
    {
        $id = $_SESSION['userid'];
    }

    $stmt = $db->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$id]);

    $user = $stmt->fetch();

    return $view->render($currentView, ['user' => $user]);
}
else
{
    // Request is POST
    if(!isset($_SESSION['userid']))
    {
        // Error, user must be logged in to use this feature
        autoRedirectTo(AUTO_SITE_HOME_URL . '/login?redirect=' . urlencode($_SERVER['REQUEST_URI']));
    }
    else
    {
        $id = $_SESSION['userid'];
    }

    $user_stmt = $db->prepare("SELECT * FROM users WHERE id = ?");
    $user_stmt->execute([$id]);
    $user = $user_stmt->fetch();

    if(!$user || $user == null)
    {
        dieWithHttpNotFound();
    }

    $errors = [];

    $email = strip_tags($_POST['email']);
    $username = isset($_POST['username']) ? strtolower(stripslashes(strip_tags($_POST['username']))) : null;
    $firstname = stripslashes(strip_tags($_POST['firstname']));
    $lastname = stripslashes(strip_tags($_POST['lastname']));

    $edt = new EasyDateTime('UTC', 'gregorian');
    $shouldKeepLatestUpdateDate = isset($_POST['keep-latest-update-time']) ? true : false;
    $updated_at = $edt->date();

    if($firstname == null || mb_strlen($firstname) <= 4 || $lastname == null || mb_strlen($lastname) <= 4)
    {
        $errors[] = 'نام و نام خانوادگی خود را به صورت صحیح وارد کنید';
    }

    if(filter_var($email, FILTER_VALIDATE_EMAIL) === false)
    {
        // Error, invalid email
        $errors[] = 'ایمیل نامعتبر است';
    }

    $password = stripslashes(strip_tags($_POST['password']));
    $password_repeat = stripslashes(strip_tags($_POST['password-repeat']));

    //$set_username = ($user['username'] == null || mb_strlen($user['username']) <= 0) ? true : false;

    if(isset($_POST['remove_avatar'])) {
        $stmt = $db->prepare("UPDATE users SET avatar = ? WHERE id = ?");
        $res = $stmt->execute([null, $id]);
        $_SESSION['avatar'] = null;
    }

    if(isset($_POST['delete']))
    {
        $stmt = $db->prepare("DELETE FROM users WHERE id = ?");
        $res = $stmt->execute([$id]);

        if($res)
        {
            autoRedirectTo(SITE_HOME_URL . '/admin/users/?delete=success');
        }
        autoRedirectTo(SITE_HOME_URL . $_SERVER['REQUEST_URI'] . '?delete=failure');
    }

    if(empty($_POST['avatar']) || $_POST['avatar'] === '') {
        goto AFTER_FILE_UPLOAD;
    }

    $picturesPath = realpath('../wwwroot/img/avatars') .  '/';
    $input = $_POST['avatar'];
    $imageData = base64_decode(getBase64WithoutHeader($input));
    $finfo = finfo_open();
    $mime_type = finfo_buffer($finfo, $imageData, FILEINFO_MIME_TYPE);
    $split = explode( '/', $mime_type );
    $extension = $split[1];
    $pictureName = generateRandomToken() . '.' . $extension;
    file_put_contents($picturesPath . $pictureName, $imageData);
    $user['avatar'] = $pictureName;
    $stmt = $db->prepare("UPDATE users SET avatar = ? WHERE id = ?");
    $res = $stmt->execute([$pictureName, $id]);

    $_SESSION['avatar'] = $pictureName;



    AFTER_FILE_UPLOAD:

    if($password == null && $password_repeat == null || $password == "" && $password_repeat == "")
    {
        // No password change
        if(count($errors) === 0)
        {
            $query = "UPDATE users SET first_name = ?, last_name = ?, username = ?, email = ?, updated_at = ? WHERE id = ?";
            $stmt = $db->prepare($query);
            try
            {
                $res = $stmt->execute([$firstname, $lastname, $username, $email, $updated_at, $id]);

                $_SESSION['username'] = $username;
                goto END;
            }
            catch (\Exception $e)
            {
                $errors[] = 'مشکلی در هنگام به روز رسانی اطلاعات به وجود آمد. لطفا این مشکل را به ما گزارش دهید';
                return $view->render($currentView, ['success' => false, 'errors' => $errors, 'user' => $user]);
            }
        }
        else
        {
            // Error occurred
            return $view->render($currentView, ['success' => false, 'errors' => $errors, 'user' => $user]);
        }
    }
    else
    {
        if($password !== $password_repeat)
        {
//                die('passwords not the same');
            // Error, Passwords must match
            $errors[] = 'کلمه ی عبور و تکرار آن باید دقیقا یکسان باشند';
        }
        if(strlen($password) < 6 || strlen($password) > 16)
        {
//              die('password did not meet the req. count is ' . count($password) . ' and the password was: ' . $password);
            // Error, Password must be between 6 and 16 characters
            $errors[] = 'کلمه 
                عبور باید از مجموعه ای از اعداد و حروف الفبای انگلیسی بین 6 تا 16 کاراکتر تشکیل شده باشد';
        }
        if(count($errors) === 0)
        {
            $h_password = \NOMVC\Utils\PasswordV1::passwordHash($password);

            $query = "UPDATE users SET first_name = ?, last_name = ?, username = ?, email = ?, password = ?, updated_at = ? WHERE id = ?";

            $stmt = $db->prepare($query);
            try
            {
                $res = $stmt->execute([$firstname, $lastname, $username, $email, $h_password, $updated_at, $id]);

                $_SESSION['username'] = $username;
                goto END;
            }
            catch (\Exception $e)
            {
                $errors[] = 'مشکلی در هنگام به روز رسانی اطلاعات به وجود آمد. لطفا این مشکل را به ما گزارش دهید';
                return $view->render($currentView, ['success' => false, 'errors' => $errors, 'user' => $user]);
            }
        }
        else
        {
            // Error occurred
            return $view->render($currentView, ['success' => false, 'errors' => $errors, 'user' => $user]);
        }
    }

    END:
    autoRedirectTo(AUTO_SITE_HOME_URL . '/admin/users');

}