<?php
include '../db_connect.php';

if($_SERVER["REQUEST_METHOD"]== "POST"){
    $email = $_POST['email'];
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $user = $_POST['username'];
    $pass = $_POST['password'];
    $recheck_pass = $_POST['acceptPW'];
    
    
    if($recheck_pass != $pass){
        echo "<script> alert('Passwords do not match!') ; window.history.back(); </script> ";
    }elseif(strlen($recheck_pass) < 8){
        echo "<script> alert('Password must be at least 8 characters long!'); window.history.back(); </script> ";
    }else{

        $hash_pass = password_hash($pass, PASSWORD_DEFAULT); 

        $u_sql = "INSERT INTO `users`(`email`, `firstname`, `lastname`, `username`, `password`) VALUES (?,?,?,?,?)";
        $insert_u = mysqli_prepare($conn, $u_sql);
        mysqli_stmt_bind_param($insert_u,"sssss",$email, $fname, $lname, $user,$hash_pass);

        if( mysqli_stmt_execute($insert_u)){
            echo "<script> alert('Register successful') ; window.location.href='login.php'; </script>";
        }else{
            echo  "Error".mysqli_error($conn);
        }

        mysqli_stmt_close($insert_u);
    }

}
    
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
</head>

<style>
        :root {
            --primary: #ff3e3e;
            --glass: rgba(255, 255, 255, 0.05);
            --border: rgba(255, 255, 255, 0.1);
        }

        body {
            background: radial-gradient(circle at top, #1a1a1a 0%, #000000 100%);
            font-family: 'Kanit', sans-serif;
            color: white;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .reg-container {
            width: 100%;
            max-width: 450px;
            background: var(--glass);
            backdrop-filter: blur(15px);
            -webkit-backdrop-filter: blur(15px);
            padding: 40px;
            border-radius: 24px;
            border: 1px solid var(--border);
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.5);
            margin: 20px;
        }

        h1 {
            font-size: 2rem;
            background: linear-gradient(to right, #ff3e3e, #ff8e8e);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 30px;
            text-transform: uppercase;
            letter-spacing: 2px;
        }

        a{
            display: block;
            color: #ffffff;
            font-size: 0.99rem;
            margin-bottom: 8px;
            text-transform: uppercase;
        }

        .form-group {
            text-align: left;
            margin-bottom: 20px;
        }

        label {
            display: block;
            color: #ffd500;
            font-size: 0.99rem;
            margin-bottom: 8px;
            text-transform: uppercase;
        }

        input {
            width: 100%;
            padding: 12px 15px;
            background: rgba(255, 255, 255, 0.07);
            border: 1px solid var(--border);
            border-radius: 12px;
            color: white;
            font-family: 'Kanit', sans-serif;
            box-sizing: border-box;
            transition: 0.3s;
        }

        input:focus {
            outline: none;
            border-color: var(--primary);
            background: rgba(255, 255, 255, 0.12);
            box-shadow: 0 0 10px rgba(255, 62, 62, 0.2);
        }

        button {
            width: 100%;
            padding: 15px;
            background: linear-gradient(45deg, #ff3e3e, #d32f2f);
            color: white;
            border: none;
            border-radius: 50px;
            font-weight: 600;
            font-size: 1rem;
            cursor: pointer;
            margin-top: 20px;
            transition: 0.3s;
            box-shadow: 0 10px 20px rgba(255, 62, 62, 0.2);
        }

        button:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 30px rgba(255, 62, 62, 0.4);
        }

        .footer-text {
            margin-top: 30px;
            font-size: 0.9rem;
            color: #f2ff00;
        }

        .footer-text a {
            color: var(--primary);
            text-decoration: none;
            font-weight: 600;
        }

        .footer-text a:hover {
            text-decoration: underline;
        }
    </style>
    
<body>
    <div align = "center">
        <h1 align = "center"> Register </h1>
        <form action = "register.php" method = "post">
            <div align = "left">
                <label for = "email"> Email</label>
                <input type = "email" id = "email" name = "email" required placeholder="exmaple@mail.com"> <br><br>

                <label for = "firstname"> Firstname</label>
                <input type = "text" id = "fname" name = "fname" required placeholder=""> <br><br>

                <label for = "lastname"> Lastname</label>
                <input type = "text" id = "lname" name = "lname" required placeholder="" > <br><br>

                
                <label for = "username"> Username</label>
                <input type = "text" id = "username" name = "username" required > <br><br>
                
                <label for = "password"> Password </label>
                <input type = "password" id = "password" name = "password" minlength="8" placeholder="At least 8 characters" required> <br><br>

                <label for = "acceptPW"> Accept Password</label>
                <input type = "password" id = "acceptPW" name = "acceptPW" minlength="8" required> <br><br>
                
                <button type="submit">REGISTER</button> <br><br>
        
                <p align = "center"> Already have an account? <a href="login.php"> Log in</a></p> 
            </div>   
        </form>
    </div>
    
</body>
</html>

