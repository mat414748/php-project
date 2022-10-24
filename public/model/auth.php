<?php
use ReallySimpleJWT\Token;

if (!isset($_COOKIE["token"]) || !Token::validateExpiration($_COOKIE["token"])) {
    $error = array("message" => "Unauthorised");
    echo json_encode($error);

    http_response_code(401);
    die();
}
?>