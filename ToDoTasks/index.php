<!DOCTYPE html>
<html>
<head>
    <title>To-Do List</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
</head>
<body>
    <h1>To-Do List</h1>
    <form method="post" action="">
        <input id="taskInput" type="text" name="taskText" placeholder="Enter a new task...">
        <button id="addButton" name="addTask">Add</button>
    </form>
   
    <ul id="taskList">
        <?php
        session_start();

        if (!isset($_SESSION['tasks'])) {
            $_SESSION['tasks'] = array();
        }

        if (isset($_POST['addTask'])) {
            $taskText = $_POST['taskText'];
            if (!empty($taskText)) {
                $_SESSION['tasks'][] = array(
                    'text' => htmlspecialchars($taskText),
                    'completed' => false
                );
            }
        }

        if (isset($_POST['markCompleted'])) {
            $taskIndex = $_POST['taskIndex'];
            if (isset($_SESSION['tasks'][$taskIndex])) {
                $_SESSION['tasks'][$taskIndex]['completed'] = true;
            }
        }

        if (isset($_POST['removeCompleted'])) {
            $_SESSION['tasks'] = array_filter($_SESSION['tasks'], function ($task) {
                return !$task['completed'];
            });
        }

        foreach ($_SESSION['tasks'] as $index => $task) {
            $completedClass = $task['completed'] ? 'completed' : '';
            $buttonOrIcon = $task['completed'] ? '&#10004;' : "<button class='markCompletedButton' name='markCompleted'>Mark as Completed</button>";
            echo "<li class='$completedClass'>" . htmlspecialchars($task['text']) . "
                <form style='display: inline;' method='post' action=''>
                    <input type='hidden' name='taskIndex' value='$index'>
                    $buttonOrIcon
                </form>
            </li>";
        }
        ?>
    </ul>
    <form method="post" action="">
        <button id="removeCompletedButton" name="removeCompleted">Remove Completed Tasks</button>
    </form>
</body>
</html>
