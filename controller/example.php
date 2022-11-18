<?php
    $request_body = file_get_contents("php://input");
    $request_data = json_decode($request_body, true);
    $result = 0;
    for ($i = 0; $i < count($request_data); $i++) {
        $result += $request_data[$i]["number"];
    }
    echo json_encode(array("result" => $result));
    /*
    if (!is_numeric($request_data[0]["number"]) || !is_numeric($request_data[1]["number"]) || !is_numeric($request_data[2]["number"]) || !is_numeric($request_data[3]["number"])) {
        $response_data = array(
            "output" => "ERROR"
          );
          http_response_code(400);
    } else {
        $response_data = array(
            "output" => "Summ " . $request_data[0]["number"] + $request_data[1]["number"] + $request_data[2]["number"] + $request_data[3]["number"]
        );
        http_response_code(401);
    }
    $responce_body = json_encode($response_data);
    echo $responce_body;   
    */
?>