<?php 
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use ReallySimpleJWT\Token;
require __DIR__ . "/../vendor/autoload.php";
require_once "model/db_functions.php";
require "config/config.php";
require "config/anti_sql_injection.php";
header("Content-Type: application/json");
/**
 * @OA\Info(title="Online shop API", version="1")
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
    if (isset($request_data[$name]) && isset($object[$name]) && !empty(anti_injection($request_data[$name]))) {
        $value = anti_injection($request_data[$name]);
        $object[$name] = $value;
        return $object;
    } else if (isset($request_data[$name]) && empty(anti_injection($request_data[$name]))) {
        message("The " . $name . " field must not be empty.", 400);
    } else {
        return $object;
    }
}

/**
 *  @OA\Post(
 *      path="/Authentication",
 *      summary="Used to authenticate and obtain an access token that will be stored in the cookies.",
 *      tags={"General"},
 *      requestBody=@OA\RequestBody(
 *          request="/Authentication",
 *          required=true,
 *          description="The credentials are passed to the server via the request body.",
 *          @OA\MediaType(
 *              mediaType="application/json",
 *              @OA\Schema(
 *                  @OA\Property(property="username", type="string", example="admin"),
 *                  @OA\Property(property="password", type="string", example="sec!ReT423*&")
 *              )
 *          )
 *      ),
 *      @OA\Response(response="200", description="Successfully authenticated")),
 *      @OA\Response(response="401", description="Invalid credentials"))
 * )
 */
$app->post("/Authentication", function (Request $request, Response $response, $args) {
    global $api_username;
    global $api_password;
    $request_body = file_get_contents("php://input");
    $request_data = json_decode($request_body, true);
    //If the parameters are not set
    if (!isset($request_data["username"]) || empty($request_data["username"])) {
        message("Please provide a \"username\" field.", 400);
    } 
    if (!isset($request_data["password"]) || empty($request_data["password"])) {
        message("Please provide a \"password\" field.", 400);
    }

    $username = anti_injection($request_data["username"]);
    $password = anti_injection($request_data["password"]);
    //Verification of login and password for token creation
    if ($username != $api_username || $password != $api_password) {
        message("Invalid credentials", 401);
    }

    $token = Token::create($username, $password, time() + 3600, "localhost");
    setcookie("token", $token);
    message("Token created", 200);
    return $response;
});

//Products
/**
 *  @OA\Post(
 *      path="/Product",
 *      summary="Creates a new product.",
 *      tags={"Create"},
 *      requestBody=@OA\RequestBody(
 *          request="/Product",
 *          required=true,
 *          description="The credentials are passed to the server via the request body.",
 *          @OA\MediaType(
 *              mediaType="application/json",
 *              @OA\Schema(
 *                  @OA\Property(property="sku", type="string", example="admin"),
 *                  @OA\Property(property="active", type="boolean", example=true),
 *                  @OA\Property(property="id_category", type="integer", example=10),
 *                  @OA\Property(property="name", type="string", example="Chocolate"),
 *                  @OA\Property(property="image", type="string", example="Chocolate image"),
 *                  @OA\Property(property="description", type="string", example="Dark chocolate"),
 *                  @OA\Property(property="price", type="decimal", example=12.5),
 *                  @OA\Property(property="stock", type="integer", example=10)
 *              )
 *          )
 *      ),
 *      @OA\Response(response="201", description="Successfully created product")),
 *      @OA\Response(response="400", description="One of the values is not entered"))
 *  )
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
    if (!isset($request_data["active"]) || !is_bool($request_data["active"])) { 
        message("Please provide an true or false for the \"active\" field.", 400);
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
    //If an error occurs specifically at the database level, then an exception will be thrown.
    try {
        create_product($sku, $active, $id_category, $name, $image, $description, $price, $stock);
    } catch (Exception $pizdec) {
        message($pizdec->getMessage(), 500);
    }
    return $response;
});
/**
 *  @OA\Get(
 *      path="/Product/{id}",
 *      summary="Returns the product with the corresponding id",
 *      tags={"Search_one"},
 *      @OA\Parameter(
 *          name="id",
 *          in="path",
 *          required=true,
 *          description="ID of the wanted product",
 *          @OA\Schema(
 *              type="integer",
 *              example=1
 *          )
 *      ),
 *      @OA\Response(response="200", description="Output of the desired product")),
 *      @OA\Response(response="404", description="Output of the desired product")),
 *  )
 */
