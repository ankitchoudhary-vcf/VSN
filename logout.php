<?php

session_start();

if(isset($_POST['logout']))
{

    
    require('database/User.php');

    $user_object = new User;

    $user_id = '';

    foreach ($_SESSION['user_data'] as $key => $value) {
        $user_id = $value['id'];
    }

    $user_object->setUserId($user_id);
    $user_object->setUserLoginStatus('Logout');

    if($user_object->update_user_login_data())
    {
        unset($_SESSION['user_data']);
        session_destroy();

        header('Location: index.php');
    }

}

?>