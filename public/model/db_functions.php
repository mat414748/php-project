<?php
$database = new mysqli("localhost", "root", "", "online_shop");
require_once "../public/index.php";
/**
 * A function for creating a new product and adding it to the database.
 * @param sku Product Sku
 * @param active Is the product active
 * @param id_category What category does the product belong to
 * @param name Product Name
 * @param image Product Image
 * @param description Product Description
 * @param price Product Price
 * @param stock Number of products in stock
 */
function create_product($sku, $active, $id_category, $name, $image, $description, $price, $stock) {
   global $database;
   $result = $database->query("INSERT INTO product VALUES ('','$sku','$active','$id_category','$name','$image','$description','$price','$stock')");
   if (!$result){
      message("Error creating a new product",500);
   } else {
      message("Successfully created product",201);
   }
}
/**
 * Function for removing product from db
 * @param id Product ID
 */
function delete_product($id) {
   global $database;
   $result = $database->query("DELETE FROM product WHERE product_id = $id");
   if (!$result){
      message("Request error", 500);  
   } else if ($result === true && $database->affected_rows == 0){
      message("An object with this ID does not exist. ID: " . $id, 404);  
   } else {
      message("The product was successfully removed",200);  
   }
}
/**
 * Function to search for all products
 */
function get_all_products() {
   global $database; 
   $result = $database->query("SELECT * FROM product");
   if (!$result){
      message("Error when requesting a list of products",500); 
   } else if ($result === true || $result->num_rows == 0){
      message("No products found",404);  
   } else {
      $products_list = array( );
		while ($product = $result->fetch_assoc()) {
			$products_list[] = $product;
		}
      message($products_list, 200);
   }
}
/**
 * Function to search for a specific product
 * @param id Product ID
 * @return product Returns the found product as an object
 */
function get_product($id) {
   global $database;
   $result = $database->query("SELECT * FROM product WHERE product_id = $id");
   if (!$result){
      message("Error when requesting a product",500); 
   } else if ($result === true || $result->num_rows == 0){
      message("No product found",404);  
   } else {
      $product = $result->fetch_assoc();
      return $product;  
   }
}
/**
 * Function for changing product data in a database row
 * @param id Product ID to change it
 * @param sku Product Sku
 * @param active Is the product active
 * @param id_category What category does the product belong to
 * @param name Product Name
 * @param image Product Image
 * @param description Product Description
 * @param price Product Price
 * @param stock Number of products in stock
 */
function update_product($id, $sku, $active, $id_category, $name, $image, $description, $price, $stock) {
   global $database;
   $result = $database->query("UPDATE product SET sku = '$sku', active = '$active', id_category = '$id_category', name = '$name', image = '$image', description = '$description', price = '$price', stock = '$stock' WHERE product_id = $id");
   if (!$result){
      message("Update error", 500);  
   } else if ($result === true && $database->affected_rows == 0){
      message("No changes have been made. Possible reasons:1.The product is not found 2.Identical product already exists", 400);  
   } else {
      message("The product has been successfully updated", 200); 
   }
}

//Categories
/**
 * A function for creating a new category and adding it to the database.
 * @param active Is the category active
 * @param name Category name
 */
function create_category($active, $name) {
   global $database;
   $result = $database->query("INSERT INTO category VALUES ('','$active','$name')");
   if (!$result){
     message("Error creating a new product",500);
   } else {
     message("Successfully created category",201);
   }
}
/**
 * Function for removing category from db
 * @param id Category ID
 */
function delete_category($id) {
   global $database;
   $result = $database->query("DELETE FROM category WHERE category_id = $id");
   if (!$result){
      message("Request error", 500);  
   } else if ($result === true && $database->affected_rows == 0){
      message("An object with this ID does not exist. ID: " . $id, 404);  
   } else {
      message("The category was successfully removed",200);  
   } 
}
/**
 * Function to search for all categories
 */
function get_all_categories() {
   global $database;
   $result = $database->query("SELECT * FROM category");
   if (!$result){
      message("Error when requesting a list of categories",500); 
   } else if ($result === true || $result->num_rows == 0){
      message("No categories found",404);  
   } else {
      $categories_list = array( );
		while ($category = $result->fetch_assoc()) {
			$categories_list[] = $category;
		}
      message($categories_list, 200);
   }
}
/**
 * Function to search for a specific category
 * @param id Category ID
 * @return category Returns the found category as an object
 */
function get_category($id) {
   global $database;
   $result = $database->query("SELECT * FROM category WHERE category_id = $id");
   if (!$result){
      message("Error when requesting a category", 500); 
   } else if ($result === true || $result->num_rows == 0){
      message("No category found", 404);  
   } else {
      $category = $result->fetch_assoc();
      return $category;  
   }
}
/**
 * Function for changing category data in a database row
 * @param id Category ID
 * @param active Is the category active
 * @param name Category name
 */
function update_category($id, $active, $name) {
   global $database;
   $result = $database->query("UPDATE category SET active = '$active', name = '$name' WHERE category_id = $id");
   if (!$result){
      message("Update error", 500);  
   } else if ($result === true && $database->affected_rows == 0){
      message("The product is not found or an identical product already exists", 400);  
   } else {
      message("The product has been successfully updated", 200); 
   }
}

?>