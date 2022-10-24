<?php 
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use ReallySimpleJWT\Token;
//Testich
require __DIR__ . "/../vendor/autoload.php";
require "model/db_functions.php";
require "model/config.php";

/**
 * @OA\Info(title="My First API", version="0.1")
 */

$app = AppFactory::create();

/**
 * @OA\Get(
 *     path="/api/resource.json",
 *     @OA\Response(response="200", description="An example resource"),
 *     @OA\Response(response="404", description="Not found")
 * )
 */
/**
 * Returns an error to the client with the given message and status code.
 * This will immediately return the response and end all scripts.
 * @param $message The error message string.
 * @param $code The response code to set for the response.
 */
function error($message, $code) {
    //Write the error as a JSON object.
    $error = array("message" => $message);
    echo json_encode($error);

    //Set the response code.
    http_response_code($code);

    //End all scripts.
    die();
}

/**
* @OA\Get(
*   path="/Student/{id}",
*   summary="Beschreibung hier einfügen (Was macht der Endpoint?)",
*   tags={"Getall"},
*   @OA\Parameter(
*       name="parameter",
*       in="path",
*       required=true,
*       description="Beschreibung des Parameters",
*       @OA\Schema(
*           type="int",
*           example= 1
*       )
*   ),
*   @OA\Response(response="200", description="Erklärung"))
*   @OA\Response(response="401", description="Erklärung"))
*   @OA\Response(response="500", description="Erklärung der Antwort mit Status 200"))
*   @OA\Response(response="404", description="Erklärung der Antwort mit Status 200"))
*/
$app->get("/Student/{id}", function (Request $request, Response $response, $args) {
    if (!isset($args["id"])) {
        $message = array("message" => "False ID format", "error" => $database->error);
        echo json_encode($message);
        http_response_code(400);
        die();
    } 
    //Chekc if authentificated
    require "model/auth.php";
    $invited = get_all_invited($args["id"]);
    return $response;
});

//DELETE
$app->delete("/Student/{id}", function (Request $request, Response $response, $args) {
    $invited_id = $args["id"];
    //Chekc if authentificated
    require "model/auth.php";
    delete_invited($invited_id);
    return $response;
});

//Put something
$app->put("/Student/{id}", function (Request $request, Response $response, $args) {
    $invited_id = $args["id"];
    $request_body = file_get_contents("php://input");
    $request_data = json_decode($request_body, true);
    //Chekc if authentificated
    require "model/auth.php";
    update_invited($invited_id,$request_data["name"],$request_data["age"]);
    return $response;
});

/**
* @OA\Post(
*   path="/Student",
*   summary="Beschreibung hier einfügen (Was macht der Endpoint?)",
*   tags={"Create"},
*   requestBody=@OA\RequestBody(
*       request="/Student",
*       required=true,
*       description="Beschreiben was im Request Body enthalten sein muss",
*       @OA\MediaType(
*           mediaType="application/json",
*           @OA\Schema(
*               @OA\Property(property="username", type="string", example="Admin"),
*               @OA\Property(property="age", type="integer", example="13")
*           )
*       )
*   ),
*   @OA\Response(response="200", description="Successfully added"))
* )
*/
$app->post("/Student", function (Request $request, Response $response, $args) {
    $request_body = file_get_contents("php://input");
    $request_data = json_decode($request_body, true);
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
    //Chekc if authentificated
    require "model/auth.php";
    add_invited($request_data["name"],$request_data["age"]);
    return $response;
});

//Create a token
$app->post("/Authenticate", function (Request $request, Response $response, $args) {
    global $api_username;
    global $api_password;
    $request_body = file_get_contents("php://input");
    $request_data = json_decode($request_body, true);

    $username = $request_data["username"];
    $password = $request_data["password"];

    if ($username != $api_username || $password != $api_password) {
        $message = array("message" => "Invalid credentials");
        echo json_encode($message);
        http_response_code(400);
        die();
    }

    $token = Token::create($username,$password,time() + 60,"localhost");
    setcookie("token",$token);
    echo $token ." time:" . time() ." ". time() + 1;//$response->getBody()->write("True");

    return $response;
});
$app->run();
?>
