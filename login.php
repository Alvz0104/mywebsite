<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Login</title>
</head>
<body>
    <?php 
    session_start();
    require "user.php";
    $user =new User();
    if($_SERVER["REQUEST_METHOD"]=="POST"){
        $username=$_POST["username"];
        $password=$_POST["password"];

        $message=$user->login($username,$password);

        if($message=="Login Successful"){
            $_SESSION["username"]=$username;
            header("Location: dashboard.php");
        }

    }
    
    if (isset($message) && $message !== "") {
        echo '<div class="message" style="color:#b00020; background:#ffecec; padding:10px 15px; margin:-1px 0; border-radius:0px;">' . htmlspecialchars($message) . '</div>';
    }
    ?>
    
    <div>
        <div class="container">
        <h1 class="login" >Login</h1>

<hr>
<br>

        <form method="POST" action="login.php">
            <label class="username">Username:</label>
            <br><input type="text" placeholder="Type here" name="username" required><br><br>
            <label class="password">Password:</label>
            <br><input type="password" placeholder="Type here" name="password" required><br><br>

            <button class="btn-click" type="submit">LOGIN</button><br>

            <h4>Don't have an account? <a class="breg" href="register.php">Register</a> </h4> 
        </form>
    </div>
    </div>


   
</body>
</html>