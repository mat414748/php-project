<?php
require "db_functions.php";
header("Content-Type: application/json");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $request_body = file_get_contents("php://input");
    $request_data = json_decode($request_body, true);
    //Check all values
    if (!isset($request_data["name"]) || empty($request_data["name"])) {
        $message = array("message" => "Please provide a name", "error" => $database->error);
        echo json_encode($message);
        http_response_code(400);
        die();
    } 
    if (!isset($request_data["age"]) || !is_numeric($request_data["age"])) {
        $message = array("message" => "Please provide a number, not a letter", "error" => $database->error);
        echo json_encode($message);
        http_response_code(400);
        die();
    }
    $name = $request_data["name"];
    $age = $request_data["age"];

    //Name validation
    if (strlen($name) > 250) {
        $message = array("message" => "Name is to long", "error" => $database->error);
        echo json_encode($message);
        http_response_code(400);
        die();
    }

    //Age validation
    if ($age < 0 || $age >200) {
        $message = array("message" => "Age must be between 1 and 200 years", "error" => $database->error);
        echo json_encode($message);
        http_response_code(400);
        die();
    }
    if (is_float($age)) {
        $message = array("message" => "Age must be without dot");
        echo json_encode($message);
        http_response_code(400);
        die();
    }
    add_invited($request_data["name"],$request_data["age"]);
} 
else if ($_SERVER["REQUEST_METHOD"] == "DELETE") {
    $invited_id = $_GET["id"];
    delete_invited($invited_id);
} 
else if ($_SERVER["REQUEST_METHOD"] == "GET") {
    //Make shure if ID from URL correct
    if (!isset($_GET["id"])) {
        $message = array("message" => "False ID format", "error" => $database->error);
        echo json_encode($message);
        http_response_code(400);
        die();
    } 
    $invited_id = $_GET["id"];
    $invited = get_all_invited ($invited_id);
    if ($invited) {
        echo json_encode($invited);
    }
} 
else if ($_SERVER["REQUEST_METHOD"] == "PUT") {
$invited_id = $_GET["id"];
$request_body = file_get_contents("php://input");
$request_data = json_decode($request_body, true);
update_invited($invited_id,$request_data["name"],$request_data["age"]);
}
?>