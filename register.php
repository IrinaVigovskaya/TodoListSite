<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=El+Messiri:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="index.css">
</head>
<?php
// Подключаемся к базе данных
$mysqli = mysqli_connect("localhost", "root", "", "todoL");

// Проверяем соединение
if ($mysqli->connect_errno) {
    echo "Не удалось подключиться к MySQL: " . $mysqli->connect_error;
    exit();
}

// Если форма была отправлена, добавляем нового пользователя в базу данных
if (isset($_POST['username'])) {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $stmt = $mysqli->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();

    header("Location: login.php");
    exit();
}
?>

<body style="text-align:center">
<div style="padding: 100px">
<h1>Регистрация</h1>
<form method='post'>
<label>Имя пользователя:</label><br>
<input type='text' name='username'><br>
<label>Пароль:</label><br>
<input type='password' name='password'><br>
<input type='submit' value='Зарегистрироваться'><br>
    <a href = "login.php">Авторизация</a>
</form>
</div>
</body>

