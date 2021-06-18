<?php

session_start();

if (!(isset($_SESSION['user_data']))) {
    header('Location: ./');
}

$error = '';
$message = '';

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


$user_object->setUserProfile($user_data['user_profile']);
$user_object->setUserName($user_data['user_name']);

if (isset($_POST['edit'])) {
    if ($_FILES['profile']['name'] != '') {
        $user_profile = $user_object->upload_image($_FILES['profile']);
        $_SESSION['user_data'][$user_id]['profile'] = $user_profile;
        $user_object->setUserProfile($user_profile);
    }
    if ($user_object->update($_POST['DOB'], $_POST['address'], $_POST['number'])) {
        $message = 'Profile Details Updated';
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
    <link rel="stylesheet" href="./css/app.min.css">
    <!-- <link rel="stylesheet" href="./css/app-dark.min.css"> -->
    <!-- <link rel="stylesheet" href="./css/bootstrap-dark.min.css"> -->
    <link rel="stylesheet" href="./css/bootstrap.min.css">
    <!-- <link rel="stylesheet" href="./css/icons.min.css"> -->

    <style>
        @media (min-width: 768px) {
            .img {
                width: 400px !important;
                height: auto;
            }

        }

        @media (min-width: 918px) {
            .img {
                width: 400px !important;
                height: 400px !important;
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
    if ($message != '') {
        echo '<article class="message is-success">
            <div class="message-header">
                <p>' . $message . '</p>
                <button class="delete" aria-label="delete"></button>
            </div>
        </article>';
    }
    ?>

    <div class="container-fluid">
        <form method="post" enctype="multipart/form-data">
            <div class="columns is-centered notification is-mobile is-multiline">
                <div class="column box">
                    <p class="notification is-success title">Profile</p>
                    <div class="columns is-multiline is-mobile is-centered">
                        <div class="column is-half">
                            <figure class="image is-square">
                                <img class="is-rounded img" id="profile_image" src="<?php echo $user_data['user_profile']; ?>">
                            </figure>
                            <span class="file is-warning is-boxed py-3">
                                <label class="file-label">
                                    <input class="file-input" type="file" name="profile" id="profile" accept="image/*" onchange="preview()">
                                    <span class="file-cta">
                                        <span class="file-icon">
                                            <i class="fas fa-cloud-upload-alt"></i>
                                        </span>
                                        <span class="file-label">
                                            Upload Profile....
                                        </span>
                                    </span>
                                </label>
                            </span>
                        </div>
                        <div class="column is-half-desktop is-full-mobile py-6 my-4">
                            <p class="title">Username : <input class="input is-success is-medium" type="text" name="username" value="<?php echo $user_data['user_name'] ?>" required></p>
                            <p class="title">Email : <input class="input is-success is-medium" type="text" value="<?php echo $user_data['user_email'] ?>" readonly></p>
                            <p class="title">Created on : <input class="input is-success is-medium" type="text" value="<?php echo $user_data['user_created_on'] ?>" readonly></p>
                            <p class="title">Date of Birth : <input class="input is-success is-medium" type="text" placeholder="YYYY-MM-DD" required title="Enter a date in this format YYYY-MM-DD" name="DOB" value="<?php echo $user_data['DOB'] ?>" required></p>
                            <p class="title">Address : <input class="input is-success is-medium" type="text" name="address" value="<?php echo $user_data['Address'] ?>" required></p>
                            <p class="title">Phone Number : <input class="input is-success is-medium" type="text" name="number" value="<?php echo $user_data['Phone_Number'] ?>" required></p>
                        </div>
                    </div>
                    <button type="submit" class="button is-large is-success" name="edit" Style="float:right;">Update</button>
                </div>
            </div>
        </form>

    </div>


    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
</body>

<script>
    var profile = document.getElementById('profile');
    var img = document.getElementById('profile_image')

    function preview() {
        const file = profile.files[0];
        if (file) {
            console.log(file)
            const reader = new FileReader();
            reader.onload = function(e) {
                const result = e.target.result;
                img.src = result;
            }
            reader.readAsDataURL(file);

        }
    }
</script>


</html>