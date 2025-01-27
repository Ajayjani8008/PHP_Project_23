<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
</head>
<body>
    <div class="container">
        <div class="login-box">
            <h1>Login</h1>
            <form method="post" action="index.php">
                <label for="getusername">Enter Username:</label>
                <input type="text" name="getusername" id="getusername"><br><br>
                <label for="getpassword">Enter Password:</label>
                <input type="password" name="getpassword" id="getpassword"><br><br>
                <button type="submit" name="sub">Log in</button><br>
            </form>
            <p class="message">
                Don't have an account? <a href="signup.php">Sign Up</a>
            </p>
            <?php
            require '_dbcon.php';

            if (isset($_POST['sub'])) {
                $getusername = $_POST['getusername'];
                $getpassword = $_POST['getpassword'];
                $sql1 = "SELECT * FROM user_data WHERE username='$getusername' AND userpass='$getpassword'";
                $sqlres = mysqli_query($conn, $sql1);
                $countrows = mysqli_num_rows($sqlres);

                if ($countrows == 0) {
                    echo "<p class='error'>Account is not available. Please <a href='signup.php'>Sign Up.</a></p>";
                } else {
                    session_start();
                    $_SESSION['loggedin'] = true;
                    $_SESSION['sendusername'] = $getusername;
                    header("location:dashboard.php");
                }
            }
            ?>
        </div>
    </div>
</body>
</html>
