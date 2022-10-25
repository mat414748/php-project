<?php
$database = new mysqli("localhost", "root", "", "online_shop");
require_once "../public/index.php";

function create_product($sku, $active, $id_category, $name, $image, $description, $price, $stock) {
   global $database;
   $result = $database->query("INSERT INTO product VALUES ('','$sku','$active','$id_category','$name','$image','$description','$price','$stock')");
   if (!$result){
      message("Error creating a new product",500);
   } else {
      message("Successfully created product",201);
   }
}

function delete_product($id) {
   global $database;
   $result = $database->query("DELETE FROM product WHERE product_id = $id");
   if (!$result){
      message("No product found for the ID: " . $registration_id,500);  
   } else if ($result === true && $database->affected_rows == 0){
      message("Non-existing ID: " . $id,400);  
   } else {
      message("The product was successfully removed",200);  
   }
}

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
		return $products_list;  
   }
}

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

function update_product($id, $sku, $active, $id_category, $name, $image, $description, $price, $stock) {
   global $database;
   $result = $database->query("UPDATE product SET sku = '$sku', active = '$active', id_category = '$id_category', name = '$name', image = '$image', description = '$description', price = '$price', stock = '$stock' WHERE product_id = $id");
   if (!$result){
      message("Update error", 500);  
   } else if ($result === true && $database->affected_rows == 0){
      message("The product is not found or an identical product already exists", 400);  
   } else {
      message("The product has been successfully updated", 200); 
   }
}

//category
function create_category($active, $name) {
   global $database;
   $result = $database->query("INSERT INTO category VALUES ('','$active','$name')");
   if (!$result){
     message("Error creating a new product",500);
   } else {
     message("Successfully created product",201);
   }
}

function delete_category($id) {
   global $database;
   $result = $database->query("DELETE FROM category WHERE category_id = $id");
   if (!$result){
      message("The category has not been removed",500);  
   } else if ($result === true && $database->affected_rows == 0){
      message("Non-existing ID: " . $id, 400);  
   } else {
      message("The category was successfully removed",200);  
   } 
}

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
		return $categories_list;  
   }
}

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