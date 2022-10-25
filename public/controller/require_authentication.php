<?php
use ReallySimpleJWT\Token;
require_once "../public/index.php";
if (!isset($_COOKIE["token"]) || !Token::validateExpiration($_COOKIE["token"])) {
    message("Unauthorised", 401);
}
?>