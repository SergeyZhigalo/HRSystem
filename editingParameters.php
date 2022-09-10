<?php
session_start();
if ($_SESSION['logged_user'] == []){header('Location: /login.php');}
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
    <title>Редактирование параметров</title>
</head>
<body onload="<?php echo $_GET['table']?>(<?php echo $_GET['table']?>)">
    <div id="topHeader">
        <div class="headerLeft">
            <a href="/">| на главную |</a>
        </div>
        <div class="headerRight">
            Вы авторизованы как <b><?php echo $_SESSION['logged_user']['login'] ?></b>
            <a href="/logout.php" class="exit">Выйти</a>
        </div>
    </div>
    <nav>
        <div style="margin: 0 auto">
            <a href="/editingParameters.php?table=education" class="<?php if($_GET['table'] == 'education')echo 'active'?>">|&nbsp;&nbsp;&nbsp;образование&nbsp;&nbsp;&nbsp;|</a>
            <a href="/editingParameters.php?table=professions" class="<?php if($_GET['table'] == 'professions')echo 'active'?>">&nbsp;&nbsp;&nbsp;профессии&nbsp;&nbsp;&nbsp;|</a>
            <a href="/editingParameters.php?table=positions" class="<?php if($_GET['table'] == 'positions')echo 'active'?>">&nbsp;&nbsp;&nbsp;должности&nbsp;&nbsp;&nbsp;|</a>
            <a href="/editingParameters.php?table=departments" class="<?php if($_GET['table'] == 'departments')echo 'active'?>">&nbsp;&nbsp;&nbsp;отделы&nbsp;&nbsp;&nbsp;|</a>
        </div>
    </nav>
<div class="root">
    <div class="data" id="data"></div>
    <div class="editing">
        <aside>
            <h3 id="addRecordH3">Добавление записи</h3>
            <p>
                <label for="addID">Идентификатор</label><br>
                <input type="text" disabled value="автоматически" id="addID">
            </p>
            <p>
                <label for="addValue">Запись</label><br>
                <input type="text" id="addValue">
            </p>
            <input type="button" value="Добавить" id="addRecord" onclick="<?php echo $_GET['table']?>Change()">
            <a href="/editingParameters.php?table=<?php echo $_GET['table']?>"><input type="button" value="Отменить"></a>
        </aside>
    </div>
</div>
</body>