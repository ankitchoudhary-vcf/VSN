<?php

session_start();

$error = '';

if (isset($_SESSION['user_data'])) {
    header('location: dashboard.php');
}

if (isset($_POST['login'])) {

    require_once('database/User.php');
    $user_object = new User;
    $user_object->setUserEmail($_POST['user_email']);
    $user_data = $user_object->get_user_data_by_email();

    if (is_array($user_data) && count($user_data) > 0) {
        if ($user_data['user_status'] == 'Enable') {
            if ($user_data['user_password'] == $_POST['user_password']) {
                $user_object->setUserId($user_data['user_id']);
                $user_object->setUserLoginStatus('Login');

                $user_token = md5(uniqid());
                $user_object->setUserToken($user_token);

                if ($user_object->update_user_login_data()) {
                    $_SESSION['user_data'][$user_data['user_id']] = [
                        'id' => $user_data['user_id'],
                        'name' => $user_data['user_name'],
                        'profile' => $user_data['user_profile'],
                        'token' => $user_token
                    ];
                    header('location: dashboard.php');
                }
            } else {
                $error = 'Wrong Password';
            }
        } else {
            $error = 'Please Verify YOur Email Address';
        }
    } else {
        $error = 'Wrong Email Address';
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

    if (isset($_SESSION['success_message'])) {
        echo '<article class="message is-success">
            <div class="message-header">
                <p>' . $_SESSION["success_message"] . '</p>
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
                <p class="title has-text-centered is-4 has-text-info">Login with your VSN Account</p>
                <form method="post" enctype="multipart/form" id="login-form">
                    <div class="field">
                        <label class="label">Username</label>
                        <div class="control has-icons-left has-icons-right">
                            <input class="input is-success" type="email" name="user_email" placeholder="Enter your Email" required>
                            <span class="icon is-small is-left">
                                <i class="fas fa-user"></i>
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
                            <button type="submit" class="button is-rounded is-success" name="login" id="login">Login</button>
                        </div>
                    </div>
                    <div class="field has-text-centered is-italic is-family-monospace">
                        <p>Create a new Account <a class="link" href="register.php">Register new account</a>!</p>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
</body>
<script>
    $(document).ready(function(){
        $('#login-form').submit(function(){
            $('#login').addClass('is-loading');
        })

        $('.delete').click(function(){
            $('.message').css('display', 'none');
        })
    })
</script>

</html>