<?php

	/**
		* Author : Kunj Ahammed Ashik
		* This file act a s aentry point.
		* From here we include OrderData class
		* We first check for the CORS error and bypass them
		* Then based on the request method GET, POST, PUT and DELETE we call the corresponding functions.
	*/

	require_once ("classes/OrderData.php");

	/**
	   ByPassing the CORS Error by accepting the all origin
	   *
	 * */
	if (isset($_SERVER['HTTP_ORIGIN'])) {
		header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
		header('Access-Control-Allow-Credentials: true');
		header('Access-Control-Max-Age: 86400');    // cache for 1 day
	}

	if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
		if(isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD'])){
			header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
		}

		if(isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS'])){
			header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");
		}
		die;
	}


	/**
		Checking what function has to be called
	*/
	switch($_SERVER['REQUEST_METHOD']) {
		case 'GET':
			getData();
			break;
		case 'POST':
			addData();
			break;
		case 'PUT':
			editData();
			break;
		case 'DELETE':
			deleteData();
			break;
	}

	/**
		Retrieve all the orders
	*/
	function getData () {
		$order_data = new OrderData;
		echo $order_data->getAllOrders();
	}

	/**
		Add order
	*/
	function addData () {
		$order_data = new OrderData;
		$add_data = getPayload();
		echo $order_data->addOrder($add_data);
	}

	/**
		Edit order
	*/
	function editData() {
		$order_data = new OrderData;
		$order_id = getSlug();
		$edit_data = getPayload();
		echo $order_data->editOrder($edit_data, $order_id);
	}

	/**
		Delete order
	*/
	function deleteData () {
		$order_id = getSlug();
		$order_data = new OrderData;
		echo $order_data->deleteOrder($order_id);
	}

	// To get the Post body
	function getPayload() {
		$post_data   =  file_get_contents("php://input");
		return json_decode($post_data, true);
	}

	// To get the id from the url
	function getSlug()
	{
		$parts = explode("/", $_SERVER['REQUEST_URI']);
		return end($parts);
	}
?>
