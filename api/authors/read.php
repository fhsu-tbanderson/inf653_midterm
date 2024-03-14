<?php

    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');

    include_once '../../config/Database.php';
    include_once('../../models/Author.php');

    $database = new Database();
    $database_connection = $database->connect();

    $author = new Author($database_connection);
    $result = $author->read();
    $result_count = $result->rowCount();

    if($result_count > 0)
    {
        $result_array = array();

        while($row = $result->fetch(PDO::FETCH_ASSOC))
        {
            extract($row);

            $item = array(
                'id'=> $id,
                'author'=> $author
            );

            array_push($result_array, $item);
        }

        echo json_encode($result_array);
    }
    else
    {
        $error_response = array('message'=>'No Authors Found');
        echo json_encode($error_response);
    }

    


