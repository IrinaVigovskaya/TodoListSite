<?php
session_start();
if(isset($_POST["id"]))
{
    $conn = new mysqli("localhost", "root", "", "todoL");
    if($conn->connect_error){
        die("Ошибка: " . $conn->connect_error);
    }
    $sql = "UPDATE tasks SET complited = '1' WHERE id = ".$_POST["id"];
    header("Location: index.php");
    if($conn->query($sql)){
        $_SESSION["Message"] = 'Задача отмечена как выполненная';
    }
    else{
        $_SESSION["Message"]= "Ошибка: " . $conn->error;
    }
    $conn->close();
}
?>