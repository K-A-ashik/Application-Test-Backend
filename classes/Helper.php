<?php

/** 
 * Here I am using Traits to demonstrate the OOP's concept
 * Below functions are used format the response data
 */
trait Helper {

	public function sendSuccess($data, $message) {
    	return json_encode([
    					"status"   => true,
    					"message"  => $message,
    					"response" => $data,
					]);
  	}

  	public function sendError($message) {
    	return json_encode([
    					"status"  => false,
    					"message" => $message,
					]);
  	}

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