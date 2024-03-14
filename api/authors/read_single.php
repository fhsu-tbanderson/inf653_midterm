<?php
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');

    include_once '../../config/Database.php';
    include_once('../../models/Author.php');

    $database = new Database();
    $database_connection = $database->connect();

    $author = new Author($database_connection);

    //Get id if it was passed
    $author->id = isset($_GET['id']) ? $_GET['id'] : die();

    $author->readSingle();

    if($author->author)
    {
        $result_array = array(
            'id'=> $author->id,
            'author'=> $author->author
        );

    }
    else
    {
        $result_array = array('message' => 'author_id Not Found');
    }


    print_r(json_encode($result_array));
