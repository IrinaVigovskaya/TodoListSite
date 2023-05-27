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

// Если форма была отправлена, проверяем данные пользователя
if (isset($_POST['username'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $mysqli->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['password'])) {
            session_start();
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['username'] = $username;
            header("Location: index.php");
            exit();
        } else {
            echo "Неверный пароль";
        }
    } else {
        echo "Пользователь не найден";
    }
}
?>
<body style="text-align:center">
<div style="padding: 100px">
<h1>Авторизация</h1>
<form method='post'>
<label>Имя пользователя:</label><br>
<input type='text' name='username'><br>
<label>Пароль:</label><br>
<input type='password' name='password'><br>
<input type='submit' value='Войти'><br>
    <a href = "register.php">Зарегестрироваться</a>
</form>
</div>
</body>