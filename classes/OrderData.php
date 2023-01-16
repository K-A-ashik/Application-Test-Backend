<?php 
require_once 'Validator.php';
require_once 'Helper.php';

/**
    * OrderData class contains all the method 
    * From here we include OrderData class
    * We first check for the CORS error and bypass them
    * Then based on the request method GET, POST, PUT and DELETE we call the corresponding functions.
    * Helper is used to demonstrate the use of Traits.
*/

class OrderData extends Validator
{
    use Helper;

    private $_file;
    
    public function __construct()
    {
        $this->_file = './files/orders.csv';
    }

    // This is used to fetch the data
    public function getAllOrders()
    {
        if (($csv = fopen($this->_file, "r")) !== false) {
            $count = 0;
            $columns = [];
            while ($data = fgetcsv($csv)) {
                if($count)  {
                    $temp = [
                        $columns[0] => $data[0],
                        $columns[1] => $data[1],
                        $columns[2] => $data[2],
                        $columns[3] => $data[3],
                        $columns[4] => $data[4],
                        $columns[5] => $data[5],
                        $columns[6] => $data[6]
                    ];
                    $orders[] = $temp;
                } else {
                    $columns = $data;
                }
                $count++;
            }
            fclose($csv);
        } else {
            return $this->sendError("Something went wrong!");
        }
        if(count($orders)) {
            return $this->sendSuccess($orders, "Retrieve successful.");
        } else {
            return $this->sendError("No records found");  
        }
    }
    
    // This is used to create a new order
    public function addOrder($order_data)
    {

        $error_msg = $this->order_validation($order_data);
        if(!$error_msg) {

            $rows = file($this->_file);
            $last_row = array_pop($rows);
            $data = str_getcsv($last_row);
            $order_data['id'] = $data[0]+1;

            // Get the formated order data in a way csv is accepted.
            $order_data = $this->formatData($order_data);

            $csv = fopen($this->_file, 'a');

            if (fputcsv($csv, $order_data) === false) {
                return $this->sendError("Unable to add order! Please try again later.");
            }
            fclose($csv);

            return $this->sendSuccess([], "Order added successfully.");
        } else {
            return $this->sendError($error_msg);
        }
    }
    
    // Used to update an order  
    public function editOrder($order_data, $order_id)
    {
        $error_msg = $this->order_validation($order_data);

        if(!$error_msg) {
            if($order_id) {

                // Create the csv format order data.
                $update_line = $order_id . "," . $order_data['name'] . "," . $order_data['state']. "," . $order_data['zip']. "," . $order_data['amount']. "," . $order_data['qty']. "," . $order_data['item'];
                
                $orders = file($this->_file);
                $c = count($orders);
                $i = 0;
                while( $i < $c) {
                    $t_array = explode(",", $orders[$i]);
                    if($t_array[0] == $order_id) { $orders[$i] = $update_line. "\n"; }
                    $i++;
                }
                $new_data = implode("", $orders);
                $file_handle = fopen($this->_file, 'w');
                if(fwrite($file_handle, $new_data) === false) {
                    return $this->sendError("Unable to edit order! Please try again later.");
                }
                fclose($file_handle);

                return $this->sendSuccess([], "Order updated successfully.");
            } else {
                return $this->sendError("Something went wrong! Please try again later.");
            }
        } else {
            return $this->sendError($error_msg);
        }
    }
    
    // Used to delete an order
    public function deleteOrder($order_id)
    {
        // open two handles on the same file
        $input = fopen($this->_file ,'r'); // read mode
        $output = fopen($this->_file, 'c'); // write mode

        $deleted = 0;
        if($input !== FALSE && $output !== FALSE) { // check for error
            while ($data = fgetcsv($input)) {

                // Get the first value from the row and check its same as order_id
                if(reset($data) == $order_id) {
                    $deleted = 1;
                    continue;
                }
                fputcsv($output, $data);
            }

            // close read handle
            fclose($input);
            // shorten file to remove overhead created by this procedure
            ftruncate($output, ftell($output));
            fclose($output);

            if($deleted) {
                $response = $this->sendSuccess([], "Order deleted successfully.");
            } else {
                $response = $this->sendError([], "Order deletion failed!");
            }
            return $response;
        } else {
            return $this->sendError("Something went wrong! Please try again later.");
        }
    }
        
}

?>