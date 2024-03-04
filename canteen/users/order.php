<?php
include('../includes/connect.php');
include('../functions/common_function.php');

if(isset($_GET['user_id'])){
    $user_id=$_GET['user_id'];
}

// Getting total items and total price of all items
$get_ip_address = getIPAddress();
$total_price = 0;
$cart_query_price = "SELECT * FROM cart_details WHERE ip_address='$get_ip_address'";
$result_cart_price = mysqli_query($con, $cart_query_price);
$invoice_number = mt_rand();
$status = 'pending';
$count_products = mysqli_num_rows($result_cart_price);

while($row_price = mysqli_fetch_array($result_cart_price)){
    $product_id = $row_price['product_id'];
    $product_quantity = $row_price['quantity']; // Fetch quantity from cart_details
    $select_product = "SELECT * FROM products WHERE product_id=$product_id";
    $run_price = mysqli_query($con, $select_product);
    
    while($row_product_price = mysqli_fetch_array($run_price)){
        $product_price = $row_product_price['product_price'];
        $total_price += $product_price * $product_quantity; // Multiply price by quantity
    }
}

// Getting quantity from cart
$get_cart = "SELECT SUM(quantity) AS total_quantity FROM cart_details WHERE ip_address='$get_ip_address'";
$run_cart = mysqli_query($con, $get_cart);
$get_item_quantity = mysqli_fetch_array($run_cart);
$quantity = $get_item_quantity['total_quantity'];

if($quantity == 0){
    $quantity = 1;
}

$subtotal = $total_price * $quantity;

// Description
$get_cart = "SELECT cart_details.*, products.product_id, products.product_title, cart_details.customization_description 
             FROM cart_details 
             INNER JOIN products ON cart_details.product_id = products.product_id";
$run_cart = mysqli_query($con, $get_cart);

$customization_description = ''; // Initialize the variable before the loop
$product_titles = ''; // Initialize the variable before the loop

while ($row = mysqli_fetch_assoc($run_cart)) {
    $product_title = $row['product_title'];
    $customization = $row['customization_description'];
    $customization_description .= "$product_title: $customization, ";
    $product_titles .= $product_title . ", "; // Concatenate product titles
}

$customization_description = rtrim($customization_description, ', ');
$product_titles = rtrim($product_titles, ', ');

// Generate a unique token
$order_token = uniqid();

// Insert into user_orders
$insert_orders = "INSERT INTO `user_orders` (user_id, amount_due, order_token, total_products, customization_description, order_date, order_status, quantity, product_titles) 
                  VALUES ($user_id, $subtotal, '$order_token', $count_products, '$customization_description', NOW(), '$status', $quantity, '$product_titles')";

$result_query = mysqli_query($con, $insert_orders);

if ($result_query) {
    echo "<script>alert('Orders are submitted successfully')</script>";
    echo "<script>window.open('profile.php','_self')</script>";
}

// Pending orders
$insert_pending_orders = "INSERT INTO `orders_pending` (user_id, invoice_number, product_id, quantity, order_status) 
                          VALUES ($user_id, $invoice_number, $product_id, $quantity, '$status')";
$result_pending_orders = mysqli_query($con, $insert_pending_orders);

// Delete items from cart
$empty_cart = "DELETE FROM `cart_details` WHERE ip_address='$get_ip_address'";
$result_delete = mysqli_query($con, $empty_cart);
?>