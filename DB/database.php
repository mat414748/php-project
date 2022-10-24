<?php 
$database = new mysqli("localhost", "root", "", "testich");
function add_student($name,$surname,$home,$color,$hobby) {
    global $database;
    $result = $database->query("INSERT INTO prikol VALUES ('','$name','$surname','$home','$color','$hobby')");
      if (!$result){
          echo "Error";
      } else {
          echo "Student created";
      }
}
function delete_student($id) {
    global $database;
    $result = $database->query("DELETE FROM prikol WHERE id = $id");
     if (!$result){
         echo "Error";
     } else {
         echo "Student deleted";
     }
}
function get_student($id) {
    global $database;
    $result = $database->query("SELECT * FROM prikol WHERE id = $id");
     if (!$result){
        http_response_code(500);
         echo "Error to get student";
     } else if ($result === true || $result->num_rows == 0){
        //True error response
        http_response_code(404);
        $error = array("message" => "No student found for ID" . $id);
        echo json_encode($error);
     } else {
        $student = $result->fetch_assoc();
        return $student;
     }
}
function update_student($id,$name,$surname,$home,$color,$hobby) {
    global $database;
    $result = $database->query("UPDATE prikol SET name = '$name', lastname = '$surname', home = '$home', color = '$color', hobby = '$hobby' WHERE id = $id");
      if (!$result){
          echo "Error";
      } else {
          echo "Student updated";
      }
}
?>
