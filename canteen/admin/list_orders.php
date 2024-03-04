<?php
include('../includes/connect.php');

// Fetch all orders
$get_orders = "SELECT * FROM `user_orders`";
$result = mysqli_query($con, $get_orders);
$row_count = mysqli_num_rows($result);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Orders</title>
    <style>
        th {
            background: #5F8670;
            color: white;
        }

        tbody {
            background-color: bisque;
        }
    </style>
</head>

<body>
    <h3 class="text-success text-center">All Orders</h3>
    <table class="table table-bordered mt-5">
        <thead>
            <tr class='bg-secondary text-light text-center'>
                <th>Sr.no.</th>
                <th>Order ID</th> <!-- New Column -->
                <th>Due Amount</th>
                <th>Token</th>
                <th>Quantity</th>
                <th>Item & Customization Details</th>
                <th>Order Date</th>
                <th>Status</th>
                <th>Token Viewed</th>
                <th>Delete</th>
            </tr>
        </thead>
        <tbody class='text-center'>
            <?php
            // If no orders found
            if ($row_count == 0) {
                echo "<h2 class='text-danger text-center mt-5'>No orders yet!</h2>";
            } else {
                $number = 0;
                while ($row_data = mysqli_fetch_array($result)) {
                    $order_id = $row_data['order_id'];
                    $amount_due = $row_data['amount_due'];
                    $token = $row_data['order_token']; // Fetch token
                    $quantity = $row_data['quantity'];
                    $customization_description = $row_data['customization_description'];
                    $order_date = $row_data['order_date'];
                    $order_status = $row_data['order_status'];
                    $token_viewed = $row_data['token_viewed']; // New column value
                    $number++;
            ?>
                    <tr>
                        <td><?php echo $number; ?></td>
                        <td><?php echo $order_id; ?></td> <!-- Display order_id -->
                        <td><?php echo $amount_due; ?></td>
                        <td><?php echo $token; ?></td>
                        <td><?php echo $quantity; ?></td>
                        <td><?php echo $customization_description; ?></td>
                        <td><?php echo $order_date; ?></td>
                        <td><?php echo $order_status; ?></td>
                        <td><?php echo $token_viewed ? 'Viewed' : 'Not Viewed'; ?></td>
                        <td><a href="admin_panel.php?delete_order=<?php echo $order_id ?>" type="button" class="text-dark" data-toggle="modal" data-target="#exampleModalLong"><i class="fa-solid fa-trash"></i></a></td>
                    </tr>
            <?php
                }
            }
            ?>
        </tbody>
    </table>
    <!-- Modal/popup confirmation -->
    <div class="modal fade" id="exampleModalLong" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <h4>Are you sure you want to delete this?</h4>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light border border-primary" data-dismiss="modal"><a href="./admin_panel.php?list_orders" class="text-decoration-none">No</a></button>
                    <button type="button" class="btn btn-primary"><a href='admin_panel.php?delete_order=<?php echo $order_id ?>' class="text-light text-decoration-none">Yes</a></button>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
