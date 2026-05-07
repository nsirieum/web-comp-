<?php session_start(['cookie_httponly' => true]);

include '../db_connect.php'; 
$err = "";

if($_SERVER["REQUEST_METHOD"]== "POST"){
    
    $username = $_POST['username'];
    $password = $_POST['password']; 

       // prepare + blind
    $stmt = mysqli_prepare($conn, "SELECT user_id, username, password FROM users WHERE username = ?");

    mysqli_stmt_bind_param($stmt, "s", $username);

    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        
        if (password_verify($password, $row['password'])) { // เทียบ hash password
         
            $_SESSION['user_id'] = $row['user_id'];
            $_SESSION['username'] = $row['username'];

            mysqli_stmt_close($stmt);

            header("Location: ../ListConcert/showing.php");
            exit();
        }

    }else {
        $err = "Username or Password is wrong.";
    }
}   
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ticker Login</title>
</head>
<body>
    <div align = "center">
        <h1> Log in </h1>


        <form action = "login.php" method = "post">
            <input type = "text" name= "username" placeholder="Username" required><br>
            <input type = "password" name= "password" placeholder="Password" required>
            <br><br>

            <?php if($err != ""){?>
                <p style="color: red; font-weight: bold;"> <?php echo htmlspecialchars($err); ?> </p>
            <?php }; ?>

            <button type="submit">LOG IN</button> <br><br>
        </form>


        <h1> Don't have an account? </h1>
        <a href="register.php"> Sign Up</a>
    </div>

</body>

<style>
    @import url('https://fonts.googleapis.com/css2?family=Kanit:wght@300;400;600&display=swap');

    body {
        font-family: 'Kanit', sans-serif;
        background-color: #0b0b0b; /* พื้นหลังดำลึก */
        background-image: radial-gradient(circle at center, #1a1a1a 0%, #0b0b0b 100%);
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: 100vh;
        margin: 0;
        color: white;
    }

    .login-container {
        background: #1a1a1a;
        padding: 40px;
        border-radius: 20px;
        box-shadow: 0 15px 35px rgba(0,0,0,0.7);
        width: 100%;
        max-width: 350px;
        border: 1px solid #333;
        text-align: center;
    }

    h1 {
        color: #ff3e3e; /* สีแดงเดียวกับตั๋ว */
        font-size: 2rem;
        margin-bottom: 30px;
        text-transform: uppercase;
        letter-spacing: 2px;
    }

    .input-group {
        margin-bottom: 20px;
        text-align: left;
    }

    input[type="text"], input[type="password"] {
        width: 100%;
        padding: 12px 15px;
        background: #0f0f0f;
        border: 1px solid #444;
        border-radius: 10px;
        color: white;
        font-size: 1rem;
        box-sizing: border-box;
        transition: 0.3s;
    }

    input:focus {
        border-color: #ff3e3e;
        outline: none;
        box-shadow: 0 0 10px rgba(255, 62, 62, 0.2);
    }

    .error-msg {
        color: #ff4d4d;
        background: rgba(255, 77, 77, 0.1);
        padding: 10px;
        border-radius: 8px;
        font-size: 0.9rem;
        margin-bottom: 20px;
        border: 1px solid rgba(255, 77, 77, 0.2);
    }

    button {
        width: 100%;
        padding: 12px;
        background: linear-gradient(135deg, #ff3e3e, #b30000);
        border: none;
        border-radius: 10px;
        color: white;
        font-size: 1.1rem;
        font-weight: 600;
        cursor: pointer;
        transition: 0.3s;
        text-transform: uppercase;
        margin-top: 10px;
    }

    button:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(255, 62, 62, 0.4);
    }

    .signup-text {
        margin-top: 25px;
        font-size: 0.9rem;
        color: #888;
    }

    .signup-text a {
        color: #ff3e3e;
        text-decoration: none;
        font-weight: 600;
    }

    .signup-text a:hover {
        text-decoration: underline;
    }
</style>

</html>

่