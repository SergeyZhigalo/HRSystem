<?php
session_start();
if ($_SESSION['logged_user'] == []){header('Location: /login.php');}
if ($_SESSION['logged_user']['idRole'] == 2){header('Location: /');}
?>
<!doctype html>
<html lang=ru>
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&amp;subset=cyrillic" rel="stylesheet">
    <link rel="stylesheet" href="css/font-awesome.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/editingParameters.css">
    <script src="js/jquery-3.5.1.js"></script>
    <script src="js/script.js"></script>
    <title>Страница администрирования</title>
</head>
<body onload="admin()">
<div id="topHeader">
    <div class="headerLeft">
        <a href="/">|&nbsp;&nbsp;&nbsp;на главную&nbsp;&nbsp;&nbsp;|</a>
        <a href="/changePassword.php">&nbsp;&nbsp;&nbsp;сменить пароль&nbsp;&nbsp;&nbsp;|</a>
    </div>
    <div class="headerRight">
        Вы авторизованы как <b><?php echo $_SESSION['logged_user']['login'] ?></b>
        <a href="/logout.php" class="exit">Выйти</a>
    </div>
</div>
<div id="admin">
    <div class="data" id="data"></div>
    <div class="editing">
        <aside>
            <h3 id="addRecordH3">Добавление записи</h3>
            <p>
                <label for="addID">Идентификатор</label><br>
                <input type="text" disabled value="автоматически" id="addID">
            </p>
            <p>
                <label for="addLogin">Логин</label><br>
                <input type="text" id="addLogin">
            </p>
            <p>
                <label for="addPassword">Пароль</label><br>
                <input type="text" id="addPassword">
            </p>
            <input type="button" value="Добавить" id="addRecord" onclick="newUser()">
            <a href="/admin.php"><input type="button" value="Отменить"></a>
        </aside>
    </div>
</div>
</body>