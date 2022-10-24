<?php 
function get_all_students() {
require "database.php";
$result = $database->query("SELECT * FROM prikol");

//Echo all students
if ($result == false) {
    echo "Error";
} else if ($result !== true) { //Not boolean and not true
    if ($result->num_rows > 0) {
        while ($student = $result->fetch_assoc()) {
            echo "<tr>
            <th>" . $student["id"] . "</th>
            <th>" . $student["name"] . "</th>
            <th>" . $student["lastname"] . "</th>
            <th>" . $student["home"] . "</th>
            <th>" . $student["color"] . "</th>
            <th>" . $student["hobby"] . "</th>
            </tr>";
        }
    }
} 
}
/*
//connect to database
$database = new mysqli("localhost", "root", "", "testich");

//Get all students
$result = $database->query("SELECT * FROM prikol");

//Echo all students
if ($result == false) {
    echo "Error";
} else if ($result !== true) { //Not boolean and not true
    if ($result->num_rows > 0) {
        while ($student = $result->fetch_assoc()) {
            echo "<tr>
            <th>" . $student["id"] . "</th>
            <th>" . $student["name"] . "</th>
            <th>" . $student["lastname"] . "</th>
            <th>" . $student["home"] . "</th>
            <th>" . $student["color"] . "</th>
            <th>" . $student["hobby"] . "</th>
            </tr>";
        }
    }
}*/
?>