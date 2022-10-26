<?php
/**
 * This function provides basic protection against sql injection
 * @param value The value that needs to be protected
 * @param decimal Whether the value is a float
 * @return return_value Returns the corrected value
 */
function anti_injection($value, $decimal = false) {
    if (is_numeric($value) && $decimal) {   //If decimal number
        $return_value = strip_tags(addslashes(intval($value)));
        return $return_value;
    } else if (is_numeric($value) && !$decimal) {   //If not decimal number
        $return_value = strip_tags(addslashes($value));
        return $return_value;
    } else {    //Only string value remains
        $return_value = strip_tags(addslashes(strval($value)));
        return $return_value;
    }
}
?>