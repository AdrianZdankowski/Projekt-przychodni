<?php
session_start();
require_once "config.php";

$conn = @mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_SCHEMA);

if ($conn === false) {
    die(mysqli_connect_error());
} else {
    $login = $_POST['login'];
    $pswd = $_POST['haslo'];

    $sql = "SELECT * FROM klienci WHERE BINARY login='$login'";
    $query = mysqli_query($conn, $sql);
    $result = mysqli_fetch_array($query);

    if (password_verify($pswd, $result["haslo"])) {
        $_SESSION['client'] = $result;
        $_SESSION['loggedin'] = true;
        header('Location: /php/menu.php');
    } else {
        $_SESSION['loginerror'] = "<p class='error'>Nie ma takiego użytkownika</p>";
        header('Location: /index.php');
    }
}
