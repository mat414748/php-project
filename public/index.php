<?php 
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use ReallySimpleJWT\Token;

require __DIR__ . "/../vendor/autoload.php";
require_once "model/db_functions.php";
require "config/config.php";
require "config/anti_sql_injection.php";

/**
 * @OA\Info(title="My First API", version="0.1")
 */

$app = AppFactory::create();


/**
 * Returns an error to the client with the given message and status code.
 * This will immediately return the response and end all scripts.
 * @param $message The error message string.
 * @param $code The response code to set for the response.
 */
function message($message, $code) {
    $message_result = array("message" => $message);
    echo json_encode($message_result);
    http_response_code($code);
    die();    
}
/**
 * Checks the entered parameter value for suitability to change it in the database table.
 * @param name Parameter name
 * @param object Which object is being changed
 * @param request_data Received values
 */
function put_check($name, $object, $request_data) {
    if (isset($request_data[$name]) && !empty(anti_injection($request_data[$name]))) {
        $value = anti_injection($request_data[$name]);
        $object[$name] = $value;
        return $object;
    } else {
        message("The " . $name . " field must not be empty.",400);
    }
}

//Create a token
$app->post("/Authentication", function (Request $request, Response $response, $args) {
    global $api_username;
    global $api_password;
    $request_body = file_get_contents("php://input");
    $request_data = json_decode($request_body, true);

    $username = anti_injection($request_data["username"]);
    $password = anti_injection($request_data["password"]);
    
    if ($username != $api_username || $password != $api_password) {
        message("Invalid credentials", 400);
    }

    $token = Token::create($username, $password, time() + 60, "localhost");
    setcookie("token", $token);
    message("Token created",200);
    return $response;
});
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
$app->get("/Product/{id}", function (Request $request, Response $response, $args) {
    require "controller/require_authentication.php";
    $id = anti_injection($args["id"],false);
    if (!isset($id) || !is_numeric($id)) {
        message("False ID format", 400);
    } 
    message(get_product($id), 200);
    return $response;
});

$app->get("/Product", function (Request $request, Response $response, $args) {
    require "controller/require_authentication.php";
    get_all_products();   
    return $response;
});

//DELETE
$app->delete("/Product/{id}", function (Request $request, Response $response, $args) {
    require "controller/require_authentication.php";
    $id = anti_injection($args["id"],false);
    if (!isset($id) || !is_numeric($id)) {
        message("False ID format", 400);
    }
    delete_product($id);
    return $response;
});

//Put something
$app->put("/Product/{id}", function (Request $request, Response $response, $args) {
    require "controller/require_authentication.php";
    $id = anti_injection($args["id"],false);
    if (!isset($id) || !is_numeric($id)) {
        message("False ID format", 400);
    } 
    $product = get_product($id);
    $request_body = file_get_contents("php://input");
    $request_data = json_decode($request_body, true);

    $product = put_check("sku",$product,$request_data);
    $product = put_check("active",$product,$request_data);
    $product = put_check("id_category",$product,$request_data);
    $product = put_check("name",$product,$request_data);
    $product = put_check("image",$product,$request_data);
    $product = put_check("description",$product,$request_data);
    $product = put_check("price",$product,$request_data);
    $product = put_check("stock",$product,$request_data);

    update_product($id,$product["sku"],$product["active"],$product["id_category"],$product["name"],$product["image"],$product["description"],$product["price"],$product["stock"]);
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
$app->post("/Product", function (Request $request, Response $response, $args) {
    //Chekc if authentificated
    require "controller/require_authentication.php";

    $request_body = file_get_contents("php://input");
    $request_data = json_decode($request_body, true);

    //If the parameters are not set
    if (!isset($request_data["sku"]) || empty($request_data["sku"])) {
        message("Please provide a \"sku\" field.", 400);
    } 
    if (!isset($request_data["active"]) || !is_numeric($request_data["active"])) {
        message("Please provide an integer number for the \"active\" field.", 400);
    }
    if (!isset($request_data["id_category"]) || !is_numeric($request_data["id_category"])) {
        message("Please provide an integer number for the \"id_category\" field.", 400);
    }
    if (!isset($request_data["name"]) || empty($request_data["name"])) {
        message("Please provide a \"name\" field.", 400);
    }
    if (!isset($request_data["image"]) || empty($request_data["image"])) {
        message("Please provide a \"image\" field.", 400);
    }
    if (!isset($request_data["description"]) || empty($request_data["description"])) {
        message("Please provide a \"description\" field.", 400);
    }
    if (!isset($request_data["price"]) || !is_numeric($request_data["price"])) {
        message("Please provide an integer or decimal number for the \"price\" field.", 400);
    }
    if (!isset($request_data["stock"]) || !is_numeric($request_data["stock"])) {
        message("Please provide an integer number for the \"stock\" field.", 400);
    }

    //Anti injection
    $sku = anti_injection($request_data["sku"]);
    $active = anti_injection($request_data["active"]);
    $id_category = anti_injection($request_data["id_category"]);
    $name= anti_injection($request_data["name"]);
    $image = anti_injection($request_data["image"]);
    $description = anti_injection($request_data["description"]);
    $price = anti_injection($request_data["price"]);
    $stock = anti_injection($request_data["stock"]);
    try {
        create_product($sku, $active, $id_category, $name, $image, $description, $price, $stock);
    } catch (Exception $pizdec) {
        message($pizdec->getMessage(), 500);
    }
    return $response;
});

//Create category
$app->post("/Category", function (Request $request, Response $response, $args) {
    //Chekc if authentificated
    require "controller/require_authentication.php";

    $request_body = file_get_contents("php://input");
    $request_data = json_decode($request_body, true);

    //If the parameters are not set
    if (!isset($request_data["active"]) || !is_bool($request_data["active"])) { 
        message("Please provide an true or false for the \"active\" field.", 400);
    } 
    if (!isset($request_data["name"]) || empty($request_data["name"])) {
        message("Please provide a \"name\" field.", 400);
    }

    //Anti injection
    $active = anti_injection($request_data["active"]);
    $name = anti_injection($request_data["name"]);
    try {
        create_category($active,$name);
    } catch (Exception $pizdec) {
        message($pizdec->getMessage(), 500);
    }
    return $response;
});

$app->get("/Category/{id}", function (Request $request, Response $response, $args) {
    require "controller/require_authentication.php";
    $id = anti_injection($args["id"],false);
    if (!isset($id) || !is_numeric($id)) {
        message("False ID format",400);
    }
    message(get_category($id),200);
    return $response;
});

$app->get("/Category", function (Request $request, Response $response, $args) {
    require "controller/require_authentication.php";
    get_all_categories();   
    return $response;
});

//DELETE
$app->delete("/Category/{id}", function (Request $request, Response $response, $args) {
    require "controller/require_authentication.php";
    $id = anti_injection($args["id"],false);
    if (!isset($id) || !is_numeric($id)) {
        message("False ID format", 400);
    } 
    delete_category($id);
    return $response;
});

//Put
$app->put("/Category/{id}", function (Request $request, Response $response, $args) {
    require "controller/require_authentication.php";
    $id = anti_injection($args["id"],false);
    if (!isset($id) || !is_numeric($id)) {
        message("False ID format", 400);
    } 
    $category = get_category($id);
    $request_body = file_get_contents("php://input");
    $request_data = json_decode($request_body, true);

    $category = put_check("active", $category, $request_data);
    $category = put_check("name", $category, $request_data);

    update_category($id, $category["active"], $category["name"]);
    return $response;
});

$app->run();
?>
