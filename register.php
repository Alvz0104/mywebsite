<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Register</title>
</head>
<body>
    <?php 
    require "user.php";
    $user=new User();
    if($_SERVER["REQUEST_METHOD"]=="POST"){
    $username=$_POST["username"];
    $password=$_POST["password"];
    $email=$_POST["email"];
    
    $message = $user->register($username, $password,$email);
    if (isset($message) && $message !== "") {
        if ($message === "Registration Successfully") {
            echo '<div class="message" style="color:#0a7e07; background:#e6ffea; padding:10px 15px; margin:-1px 0; border-radius:0px;">' . htmlspecialchars($message) . '</div>';
        } else {
            echo '<div class="message" style="color:#b00020; background:#ffecec; padding:10px 15px; margin:-1px 0; border-radius:0px;">' . htmlspecialchars($message) . '</div>';
        }
    }
    
}
    ?>
    
         <div class="container">
        <h1 class="login" >Register</h1>
<hr>
<br>
        <form method="POST" action="register.php">
            <label class="username">Username:</label>
            <br><input type="text" placeholder="Type here" name="username" required> <br><br>
            <label class="password">Password:</label>
            <br><input type="password" placeholder="Type here" name="password" required> <br><br>
            <label class="email">Email:</label>
            <br><input type="text" placeholder="Type here" name="email" required> <br><br>

            <button class="btn-click" type="submit">REGISTER</button>

            <h4>Already have an account? <a class="breg" href="login.php">Login</a> </h4>
        </form>
    </div>
</body>
</html>