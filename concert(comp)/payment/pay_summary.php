<?php session_start(['cookie_httponly' => true]);

include "../db_connect.php";
$err = "";
    

if(!isset($_SESSION['user_id'])) {
    header("Location: ../user-login-re/login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

$selected_payment = isset($_POST['payment_method']) ? $_POST['payment_method'] :'' ;
if(isset($selected_payment) ) {
   // echo $selected_payment;
}


// รับค่าจาก show_detail
$cc_id = $_POST['concert_id'];
$qty = $_POST['ticket_qty'];


if(!isset($cc_id)){
    die("Please Select Event!");
};


//ดึง database 
    // concert
$cc_id_sql= mysqli_prepare($conn,"SELECT* FROM concert_list WHERE cc_id=? ");
mysqli_stmt_bind_param($cc_id_sql,"s", $cc_id);
mysqli_stmt_execute($cc_id_sql);
$cc_id_result = mysqli_stmt_get_result($cc_id_sql);
$cc_id_row = mysqli_fetch_assoc($cc_id_result);

    // users
$u_sql = mysqli_prepare($conn,"SELECT* FROM users WHERE user_id=? ");
mysqli_stmt_bind_param($u_sql,"s", $user_id);
mysqli_stmt_execute($u_sql);
$u_result = mysqli_stmt_get_result($u_sql);
$u_row = mysqli_fetch_assoc($u_result);


if($cc_id_row && $u_row){
    $price_per_ticket = $cc_id_row['price'];
    
    // เช้คเงื่อนไข qty
    if( $qty > 3 || $qty <= 0 ){
        header("Location: ../ListConcert/show_detail.php?cc_id=$cc_id");
        exit();
    }

    $total_price = $qty * $price_per_ticket;

    // เลข order
    $order_id =  substr(base64_encode(md5(time())),0,15);

    if(isset($_POST['order_id'])) {
        $order_id = $_POST['order_id'];
    }else{
        $order_id =  substr(base64_encode(md5(time())),0,15);
    }

    date_default_timezone_set("Asia/Bangkok");
    $time = date("Y-m-d H:i:s");

}else{

    die("Not found your concert!");
}


?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>Order Summary | Get Ready for Concert</title> 
    <style>
        body { font-family: 'Kanit', sans-serif; background-color: #0b0b0b; color: white; padding: 40px; }
        .summary-container { max-width: 600px; margin: auto; background: #1a1a1a; padding: 30px; border-radius: 20px; border: 1px solid #333; }
        .back-link { display: inline-flex; align-items: center; text-decoration: none; color: #888; font-size: 0.9em; margin-bottom: 15px; transition: 0.3s; }
        .back-link:hover { color: #fff; transform: translateX(-5px); }
        .arrow { margin-right: 5px; }
        
        h1 { color: #ff3e3e; text-align: center; border-bottom: 2px solid #333; padding-bottom: 15px; text-transform: uppercase; letter-spacing: 1px; }
        .detail-row { display: flex; justify-content: space-between; margin: 15px 0; border-bottom: 1px dashed #333; padding-bottom: 8px; }
        .label { color: #888; font-size: 0.9rem; }
        .value { color: #fff; font-weight: bold; }
        .total-box { background: linear-gradient(145deg, #222, #111); padding: 25px; border-radius: 15px; margin: 25px 0; text-align: center; border: 1px solid #444; } 
        .total-price { font-size: 2.5em; color: #ffcc00; text-shadow: 0 0 10px rgba(255, 204, 0, 0.2); }    

        .payment-title { text-align: center; color: #55b65a; margin: 20px 0 15px; font-weight: 600; text-transform: uppercase; font-size: 0.8rem; }
        .payment-grid { display: grid; grid-template-columns; gap: 15px; margin-bottom: 25px; }
        .pay-card input[type="radio"] { display: none; }
        .card-content { background: #222; border: 2px solid #333; border-radius: 12px; padding: 15px; text-align: center; cursor: pointer; transition: 0.3s; color: #bbb; font-size: 0.85rem; }
        .pay-card:hover .card-content { border-color: #55b65a; background: #2a2a2a; }
        .pay-card input[type="radio"]:checked + .card-content { border-color: #55b65a; background: rgba(85, 182, 90, 0.1); color: white; font-weight: bold; }

        .cc-panel { background: #252525; padding: 20px; border-radius: 12px; border: 1px solid #444; margin-top: 20px; animation: fadeIn 0.4s ease-out; }
        .cc-label { display: block; color: #ffd500; font-size: 0.8rem; margin-bottom: 5px; text-transform: uppercase; }
        .cc-input { width: 100%; padding: 12px; background: #111; border: 1px solid #444; border-radius: 8px; color: white; margin-bottom: 15px; box-sizing: border-box; }
        .cc-input:focus { border-color: #ff3e3e; outline: none; }

        .btn-pay { display: block; width: 100%; background: #28a745; color: white; text-align: center; padding: 18px; border-radius: 50px; text-decoration: none; margin-top: 30px; font-weight: bold; border: none; font-size: 1.1rem; cursor: pointer; transition: 0.3s; }
        .btn-pay:hover { background: #218838; transform: translateY(-3px); box-shadow: 0 10px 20px rgba(40, 167, 69, 0.3); }
        
        @keyframes fadeIn { from { opacity: 0; transform: translateY(-10px); } to { opacity: 1; transform: translateY(0); } }
    </style>
</head>

<body>
<div class="summary-container">

    <a href="../ListConcert/show_detail.php?cc_id=<?php echo $cc_id; ?>" class="back-link">
        <span class="arrow">←</span> Back to detail
    </a>

    <h1>Order Summary</h1>

    <div class="detail-row">
        <span class="label">Order ID :</span>
        <span class="value"><?php echo htmlspecialchars($order_id,ENT_QUOTES, 'UTF-8'); ?></span>
    </div>

    <div class="detail-row">
        <span class="label">Event Title :</span>
        <span class="value"><?php echo htmlspecialchars($cc_id_row['cc_name'],ENT_QUOTES, 'UTF-8'); ?></span>
    </div>

    <div class="detail-row">
        <span class="label">Artist :</span>
        <span class="value"><?php echo htmlspecialchars($cc_id_row['Artist'],ENT_QUOTES, 'UTF-8'); ?></span>
    </div>

    <div class="detail-row">
        <span class="label">Event Date : </span>
        <span class="value"><?php echo htmlspecialchars($cc_id_row['show_date'],ENT_QUOTES, 'UTF-8'); ?></span>
    </div>

    <div class="detail-row">
        <span class="label">Order Date (yyyy-mm-dd) :</span>
        <span class="value"><?php echo htmlspecialchars($time,ENT_QUOTES, 'UTF-8'); ?></span>
    </div>

    <div class="detail-row">
        <span class="label">Purchaser Name :</span>
        <span class="value">
            <?php 
                if(isset($u_row['firstname']) && isset($u_row['lastname'])){
                    echo htmlspecialchars($u_row['firstname']).' '.htmlspecialchars($u_row['lastname']);    

                }else{
                    echo htmlspecialchars("@".$_SESSION['username'],ENT_QUOTES, 'UTF-8');
            }
            ?></span> 
        </div>

    <div class="detail-row">
        <span class="label">Ticket Quantity :</span>
        <span class="value"><?php echo htmlspecialchars($qty,ENT_QUOTES, 'UTF-8'); ?></span>
    </div>    

    <div class="total-box">
        <span class="label">Total Amount</span><br>
        <span class="total-price">฿<?php echo htmlspecialchars(number_format($total_price),ENT_QUOTES, 'UTF-8'); ?></span>
    </div>

    <div class="payment-title"> Payment Method</div>
    


    <form action="pay_summary.php" method="POST">
        <input type="hidden" name="concert_id" value="<?php echo htmlspecialchars($cc_id,ENT_QUOTES, 'UTF-8'); ?>">
        <input type="hidden" name="ticket_qty" value="<?php echo htmlspecialchars($qty,ENT_QUOTES, 'UTF-8'); ?>">
        <input type="hidden" name="order_id" value="<?php echo htmlspecialchars($order_id,ENT_QUOTES, 'UTF-8'); ?>">

        <div class="payment-grid">
            <label class="pay-card">
                <input type="radio" name="payment_method" value="credit" <?php if($selected_payment == 'credit') echo htmlspecialchars('checked',ENT_QUOTES, 'UTF-8'); ?> onchange="this.form.submit()">
                <div class="card-content">Credit Card</div>
            </label>
        </div>
    </form>


    <form action="check_payment.php" method="POST">
        <input type= "hidden" name= "order_id" value="<?php echo htmlspecialchars($order_id,ENT_QUOTES, 'UTF-8'); ?>" >
        <input type="hidden" name="concert_id" value="<?php echo htmlspecialchars($cc_id,ENT_QUOTES, 'UTF-8'); ?>">
        <input type="hidden" name="ticket_qty" value="<?php echo htmlspecialchars($qty,ENT_QUOTES, 'UTF-8'); ?>">
        <input type="hidden" name="time" value="<?php echo htmlspecialchars($time,ENT_QUOTES, 'UTF-8'); ?>">
        <input type="hidden" name="payment_method" value="<?php echo htmlspecialchars($selected_payment,ENT_QUOTES, 'UTF-8');?>">

        <?php if($selected_payment == 'credit'){?>
            <div class="cc-panel">
                <h3 style="color: #ffd500; font-size: 0.9rem; margin-top: 0;">CARD INFORMATION</h3>

                <div style="margin-bottom: 12px;">
                    <label class="cc-label">Cardholder Name</label>
                    <input type="text" name="card_name" class="cc-input" placeholder="Name on Card" required>
                </div>

                <div style="margin-bottom: 12px;">
                    <label class="cc-label">Card Number (XXXX-XXXXXX-XXXXXX) </label>
                    <input type="text" name="card_number" class="cc-input" minlength="18" maxlength="18" required>
                </div>

                <div style="display: flex; gap: 10px;">
                    <div style="flex: 1;">
                        <label class="cc-label">Exp. Date</label>
                        <input type="text" name="exp" class="cc-input" placeholder="MM/YY" minlength="5" maxlength="5" required>
                    </div>

                    <div style="flex: 1;">
                        <label class="cc-label">CVV / CVC</label>
                        <input type="password" name="cvv" class="cc-input" placeholder="***" minlength="3" maxlength="3" required>
                    </div>
                </div>
            </div>
             <button type="submit" class="btn-pay" >Confirm Payment</button>
        <?php };?>
    </form>


</body>
</html>