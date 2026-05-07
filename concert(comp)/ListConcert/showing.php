<?php session_start(['cookie_httponly' => true]);
include '../db_connect.php';


if (isset($_SESSION['username'])) {
    $username = $_SESSION['username'];
}else{
    header('Location: ../user-login-re/login.php');
}

$cc_sql = "SELECT * FROM concert_list";
$cc_all_result = mysqli_query($conn, $cc_sql);


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Showing Now</title>
</head>
<body>

    <div class="header-container">
            
        <div class="header-left">
            <a href="../user-login-re/login.php" class="btn-logout">Log out</a>
        </div>

        <div class="header-center">
            <span class="brand">tricker.com</span>
        </div>

        <div class="header-right">
            <span class="welcome-text">Hi, <?php echo htmlspecialchars($username,ENT_QUOTES, 'UTF-8'); ?></span>
            <span class="divider">|</span>
            <a href="../payment/history.php" class="history-link">View Order History</a>
        </div>
    </div>

    <h1 align="center">SHOWING AVAILABLE</h1>

    <div class = "All-showing">
        <?php while($cc_all_row = mysqli_fetch_array($cc_all_result)){  ?>

            <div class="card">

                <img src="../img/<?php echo htmlspecialchars($cc_all_row['image'],ENT_QUOTES, 'UTF-8');?>" onerror="this.onerror=null; this.src='../img/default.jpg';" >

                <h1><?php echo htmlspecialchars($cc_all_row['cc_name'],ENT_QUOTES, 'UTF-8'); ?></h1>
             

                <a href= "show_detail.php?cc_id=<?php echo htmlspecialchars($cc_all_row['cc_id'],ENT_QUOTES, 'UTF-8'); ?>" class="btn-" > Buy Now </a>
            </div>
        <?php };?>
    </div>
    <br>

</body>

<style>
    
    body {
        font-family: 'Kanit', sans-serif;
        background-color: #f4f4f9;
        margin: 0;
        padding: 20px;
    }

   
    .All-showing {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 25px; 
        padding: 20px;
        max-width: 1200px;
        margin: 0 auto; 
    }
  
    .card {
        background: #fff;
        border-radius: 15px;
        overflow: hidden; 
        box-shadow: 0 4px 15px rgba(0,0,0,0.1); 
        transition: transform 0.3s ease; 
        display: flex;
        flex-direction: column;
        text-align: center;
    }

    .card img {
        width: 100%;
        height: 400px;
        object-fit: cover;
        background-color: #ddd;
    }

    .card h1 {
        font-size: 1.2rem;
        margin: 15px 10px;
        color: #333;
        min-height: 50px; 
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .btn- {
        display: block;
        background-color: #ff4757;
        color: white;
        text-decoration: none;
        padding: 12px 0;
        margin: 0 20px 20px 20px;
        border-radius: 25px;
        font-weight: bold;
        transition: background 0.3s;
    }

    .btn-:hover {
        background-color: #e84118;
    }

       
    a[href*="login.php"] {
        display: inline-block;
        margin-top: 50px;
        padding: 10px 20px;
        background-color: #333;
        color: #fff !important;
        text-decoration: none;
        border-radius: 5px;
    }

   .header-container {
    display: flex;
    justify-content: space-between;
    align-items: center; 
    padding: 15px 30px;
    background-color: #1a1a1a;
    color: white;
    border-radius: 12px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.2);
    }

   
    .header-left, .header-right {
        flex: 1; 
    }

    .header-center {
        flex: 1;
        text-align: center;
    }

    .header-right {
        display: flex;
        justify-content: flex-end; 
        align-items: center;
        gap: 15px;
    }

    .brand {
        font-size: 1.5rem;
        font-weight: bold;
        color: #ff3e3e;
        letter-spacing: 1px;
        text-transform: lowercase;
    }


</style>

</html>