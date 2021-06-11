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

$user_private_data = $user_object->get_user_all_data_with_status_count();

require('database/ChatRooms.php');

$chat_object = new ChatRooms;
$chat_data = $chat_object->get_all_chat_data();

require('database/Post.php');
$post_object = new Post;
$post_data = $post_object->getAll_posts();


if (isset($_POST['post'])) {
    $post_object->setUser_id($user_id);

    if (empty($_POST['text']) && (empty($_FILES['media']['name']))) {
        $error = 'Invalid Input';
    } elseif (empty($_POST['text']) && ((!empty($_FILES['media']['name'])))) {
        $post_object->setPost_type('Media');
        $post_object->setMedia_post($post_object->UploadMedia_post($_FILES['media']));
        $post_object->save_post();
        header("Refresh:0");
    } elseif (!(empty($_POST['text'])) && (empty($_FILES['media']['name']))) {
        $post_object->setPost_type('Text');
        $post_object->setPost_content($_POST['text']);
        $post_object->save_post();
        header("Refresh:0");
    } else {
        $post_object->setPost_type('Media');
        $post_object->setMedia_post($post_object->UploadMedia_post($_FILES['media']));
        $post_object->setPost_content($_POST['text']);
        $post_object->save_post();
        header("Refresh:0");
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
        <div class="columns is-centered notification is-mobile">
            <div class="column m-2 is-hidden-mobile box">
            </div>
            <div class="column is-half-desktop is-full-mobile m-2">
                <div class="container">
                    <div class="card">
                        <form method="post" enctype="multipart/form-data">
                            <header class="card-header notification is-light p-0">
                                <p class="card-header-title">
                                    Create Post
                                </p>
                                <div class="card-header-icon">
                                    <label class="file-label">
                                        <input class="file-input" id="file" type="file" accept=".png, .jpg, .gif, .jpeg" name="media">
                                        <span class="file-cta" style="background: #23D182;">
                                            <span class="file-label">
                                                Add Files...
                                            </span>
                                        </span>
                                    </label>
                                </div>
                            </header>
                            <div class="card-content">

                                <textarea id="text_content" class="textarea" name="text" placeholder="Write Something....."></textarea>
                                <article class="image">
                                    <img id="image_content">
                                </article>
                            </div>
                            <footer class="card-footer notification is-light p-0">
                                <button type="submit" class="button is-primary m-2" name="post">POST</button>
                            </footer>
                        </form>
                    </div>
                </div>

                <div class="container">
                    <?php
                    foreach ($post_data as $key => $post) {
                    ?>
                        <div class="card" style="margin-top:10%;">
                            <header class="card-header notification is-success is-light p-0">
                                <a class="image m-2" href="#" style="width: 40px; height: 40px; display: flex; text-decoration: none;" title="Profile">
                                    <img class="is-rounded mr-2" src="<?php echo $user_object->get_user_profile_by_id($post['user_id'])['user_profile']; ?>" alt="Profile">
                                    <p class="has-text-weight-bold is-size-4"><?php echo $user_object->get_user_name_by_id($post['user_id'])['user_name']; ?></p>
                                </a>
                            </header>
                            <div class="card-content">
                                <?php
                                if (!(empty($post['post_content']))) {
                                ?>
                                    <p class="content">
                                        <?php echo $post['post_content']; ?>
                                    </p>
                                <?php
                                }
                                ?>
                                <?php

                                if (!(empty($post['media_post']))) {
                                ?>

                                    <article class="image is-256x256">
                                        <img src="<?php echo $post['media_post'] ?>" alt="post">
                                    </article>

                                <?php
                                }

                                ?>
                            </div>
                        </div>
                    <?php
                    }
                    ?>
                </div>
            </div>
            <div class="column m-2 is-hidden-mobile box">
            </div>
        </div>
    </div>


    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
</body>
<script>
    var media = document.querySelector('#file');
    var image = document.querySelector('#image_content');
    media.onchange = event => {
        const [file] = media.files;
        if (file) {
            image.src = URL.createObjectURL(file);
            image.style.width = '200px';
            image.style.marginTop = '15px';
        }
    }
</script>
<script>
    $(document).ready(function() {
        $('.navbar-burger').click(function() {
            $('.navbar-menu').toggleClass('is-active');
        })

        $('#friend_request_area').click(function() {
            $('.dropdown').toggleClass('is-active');
        })

        $('.delete').click(function() {
            $('.message').css('display', 'none');
        })
    })
</script>

</html>