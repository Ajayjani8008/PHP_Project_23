<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up Page</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
</head>
<body>
    <div class="container">
        <div class="signup-box">
            <h1>Sign Up</h1>
            <form method="post" action="signup.php">
                <label for="getname">Enter your name:</label>
                <input type="text" name="getname" id="getname"><br><br>
                <label for="getusername">Enter Username:</label>
                <input type="text" name="getusername" id="getusername"><br><br>
                <label for="getpassword">Enter Password:</label>
                <input type="password" name="getpassword" id="getpassword"><br><br>
                <label for="conpassword">Confirm Password:</label>
                <input type="password" name="conpassword" id="conpassword"><br><br>
                <button type="submit" name="sub">Sign Up</button><br>
            </form>
            <p class="message">
                Already have an account? <a href="index.php">Log in</a>
            </p>
            <?php
            require '_dbcon.php';

            if (isset($_POST['sub'])) {
                $getname = $_POST['getname'];
                $getusername = $_POST['getusername'];
                $getpassword = $_POST['getpassword'];
                $conpassword = $_POST['conpassword'];

                $sql = "SELECT username FROM user_data WHERE username='" . $getusername . "'";

                $sqlres = mysqli_query($conn, $sql);
                $rowcount = mysqli_num_rows($sqlres);

                if ($rowcount != 0) {
                    echo "<p class='error'>Username is not available. Try another one.</p>";
                } elseif ($getpassword != $conpassword) {
                    echo "<p class='error'>Passwords do not match.</p>";
                } else {
                    // Insert user data into the database
                    $insert_query = "INSERT INTO user_data (username, userpass) VALUES ('$getusername', '$getpassword')";
                    if (mysqli_query($conn, $insert_query)) {
                        echo "<p class='success'>You have successfully signed up.</p>";
                    } else {
                        echo "<p class='error'>Error: " . mysqli_error($conn) . "</p>";
                    }
                }
            }
            ?>
        </div>
    </div>
</body>
</html>
