<?php
require_once ("class/OrderData.php");


// Allow from any origin
if (isset($_SERVER['HTTP_ORIGIN'])) {
    header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
    header('Access-Control-Allow-Credentials: true');
    header('Access-Control-Max-Age: 86400');    // cache for 1 day
}

// Access-Control headers are received during OPTIONS requests
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS");         

    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
        header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");

    exit(0);
}

/** 
	Checking what function has to be called
*/
switch($_GET['fn']) {
	case 'getData':
	    getData();
   	case 'addData':
	    addData();
    case 'editData':
	    editData();
	case 'deleteData':
	    deleteData();
}

/**
	Retrieve all the orders
*/
function getData () {
	$data = new OrderData;
	echo json_encode($data->getAllOrders());
}
/**
	Add order
*/
function addData () {
	$order_data = ['5',"Vino",'WA','25678','12.7','4','FG909'];

	$data = new OrderData;
	$data->addOrder($order_data);
}
/**
	Edit order
*/
function editData () {
	$order_id = '5';
	$order_data = [
			'name' => "Vino Palanivel", 
			'state' => 'Wb', 
			'zip' => '25674', 
			'amount' => '12.9', 
			'quantity' => '6', 
			'item' => 'VI909'
		];

	$data = new OrderData;
	$data->editOrder($order_data, $order_id);
}
/**
	Delete order
*/
function deleteData () {
	$order_id = '4';

	$data = new OrderData;
	$data->deleteOrder($order_id);
}
	
?>
