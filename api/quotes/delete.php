<?php
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: DELETE');
    header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, categoryization, X-Requested-With');

    include_once '../../config/Database.php';
    include_once('../../models/Quote.php');

    $database = new Database();
   
    $database_connection = $database->connect();

    $quote = new Quote($database_connection);

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
        echo json_encode(array('message' => 'No Quotes Found'));
    }
    
    function messageDeleteSucess($inputQuote)
    {
        echo json_encode(
            array(
                'id' => $inputQuote->id
        )
    );
    }

    function messageDeleteFailure()
    {
        echo json_encode(array('message' => 'The quote has not been deleted'));
    }

    function deleteItem($quote, $requestBody)
    {

        if(isMissingRequirements($requestBody))
        {
            messageMissingRequirements();
            return;
        }

        $quote->id = $requestBody->id;

        if(!$quote->isIDPresent('quotes', $quote->id))
        {
            messageIDNotFound();
            return;
        }

        if($quote->delete())
        {
            messageDeleteSucess($quote);
        }
        else
        {
            messageDeleteFailure();
        }

    }

    deleteItem($quote, $requestBody);


