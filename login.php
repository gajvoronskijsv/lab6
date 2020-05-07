<?php

/**
 * Файл login.php для не авторизованного пользователя выводит форму логина.
 * При отправке формы проверяет логин/пароль и создает сессию,
 * записывает в нее логин и id пользователя.
 * После авторизации пользователь перенаправляется на главную страницу
 * для изменения ранее введенных данных.
 **/

// Отправляем браузеру правильную кодировку,
// файл login.php должен быть в кодировке UTF-8 без BOM.
header('Content-Type: text/html; charset=UTF-8');

// Начинаем сессию.
session_start();
//session_destroy();

// В суперглобальном массиве $_SESSION хранятся переменные сессии.
// Будем сохранять туда логин после успешной авторизации.
if (!empty($_SESSION['login'])) {
  // Если есть логин в сессии, то пользователь уже авторизован.
  // TODO: Сделать выход (окончание сессии вызовом session_destroy()
  //при нажатии на кнопку Выход).
  // Делаем перенаправление на форму.
  header('index.php');
}
// В суперглобальном массиве $_SERVER PHP сохраняет некторые заголовки запроса HTTP
// и другие сведения о клиненте и сервере, например метод текущего запроса $_SERVER['REQUEST_METHOD'].
if ($_SERVER['REQUEST_METHOD'] == 'GET') 
{
  $loginMessages = array();
  $loginErrors = array();
  //$loginErrors['post'] = !empty($_COOKIE['loginPost_error']);
  //$loginErrors['connect'] = !empty($_COOKIE['loginConnect_error']);
  $loginErrors['row'] = !empty($_COOKIE['loginRow_error']);

  /*if ($loginErrors['post']) {
    // Удаляем куку, указывая время устаревания в прошлом.
    setcookie('loginPost_error', '', 100000);
    // Выводим сообщение.
    $loginMessages['post'] = '<div class="error">post works</div>';
  }
  if ($loginErrors['connect']) {
    // Удаляем куку, указывая время устаревания в прошлом.
    setcookie('loginConnect_error', '', 100000);
    // Выводим сообщение.
    $loginMessages['connect'] = '<div class="error">Connected to db</div>';
  }*/
  if ($loginErrors['row']) {
    // Удаляем куку, указывая время устаревания в прошлом.
    setcookie('loginRow_error', '', 100000);
    // Выводим сообщение.
    $loginMessages['row'] = '<div class="error">Нет совпадений в базе данных</div>';
  }


	if (!empty($loginMessages)) {
	  print('<div id="messages">');
	  // Выводим все сообщения.
	  foreach ($loginMessages as $message) {
	    print($message);
	  }
	  print('</div>');
	}
	?>
	
	<form action="" method="post">
	  <input name="login" />
	  <input name="pass" />
	  <input type="submit" value="Войти" />
	</form>

	<?php
}
// Иначе, если запрос был методом POST, т.е. нужно сделать авторизацию с записью логина в сессию.
else {
//setcookie('loginPost_error', '1', time() + 24 * 60 * 60);
  // TODO: Проверть есть ли такой логин и пароль в базе данных.
  // Выдать сообщение об ошибках.
       
    try {
    //setcookie('loginConnect_error', '1', time() + 24 * 60 * 60);
    $db = new PDO('mysql:host=localhost;dbname=u20296', 'u20296', '1377191');
    $row=$db->query("SELECT login FROM DBlab5 where login='".(string)$_POST['login']."' AND pass='".(string)md5($_POST['pass'])."'")->fetch();
    $db = null;
	}
	catch(PDOException $e){}
  if (!empty($row)) {
    // Если все ок, то авторизуем пользователя.
    $_SESSION['login'] = (string)$_POST['login'];
    $_SESSION['pass'] = (string)md5($_POST['pass']);
    // Записываем ID пользователя.
    $_SESSION['uid'] = $_SESSION['login'];
    // Делаем перенаправление..
    header('Location: index.php');
  }
  else 
  {
  	setcookie('loginRow_error', '1', time() + 24 * 60 * 60);
  	header('Location: login.php'); 
  }
}
