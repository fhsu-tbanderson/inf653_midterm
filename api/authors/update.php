<?php
    // Headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: PUT');
    header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, categoryization, X-Requested-With');

    include_once '../../config/Database.php';
    include_once('../../models/Author.php');


    $database = new Database();
    $database_connection = $database->connect();

    $author = new Author($database_connection);

    $requestBody = json_decode(file_get_contents("php://input"));

    function isMissingRequirements($requestBody)
    {
        return !(isset($requestBody->id) && isset($requestBody->author));
    }


    function messageUpdateSuccess($inputAuthor)
    {
        echo json_encode(
            array("id" =>$inputAuthor->id, 
                "author" => $inputAuthor->author)
        );
    }

    function messageUpdateFailed()
    {
        echo json_encode(
            array('message' => 'The author has not been updated' )
        );
    }

    function messageMissingRequirements()
    {
        echo json_encode(
            array('message' => 'Missing Required Parameters')
        );
    }

    function messageIdNotFound()
    {
        echo json_encode(
            array('message' => 'author_id Not Found' )
        );

    }

    function updateItem($author, $requestBody)
    {
        if(isMissingRequirements($requestBody))
        {
            messageMissingRequirements();
            return;
        }

        if(!$author->isIdPresent($requestBody->id))
        {
            messageIdNotFound();
            return;
        }

        $author->id = $requestBody->id;
        $author->author = $requestBody->author;

        if($author->update())
        {
            messageUpdateSuccess($author);
        }
        else
        {
            messageUpdateFailed();
        }


    }

    updateItem($author, $requestBody);

 

   

    
