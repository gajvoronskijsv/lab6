<?php
extract($_POST);
try {
    $user = 'u20296';
    $password = '1377191';
    $db = new PDO('mysql:host=localhost;dbname=u20296', $user, $password);
    $login = $_POST['sendform'];
    $sth = $db->prepare("DELETE FROM DBlab5 WHERE login=:login");
    $sth->bindParam(':login', $login);
    $sth->execute();
    header('Location: admin.php');
}
catch(PDOException $e){
    print('Error : ' . $e->getMessage());
    exit();
}
?>
