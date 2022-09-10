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
    <link rel="stylesheet" href="css/staff.css">
    <script src="js/jquery-3.5.1.js"></script>
    <script src="js/jquery.maskedinput.js"></script>
    <script src="js/script.js"></script>
    <title>Добавление кадра</title>
</head>
<body onload="loadingStaff()">
<div id="topHeader">
    <div class="headerLeft">
        <a href="/">| на главную |</a>
    </div>
    <div class="headerRight">
        Вы авторизованы как <b><?php echo $_SESSION['logged_user']['login'] ?></b>
        <a href="/logout.php" class="exit">Выйти</a>
    </div>
</div>
<div id="main">
    <form action="">
        <h3 id="addRecordH3"><?php if ($_GET['id']) echo 'Обновление данных кадра'; else echo 'Добавление кадра';?></h3>
        <p>
            <label for="id">Идентификатор</label><br>
            <input type="text" disabled value="автоматически" id="id">
        </p>
        <p>
            <label for="surname">Фамилия</label><br>
            <input type="text" id="surname" value="" placeholder="Фамилия" minlength="3" required>
        </p>
        <p>
            <label for="name">Имя</label><br>
            <input type="text" id="name" value="" placeholder="Имя" minlength="3" required>
        </p>
        <p>
            <label for="middlename">Отчество</label><br>
            <input type="text" id="middlename" value="" placeholder="Отчество" minlength="3" required>
        </p>
        <p>
            <label for="passport">Паспорт</label><br>
            <input type="text" id="passport" value="" placeholder="1234567890" minlength="10" maxlength="10" required>
        </p>
        <p>
            <label for="address">Адрес</label><br>
            <textarea id="address" minlength="10" required></textarea>
        </p>
        <p>
            <label for="phone">Телефон</label><br>
            <input type="text" id="phone" value="" placeholder="89990000000" minlength="11" maxlength="11" required>
        </p>
        <p>
            <label for="birthday">День рождения</label><br>
            <input type="date" id="birthday" value="" required>
        </p>
        <p>
            <label for="idSex">Пол</label><br>
            <select id="idSex"></select>
        </p>
        <p>
            <label for="employmentDate">Дата приема на работу</label><br>
            <input type="date" id="employmentDate" value="" required>
        </p>
        <p>
            <label for="idEducation">Образование</label><br>
            <select id="idEducation"></select>
        </p>
        <p>
            <label for="idProfession">Профессия</label><br>
            <select id="idProfession"></select>
        </p>
        <p>
            <label for="idPositions">Должность</label><br>
            <select id="idPositions"></select>
        </p>
        <p>
            <label for="idDepartment">Отдел</label><br>
            <select id="idDepartment"></select>
        </p>
        <input type="button" value="<?php if ($_GET['id']) echo 'Обновить'; else echo 'Добавить';?>" id="addStaff" onclick="<?php if ($_GET['id']) echo 'changeStaff()'; else echo 'newStaff()';?>">
    </form>
</div>
</body>