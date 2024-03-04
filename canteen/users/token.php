<?php 
include('../includes/connect.php');
include('../functions/common_function.php');

// Check if the order ID is set
if(isset($_GET['order_id'])){
    // Get the order ID from the URL
    $order_id = $_GET['order_id'];
} else {
    // Redirect the user to an error page or display an error message
    exit("Order ID is not set.");
}

// Check if the order token has already been viewed
$get_token_viewed_query = "SELECT token_viewed FROM user_orders WHERE order_id = $order_id";
$token_viewed_result = mysqli_query($con, $get_token_viewed_query);
if (!$token_viewed_result) {
    // Handle the case where the query fails
    exit("Failed to fetch token viewed status.");
}
$row = mysqli_fetch_assoc($token_viewed_result);
$token_viewed = $row['token_viewed'];

// If the token has already been viewed, redirect the user to an error page or display an error message
if ($token_viewed) {
    exit("<div style='text-align: center;'><h2 style='color: red; font-weight: bold;'>Token has already been viewed.</h2><br><a href='../index.php' style='display: inline-block; padding: 10px 20px; background-color: black; color: #fff; text-decoration: none; border-radius: 4px; transition: background-color 0.3s ease;'>Home</a></div>");
}





// Generate a unique token
$order_token = uniqid();

// Update user_orders table with the generated order token and mark it as viewed
$update_query = "UPDATE user_orders SET order_token = '$order_token', token_viewed = 1 WHERE order_id = $order_id";
$result = mysqli_query($con, $update_query);
if (!$result) {
    // Handle the case where the update query fails
    exit("Failed to update order token.");
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Token Page</title>
  
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h1 {
            text-align: center;
            margin-bottom: 30px;
        }
        .token {
            font-size: 24px;
            text-align: center;
            padding: 20px;
            background-color: #f0f0f0;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        .btn {
            display: block;
            width: 100%;
            max-width: 200px;
            margin: 0 auto;
            padding: 10px 20px;
            background-color: #4caf50;
            color: #fff;
            text-align: center;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }
        .btn:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Order Token</h1>
        <div class="token">
            Order ID: <?php echo $order_id; ?><br>
            Order Token: <?php echo $order_token; ?>
        </div>
        <a href="profile.php" class="btn">Back to Profile</a>
    </div>
</body>
</html>
