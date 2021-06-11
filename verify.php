<?php

$error  = '';

session_start();

if(isset($_GET['code']))
{
    require_once('database/User.php');

    $user_object = new User;
    $user_object->setUserVerificationCode($_GET['code']);

    if($user_object->is_valid_email_verification_code())
    {
        $user_object->setUserStatus('Enable');

        if($user_object->enable_user_account())
        {
            $_SESSION['success_message'] = 'Your Email Successfully verify, now you can login into this VSN Application';
            header('location:index.php');
        }
    }
    else
    {
        $error = 'something went wrong try again....';
    }
}

?>