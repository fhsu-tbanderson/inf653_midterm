<?php
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: POST');
    header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, categoryization, X-Requested-With');

    include_once '../../config/Database.php';
    include_once('../../models/Quote.php');

    $database = new Database();
    $database_connection = $database->connect();

    $quote = new Quote($database_connection);
    $requestBody = json_decode(file_get_contents("php://input"));

    function isMissingRequirements($requestBody)
    {
        return !(isset($requestBody->quote) && isset($requestBody->author_id) && isset($requestBody->category_id));
    }

    function messageMissingRequirements()
    {
        echo json_encode(array('message' => 'Missing Required Parameters'));
    }

    function messageCreateSucess($inputQuote)
    {
        echo json_encode(
            array("id" =>$inputQuote->id,
                "quote" => $inputQuote->quote, 
                "author_id" => $inputQuote->author_id,
                "category_id" => $inputQuote->category_id
            )
        );
    }

    function messageQuoteCreateFail()
    {
        echo json_encode(array('message' => 'The quote has not been created'));
    }

    function messageForeignKeyConstraintNotMetAuthorId()
    {
        echo json_encode(array('message' => 'author_id Not Found'));
    }

    function messageForeignKeyConstraintNotMetCategoryId()
    {
        echo json_encode(array('message' => 'category_id Not Found'));
    }


    
    function createQuote($quote, $requestBody)
    {
        if(isMissingRequirements($requestBody))
        {
            messageMissingRequirements();
            return;
        }
    
        $quote->quote = $requestBody->quote;
        $quote->author_id = $requestBody->author_id;
        $quote->category_id = $requestBody->category_id;

        if(!$quote->isUpdateForeignKeyConstraintMetAuthorId($quote->author_id))
        {
            messageForeignKeyConstraintNotMetAuthorId();
            return;
        }

        if(!$quote->isUpdateForeignKeyConstraintMetCategoryId($quote->category_id))
        {
            messageForeignKeyConstraintNotMetCategoryId();
            return;
        }

        if($quote->create())
        {
            messageCreateSucess($quote);
        }
        else
        {
            messageQuoteCreateFail();
        }


    }
    
    createQuote($quote, $requestBody);