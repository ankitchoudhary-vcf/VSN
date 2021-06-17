<?php

session_start();

if (!(isset($_SESSION['user_data']))) {
    header('Location: ./');
}

$error = '';

require('database/User.php');

$user_object = new User;

$user_id = '';
$token = '';

foreach ($_SESSION['user_data'] as $key => $value) {
    $user_id = $value['id'];
    $token = $value['token'];
}

$user_object->setUserId($user_id);


$user_data = $user_object->get_user_data_by_id();

$users_data = $user_object->get_user_all_data();


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
    <link rel="stylesheet" href="./css/app.min.css">
    <!-- <link rel="stylesheet" href="./css/app-dark.min.css"> -->
    <!-- <link rel="stylesheet" href="./css/bootstrap-dark.min.css"> -->
    <link rel="stylesheet" href="./css/bootstrap.min.css">
    <!-- <link rel="stylesheet" href="./css/icons.min.css"> -->

    <style>
        @media (min-width: 768px) {
            .img{
            width: 400px !important;
            height:auto;
        }
            
        }
        @media (min-width: 918px) {
            .img{
            width: 400px !important;
            height:400px !important;
        }
            
        }
    </style>
</head>

<body>

    <?php
    include('header.php');

    ?>

    <?php

    if ($error != '') {
        echo '<article class="message is-danger">
            <div class="message-header">
                <p>' . $error . '</p>
                <button class="delete" aria-label="delete"></button>
            </div>
        </article>';
    }
    ?>

    <div class="container-fluid">
        <div class="columns is-centered notification is-mobile is-multiline">
            <div class="column box">
                <p class="notification is-success title">Profile</p>
                <div class="columns is-multiline is-mobile is-centered">
                    <div class="column is-half">
                        <figure class="image is-square">
                            <img class="is-rounded img" src="<?php echo $user_data['user_profile']; ?>">
                        </figure>
                    </div>
                    <div class="column is-half-desktop is-full-mobile py-6 my-4">
                        <p class="title">Username : <span class="title is-4"><?php echo $user_data['user_name']?></span></p>
                        <p class="title">Email : <span class="title is-4"><?php echo $user_data['user_email']?></span></p>
                        <p class="title">Created on : <span class="title is-4"><?php echo $user_data['user_created_on']?></span></p>
                    </div>
                </div>

            </div>
        </div>


    </div>


    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
</body>


</html>