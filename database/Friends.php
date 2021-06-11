<?php

class Friends
{

    private $request_from_id;
    private $request_to_id;
    private $status;

    public function __construct()
    {
        require_once('Database_connection.php');

        $database_object = new Database_connection;
        $this->connect = $database_object->connect();
    }

    function setRequest_From_id($request_from_id)
    {
        $this->request_from_id = $request_from_id;
    }
    function setRequest_To_id($request_to_id)
    {
        $this->request_to_id = $request_to_id;
    }
    function setStatus($status)
    {
        $this->status = $status;
    }
    function getStatus()
    {
        return $this->status;
    }
    function getRequest_From_id()
    {
        return $this->request_from_id;
    }
    function getRequest_To_id()
    {
        return $this->request_to_id;
    }

    function sendFriendRequest()
    {
        $query = "INSERT INTO friend_request(request_from_id, request_to_id, request_status) VALUE (:request_from_id, :request_to_id, :request_status)";

        $statement = $this->connect->prepare($query);
        $statement->BindParam(':request_from_id', $this->request_from_id);
        $statement->BindParam(':request_to_id', $this->request_to_id);
        $statement->BindParam(':request_status', $this->status);

        $statement->execute();
    }

    function getALLFriend($request_from_id)
    {
        $query = "SELECT * FROM chat_user_table JOIN friend_request ON friend_request.request_to_id = user_id WHERE friend_request.request_from_id = $request_from_id AND friend_request.request_status = 'Accepted'";

        $statement = $this->connect->prepare($query);

		$statement->execute();

		return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    function getOtherConnection($request_from_id)
    {
        $query = "SELECT * FROM chat_user_table  WHERE user_id !=$request_from_id AND NOT EXISTS (SELECT *  FROM friend_request WHERE user_id = friend_request.request_to_id)";

        $statement = $this->connect->prepare($query);

		$statement->execute();

		return $statement->fetchAll(PDO::FETCH_ASSOC);
    }
}

?>