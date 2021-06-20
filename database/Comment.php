<?php

class Comment
{
    private $id;
    private $post_id;
    private $user_id;
    private $comment;
    private $connect;

    function __construct(){ 
        require_once('Database_connection.php');
        $database_object = new Database_connection;
        $this->connect = $database_object->connect();
    }

    function setUserId($user_id){ 
        $this->user_id = $user_id;
    }
    function setPostId($post_id){
        $this->post_id = $post_id;
    }
    function setComment($comment){
        $this->comment = $comment;
    }

    function getAllCommentsBypost_id(){
        $query = "SELECT * FROM comment WHERE post_id = $this->post_id ORDER BY id DESC";
        $statement = $this->connect->prepare($query);
        $statement->execute();
        $data  = $statement->fetchAll(PDO::FETCH_ASSOC);
        return $data;
    }
    function saveComment(){
        $query = "INSERT INTO comment (post_id, comments, user_id) VALUES($this->post_id, '$this->comment', $this->user_id)";
        $statement = $this->connect->prepare($query);
        if($statement->execute())
        {
            return true;
        }
    }

}

?>