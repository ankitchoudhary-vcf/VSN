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

require('database/Friends.php');
$friend_object = new Friends;
$friend_data = $friend_object->getALLFriend($user_id);
$otherConnection_data = $friend_object->getOtherConnection($user_id);
$friend_request_data = $friend_object->getFriendRequest($user_id);


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
    <link rel="stylesheet" href="./css/app.min.css">
    <!-- <link rel="stylesheet" href="./css/app-dark.min.css"> -->
    <!-- <link rel="stylesheet" href="./css/bootstrap-dark.min.css"> -->
    <!-- <link rel="stylesheet" href="./css/bootstrap.min.css"> -->
    <!-- <link rel="stylesheet" href="./css/icons.min.css"> -->
    <style>
        .dropdown-content {
            position: absolute;
            right: 100%;
        }

        @media(max-width: 1023px) {
            .dropdown-content {
                position: absolute;
                right: -50%;
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


    <input type="hidden" name="login_user_id" id="login_user_id" value="<?php echo $user_id; ?>">
    <input type="hidden" name="is_active_chat" id="is_active_chat" value="No" />
    <div class="container-fluid">
        <div class="columns is-centered notification is-mobile is-multiline">
            <div class="column is-one-third-desktop m-2">
                <article class="panel is-success user_list">
                    <p class="panel-heading">
                        Connection
                    </p>
                    <p class="panel-tabs">
                        <a class="is-active" id="friend-panel">Friends</a>
                        <a id="user-panel">Other Users</a>
                    </p>
                    <div id="Other_Users" style="display: none; height: 450px; overflow-y: scroll">
                        <?php
                        foreach ($otherConnection_data as $key => $friend) {
                        ?>
                            <a class="panel-block">
                                <article class="media">
                                    <figure class="media-left">
                                        <p class="image is-48x48">
                                            <img class="is-rounded" src="<?php echo $friend['user_profile']; ?>">
                                        </p>
                                    </figure>
                                    <div class="media-content">
                                        <div class="content">
                                            <p>
                                                <strong><?php echo $friend['user_name']; ?></strong>
                                            </p>
                                        </div>
                                    </div>
                                    <nav class="level m-2">
                                        <div class="level-right">
                                            <div class="level-item">
                                                <a class="button is-primary is-rounded" href="sendFriendRequest.php/?id=<?php echo $friend['user_id']; ?>">connect</a>
                                            </div>
                                        </div>
                                    </nav>
                                </article>
                            </a>

                        <?php
                        }
                        ?>
                    </div>
                    <div id="Friends" style="height: 450px; overflow-y: scroll;">
                        <?php
                        foreach ($friend_data as $key => $friend) {
                        ?>
                            <a class="panel-block select_user" data-username="<?php echo $friend['user_name']; ?>" data-profile="<?php echo $friend['user_profile']; ?>" data-id="<?php echo $friend['user_id']; ?>">
                                <article class="media">
                                    <figure class="media-left">
                                        <p class="image is-48x48">
                                            <img class="is-rounded" src="<?php echo $friend['user_profile']; ?>">
                                        </p>
                                    </figure>
                                    <div class="media-content">
                                        <div class="content">
                                            <p>
                                                <strong><?php echo $friend['user_name']; ?></strong>

                                            </p>
                                        </div>
                                    </div>
                                </article>
                            </a>

                        <?php
                        }
                        ?>
                    </div>
                </article>

                <div class="card" id="chat_area">

                </div>
            </div>
            <div class="column is-full-mobile m-2">
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

    var friend_btn = document.getElementById('friend-panel')
    var other_user_btn = document.getElementById('user-panel')
    var friend_panel = document.getElementById('Friends')
    var user_panel = document.getElementById('Other_Users')

    friend_btn.addEventListener('click', () => {
        friend_panel.style.display = 'block';
        user_panel.style.display = 'none';
        friend_btn.classList.add('is-active');
        other_user_btn.classList.remove('is-active');
    })

    other_user_btn.addEventListener('click', () => {
        friend_panel.style.display = 'none';
        user_panel.style.display = 'block';
        friend_btn.classList.remove('is-active');
        other_user_btn.classList.add('is-active');
    })
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



        var receiver_user_id = '';

        var conn_private = new WebSocket('ws://localhost:8282?token=<?php echo $token; ?>');

        conn_private.onopen = function(event) {
            console.log('Connection Established!');
        };
        conn_private.onmessage = function(event) {
            var data = JSON.parse(event.data);
            var html_data = '';
            if (data.from == 'Me') {
                html_data += "<li><div class='conversation-list'><div class='chat-avatar'><img src=" + data.user_profile + " alt=''></div><div class='user-chat-content'><div class='ctext-wrap'><div class='ctext-wrap-content'><p class='mb-0'>" + data.msg + "</p><p class='chat-time mb-0'><i class='ri-time-line align-middle'></i><span class='align-middle'>" + data.msgTime + "</span></p></div></div><div class='conversation-name'>" + data.from + "</div></div></div></li>";
            } else {
                html_data += "<li class='right'><div class='conversation-list'><div class='chat-avatar'><img src=" + data.user_profile + " alt=''></div><div class='user-chat-content'><div class='ctext-wrap'><div class='ctext-wrap-content'><p class='mb-0'>" + data.msg + "</p><p class='chat-time mb-0'><i class='ri-time-line align-middle'></i><span class='align-middle'>" + data.msgTime + "</span></p></div></div><div class='conversation-name'>" + data.from + "</div></div></div></li>";
            }
            if (receiver_user_id == data.userId || data.from == 'Me') {
                if ($('#is_active_chat').val() == 'Yes') {
                    $('#private_chat_area').append(html_data);
                    $('#private_chat_area').scrollTop($('#private_chat_area')[0].scrollHeight);
                    $('#private_chat_message').val('');
                }
            }


        };
        conn_private.onclose = function(event) {
            console.log('connection closed!');
        };


        function make_chat_area(receiver_user_name, receiver_user_profile) {
            var html = `<header class="card-header notification is-success p-0 mb-0">
                        <a class="card-header-icon back" style="text-decoration: none;">
                            <i class="fas fa-arrow-alt-circle-left"></i>
                        </a>
                        <article class="media m-2">
                            <figure class="media-left">
                                <p class="image is-48x48">
                                    <img class="is-rounded" src=` + receiver_user_profile + `>
                                </p>
                            </figure>
                            <div class="media-content" style="align-self: center; margin-top: 0%;">
                                <div class="content">
                                    <p>
                                        <strong>` + receiver_user_name + `</strong>
                                    </p>
                                </div>
                            </div>
                        </article>

                    </header>
                    <div class="card-content" id="private_chat_message" style="height: 400px; overflow-y: scroll;">

                    </div>
                    <footer class="card-footer notification is-success is-light p-0">
                        <form method="post" id="private_chat_form enctype="multipart/form-data" class="m-2" style="display: inline-flex;">
                            <input type="text" name="message" class="input is-rounded is-primary" placeholder="Enter message...." style="align-self: center;">
                            <button type="submit" class="button is-primary is-rounded m-2" name="send"><i class="fas fa-paper-plane" style="transform: rotate(45deg);"></i></button>
                        </form>
                    </footer>`;

            $('#chat_area').html(html);

        }

        $(document).on('click', '.select_user', function() {

            receiver_user_id = $(this).data('id');
            var from_user_id = $('#login_user_id').val();
            var receiver_user_name = $(this).data('username');
            var receiver_user_profile = $(this).data('profile');


            $('.select_user.is-active').removeClass('is-active');
            $('.user_list').css('display', 'none');
            $('#chat_area').css('display', 'block');
            $(this).addClass('is-active');

            make_chat_area(receiver_user_name, receiver_user_profile);

            $('#is_active_chat').val('Yes');

        });

        $(document).on('click', '.back', function() {

            $('.select_user.is-active').removeClass('is-active');
            $('#chat_area').css('display', 'none');
            $('.user_list').css('display', 'block');
            receiver_user_id = '';
            void(0);
        })
    });
</script>

</html>