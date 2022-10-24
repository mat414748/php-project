<?php
require "../DB/database.php";

header("Content-Type: application/json");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    //Read request body input string
    $request_body_string = file_get_contents("php://input");
    echo "The request body: " . $request_body_string;
    //Parse the JSON string
    $request_data = json_decode($request_body_string, true);
    echo "The name is: " . $request_data["name"];
    //$database->query("INSERT INTO prikol VALUES ('','$request_data["name"]','$surname','$home','$color','$hobby')");
    add_student($request_data["name"],$request_data["surname"],$request_data["home"],$request_data["color"],$request_data["hobby"]);
} 
else if ($_SERVER["REQUEST_METHOD"] == "DELETE") {
    //Get the ID of the enity to delete
    $student_id = $_GET["id"];
    //Delete entity
    delete_student($student_id);
} 
else if ($_SERVER["REQUEST_METHOD"] == "GET") {
    //Get the ID of the enity to get
    $student_id = $_GET["id"];
    //Get entity
    $student = get_student($student_id);
    if ($student) {
        echo json_encode($student);
    }
} 
else if ($_SERVER["REQUEST_METHOD"] == "PUT") {
    //Get the ID of the enity to get
    $student_id = $_GET["id"];
    //Read request body input string
    $request_body_string = file_get_contents("php://input");
    //Parse the JSON string
    $request_data = json_decode($request_body_string, true);
    //$database->query("INSERT INTO prikol VALUES ('','$request_data["name"]','$surname','$home','$color','$hobby')");
    update_student($student_id,$request_data["name"],$request_data["surname"],$request_data["home"],$request_data["color"],$request_data["hobby"]);
}
?>