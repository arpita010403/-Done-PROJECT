<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My orders</title>
    <style>
        th {
            background: #5F8670;
            color: white;
        }
        
    </style>
</head>
<body>
    <?php
    
    $username=$_SESSION['username'];
    $get_user="Select * from `user_table` where username='$username'";
    $result=mysqli_query($con,$get_user);
    $row_fetch=mysqli_fetch_array($result);
    $user_id=$row_fetch['user_id'];
    
    ?>
    <h3 class="text-success">My orders</h3>
    <table class="table table-bordered mt-5 p-0">
        <thead class="border border-dark rounded">
            <tr>
                <th>Sr.No.</th>
                <th>Amount Due</th>
                <th>Total Items</th>
                <th>Date</th>
                <th>Complete/Incomplete</th>
                <th>Status</th>
                <th>Token</th>
              
            </tr>
        </thead>
        <tbody>
            <?php 
            $get_order_details="Select * from `user_orders` where user_id=$user_id";
            $result_orders=mysqli_query($con,$get_order_details);
            $number=1;
            while($row_orders=mysqli_fetch_array($result_orders)){
                $order_id=$row_orders['order_id'];
                $amount_due=$row_orders['amount_due'];
                $total_products=$row_orders['total_products'];
                
                $order_date=$row_orders['order_date'];
                $order_status=$row_orders['order_status'];
                $token = $row_orders['order_token']; // Get token
                if($order_status=='pending'){
                    $order_status='Incomplete';
                }else{
                    $order_status="Complete";
                }
                
                echo "<tr>
                    <td>$number</td>
                    <td>₹$amount_due/-</td>
                    <td>$total_products</td>
                   
                    <td>$order_date</td>
                    <td>$order_status</td>";
                if($order_status=='Complete'){
                    echo "<td>Paid</td>";
                } else{
                    echo "<td><a href='confirm_order.php?order_id=$order_id'>Confirm</a></td>";
                    
                }
                
                // Add View Token option for orders with Complete status
                if($order_status == 'Complete') {
                    echo "<td><a href='token.php?order_id=$order_id'>View Token</a></td>";
                } else {
                    echo "<td></td>";
                }
                echo "</tr>";
                
                $number++;
            }

            ?>
            
        </tbody>
    </table>
</body>
</html>