$app->get("/Product/{id}", function (Request $request, Response $response, $args) {
    require "controller/require_authentication.php";
    $id = anti_injection($args["id"], true);
    //If the parameters are not set
    if (!isset($id) || !is_numeric($id)) {
        message("False ID format", 400);
    }

    message(get_product($id), 200);
    return $response;
});
/**
 *  @OA\Get(
 *      path="/Product",
 *      summary="Shows all products",
 *      tags={"Search_many"},
 *      @OA\Response(response="200", description="All products are shown"))
 *  )
 */
$app->get("/Product", function (Request $request, Response $response, $args) {
    require "controller/require_authentication.php";
    get_all_products();   
    return $response;
});
/**
 *  @OA\Delete(
 *      path="/Product/{id}",
 *      summary="Deletes the product with the corresponding id",
 *      tags={"Delete"},
 *      @OA\Parameter(
 *          name="id",
 *          in="path",
 *          required=true,
 *          description="ID of the wanted product",
 *          @OA\Schema(
 *              type="integer",
 *              example=1
 *          )
 *      ),
 *      @OA\Response(response="200", description="The product was successfully removed"))
 *  )
 */
$app->delete("/Product/{id}", function (Request $request, Response $response, $args) {
    require "controller/require_authentication.php";
    $id = anti_injection($args["id"], true);
    //If the parameters are not set
    if (!isset($id) || !is_numeric($id)) {
        message("False ID format", 400);
    }

    delete_product($id);
    return $response;
});
/**
 *  @OA\Put(
 *      path="/Product/{id}",
 *      summary="Product Update",
 *      tags={"Update"},
 *      @OA\Parameter(
 *          name="id",
 *          in="path",
 *          required=true,
 *          description="ID of the wanted product",
 *          @OA\Schema(
 *              type="integer",
 *              example=1
 *          )
 *      ),
 *      requestBody=@OA\RequestBody(
 *          request="/Product/{id}",
 *          required=true,
 *          description="The credentials are passed to the server via the request body.",
 *          @OA\MediaType(
 *              mediaType="application/json",
 *              @OA\Schema(
 *                  @OA\Property(property="sku", type="string", example="bebrik"),
 *                  @OA\Property(property="active", type="boolean", example="true"),
 *                  @OA\Property(property="id_category", type="integer", example=10),
 *                  @OA\Property(property="name", type="string", example="Chocolate"),
 *                  @OA\Property(property="image", type="string", example="Chocolate image"),
 *                  @OA\Property(property="description", type="string", example="Dark chocolate"),
 *                  @OA\Property(property="price", type="decimal", example=12.5),
 *                  @OA\Property(property="stock", type="integer", example=10)
 *              )
 *          )
 *      ),
 *      @OA\Response(response="200", description="The product has changed"))
 *  )
 */
$app->put("/Product/{id}", function (Request $request, Response $response, $args) {
    require "controller/require_authentication.php";
    $id = anti_injection($args["id"], true);
    //If the parameters are not set
    if (!isset($id) || !is_numeric($id)) {
        message("False ID format", 400);
    }

    $product = get_product($id);
    $request_body = file_get_contents("php://input");
    $request_data = json_decode($request_body, true);

    $product = put_check("sku", $product, $request_data);
    $product = put_check("active", $product, $request_data);
    $product = put_check("id_category", $product, $request_data);
    $product = put_check("name", $product, $request_data);
    $product = put_check("image", $product, $request_data);
    $product = put_check("description", $product, $request_data);
    $product = put_check("price", $product, $request_data);
    $product = put_check("stock", $product, $request_data);
    //If an error occurs specifically at the database level, then an exception will be thrown.
    try {
        update_product($id, $product["sku"], $product["active"], $product["id_category"], $product["name"], $product["image"], $product["description"], $product["price"], $product["stock"]);
    } catch (Exception $pizdec) {
        message($pizdec->getMessage(), 500);
    }
    return $response;
});

