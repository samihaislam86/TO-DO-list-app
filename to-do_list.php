<?php
const TASKS_FILE = 'task.json';

function saveTasks(array $task):void
{
    file_put_contents(TASKS_FILE, json_encode($task,JSON_PRETTY_PRINT));

}


function loadTasks(){
    if(!file_exists(TASKS_FILE)){
        return [] ;
    }
    $data = file_get_contents(TASKS_FILE);
    return $data ? json_decode($data,true): [];

}


$task=loadTasks();

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    if(isset($_POST['task']) && !empty(trim($_POST['task']))){
        $task[]=[
            "task"=>htmlspecialchars(trim($_POST['task'])),
            "done"=>false


        ];
        saveTasks($task);
        header('Location: '.$_SERVER['PHP_SELF']);
        exit;
    }elseif(isset($_POST['delete'])){
        unset($task[$_POST['delete']]);
        $task = array_values($task);
        saveTasks($task);
        header('Location: '.$_SERVER['PHP_SELF']);
        exit;
        

    }elseif(isset($_POST['toggle'])){
        $task[$_POST['toggle']]['done'] =!$task[$_POST['toggle']]['done'];
        saveTasks($task);
        header('Location: '.$_SERVER['PHP_SELF']);


    }

}





?>

<!-- UI -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>To-Do App</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/milligram/1.4.1/milligram.min.css">
    <style>
        body {
            margin-top: 20px;
        }
        .task-card {
            border: 1px solid #ececec; 
            padding: 20px;
            border-radius: 5px;
            background: #fff;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); 
        }
        .task{
            color: #888;
        }
        .task-done {
            text-decoration: line-through;
            color: #888;
        }
        .task-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 10px;
        }
        ul {
            padding-left: 20px;
        }
        button {
            cursor: pointer;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="task-card">
            <h1>To-Do App</h1>

            <!-- Add Task Form -->
            <form method="POST">
                <div class="row">
                    <div class="column column-75">
                        <input type="text" name="task" placeholder="Enter a new task" required>
                    </div>
                    <div class="column column-25">
                        <button type="submit" class="button-primary">Add Task</button>
                    </div>
                </div>
            </form>

            <!-- Task List -->
            <h2>Task List</h2>
            <ul style="list-style: none; padding: 0;">
                <!-- TODO: Loop through tasks array and display each task with a toggle and delete option -->
                <!-- If there are no tasks, display a message saying "No tasks yet. Add one above!" -->
                <?php if(empty($task)): ?>
                    <li>No tasks yet. Add one above!</li>

                <?php else: ?>
                    <?php foreach($task as $index => $tasks):?>
                    <li class="task-item">
                            <form method="POST" style="flex-grow: 1;">
                                <input type="hidden" name="toggle" value="<?= $index?>">
                           
                            <button type="submit" style="border: none; background: none; cursor: pointer; text-align: left; width: 100%;">
                        <span class="task <?= $tasks['done']? 'task-done':''?>">
                          <?= $index?> <?=$tasks['task']?>
                        </span>
                    </button>
                    </form>

                    <form method="POST">
                    <input  name="delete" value="<?= $index?>">
                    <button type="submit" class="button button-outline" style="margin-left: 10px;">Delete</button>
                    </form>
                    </li>
                <?php endforeach; ?>

                <?php endif; ?>
                     
                    
                 
                    
                       
                       

            </ul>

        </div>
    </div>
</body>
</html>