<?php

/**
 * Validator class is to demonstrate inheritance concept.
 * This class is used for field calidations.
 */
class Validator
{
    public function order_validation($post_data) {
        $empty       = $this->_empty_fields($post_data, array('name', 'state', 'zip', 'amount', 'quantity', 'item'));
        $check_state = $this->_is_valid_string($post_data['state']);
        $check_zip   = $this->is_valid_number($post_data['zip']);
        $check_amnt  = $this->is_valid_number($post_data['amount']);
        $check_qty   = $this->is_valid_number($post_data['quantity']);
        
        // checking empty fields
        if($empty != null) {
            return $empty;
        }
        if (!$check_state) {
            return 'Please provide correct state.';
        }
        if (!$check_zip) {
            return 'Zip should be only numbers.';
        }
        if (!$check_amnt) {
            return 'Amount should be only numbers.';
        }
        if (!$check_qty) {
            return 'Quantity should be only numbers.';
        }
        return false;
    }

    public function _empty_fields($data, $fields)
    {
        $errMsg = null;
        foreach ($fields as $value) {
            if (empty($data[$value])) {
                $errMsg .= "$value field is empty <br />";
            }
        }
        
        return $errMsg;
    }

    public function _is_valid_string($string)
    {
        if (!preg_match("/^[a-zA-z]*$/", $string) ) {
            return "Only alphabets and whitespace are allowed.";
        }

        return true;
    }
    
    public function is_valid_number($number)
    {
        if(!is_numeric($number)) {
            return "Only numeric value is allowed.";
        }

        return true;
    }
}
?>