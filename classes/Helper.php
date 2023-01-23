<?php

/** 
 * Here I am using Traits to demonstrate the OOP's concept
 * Below functions are used format the response data
 */
trait Helper {

	// Create global static order format array
	public $order_format = ['id','name','state','zip','amount','qty','item'];

	private $_file;

	// This function is to get the file and create if not exist
	public function initFile()
	{
		$this->_file = './files/orders.csv';
		// Check if file exist and writable using is_writable method
		if (!is_writable($this->_file)) {
			chmod($this->_file, 0777);
			// open file in write mode
            $f = $this->openFile('w');
            if ($f === false) {
                die('Error opening the file ' . $this->_file);
            }
			// Add the column headers to the new file
            fputcsv($f, $this->order_format);
        }
	}

	// Helper function to open the file with specified type
	public function openFile($type)
	{
		return fopen($this->_file, $type);
	}

	// To send success response to the user
	public function sendSuccess($data, $message) {
    	return json_encode([
    					"status"   => true,
    					"message"  => $message,
    					"response" => $data,
					]);
  	}

	// To generate the incremental ID
	public function getIncrementalId()
	{
		// Read csv
		$rows = file($this->_file);
		// get the last row
		$last_row = array_pop($rows);
		$data = str_getcsv($last_row);
		// Return the number after adding 1 to the last id
		return $data[0]+1;
	}

	// To send the error response to the user
  	public function sendError($message) {
    	return json_encode([
    					"status"  => false,
    					"message" => $message,
					]);
  	}

	public function getFile()
	{
		return file($this->_file);
	}
	// To format the Order coming from client side, in order to insert in to the file
	public function formatPutOrder($data, $order_id)
	{
		return $order_id . "," . $data['name'] . "," . $data['state']. "," . $data['zip']. "," . $data['amount']. "," . $data['qty']. "," . $data['item'];
	}

	// To format the order data index wise
	public function formatData($data) {

		return [
			0 => $data['id'],
			1 => $data['name'],
			2 => $data['state'],
			3 => $data['zip'],
			4 => $data['amount'],
			5 => $data['qty'],
			6 => $data['item']
		];
	}
}

?>