//Category
/**
 *  @OA\Post(
 *      path="/Category",
 *      summary="Creates a new category.",
 *      tags={"Create"},
 *      requestBody=@OA\RequestBody(
 *          request="/Category",
 *          required=true,
 *          description="The credentials are passed to the server via the request body.",
 *          @OA\MediaType(
 *              mediaType="application/json",
 *              @OA\Schema(
 *                  @OA\Property(property="active", type="boolean", example=true),
 *                  @OA\Property(property="name", type="string", example="Headphones")
 *              )
 *          )
 *      ),
 *      @OA\Response(response="201", description="Successfully created category"))
 *  )
 */
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

    $active = anti_injection($request_data["active"]);
    $name = anti_injection($request_data["name"]);
    //If an error occurs specifically at the database level, then an exception will be thrown. 
    try {
        create_category($active, $name);
    } catch (Exception $pizdec) {
        message($pizdec->getMessage(), 500);
    }
    return $response;
});
/**
 *  @OA\Get(
 *      path="/Category/{id}",
 *      summary="Returns the category with the corresponding id",
 *      tags={"Search_one"},
 *      @OA\Parameter(
 *          name="id",
 *          in="path",
 *          required=true,
 *          description="ID of the wanted category",
 *          @OA\Schema(
 *              type="integer",
 *              example=1
 *          )
 *      ),
 *      @OA\Response(response="200", description="Output of the desired category")) 
 *  )
 */
$app->get("/Category/{id}", function (Request $request, Response $response, $args) {
    require "controller/require_authentication.php";
    $id = anti_injection($args["id"], true);
    //If the parameters are not set
    if (!isset($id) || !is_numeric($id)) {
        message("False ID format", 400);
    }

    message(get_category($id), 200);
    return $response;
});
/**
 *  @OA\Get(
 *      path="/Category",
 *      summary="Shows all categories",
 *      tags={"Search_many"},
 *      @OA\Response(response="200", description="All categories are shown"))
 *  )
 */
$app->get("/Category", function (Request $request, Response $response, $args) {
    require "controller/require_authentication.php";
    get_all_categories();   
    return $response;
});
/**
 *  @OA\Delete(
 *      path="/Category/{id}",
 *      summary="Deletes the category with the corresponding id",
 *      tags={"Delete"},
 *      @OA\Parameter(
 *          name="id",
 *          in="path",
 *          required=true,
 *          description="ID of the wanted category",
 *          @OA\Schema(
 *              type="integer",
 *              example=1
 *          )
 *      ),
 *      @OA\Response(response="200", description="The category was successfully removed"))
 *  )
 */
$app->delete("/Category/{id}", function (Request $request, Response $response, $args) {
    require "controller/require_authentication.php";
    $id = anti_injection($args["id"], true);
    //If the parameters are not set
    if (!isset($id) || !is_numeric($id)) {
        message("False ID format", 400);
    }

    delete_category($id);
    return $response;
});
/**
 *  @OA\Put(
 *      path="/Category/{id}",
 *      summary="Category Update",
 *      tags={"Update"},
 *      @OA\Parameter(
 *          name="id",
 *          in="path",
 *          required=true,
 *          description="ID of the wanted category",
 *          @OA\Schema(
 *              type="integer",
 *              example=1
 *          )
 *      ),
 *      requestBody=@OA\RequestBody(
 *          request="/Category/{id}",
 *          required=true,
 *          description="The credentials are passed to the server via the request body.",
 *          @OA\MediaType(
 *              mediaType="application/json",
 *              @OA\Schema(
 *                  @OA\Property(property="active", type="boolean", example="true"),
 *                  @OA\Property(property="name", type="string", example="Headphones"),
 *              )
 *          )
 *      ),
 *      @OA\Response(response="200", description="The category has changed"))
 *  )
 */
$app->put("/Category/{id}", function (Request $request, Response $response, $args) {
    require "controller/require_authentication.php";
    $id = anti_injection($args["id"], true);
    //If the parameters are not set
    if (!isset($id) || !is_numeric($id)) {
        message("False ID format", 400);
    }

    $category = get_category($id);
    $request_body = file_get_contents("php://input");
    $request_data = json_decode($request_body, true);

    $category = put_check("active", $category, $request_data);
    $category = put_check("name", $category, $request_data);
    //If an error occurs specifically at the database level, then an exception will be thrown.
    try {
        update_category($id, $category["active"], $category["name"]);
    } catch (Exception $pizdec) {
        message($pizdec->getMessage(), 500);
    }
    return $response;
});
//Vendor Launch
$app->run();
?>
