<?php
/**
 * This function provides basic protection against sql injection
 * @param value The value that needs to be protected
 * @param decimal Whether the value is a float
 * @return return_value Returns the corrected value
 */
function anti_injection($value, $decimal = false) {
    //If decimal number
    if (is_numeric($value) && $decimal) {   
        $return_value = strip_tags(addslashes(intval($value)));
        return $return_value;
    } 
    //If not decimal number
    else if (is_numeric($value) && !$decimal) {   
        $return_value = strip_tags(addslashes($value));
        return $return_value;
    } 
    //Only string value remains
    else {    
        $return_value = strip_tags(addslashes(strval($value)));
        return $return_value;
    }
}
?>