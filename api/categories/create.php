<?php
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: POST');
    header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, categoryization, X-Requested-With');

    include_once '../../config/Database.php';
    include_once('../../models/Category.php');

    $database = new Database();
    $database_connection = $database->connect();

    $category = new Category($database_connection);
    $requestBody = json_decode(file_get_contents("php://input"));


    function isMissingRequirements($requestBody)
    {
        return !(isset($requestBody->category));
    }

    function messageMissingRequirements()
    {
        echo json_encode(array('message' => 'Missing Required Parameters'));
    }

    
    function messageCreateSucess()
    {
        echo json_encode(array('message' => 'The category has been created'));
    }

    function messageCreateFailure()
    {
        echo json_encode(array('message' => 'The category has not been created'));
    }
    
    function createItem($category, $requestBody)
    {
        if (isMissingRequirements($requestBody)) 
        {
            messageMissingRequirements();
            return;
        }

    
        $category->category =  $requestBody->category;
    
        if ($category->create()) 
        {
            messageCreateSucess();
        } 
        else 
        {
            messageCreateFailure();
        }
    }
    
    createItem($category, $requestBody);