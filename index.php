<style>
    .overdue {
        color: tomato;
        font-weight: bold;
    }
</style>

<style>
    .just {
        color: #b6ffbb;
    }
</style>
<head>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=El+Messiri:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="index.css">
</head>
<?php
date_default_timezone_set('UTC');
// Подключаемся к базе данных
$mysqli = mysqli_connect("localhost", "root", "", "todoL");

// Проверяем соединение
if ($mysqli->connect_errno) {
    echo "Не удалось подключиться к MySQL: " . $mysqli->connect_error;
    exit();
}

// Проверяем, авторизован ли пользователь
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
$username = $_SESSION['username']; // $username - имя пользователя
$current_date = date('Y-m-d H:i:s');
if (!isset($_SESSION['username'])) {
    // Перенаправление на страницу входа, если имя пользователя не установлено в сессии
    header('Location: login.php');
    exit();
}
?>
<body>
<header class="container-fluid">
    <div class="container">
        <div class="row">
            <div class="col-4">
                <h1>ToDo List</h1>
            </div>
            <nav class="col-8">
                <ul>
                    <li>
                        <a href="index.php">Главная страница</a>
                    </li>
                    <li>
                        <a href="login.php"> Выход</a>
                    </li>
                    <h4>Добро пожаловать,
                        <?php
                        echo $_SESSION['username'];
                        ?>
                    </h4>
                </ul>
            </nav>
        </div>
    </div>
</header>
<div class="container-fluid">
    <div class="container">
        <div class="row" style="width: 1000px">
<div class="col=8" style="width: 500px">
            <div class="search" style="float: right">
                <h2>Найти задачу</h2>
                <form method="get" action="index.php">
                    <input type="text" name="query" placeholder="Поиск...">
                    <button type="submit">Найти</button>
                </form>
            </div>
<?php
$query = $_GET['query'];
if (isset($_POST['task_name'])) {
$task_name = $_POST['task_name'];
$task_description = $_POST['task_description'];
$task_due_date = $_POST['task_due_date'];
$user_id = $_SESSION['user_id'];

$stmt = $mysqli->prepare("INSERT INTO tasks (user_id, task_name, task_description, task_due_date) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $user_id, $task_name, $task_description, $task_due_date);
    $stmt->execute();
}

// Получаем список задач из базы данных
$user_id = $_SESSION['user_id'];

// Отображаем список задач
echo "<h2>Список задач</h2>";
echo "<ul>";
if($query == null OR $query == '') {
    $result = $mysqli->query("SELECT * FROM tasks WHERE user_id = '$user_id'");
while ($row = $result->fetch_assoc()) {
    $task_due_date = $row['task_due_date'];
    $completed = $row['completed'];
    // Если задача не выполнена и дата выполнения прошла, выделить ее как просроченную
    if ($completed == 0 && ($task_due_date <= $current_date)) {
        echo '<span class="overdue">' . $row['task_name'] . " " . $row['task_due_date'] . ' (Просрочено) </span><br>';
        echo "<form action='delete.php' method='post'>
                        <input type='hidden' name='id' value='" . $row["id"] . "' />
                        <input type='submit' value='Удалить'>
                </form>";
    } else {
        echo '<span class="just">', "<li>" . $row['task_name'] . " - " . $row['task_description'] . " - " . $row['task_due_date'].'</span>';
        if ($row["complited"] == 0) {
            echo "<form action='incr.php' method='post'>
                        <input type='hidden' name='id' value='" . $row["id"] . "' />
                        <input type='submit' value='Выполнить'>
                </form>";
        } else {
            echo " ✔";
        }
        echo "<form action='delete.php' method='post'>
                        <input type='hidden' name='id' value='" . $row["id"] . "' />
                        <input type='submit' value='Удалить'>
                </form>";
        //echo "<form action='index.php'>
        //<input type='checkbox' name='comp' value='comp2'> Выполнение</form>";
    }}}
    else{
        $result = mysqli_query($mysqli, "SELECT * FROM tasks WHERE user_id = $user_id && (task_name LIKE '%$query%' || task_description LIKE '%$query%')");
        $query ='';
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $task_due_date = $row['task_due_date'];
                $completed = $row['completed'];

                // Если задача не выполнена и дата выполнения прошла, выделить ее как просроченную
                if ($completed == 0 && ($task_due_date < $current_date)) {
                    echo '<span class="overdue">' . $row['task_name'] . ' (Просрочено)</span><br>';
                    echo "<form action='delete.php' method='post'>
                        <input type='hidden' name='id' value='" . $row["id"] . "' />
                        <input type='submit' value='Удалить'>
                </form>";
                } else {
                    echo '<span class="just">', "<li>" . $row['task_name'] . " - " . $row['task_description'] . " - " . $row['task_due_date'].'</span>';
                    if ($row["complited"] == 0) {
                        echo "<form action='incr.php' method='post'>
                        <input type='hidden' name='id' value='" . $row["id"] . "' />
                        <input type='submit' value='Выполнить'>
                </form>";
                    } else {
                        echo " ✔";
                    }
                    echo "<form action='delete.php' method='post'>
                        <input type='hidden' name='id' value='" . $row["id"] . "' />
                        <input type='submit' value='Удалить'>
                </form>";
                }
            }
        } else {
            echo 'Ничего не найдено.';
        }
    }

if($_SESSION["Message"]){
    echo $_SESSION["Message"];
    $_SESSION["Message"] = null;
}
?>
</div>
    <div class="col-4" style="width: 500px">
<?php
// Отображаем форму для добавления новой задачи
echo "<h2>Добавить задачу</h2>";
echo '<span class="just">',"<form method='post'>";
echo "<label>Название задачи:</label><br>";
echo "<input type='text' name='task_name'><br>";
echo "<label>Описание задачи:</label><br>";
echo "<textarea name='task_description'></textarea><br>";
echo "<label>Срок выполнения:</label><br>";
echo "<input type='date' name='task_due_date'><br><br>";
echo "<input type='submit' value='Добавить'>";
echo "</form>";
?>
</div>
</div>
    </div>
</div>
</body>
