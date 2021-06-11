
<?php

class Post
{
    private $post_id;
    private $user_id;
    private $post_content;
    private $post_type;
    private $media_post;

    public function __construct()
    {
        require_once('Database_connection.php');

        $database_object = new Database_connection;
        $this->connect = $database_object->connect();
    }

    function setPost_id($post_id)
    {
        $this->post_id = $post_id;
    }
    function getPost_id()
    {
        return $this->post_id;
    }

    function setUser_id($user_id)
    {
        $this->user_id = $user_id;
    }
    function getUser_id()
    {
        return $this->user_id;
    }

    function setPost_content($post_content)
    {
        $this->post_content = $post_content;
    }
    function getPost_content()
    {
        return $this->post_content;
    }

    function setPost_type($post_type)
    {
        $this->post_type = $post_type;
    }
    function getPost_type()
    {
        return $this->post_type;
    }

    function setMedia_post($media_post)
    {
        $this->media_post = $media_post;
    }
    function getMedia_post()
    {
        return $this->media_post;
    }

    function UploadMedia_post($media_post)
    {
        $extension = explode('.', $media_post['name']);
        $new_name = rand(). '.'.$extension[1];
        $destination = 'images/' . $new_name;
        move_uploaded_file($media_post['tmp_name'], $destination);
        return $destination;
    }

    function save_post(){
        $query = "INSERT INTO posts(user_id, post_content, post_type,  media_post) VALUES(:user_id, :post_content, :post_type, :media_post)";

        $statement = $this->connect->prepare($query);
        $statement->BindParam(':user_id', $this->user_id);
        $statement->BindParam(':post_content', $this->post_content);
        $statement->BindParam(':post_type', $this->post_type);
        $statement->BindParam(':media_post', $this->media_post);

        $statement->execute();
    }

    function getAll_posts() {
        $query = "SELECT * FROM posts ORDER BY post_id DESC";

		$statement = $this->connect->prepare($query);

		$statement->execute();

		return $statement->fetchAll(PDO::FETCH_ASSOC);
    }
}


?>
