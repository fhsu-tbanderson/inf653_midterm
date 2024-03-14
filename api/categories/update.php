<?php
    // Headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: PUT');
    header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, categoryization, X-Requested-With');

    include_once '../../config/Database.php';
    include_once('../../models/Category.php');


    $database = new Database();
    $database_connection = $database->connect();

    $category = new Category($database_connection);

    $requestBody = json_decode(file_get_contents("php://input"));

    function isMissingRequirements($requestBody)
    {
        return !(isset($requestBody->id) && isset($requestBody->category));
    }


    function messageUpdateSuccess()
    {
        echo json_encode(
            array('message' => 'The category has been updated')
        );
    }

    function messageUpdateFailed()
    {
        echo json_encode(
            array('message' => 'The category has not been updated' )
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
            array('message' => 'category_id Not Found' )
        );

    }

    function updateItem($category, $requestBody)
    {
        if(isMissingRequirements($requestBody))
        {
            messageMissingRequirements();
            return;
        }

        if(!$category->isIdPresent($requestBody->id))
        {
            messageIdNotFound();
            return;
        }

        $category->id = $requestBody->id;
        $category->category = $requestBody->category;

        if($category->update())
        {
            messageUpdateSuccess();
        }
        else
        {
            messageUpdateFailed();
        }


    }

    updateItem($category, $requestBody);

 

   

    
