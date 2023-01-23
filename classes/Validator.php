<?php

/**
 * Validator class is to demonstrate inheritance concept.
 * This class is used for field calidations.
 */
class Validator
{
    private $_order_data;
    private $_headers = ['name', 'state', 'zip', 'amount', 'qty', 'item'];

    public function validateOrder($post_data) {
        // Check empty
        $this->_order_data = $post_data;
        $empty       = $this->empty_fields();
        if($empty != null) {
            return $empty;
        }

        // Accept only string
        $is_string  = $this->check_string(['name', 'state']);
        if($is_string != null) {
            return $is_string;
        }

        // Accept only string and number
        $is_strnum  = $this->check_strnum(['item']);
        if($is_strnum != null) {
            return $is_strnum;
        }
        
        $is_num   = $this->check_num(['zip', 'amount', 'qty']);
        if($is_num != null) {
            return $is_num;
        }

       
        return false;
    }

    public function empty_fields()
    {
        $errMsg = null;
        foreach ($this->_headers as $col) {
            if (empty($this->_order_data[$col])) {
                $errMsg .= "$col field is empty <br />";
            }
            if($col == 'name' && strlen($this->_order_data[$col]) > 30) {
                $errMsg .= "$col length should not be over 30 <br />";
            } elseif($col == 'state' && strlen($this->_order_data[$col]) > 20) {
                $errMsg .= "$col length should not be over 20 <br />";
            } elseif($col == 'zip' && strlen($this->_order_data[$col]) != 6) {
                $errMsg .= "$col length should be 6 <br />";
            }
        }
        
        return $errMsg;
    }

    public function check_string($fields)
    {
        $errMsg = null;
        foreach ($fields as $col) {
            if (!preg_match("/^[a-zA-z ]*$/", $this->_order_data[$col]) ) {
                $errMsg .= "$col Field accepts only alphabets and whitespace.";
            }
        }

        return $errMsg;
    }

    public function check_strnum($fields)
    {
        $errMsg = null;
        foreach ($fields as $col) {
            if (!preg_match("/^[a-zA-Z0-9]+$/", $this->_order_data[$col]) ) {
                $errMsg .= "$col Field accepts only alphabets and numbers.";
            }
        }

        return $errMsg;
    }
    
    public function check_num($fields)
    {
        $errMsg = null;
        foreach ($fields as $col) {
            if ($col == 'amount') {
                if(!preg_match("/^([0-9]*[.])?[0-9]+$/", $this->_order_data[$col]) ) {
                    $errMsg .= "$col Field accepts only numbers and float values.";
                }
            } elseif (!preg_match("/^[0-9]*$/", $this->_order_data[$col]) ) {
                $errMsg .= "$col Field accepts only numbers.";
            }
        }

        return $errMsg;
    }
}
?>