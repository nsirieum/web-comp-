<?php

include '../db_connect.php';


if(isset($_GET['cc_id'])){
    $cc_id = $_GET['cc_id'];
}else{
    header('Location: showing.php');
    exit;
}


$cc_all_sql = mysqli_prepare($conn, "SELECT * FROM concert_list WHERE cc_id=?");
mysqli_stmt_bind_param($cc_all_sql,"s", $cc_id);
mysqli_stmt_execute( $cc_all_sql );
$cc_all_result = mysqli_stmt_get_result($cc_all_sql);

if($cc_all_result && mysqli_num_rows($cc_all_result) > 0){
    $cc_all_row = mysqli_fetch_assoc($cc_all_result);
}else{
    echo htmlspecialchars("<body style='background:#000; color:#fff; font-family:sans-serif;'><div align='center'><h1> Concert not found! </h1><a href='showing.php' style='color:red;'>Back to list</a></div></body>",ENT_QUOTES,'UTF-8');
    exit; 
}

?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($cc_all_row['cc_name'],ENT_QUOTES, 'UTF-8'); ?> - Detail</title>
    <style>
        body { font-family: 'Kanit', sans-serif; background-color: #0b0b0b; color: white; margin: 0; padding: 40px; }
        .container { max-width: 1000px; margin: auto; display: flex; gap: 50px; background: #1a1a1a; padding: 30px; border-radius: 20px; box-shadow: 0 20px 50px rgba(0,0,0,0.5); }
        
        
        .poster-sec { flex: 1; }
        .poster-sec img { width: 100%; border-radius: 15px; box-shadow: 0 10px 20px rgba(0,0,0,0.4); }

     
        .info-sec { flex: 1.5; display: flex; flex-direction: column; justify-content: center; }
        .info-sec h1 { font-size: 2.5em; margin-bottom: 10px; color: #fff; }
        .artist { font-size: 1.5em; color: #ff3e3e; margin-bottom: 20px; }
        .detail-item { margin-bottom: 15px; font-size: 1.1em; color: #ccc; }
        .detail-item b { color: #fff; }
        
        .price-box { font-size: 2em; color: #ffcc00; margin: 25px 0; font-weight: bold; }
        .btn-buy { background: #ff3e3e; color: white; padding: 15px 40px; text-decoration: none; border-radius: 50px; font-weight: bold; text-align: center; transition: 0.3s; }
        .btn-buy:hover { background: #d32f2f; transform: scale(1.05); }
        .back-link { margin-top: 30px; color: #888; text-decoration: none; display: inline-block; }

        .stepper-input {
            display: flex;
            align-items: center;
            background-color: #333; 
            border-radius: 5px;
            overflow: hidden;
            width: 150px; 
        }

        .stepper-input button {
            background: transparent;
            color: #fff;
            border: none;
            font-size: 1.5em;
            cursor: pointer;
            padding: 10px 15px;
            transition: 0.2s;
        }

        .stepper-input button:hover {
            background-color: #444; 
        }

     
        .stepper-input input[type="number"] {
            display: none;
        }

        
        .qty-display {
            color: #fff;
            font-size: 1.2em;
            font-weight: bold;
            flex-grow: 1;
            text-align: center;
            background-color: transparent;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="poster-sec">
        <img src="../img/<?php echo htmlspecialchars($cc_all_row['image'],ENT_QUOTES, 'UTF-8'); ?>" this.onerror=null ; this.src='../img/default.jpg'>
    </div>

    <div class="info-sec">
        <h1><?php echo htmlspecialchars($cc_all_row['cc_name'],ENT_QUOTES, 'UTF-8'); ?></h1>
        <div class="artist"><?php echo htmlspecialchars($cc_all_row['Artist'],ENT_QUOTES, 'UTF-8'); ?></div>
        
        <div class="detail-item"><b>Venue:</b> <?php echo htmlspecialchars($cc_all_row['venue'],ENT_QUOTES, 'UTF-8'); ?></div>
        <div class="detail-item"><b>Date:</b> <?php echo htmlspecialchars($cc_all_row['show_date'],ENT_QUOTES, 'UTF-8'); ?></div>
        <div class="detail-item"><b>Detail:</b> <?php echo htmlspecialchars($cc_all_row['detail'],ENT_QUOTES, 'UTF-8'); ?></div>
        <div class="detail-item"><b>Remaining:</b> <?php echo htmlspecialchars($cc_all_row['total_seat'],ENT_QUOTES, 'UTF-8'); ?> Seats</div>

        <div class="price-box">฿<?php echo htmlspecialchars(number_format($cc_all_row['price']),ENT_QUOTES, 'UTF-8'); ?></div>

        <form action="../payment/pay_summary.php" method = "POST" >
            <input type="hidden" name="concert_id" value="<?php echo htmlspecialchars($cc_id,ENT_QUOTES, 'UTF-8') ?>">

            <div style= "margin: 50px 200;">
                <label for = "ticket_qty"> Select Ticket Quantity* </label>
                <select name = "ticket_qty" id="qty">
                    <option value = 0 > 0 </option>
                    <option value = 1 > 1 </option>
                    <option value = 2 > 2 </option>
                    <option value = 3 > 3 </option>
                </select>
            </div>
                           
            <button type="submit" class="btn-buy" style="width: 50%; border: none; cursor: pointer;"> BUY </button>
            <br>
        </form>

        <a href="showing.php" class="back-link">← Back to Concert List</a>
      
        
   
</div>

</body>
</html>