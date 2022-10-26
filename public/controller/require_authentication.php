<?php
use ReallySimpleJWT\Token;
require_once "../public/index.php";
//If the token does not exist or has expired
if (!isset($_COOKIE["token"]) || !Token::validateExpiration($_COOKIE["token"])) {
    message("Unauthorised", 401);
}
?>