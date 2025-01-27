<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>fill up personal info</title>
    <?php include '_dbcon.php'
    ?>
</head>


<body>
    <div style="display:flex; align-items:center; justify-content:center;height:100vh">
        <div>
            <h1>Insert your personal Info</h1>
            <form method="POST">
                <label for="">name:</label>
                <input type="text" name="name"><br><br>
                <label for="">email:</label>
                <input type="text" name="email"><br><br>
                <label for="">mobile:</label>
                <input type="text" name="mobile"><br><br>
                <label for="">pincode:</label>
                <input type="text" name="pincode"><br><br>
                <label for="">age:</label>
                <input type="text" name="age"><br><br>
                <button type="submit" name="submit">submit</button><br><br>
                <button type="submit" name="submit">Submit</button><br><br>
                <a href="selectq.php" style="text-decoration: none;">Check Users</a><br>

            </form>
        </div>
    </div>
    <?php
    ?>
</body>


</html>
<?php
if (isset($_POST['submit'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $mobile = $_POST['mobile'];
    $pincode = $_POST['pincode'];
    $age = $_POST['age'];



    $insert = "INSERT INTO dtable (name, email, mobile, pincode, age) VALUES ( '$name', '$email', '$mobile', '$pincode', '$age');";



    $query = mysqli_query($con, $insert);
    if (!$query) {
        print("error!!!");
    } else {
        print("data inserted succesfully.");
    }
}
?>