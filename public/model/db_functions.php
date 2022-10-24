<?php
$database = new mysqli("localhost", "root", "", "testich");

function add_invited($name,$age) {
    global $database;
    $name = strip_tags($name);
    $name = addslashes($name);
    $age = intval($age);
    $result = $database->query("INSERT INTO invite_list VALUES ('','$name','$age')");
      if (!$result){
        http_response_code(500);
        $message = array("message" => "Error adding the invited", "error" => $database->error);
        echo json_encode($message);
      } else {
        http_response_code(201);
        $message = array("message" => "The invited has been successfully created");
        echo json_encode($message);
      }
}
function delete_invited($id) {
    global $database;
    $id = intval($id);
    $result = $database->query("DELETE FROM invite_list WHERE id = $id");//id = 1 or 1=1; --;
     if (!$result){
        http_response_code(500);
        $message = array("message" => "Error when deleted the invited");
        echo json_encode($message);
     } else if ($result === true && $database->affected_rows == 0){
        http_response_code(400);
        $message = array("message" => "Not real ID " . $id);
        echo json_encode($message);
     } else {
        http_response_code(200);
        $message = array("message" => "The invited has been successfully deleted");
        echo json_encode($message);
     }
}
function get_all_invited($id) {
    global $database;
    $id = intval($id);
    $result = $database->query("SELECT * FROM invite_list WHERE id = $id");
     if (!$result){
        http_response_code(500);
        $message = array("message" => "Error to get the invited");
        echo json_encode($message);
     } else if ($result === true || $result->num_rows == 0){
        http_response_code(404);
        $message = array("message" => "No invited found for ID: " . $id);
        echo json_encode($message);
     } else {
        http_response_code(200);
        $message = array("message" => "The invited has been successfully found");
        echo json_encode($message);
        $student = $result->fetch_assoc();
        return $student;
     }
}
function update_invited($id,$name,$age) {
    global $database;
    $name = strip_tags($name);
    $name = addslashes($name);
    $age = intval($age);
    $result = $database->query("UPDATE invite_list SET name = '$name', age = '$age' WHERE id = $id");
      if (!$result){
        http_response_code(500);
        $message = array("message" => "Update Error");
        echo json_encode($message);
      } else if ($result === true && $database->affected_rows == 0){
        http_response_code(400);
        $request_body = file_get_contents("php://input");
        $request_data = json_decode($request_body, true);
        $message = array("message" => "Not real ID: ". $id ." or an identical value" , "name" => $request_data["name"] , "age" => $request_data["age"], "error" => $database->error);
        echo json_encode($message);
     } else {
        http_response_code(200);
        $message = array("message" => "The invited has been successfully updated");
        echo json_encode($message);
      }
}
?>