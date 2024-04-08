<?php
session_start();

$username = $_POST['username'];
$password = $_POST['password'];

if ($username == 'admin' && $password == '123') {
    $_SESSION['loggedin'] = true;
    header('Location: inputform.php');
} else {
    $_SESSION['loggedin'] = false;
    header('Location: index.html');
}
?>
