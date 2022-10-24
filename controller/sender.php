<?php

if (isset($_POST["submit"])) {
    $name = $_POST["firstname"];
    $surname = $_POST["lastname"];
    $home = $_POST["home"];
    $color = $_POST["color_chose"];
    $hobby = "";
    //connect to database
    require "../DB/database.php";
    //Hobby
    if (isset($_POST["hobby"])) {
    foreach ($_POST["hobby"] as $item) {
        $hobby .= $item . ",";
    }
    }
    //Add new student
    $database->query("INSERT INTO prikol VALUES ('','$name','$surname','$home','$color','$hobby')");

    echo "Firstname: <b>". $name . "</b><br>Lastname: <b>" . $surname  . "</b><br>Home: <b>" . $home . "</b><br>Color: <b>" . $color . "</b><br>Hobby: <b>" . $hobby . "</b>";
    function show_all_parametrs() {
        echo "<pre>";
        print_r($_POST);
        var_dump($_POST);
        echo "</pre>";
    }
    show_all_parametrs();
    if (!empty($color)) {
            switch ($color) {
                case 'red':
                    echo "Red";
                    break;
                case 'blue':
                    echo "blue";
                    break;
                case 'gold':
                    echo "gold  ";
                    break;
                    
            }
    }
} else {
    echo "Please complete the form";
}
?>