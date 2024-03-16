<?php
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: POST');
    header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, categoryization, X-Requested-With');

    include_once '../../config/Database.php';
    include_once('../../models/Author.php');

    $database = new Database();
    $database_connection = $database->connect();

    $author = new Author($database_connection);
    $requestBody = json_decode(file_get_contents("php://input"));


    function isMissingRequirements($requestBody)
    {
        return !(isset($requestBody->author));
    }

    function messageMissingRequirements()
    {
        echo json_encode(array('message' => 'Missing Required Parameters'));
    }

    
    function messageCreateSucess($inputAuthor)
    {
        echo json_encode(`created author ({$inputAuthor->id}, {$inputAuthor->author})`
        );
    }

    function messageCreateFailure()
    {
        echo json_encode(array('message' => 'The author has not been created'));
    }
    
    function createItem($author, $requestBody)
    {
        if (isMissingRequirements($requestBody)) 
        {
            messageMissingRequirements();
            return;
        }

    
        $author->author =  $requestBody->author;
    
        if ($author->create()) 
        {
            messageCreateSucess($author);
        } 
        else 
        {
            messageCreateFailure();
        }
    }
    
    createItem($author, $requestBody);