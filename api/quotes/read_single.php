<?php
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');

    include_once '../../config/Database.php';
    include_once('../../models/Quote.php');

    $database = new Database();
    $database_connection = $database->connect();

    $quote = new Quote($database_connection);


    function messageNotFound()
    {
        return array('message' => 'No Quotes Found');
    }

    function messageFound($quote)
    {
        return array
        (
            'id' => $quote->id,
            'quote' => $quote->quote,
            'author' => $quote->author,
            'category' => $quote->category
        );
    }

    function isQuoteValid($quote)
    {
        return isset($quote->id);
    }


    $quote->id = $_GET['id'];
    $quote->readSingle();
    $result_array = $quote->isIdPresent('quotes', $quote->id) ? messageFound($quote) : messageNotFound();
   
    print_r(json_encode($result_array));
