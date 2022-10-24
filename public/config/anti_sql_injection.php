<?php
function anti_injection($value, $decimal=true) {
    if (is_numeric($value) && !$decimal) {
        $return_value = strip_tags(addslashes(intval($value)));
        return $return_value;
    } else if (is_numeric($value) && $decimal) {
        $return_value = strip_tags(addslashes($value));
        return $return_value;
    } else {
        $return_value = strip_tags(addslashes($value));
        return $return_value;
    }
}
?>