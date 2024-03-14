<?php
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: DELETE');
    header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, categoryization, X-Requested-With');

    include_once '../../config/Database.php';
    include_once('../../models/Author.php');

    $database = new Database();
   
    $database_connection = $database->connect();

    $author = new Author($database_connection);

    $requestBody = json_decode(file_get_contents("php://input"));
    

    function isMissingRequirements($requestBody)
    {
        return !(isset($requestBody->id));
    }

    function messageMissingRequirements()
    {
        echo json_encode(array('message' => 'Missing Required Parameters'));
    }

    function messageIDNotFound()
    {
        echo json_encode(array('message' => 'author_id Not Found'));
    }
    
    function messageDeleteSucess()
    {
        echo json_encode(array('message' => 'The author has been deleted'));
    }

    function messageDeleteFailure()
    {
        echo json_encode(array('message' => 'The author has not been deleted'));
    }

    function deleteItem($author, $requestBody)
    {

        if(isMissingRequirements($requestBody))
        {
            messageMissingRequirements();
            return;
        }

        $author->id = $requestBody->id;

        if(!$author->isIdPresent($author->id))
        {
            messageIDNotFound();
            return;
        }

        if($author->delete())
        {
            messageDeleteSucess();
        }
        else
        {
            messageDeleteFailure();
        }

    }

    deleteItem($author, $requestBody);


