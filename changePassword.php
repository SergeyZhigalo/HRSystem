<?php
session_start();
if ($_SESSION['logged_user'] == []){header('Location: /login.php');}
if ($_SESSION['logged_user']['idRole'] == 2){header('Location: /');}
$data = $_POST;
if (isset($data['changePassword'])) {
    $errors = array();
    try {
        $db = new PDO('sqlite:requests/kadr.db');
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "SELECT * FROM users WHERE login = '".$_SESSION['logged_user']['login']."';";
        $result = $db->query($sql);
        $result = ($result->fetchAll(PDO::FETCH_ASSOC));
    } catch (PDOException $e) {
        echo json_encode($e->getMessage());
    }
    if ($result){
        if ($result[0]['password'] != $data['oldPassword']){;
            $errors[] = 'Неверно введен пароль!';
        }else if ($data['newPassword1'] != $data['newPassword2']){
            $errors[] = 'Новый пароль не совпадает с проверкой!';
        }else{
            try {
                $db = new PDO('sqlite:requests/kadr.db');
                $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $sql = "UPDATE users SET password = '".$data['newPassword1']."' WHERE idUser = ".$_SESSION['logged_user']['idUser'];
                //echo $sql;
                $result = $db->query($sql);
                $result = ($result->fetchAll(PDO::FETCH_ASSOC));
            } catch (PDOException $e) {
                echo json_encode($e->getMessage());
            }
            header('Location: /logout.php');
        }
    }else{
        header('Location: /logout.php');
    }
}
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
    <script src="js/script.js"></script>
    <title>Смена пароля</title>
</head>
<body>
<div id="topHeader">
    <div class="headerLeft">
        <a href="/">| на главную |</a>
    </div>
    <div class="headerRight">
        Вы авторизованы как <b><?php echo $_SESSION['logged_user']['login'] ?></b>
        <a href="/logout.php" class="exit">Выйти</a>
    </div>
</div>
<?php
if (!empty($errors)) {
    echo '<div class="error" ">'.array_shift($errors).'</div><hr>';
}
?>
<div id="changePassword">
    <h3>Смена пароля</h3>
    <form action="changePassword.php" method="post">
        <p>
            <label for="oldPassword">Старый пароль</label><br>
            <input type="text" name="oldPassword" id="oldPassword"  value="" minlength="3" required>
        </p>
        <p>
            <label for="newPassword1">Новый пароль</label><br>
            <input type="text" name="newPassword1" id="newPassword1" value="" minlength="3" required>
        </p>
        <p>
            <label for="newPassword2">Повторите пароль</label><br>
            <input type="text" name="newPassword2" id="newPassword2" value="" minlength="3" required>
        </p>
        <input type="submit" value="Сменить" name="changePassword">
    </form>
</div>
</body>