<?php
require_once './cfg.php';
require_once './gl_fun.php';
session_start();
if (isLogin()) die ('You have log on');

if (isset($_POST['master_log']) && isset($_POST['master_pass'])) {
  mysql_connect($HOST, $USER, $PASS) or die ("Не могу создать соединение"); //устанавливаем соединение с хостом, если не получилось завершаем скрипт с ошибкой
  mysql_select_db($DB) or die (mysql_error().' вот такая херня');  			//Выбор базы данных или завершение скрипта
  //$query = "INSERT INTO `masters` VALUES (906063, 123123, 0, 'none', 1)";
  //$result = mysql_query($query) or die("Ошибка " . mysql_error());
  $query = "SELECT `masters_log`, `masters_pass` FROM `masters` WHERE masters_log=".$_POST['master_log'];
  $result = mysql_query($query) or die("Ошибка " . mysql_error());
  if($result)
  {
	  $rows = mysql_num_rows($result);
      if (count($rows)>0)
      {
            $row = mysql_fetch_row($result);
            if (($row[0] == $_POST['master_log']) && ($row[1] == $_POST['master_pass']))
			{
				$_SESSION['login'] = $_POST['master_log'];
        header('Location: ./master.php');
        exit;
			}
            else echo 'ты ввел какую-то дичь';
      }
      else {
		  echo 'ты че то напутал бро';
		  }
  }
  else {
    echo 'Ошибка выполнения запроса, повторите еще раз';
  }
  mysql_free_result($result);
  mysql_close();
  //}
}
?>


<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no, user-scalable=no">
    <link href="css/style.css" rel="stylesheet">
    <title>HelPC - Авторизация</title>
  </head>
  <body class="bg-light justify-content-center">
    <div class="mt-5">
      <form class="form-signin" method="POST" action="login.php">
        <div class="input-group-lg">
          <div class="form-label-group">
            <input name="master_log" class="form-control input-upper" id="inputEmail" placeholder="E-mail" required autofocus>
            <label for="inputEmail">Электронная почта</label>
          </div>
          <div class="divider px-2">
            <hr class="m-0">
          </div>
          <div class="form-label-group mb-3">
            <input name="master_pass" class="form-control input-lower" id="inputPassword" type="password" placeholder="Пароль" required>
            <label for="inputPassword">Пароль</label>
          </div>
        </div><input type="submit" class="btn btn-lg btn-primary btn-block" href="index.html" value="Войти"></input>
      </form>
      <footer class="footer-sticky text-center">
        <p style="color: rgba(52, 58, 64, .3);">&copy; 2017 HelPC. All Rights Reserved.</p>
      </footer>
    </div>
  </body>
  <script src="js/libs.js"></script>
  <script src="js/scripts.js"></script>
</html>
