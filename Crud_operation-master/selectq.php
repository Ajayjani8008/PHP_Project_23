<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>the webdevelopers</title>
</head>

<body>
    <div style="display:flex; align-items:center; justify-content:center;height:100vh">
        <table>
            <thead>
                <th>id</th>
                <th>name</th>
                <th>email</th>
                <th>mobile</th>
                <th>pincode</th>
                <th>age</th>
                <th colspan="2">operation</th>
            </thead>
            <tbody>
                <?php include '_dbcon.php';

                $selectquery = "select * from dtable ";

                $query = mysqli_query($con, $selectquery);

                while ($result = mysqli_fetch_assoc($query)) {
                ?>
                <tr>
                    <td><?php echo $result['id'] ?></td>
                    <td><?php echo $result['name'] ?></td>
                    <td><?php echo $result['email'] ?></td>
                    <td><?php echo $result['mobile'] ?></td>
                    <td><?php echo $result['pincode'] ?></td>
                    <td><?php echo $result['age'] ?></td>
                    <td><a href="delete.php?id=<?php echo $result['id']; ?>">Delete</a></td>
                    <td><a href="update.php?id=<?php echo $result['id']; ?>">Modify</a></td>
                </tr>
                <?php
                }
                ?>
            </tbody>
        </table>
    </div>
</body>

</html>
