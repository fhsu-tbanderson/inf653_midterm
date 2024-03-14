<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
$method = $_SERVER['REQUEST_METHOD'];

switch ($method) 
{
    case 'OPTIONS':
        handleOptionsRequest();
        break;
    case 'GET':
        handleGetRequest();
        break;
    case 'POST':
        handlePostRequest();
        break;
    case 'PUT':
        handlePutRequest();
        break;
    case 'DELETE':
        handleDeleteRequest();
        break;

    default:
        break;
}

function handleOptionsRequest() 
{
    header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
    header('Access-Control-Allow-Headers: Origin, Accept, Content-Type, X-Requested-With');
    exit();
}

function handleGetRequest() 
{
    if (isset($_GET['id'])) 
    {
        require('read_single.php');
    } 
    else
    {
        require('read.php');
    }
}

function handlePostRequest() 
{
    require('create.php');
}

function handlePutRequest() 
{
    require('update.php');
}

function handleDeleteRequest() 
{
    require('delete.php');
}

?>