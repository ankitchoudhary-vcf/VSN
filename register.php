<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';


$error = '';
$success_message = '';

session_start();

if (isset($_SESSION['user_data'])) {
    header('location: dashboard.php');
}

if (isset($_POST['register'])) {
    require_once('database/User.php');

    $user_object = new User;
    $user_object->setUserName($_POST['user_name']);
    $user_object->setUserEmail($_POST['user_email']);
    $user_object->setUSerPassword($_POST['user_password']);
    $user_object->setUserProfile($user_object->make_avatar(strtoupper($_POST['user_name'][0])));
    $user_object->setUserStatus('Disable');
    $user_object->setUserLoginStatus('Logout');
    $user_object->setUserCreateOn(date('Y-m-d H:i:s'));
    $user_object->setUserVerificationCode(md5(uniqid()));
    $user_data = $user_object->get_user_data_by_email();

    if (is_array($user_data) && count($user_data) > 0) {
        $error = 'This Email Already Register';
    } else {
        if ($user_object->save_data()) {
            $mail = new PHPMailer(true);
            $mail->IsSMTP();
            $mail->isHTML(true);
            $mail->SMTPDebug  = 0;
            $mail->SMTPAuth   = true;
            $mail->SMTPSecure = "ssl";
            $mail->Host       = "smtp.gmail.com";
            $mail->Port        = '465';
            $mail->Username = 'aicephotoc@gmail.com';
            $mail->Password = 'aicephotoc@123';
            $mail->setFrom('aicephotoc@gmail.com', 'VSN');
            $mail->addAddress($user_object->getUserEmail());
            $mail->Subject = 'Registration Verification for VSN Application';
            $mail->Body = '
            <p>Thank you for registering for VSN Application.</p>
                <p>This is a verification email, please click on the link to verify your email address.</p>
                <p><a href="http://localhost/VSN/verify.php?code=' . $user_object->getUserVerificationCode() . '">Click to Verify</a></p>
                <p>Thank you....</p>
            ';

            $mail->send();

            $success_message = 'Verification Email sent to ' . $user_object->getUserEmail() . ', so before login first verify your account';
        } else {
            $error  = 'Something went wrong try again';
        }
    }
}



?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.2/css/bulma.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.12.0/css/all.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <title>VNS</title>
    <link rel="shortcut icon" href="assets/images/favicon.ico">
    <style>
        html {
            background: #209cee;
        }

        .login-container {
            margin: 0;
            position: absolute;
            top: 50%;
            left: 50%;
            -ms-transform: translate(-50%, -50%);
            transform: translate(-50%, -50%);
            width: 70%;
        }

        @media (max-width: 467px) {
            .login-container {
                width: 100%;
            }
        }
    </style>
</head>

<body>
    <?php

    if ($success_message != '') {
        echo '<article class="message is-success">
            <div class="message-header">
                <p>' . $success_message . '</p>
                <button class="delete" aria-label="delete"></button>
            </div>
        </article>';


        unset($_SESSION['success_message']);
    }

    if ($error != '') {
        echo '<article class="message is-danger">
            <div class="message-header">
                <p>' . $error . '</p>
                <button class="delete" aria-label="delete"></button>
            </div>
        </article>';
    }

    ?>


    <div class="login-container">
        <div class="columns is-centered box">
            <div class="column">
                <p class="title has-text-centered"><i>Welcome to the VSN!</i></p>
                <p class="title has-text-centered is-4 has-text-info">Register your VSN Account</p>
                <form method="post" enctype="multipart/form" id="register-form">
                    <div class="field">
                        <label class="label">Password</label>
                        <div class="control has-icons-left has-icons-right">
                            <input class="input is-success" type="text" name="user_name" placeholder="Enter your name" required>
                            <span class="icon is-small is-left">
                                <i class="fas fa-user"></i>
                            </span>
                        </div>
                    </div>
                    <div class="field">
                        <label class="label">Username</label>
                        <div class="control has-icons-left has-icons-right">
                            <input class="input is-success" type="email" name="user_email" placeholder="Enter your Email" required>
                            <span class="icon is-small is-left">
                                <i class="fas fa-envelope"></i>
                            </span>

                        </div>
                    </div>
                    <div class="field">
                        <label class="label">Password</label>
                        <div class="control has-icons-left has-icons-right">
                            <input class="input is-success" type="password" name="user_password" placeholder="Enter your Password" required>
                            <span class="icon is-small is-left">
                                <i class="fas fa-lock"></i>
                            </span>
                        </div>
                    </div>
                    <div class="field is-grouped-right mt-6">
                        <div class="control">
                            <button type="submit" class="button is-rounded is-warning" name="register" id="register">Register</button>
                        </div>
                    </div>
                    <div class="field has-text-centered is-italic is-family-monospace">
                        <p>If already registered <a class="link" href="index.php">Login</a>!</p>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
</body>
<script>
    $(document).ready(function() {
        $('#register-form').submit(function() {
            $('#register').addClass('is-loading');
        })
        $('.delete').click(function() {
            $('.message').css('display', 'none');
        })
    })
</script>

</html>