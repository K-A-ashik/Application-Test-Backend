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
    
    public function __construct()
    {
        // This will create a file if not exist.
        $this->initFile();
    }

    // This is used to fetch the data
    public function getAllOrders()
    {
        // Open csv in read mode
        $csv = $this->openFile("r");
        if($csv !== false) {
            $count = 0;
            // read the csv files
            while ($data = fgetcsv($csv)) {
                if($count)  {
                    $temp = [];
                    // Loop through the data
                    foreach ($this->order_format as $key => $value) {
                        // Form an array
                        $temp[$value] = $data[$key];
                    }
                    // Assign to the global array
                    $orders[] = $temp;
                }
                $count++;
            }

            // Close csv
            fclose($csv);
        } else {
            // Return the response
            return $this->sendError("Something went wrong!");
        }
        if(count($orders)) {
            // Return the response
            return $this->sendSuccess($orders, "Retrieve successful.");
        } else {
            // Return the response
            return $this->sendError("No records found");
        }
    }
    
    // This is used to create a new order
    public function addOrder($order_data)
    {
        // Validate the Order data
        $error_msg = $this->validateOrder($order_data);
        if(!$error_msg) {

            // Get the incremental value from csv
            $order_data['id'] = $this->getIncrementalId();

            // Get the formated order data in a way csv is accepted.
            $order_data = $this->formatData($order_data);

            // Write to the csv by appending
            $csv = $this->openFile('a');

            // Write to the csv
            if (fputcsv($csv, $order_data) === false) {
                // Return the response
                return $this->sendError("Unable to add order! Please try again later.");
            }

            // Close csv
            fclose($csv);

            // Return the response
            return $this->sendSuccess([], "Order added successfully.");
        } else {
            // Return the response
            return $this->sendError($error_msg);
        }
    }
    
    // Used to update an order
    public function editOrder($order_data, $order_id)
    {
        // Validate the Order data
        $error_msg = $this->validateOrder($order_data);

        if(!$error_msg && $order_id) {
            // Create the csv format order data.
            $update_line = $this->formatPutOrder($order_data, $order_id);
            
            // Read through the file
            $orders = $this->getFile();
            $c = count($orders);
            $i = 0;
            while( $i < $c) {
                $t_array = explode(",", $orders[$i]);
                // If the id matches replace the order row
                if($t_array[0] == $order_id) { $orders[$i] = $update_line. "\n"; }
                $i++;
            }
            $new_data = implode("", $orders);
            // Open file in write mode and write the new data
            $file_handle = $this->openFile('w');
            if(fwrite($file_handle, $new_data) === false) {
                // Return the response
                return $this->sendError("Unable to edit order! Please try again later.");
            }
            fclose($file_handle);

            // Return the response
            return $this->sendSuccess([], "Order updated successfully.");
        } else {
            // Return the response
            return $this->sendError(($error_msg) ? $error_msg : "Something went wrong! Please try again later.");
        }
    }
    
    // Used to delete an order
    public function deleteOrder($order_id)
    {
        // Check if it has multiple ids
        $order_ids = explode(',', $order_id);
        // open two handles on the same file
        $input = $this->openFile('r'); // read mode
        $output = $this->openFile('c'); // write mode

        $deleted = 0;
        if($input !== false && $output !== false) { // check for error
            while ($data = fgetcsv($input)) {
                $continue = 0;
                // Loop through the order_id from path parameters
                foreach ($order_ids as $id) {
                    // Get the first value from the row and check its same as order_id
                    if(reset($data) == $id) {
                        $deleted = 1;
                        $continue = 1;
                    }
                }
                if(!$continue) {
                    fputcsv($output, $data);
                }
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
            // Return the response
            return $response;
        } else {
            // Return the response
            return $this->sendError("Something went wrong! Please try again later.");
        }
    }
        
}

?>