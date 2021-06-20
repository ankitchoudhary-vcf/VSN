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

require('./database/Comment.php');
$comment_object = new Comment;


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

if (isset($_POST['sendcomment'])) {

    echo $_POST['post_id'];
    echo $_POST['comment'];

    $comment_object->setPostId($_POST['post_id']);
    $comment_object->setUserId($user_id);
    $comment_object->setComment($_POST['comment']);

    $comment_object->saveComment();
    header("Refresh:0");


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


    <input type="hidden" name="login_user_id" id="login_user_id" value="<?php echo $user_id; ?>" />

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
                        <ul class="list-unstyled chat-list chat-user-list">

                            <?php
                            foreach ($otherConnection_data as $key => $friend) {
                            ?>

                                <li>
                                    <div class="list-group-item list-group-action">
                                        <div class="media">
                                            <div class="chat-user-img  align-self-center mr-3">
                                                <img src="<?php echo $friend['user_profile']; ?>" ; class="rounded-circle avatar-xs" alt="">
                                            </div>
                                            <div class="media-body overflow-hidden" style="display:flex;">
                                                <h5 class="text-truncate font-size-15 mb-1"><?php echo $friend['user_name']; ?></h5>
                                                <span class="ml-4"><a class="button is-primary is-rounded py-0 px-2" href="sendFriendRequest.php/?id=<?php echo $friend['user_id']; ?>" style="height: auto;">connect</a></span>
                                            </div>
                                        </div>

                                    </div>
                                </li>

                            <?php
                            }
                            ?>
                        </ul>
                    </div>
                    <div id="Friends" style="height: 450px; overflow-y: scroll;">

                        <ul class="list-unstyled chat-list chat-user-list">
                            <?php
                            foreach ($friend_data as $key => $friend) {

                                foreach ($user_private_data as $key => $user) {
                                    if ($user['user_id'] == $friend['user_id']) {
                                        if ($user['user_login_status'] == 'Login') {
                                            $status = "online";
                                        } else {
                                            $status = "away";
                                        }
                                        if ($user['count_status'] > 0) {
                                            echo '
                                                <li class="unread">
                                                    <a href="#" class ="list-group-item list-group-action select_user" style="cursor:pointer;" data-id=' . $user['user_id'] . ' id="' . $user['user_id'] . '"  data-username=' . $user['user_name'] . ' data-profile =' . $user['user_profile'] . ' >
                                                        <div class="media">
                                                            <div class="chat-user-img ' . $status . ' align-self-center mr-3">
                                                                <img src="' . $user['user_profile'] . '";
                                                                    class="rounded-circle avatar-xs" alt="">
                                                                <span class="user-status" style="bottom:4px;"></span>
                                                            </div>
                                                            <div class="media-body overflow-hidden">
                                                                <h5 class="text-truncate font-size-15 mb-1">' . $user['user_name'] . '</h5>
                                                                <span id="userLs_' . $user['user_id'] . '" class="chat-user-message text-truncate mb-0"></span>
                                                            </div>

                                                            <div class="unread-message">
                                                                <span id="userid_' . $user['user_id'] . '" class="badge badge-soft-danger badge-pill">' . $user['count_status'] . '</span>
                                                            </div>
                                                        </div>
                                                    </a>
                                                </li>
                                                ';
                                        } else {
                                            echo '
                                                <li>
                                                    <a href="#" class ="list-group-item list-group-action select_user" style="cursor:pointer;" data-id=' . $user['user_id'] . ' id="' . $user['user_id'] . '" data-username=' . $user['user_name'] . ' data-profile =' . $user['user_profile'] . ' >
                                                        <div class="media">
                                                            <div class="chat-user-img ' . $status . ' align-self-center mr-3">
                                                                <img src="' . $user['user_profile'] . '";
                                                                    class="rounded-circle avatar-xs" alt="">
                                                                <span class="user-status" style="bottom:4px;"></span>
                                                            </div>
                                                            <div class="media-body overflow-hidden">
                                                                <h5 class="text-truncate font-size-15 mb-1">' . $user['user_name'] . '</h5>
                                                                <span id="userLs_' . $user['user_id'] . '" class="chat-user-message text-truncate mb-0"></span>
                                                            </div>
                                                        </div>
                                                    </a>
                                                </li>
                                                ';
                                        }
                                    }
                                }
                            }

                            ?>
                        </ul>

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
                            <footer>
                                <form method="post" enctype="multipart/form-data" style="display:inline-flex;">
                                    <input type="hidden" name="post_id" value="<?php echo $post['post_id']; ?>" >
                                    <input type="text" name="comment" class="input is-rounded is-primary" placeholder="Enter comment...." style="align-self: center;">
                                    <button type="submit" class="button is-primary is-rounded m-2" name="sendcomment"><i class="fas fa-paper-plane" style="transform: rotate(45deg);"></i></button>
                                </form>
                                <div class="notification content is-light">
                                    <ul class='list-unstyled mb-0'>

                                        <?php
                                        $comment_object->setPostId($post['post_id']);
                                        $comment_data = $comment_object->getAllCommentsBypost_id();


                                        foreach ($comment_data as $key => $comment) {
                                            $name = $user_object->get_user_name_by_id($post['user_id']);
                                            $profile = $user_object->get_user_profile_by_id($post['user_id']);
                                        ?>

                                            <li class='right'>
                                                <div class='conversation-list'>
                                                    <div class='chat-avatar'>
                                                        <img src="<?php echo $profile['user_profile']; ?>" alt=''>
                                                    </div>
                                                    <div class='user-chat-content'>
                                                        <div class='ctext-wrap'>
                                                            <div class='ctext-wrap-content'>
                                                                <p class='mb-0'> <?php echo $comment['comments']; ?></p>
                                                                <p class='chat-time mb-0'>
                                                                    <i class='ri-time-line align-middle'></i>
                                                                    <span class='align-middle'> <?php echo $comment['created_on'] ?> </span>
                                                                </p>
                                                            </div>
                                                        </div>
                                                        <div class='conversation-name'><?php echo $name['user_name']; ?></div>
                                                    </div>
                                                </div>
                                            </li>

                                        <?php
                                        }
                                        ?>
                                    </ul>
                                </div>
                            </footer>
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
    var media = document.querySelector(' #file');
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
                        <a class="card-header-icon user-private-chat-remove" style="text-decoration: none;">
                            <i class="fas fa-arrow-alt-circle-left"></i>
                        </a>
                        <article class="media m-2">
                            <figure class="media-left">
                                <p>
                                    <img class="rounded-circle avatar-xs" src=` + receiver_user_profile + `>
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
                    <div class="card-content chat-conversation" style="height: 400px; overflow-y: scroll;">
                    <ul class='list-unstyled mb-0' id='private_chat_area'></ul>
                    </div>
                    <footer class="card-footer notification is-success is-light p-0">
                        <form method="post" id="private_chat_form" enctype="multipart/form-data" class="m-2" style="display: inline-flex;">
                            <input type="text" name="chat_message"  id="private_chat_message" class="input is-rounded is-primary" placeholder="Enter message...." style="align-self: center;">
                            <button type="submit" class="button is-primary is-rounded m-2" name="sendmsg" id="sendmsg"><i class="fas fa-paper-plane" style="transform: rotate(45deg);"></i></button>
                        </form>
                    </footer>`;

            $('#chat_area').html(html);
            // $('#private_chat_form').parsley();

        }

        $(document).on('click', '.select_user', function() {

            conn_private = new WebSocket('ws://localhost:8282?token=<?php echo $token; ?>');
            conn_private.onopen = function(event) {
                console.log('Connection Established!');
            };
            conn_private.onmessage = function(event) {
                var data = JSON.parse(event.data);
                var html_data = '';
                if (data.from == 'Me') {
                    html_data += "<li><div class='conversation-list'><div class='chat-avatar'><img src=" + data.sender_profile + " alt=''></div><div class='user-chat-content'><div class='ctext-wrap'><div class='ctext-wrap-content'><p class='mb-0'>" + data.msg + "</p><p class='chat-time mb-0'><i class='ri-time-line align-middle'></i><span class='align-middle'>" + data.msgTime + "</span></p></div></div><div class='conversation-name'>" + data.from + "</div></div></div></li>";
                } else {
                    html_data += "<li class='right'><div class='conversation-list'><div class='chat-avatar'><img src=" + data.sender_profile + " alt=''></div><div class='user-chat-content'><div class='ctext-wrap'><div class='ctext-wrap-content'><p class='mb-0'>" + data.msg + "</p><p class='chat-time mb-0'><i class='ri-time-line align-middle'></i><span class='align-middle'>" + data.msgTime + "</span></p></div></div><div class='conversation-name'>" + data.from + "</div></div></div></li>";
                }
                if (receiver_user_id == data.userId || data.from == 'Me') {
                    if ($('#is_active_chat').val() == 'Yes') {
                        $('#private_chat_area').append(html_data);
                        $('#private_chat_area').scrollTop($('#private_chat_area')[0].scrollHeight);
                        $('#private_chat_message').val('');
                        $('#userLs_' + receiver_user_id).html(data.msg);
                    } else {
                        var count_chat = $('#userid_' + data.userId).text();

                        if (count_chat == '') {
                            count_chat = 0;
                        }

                        count_chat++;

                        $('#userid_' + data.userId).html('<span class="badge badge-danger badge-pill">' + count_chat + '</span>');
                    }
                }

            };
            conn_private.onclose = function(event) {
                console.log('connection closed!');
            };


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

            $.ajax({
                url: 'action.php',
                method: 'POST',
                data: {
                    to_user_id: receiver_user_id,
                    from_user_id: from_user_id,
                    action: 'fetch_chat'
                },
                success: function(data) {
                    data = JSON.parse(data);
                    if (data.length > 0) {
                        var html_data = '';

                        for (var count = 0; count < data.length; count++) {
                            if (data[count].from_user_id == from_user_id) {
                                html_data += `
                                    <li>
                                        <div class="conversation-list">
                                            <div class="chat-avatar">
                                                <img src=` + data[count].from_user_profile + ` alt="">
                                            </div>
                                            <div class="user-chat-content">
                                                <div class="ctext-wrap">
                                                    <div class="ctext-wrap-content">
                                                        <p class="mb-0">` + data[count].chat_message + `</p>
                                                        <p class="chat-time mb-0">
                                                            <i class="ri-time-line align-middle"></i>
                                                            <span class="align-middle">` + data[count].timestamp + `</span>
                                                        </p>
                                                    </div>
                                                </div>
                                            <div class="conversation-name">Me</div>
                                        </div>
                                    </li>`;
                            } else {
                                html_data += `
                                            <li class='right'>
                                                <div class='conversation-list'>
                                                    <div class='chat-avatar'>
                                                        <img src=` + data[count].from_user_profile + ` alt=''>
                                                    </div>
                                                    <div class='user-chat-content'>
                                                        <div class='ctext-wrap'>
                                                            <div class='ctext-wrap-content'>
                                                                <p class='mb-0'>` + data[count].chat_message + `</p>
                                                                <p class='chat-time mb-0'>
                                                                    <i class='ri-time-line align-middle'></i>
                                                                    <span class='align-middle'>` + data[count].timestamp + `</span>
                                                                </p>
                                                            </div>
                                                        </div>
                                                        <div class='conversation-name'>` + data[count].from_user_name + `</div>
                                                    </div>
                                                </div>
                                            </li>`;
                            }

                            $('#userid_' + receiver_user_id).html('');
                            $('#userLs_' + receiver_user_id).html(data[count].chat_message);
                            $('#private_chat_area').html(html_data);
                            $('#private_chat_area').scrollTop($('#private_chat_area')[0].scrollHeight);
                        }
                    }

                }
            })
        })

        $(document).on('submit', '#private_chat_form', function(event) {

            event.preventDefault();

            var user_id = $('#login_user_id').val();
            var message = $('#private_chat_message').val();
            var data = {
                userId: user_id,
                msg: message,
                receiver_userId: receiver_user_id,
                command: 'Private'
            };

            $('#private_chat_message').val('');
            conn_private.send(JSON.stringify(data));

            // if ($('#private_chat_form').parsley().isValid()) {


            // }
        });

        $(document).on('click', '.user-private-chat-remove', function() {

            $('.select_user.is-active').removeClass('is-active');
            $('#chat_area').css('display', 'none');
            $('.user_list').css('display', 'block');
            receiver_user_id = '';
            void(0);
        })
    });
</script>

</html>