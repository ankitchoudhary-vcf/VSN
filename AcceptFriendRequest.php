<?php

session_start();

if (!(isset($_SESSION['user_data']))) {
    header('Location: ./');
}

require('database/User.php');

$user_object = new User;

$user_id = '';
$token = '';

foreach ($_SESSION['user_data'] as $key => $value) {
    $user_id = $value['id'];
}

if (isset($_GET['id'])) {
    $to = $_GET['id'];

    require('database/Friends.php');
    $friend_object = new Friends;
    $friend_object->setRequest_From_id($user_id);
    $friend_object->setRequest_To_id($to);
    $friend_object->setStatus('Accepted');

    $friend_object->AcceptFriendRequest();

    if (isset($_SERVER["HTTP_REFERER"])) {
        header("Location: " . $_SERVER["HTTP_REFERER"]);
    }
}

?>
