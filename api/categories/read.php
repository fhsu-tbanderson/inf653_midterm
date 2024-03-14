<?php

    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');

    include_once '../../config/Database.php';
    include_once('../../models/category.php');

    $database = new Database();
    $database_connection = $database->connect();

    $category = new Category($database_connection);
    $result = $category->read();
    $result_count = $result->rowCount();

    if($result_count > 0)
    {
        $result_array = array();
       

        while($row = $result->fetch(PDO::FETCH_ASSOC))
        {
            extract($row);

            $item = array(
                'id'=> $id,
                'category'=> $category
            );

            array_push($result_array, $item);
        }

        echo json_encode($result_array);
    }
    else
    {
        $error_response = array('message'=>'No categories Found');
        echo json_encode($error_response);
    }

    


