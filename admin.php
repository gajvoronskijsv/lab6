<?php
 if (!empty($messages)) {
  print('<div id="messages">');
  // Выводим все сообщения.
  foreach ($messages as $message) {
    print($message);
  }
  print('</div>');
}
function authenticate() {
  header('HTTP/l.1 401 Unauthorized');
  header('WWW-Authenticate: Basic rеаlm="admin.php"');
  print('<h1>401 Требуется авторизация</h1>');
  exit();
}
if(isset($_POST['OldAuth'])){
  $p = $_POST['OldAuth'];
}
else{
  $p=0;
}
if (empty($_SERVER['PHP_AUTH_USER'])) {
  authenticate(); 
}
else{
  try {
    $db = new PDO('mysql:host=localhost;dbname=u20296', 'u20296', '1377191');
    $row=$db->query("SELECT login FROM admin where login='".(string)$_SERVER['PHP_AUTH_USER']."' AND password='".(string)md5($_SERVER['PHP_AUTH_PW'])."'")->fetch();
  }
  catch(PDOException $e){
    header('HTTP/l.1 401 Unauthorized');
    header('WWW-Authenticate: Basic rеаlm="admin.php"'); 
    exit();
  }
  if (!empty($row)){
    echo "<p>Добро пожаловать: " . htmlspecialchars($_SERVER['PHP_AUTH_USER']) . "<br />";
    echo "<form action='' method='post'>\n";
    echo "<input type='hidden' name='SeenBefore' value='1' />\n";
    echo "<input type='hidden' name='OldAuth' value='".$_SERVER['PHP_AUTH_USER']."' />\n";
    echo "<input type='hidden' name='sendform'/>\n";
    echo "<input style='border-radius: 50px; margin:5px;' type='submit' value='Войти как администратор'/>\n";
    echo "</form></p>\n";
    echo "<form action='index.php' method='post'>\n";
    echo "<input type='submit' name='sendform' id='out' value='Создать нового пользователя'/>\n";
    echo "<input type='submit' name='sendform' id='out' value='Войти как пользователь'/>\n";
    echo "</form></p>\n";
    $_SERVER['PHP_AUTH_USER']=FALSE;
    //$_SERVER['PHP_AUTH_PW']=0;
    $num=1;
    $messages[] = sprintf("
      <head>
        <meta charset='utf-8'>
        <link rel='stylesheet' href = 'style.css'>
        <title>admin page</title>
      </head>
      <table>
      <tr>
        <th>num</th>
        <th class='short'>login</th>
        <th class='short'>password</th>
        <th >name</th>
        <th >mail</th>
        <th >date</th>
        <th class='middle'>gender</th>
        <th class='short'>limb</th>
        <th class='middle'>super1</th>
        <th class='middle'>super2</th>
        <th class='middle'>super3</th>
        <th class='long'>bio</th>
        <th class='middle'>Удалить</th>
      </tr>
    ");
    //строки из таблицы
    foreach($db->query('SELECT * FROM DBlab5') as $row){
      $n=1;
      while(true){
        if(md5($n)!=$row['pass']){
          $n=$n+1;
        }
        else{
          break;
        }
      }
      $messages[] = sprintf("
        <tr>
          <td>%s</td>
          <td class='short'>%s</td>
          <td class='short'>%s</td>
          <td >%s</td>
          <td >%s</td>
          <td >%s</td>
          <td class='middle'>%s</td>
          <td class='short'>%s</td>
          <td class='middle'>%s</td>
          <td class='middle'>%s</td>
          <td class='middle'>%s</td>
          <td class='long'>%s</td>
          <td class='middle'>
            <form method='POST' action='delete.php'>
              <input type='submit' name='sendform' value='%s' />
            </form>
          </td>
        </tr>
      ",
      strip_tags($num),
      strip_tags($row['login']),
      strip_tags($n),
      strip_tags($row['name']),
      strip_tags($row['mail']),
      strip_tags($row['date']),
      strip_tags($row['gender']),
      strip_tags($row['limb']),
      strip_tags($row['super1']),
      strip_tags($row['super2']),
      strip_tags($row['super3']),
      strip_tags($row['bio']),
      strip_tags($row['login']),
      );
      $num=$num+1;
    }
    $db = null;
    $messages[] = sprintf("</table>");
    if (!empty($messages)) {
      print('<div id="messages">');
      // Выводим все сообщения.
      foreach ($messages as $message) {
        print($message);
      }
      print('</div>');
    }
  }
  else{
  	FALSE$_SERVER['PHP_AUTH_USER']=0;
    //$_SERVER['PHP_AUTH_PW']=0;
    authenticate();
  }
}
?>