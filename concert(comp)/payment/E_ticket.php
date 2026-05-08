<?php session_start(['cookie_httponly' => true]);

include '../db_connect.php';
error_reporting(E_ALL);
ini_set('display_errors', 1);


if (isset($_GET['order_id'])) {
    $order_id = $_GET['order_id'];
}else{
    $order_id = '';
}


$orders_sql = mysqli_prepare($conn,"SELECT * FROM orders WHERE order_id =?");
mysqli_stmt_bind_param($orders_sql,"s", $order_id);
mysqli_stmt_execute($orders_sql);
$orders_result = mysqli_stmt_get_result($orders_sql);
$orders_row = mysqli_fetch_assoc($orders_result);


if(!$orders_row){
    die("404 NOT FOUND");
}

    // กัน IDOR -> ดูตั๋วคนอื่น
if($orders_row['order_id']!= $_SESSION['order_id']){
    die("404 NOT FOUND - You do not have permission");
}



?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <div class="ticket-card">
        <div class="ticket-header">
            <h2>E-TICKET</h2>
        </div>

        <div class="ticket-body">
            <div class="info-group">
                <span class="label">Event</span>
                <span class="value"><?php echo $orders_row['cc_name']; ?></span>
            </div>

            <div style="display: flex; justify-content: space-between;">
                <div class="info-group">
                    <span class="label">Artist</span>
                    <span class="value"><?php echo htmlspecialchars($orders_row['Artist'],ENT_QUOTES, 'UTF-8'); ?></span>
                </div>
                <div class="info-group" style="text-align: right;">
                    <span class="label">Qty</span>
                    <span class="value"><?php echo htmlspecialchars($orders_row['qty'],ENT_QUOTES, 'UTF-8'); ?></span>
                </div>
            </div>

            <div class="info-group">
                <span class="label">Show Date</span>
                <span class="value"><?php echo htmlspecialchars($orders_row['show_date'],ENT_QUOTES, 'UTF-8'); ?></span>
            </div>

            <div class="stub-border"></div>

            <div class="info-group">
                <span class="label">Purchaser</span>
                <span class="value"><?php echo htmlspecialchars($orders_row['owner'],ENT_QUOTES, 'UTF-8'); ?></span>
            </div>

            <div class="info-group">
                <span class="label">Purchaser Time</span>
                <span class="value"><?php echo htmlspecialchars($orders_row['order_date'],ENT_QUOTES, 'UTF-8'); ?></span>
            </div>


        </div>

        <div class="ticket-footer">
            <p class="barcode"><?php echo htmlspecialchars($orders_row['order_id'],ENT_QUOTES, 'UTF-8'); ?></p>
            <p class="order-id"><?php echo htmlspecialchars($orders_row['order_id'],ENT_QUOTES, 'UTF-8'); ?></p>
            <div a href = "history.php">
                <button onclick="window.location.href='history.php'" class="btn-back" > VIEW HISTORY</button>
            </div>
        </div>
    </div>
</body>
</html>
<style>
    @import url('https://fonts.googleapis.com/css2?family=Kanit:wght@300;400;600&family=Libre+Barcode+128&display=swap');

    body {
        font-family: 'Kanit', sans-serif;
        background-color: #0f0f0f;
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: 100vh;
        margin: 0;
        color: white;
    }

    .ticket-card {
        background: #1e1e1e;
        width: 380px;
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 20px 50px rgba(0,0,0,0.5);
        position: relative;
        border: 1px solid #333;
    }

 
    .ticket-header {
        background: linear-gradient(135deg, #ff3e3e, #b30000);
        padding: 20px;
        text-align: center;
    }

    .ticket-header h2 {
        margin: 0;
        font-size: 1.5rem;
        text-transform: uppercase;
        letter-spacing: 2px;
    }


    .ticket-body {
        padding: 25px;
        position: relative;
    }

    .info-group {
        margin-bottom: 20px;
    }

    .label {
        color: #888;
        font-size: 0.8rem;
        text-transform: uppercase;
        display: block;
    }

    .value {
        font-size: 1.2rem;
        font-weight: 600;
        color: #fff;
    }

    /* เส้นประรอยปรุ */
    .stub-border {
        border-top: 2px dashed #333;
        position: relative;
        margin: 20px 0;
    }

    /* วงกลมเว้าข้างตั๋ว */
    .stub-border::before, .stub-border::after {
        content: '';
        position: absolute;
        width: 20px;
        height: 20px;
        background: #0f0f0f;
        border-radius: 50%;
        top: -10px;
    }
    .stub-border::before { left: -35px; }
    .stub-border::after { right: -35px; }


    .ticket-footer {
        padding: 20px;
        background: #252525;
        text-align: center;
    }

    .barcode {
        font-family: 'Libre Barcode 128', cursive;
        font-size: 4rem;
        color: #fff;
        margin: 0;
    }

    .order-id {
        font-family: monospace;
        color: #ffcc00;
        letter-spacing: 3px;
    }

    .btn-back {
        background: transparent;
        border: 1px solid #555;
        color: #888;
        padding: 8px 15px;
        border-radius: 50px;
        cursor: pointer;
        margin-top: 15px;
        transition: 0.3s;
    }
    .btn-print:hover { background: #fff; color: #000; }
</style>
