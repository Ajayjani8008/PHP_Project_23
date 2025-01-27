<?php
include '_dbcon.php';

// Check if 'id' is set in the URL
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Retrieve user data by ID
    $selectQuery = "SELECT * FROM dtable WHERE id = $id";
    $result = mysqli_query($con, $selectQuery);

    if ($result && mysqli_num_rows($result) > 0) {
        $userData = mysqli_fetch_assoc($result);
    } else {
        echo "User not found.";
        exit();
    }

    // Check if the update form is submitted
    if (isset($_POST['update'])) {
        $name = $_POST['name'];
        $email = $_POST['email'];
        $mobile = $_POST['mobile'];
        $pincode = $_POST['pincode'];
        $age = $_POST['age'];

        // Update user data
        $updateQuery = "UPDATE dtable SET name='$name', email='$email', mobile='$mobile', pincode='$pincode', age='$age' WHERE id=$id";
        $updateResult = mysqli_query($con, $updateQuery);

        if ($updateResult) {
            header("Location: selectq.php"); // Redirect back to the list of users after updating
            exit();
        } else {
            echo "Error updating user: " . mysqli_error($con);
        }
    }
} else {
    echo "Invalid user ID.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User</title>
</head>
<body>
    <div style="display:flex; align-items:center; justify-content:center;height:100vh">
        <form method="POST">
            <input type="hidden" name="id" value="<?php echo $userData['id']; ?>">
            <label for="name">Name:</label>
            <input type="text" name="name" id="name" value="<?php echo $userData['name']; ?>"><br><br>
            <label for="email">Email:</label>
            <input type="email" name="email" id="email" value="<?php echo $userData['email']; ?>"><br><br>
            <label for="mobile">Mobile:</label>
            <input type="text" name="mobile" id="mobile" value="<?php echo $userData['mobile']; ?>"><br><br>
            <label for="pincode">Pincode:</label>
            <input type="text" name="pincode" id="pincode" value="<?php echo $userData['pincode']; ?>"><br><br>
            <label for="age">Age:</label>
            <input type="text" name="age" id="age" value="<?php echo $userData['age']; ?>"><br><br>
            <button type="submit" name="update">Update</button>
        </form>
    </div>
</body>
</html>
