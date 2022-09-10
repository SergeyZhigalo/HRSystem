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
    <script src="js/jquery-3.5.1.js"></script>
    <script src="js/script.js"></script>
    <title>Главная</title>
</head>
<body onload="loadingIndex()">
    <div id="topHeader">
        <div class="headerLeft">
            <a href="/editingParameters.php?table=education">|&nbsp;&nbsp;&nbsp;редактирование параметров&nbsp;&nbsp;&nbsp;|</a>
            <?php
                if ($_SESSION['logged_user']['idRole'] == 1){echo '<a href="/admin.php">&nbsp;&nbsp;&nbsp;управление учетными записями&nbsp;&nbsp;&nbsp;|</a>';};
            ?>
        </div>
        <div class="headerRight">
            Вы авторизованы как <b><?php echo $_SESSION['logged_user']['login'] ?></b>
            <a href="/logout.php" class="exit">Выйти</a>
        </div>
    </div>
    <div id="header">
        <div class="logo">
            <a href="/">
                <img src="logo.png" alt="логотип">
            </a>
        </div>
        <h1>Кадровый состав</h1>
    </div>
    <a href="/staff.php">
        <button type="button" class="change addStaff" title="изменить">
            <i class="fas fa-plus"></i>
        </button>
    </a>
    <div id="root">
        <div class="cards" id="cards">
        </div>
        <div class="filter">
            <aside>
                <input type="text" class="search" name="search" id="search" placeholder="Поиск..." minlength="3">
                    <i class="fad fa-search"></i>
                <select name="position" id="position">
                    <option value="" selected="selected">Должность</option>
                </select>
                <select name="department" id="department">
                    <option value="" selected="selected">Отдел</option>
                </select>
                <p>Возраст</p>
                <input type="number" name="ageFrom" id="ageFrom" placeholder="От" class="age">
                <input type="number" name="ageUpTo" id="ageUpTo" placeholder="До" class="age">
                <input type="button" value="Показать" onclick="find()">
                <div id="amountsResult"></div>
            </aside>
        </div>
    </div>
</body>
</html>