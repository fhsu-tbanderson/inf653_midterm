<?php
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: DELETE');
    header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, categoryization, X-Requested-With');

    include_once '../../config/Database.php';
    include_once('../../models/Category.php');

    $database = new Database();
   
    $database_connection = $database->connect();

    $category = new Category($database_connection);

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
        echo json_encode(array('message' => 'category_id Not Found'));
    }
    
    function messageDeleteSucess()
    {
        echo json_encode(array('message' => 'The category has been deleted'));
    }

    function messageDeleteFailure()
    {
        echo json_encode(array('message' => 'The category has not been deleted'));
    }

    function deleteItem($category, $requestBody)
    {

        if(isMissingRequirements($requestBody))
        {
            messageMissingRequirements();
            return;
        }

        $category->id = $requestBody->id;

        if(!$category->isIdPresent($category->id))
        {
            messageIDNotFound();
            return;
        }

        if($category->delete())
        {
            messageDeleteSucess();
        }
        else
        {
            messageDeleteFailure();
        }

    }

    deleteItem($category, $requestBody);


