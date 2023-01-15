<?php 
class OrderData
{
    private $_file;
    private $_orders;
    
    public function __construct() {
        $this->_file = './files/orders.csv';

        if (($csv = fopen($this->_file, "r")) !== FALSE) {
            while ($data = fgetcsv($csv)) {
                $orders[] = $data;
            }
            fclose($csv);
        }

        $this->_orders = $orders;
    }

    public function getAllOrders() {
        return $this->_orders;
    }
    
    public function addOrder($order_data) {
        $csv = fopen($this->_file, 'a');

        if (fputcsv($csv, $order_data) === false) {
            return false;
        }

        fclose($csv); 
    }
    
    public function editOrder($order_data, $order_id) {
        if($order_id) {
            $update_line = $order_id . "," . $order_data['name'] . "," . $order_data['state']. "," . $order_data['zip']. "," . $order_data['amount']. "," . $order_data['quantity']. "," . $order_data['item'];

            $orders = file($this->_file);
            $c = count($orders);
            $i = 0;
            while( $i < $c) {
                $t_array = explode(",", $orders[$i]);
                if($t_array[0] == $order_id) { $orders[$i] = $update_line; }
                $i ++;
            }
            $new_data = implode("", $orders);
            $file_handle = fopen($this->_file, 'w');
            fwrite($file_handle, $new_data);
            fclose($file_handle);
        }
    }
    
    public function deleteOrder($order_id) {
        // open two handles on the same file
        $input = fopen($this->_file ,'r'); // read mode
        $output = fopen($this->_file, 'c'); // write mode

        if($input !== FALSE && $output !== FALSE) { // check for error
            while (($data = fgetcsv($input)) !== FALSE) {
                if(reset($data) == $order_id) {
                    continue;
                }
                fputcsv($output, $data);
            }

            // close read handle
            fclose($input);
            // shorten file to remove overhead created by this procedure
            ftruncate($output, ftell($output));
            fclose($output);
        }
    }
    
    public function getOrderById($order_id) {
        // $this->_orders[$order_id]
        // return $result;
    }
    
}

?>