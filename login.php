<?php
session_start();
//session_start();
if ($_SESSION['logged_user']){header('Location: /');}
$data = $_POST;
if (isset($data['do_login'])) {
    $errors = array();
    try {
        $db = new PDO('sqlite:requests/kadr.db');
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = "SELECT * FROM users WHERE login = '".$data['login']."';";
        $result = $db->query($sql);
        $result = ($result->fetchAll(PDO::FETCH_ASSOC));
    } catch (PDOException $e) {
        echo json_encode($e->getMessage());
    }
    if ($result){
        if ($result[0]['password'] != $data['password']){
            $errors[] = 'Неверно введен пароль!';
        }else{
            $_SESSION['logged_user'] = $result[0];
            header('Location: /');
        }
    }else{
        $errors[] = 'Пользователь с таким логином не найден!';
    }
    if (!empty($errors)) {
        echo '<div class="error" ">'.array_shift($errors).'</div><hr>';
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
    <script src="js/jquery-3.5.1.js"></script>
    <script src="js/script.js"></script>
    <title>Страница входа</title>
</head>
<style>
    #formLogin{
        max-width: 400px;
        margin: 30vh auto;
    }
    #formLogin h3{
        font-size: 30px;
        font-weight: 500;
        margin: 20px 0;
    }
    input[type=submit] {
        color: black !important;
        cursor: pointer !important;
        font-size: 20px !important;
    }
    #formLogin label{
        font-size: 18px;
        font-weight: 300;
    }
    .error{
        color: #ff0000;
        margin: 10px;
        font-size: 20px;
        font-weight: 500;
    }
</style>
<body>
    <div id="formLogin">
        <h3>Форма входа</h3>
        <form action="login.php" method="POST">
            <p>
                <label for="login">Логин</label>
                <input type="text" name="login" id="login" value="<?php echo @$data['login'];?>" required>
            </p>
            <p>
                <label for="password">Пароль</label>
                <input type="password" name="password" id="password" value="<?php echo @$data['password'];?>" required><br>
            </p>
            <input type="submit" name="do_login" value="Войти">
        </form>
    </div>
</body>
