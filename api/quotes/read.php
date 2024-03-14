<?php

    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');

    include_once '../../config/Database.php';
    include_once('../../models/Quote.php');

    $database = new Database();
    $database_connection = $database->connect();

    $quote = new Quote($database_connection);

     //Get query string parameters if passed
     $queryStringQuoteId = $_GET['id'] ?? null;
     $queryStringAuthorId = $_GET['author_id'] ?? null;
     $queryStringCategoryId = $_GET['category_id'] ?? null;



     if ($queryStringAuthorId && $queryStringCategoryId)
     {
        $quote->author_id = $queryStringAuthorId;
        $quote->category_id = $queryStringCategoryId;
        $result = $quote->readParameterAuthorAndCategory();
     }


     else if ($queryStringAuthorId)
     {
        $quote->author_id = $queryStringAuthorId;
        $result = $quote->readParameterAuthor();
     }

     else if ($queryStringCategoryId)
     {
        $quote->category_id = $queryStringCategoryId;
        $result = $quote->readParamaterCategory();
     }

    
     else
     {
         $result = $quote->read();
     }

    $result_count = $result ? $result->rowCount() : 0;

    if($result_count > 0)
    {
        $result_array = array();

        while($row = $result->fetch(PDO::FETCH_ASSOC))
        {
            extract($row);

            $item = array(
                'id'=> $id,
                'quote' => $quote,
                'author' => $author,
                'category'=> $category
            );

            array_push($result_array, $item);
        }

        echo json_encode($result_array);
    }
    else
    {
        $error_response = array('message'=>'No Quotes Found');
        echo json_encode($error_response);
    }

    


