<?php
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');

    include_once '../../config/Database.php';
    include_once('../../models/category.php');

    $database = new Database();
    $database_connection = $database->connect();

    $category = new Category($database_connection);

    //Get id if it was passed
    $category->id = isset($_GET['id']) ? $_GET['id'] : die();

    $category->readSingle();

    if($category->category)
    {
        $result_array = array(
            'id'=> $category->id,
            'category'=> $category->category
        );

    }
    else
    {
        $result_array = array('message' => 'category_id Not Found');
    }


    print_r(json_encode($result_array));
