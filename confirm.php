<?php
if(isset($_POST['user'])) {
$dsn='mysql:dbname=EC;charset=utf8';
$user='root';
$password='';
$dbh = new PDO($dsn,$user,$password);

$stmt = $dbh->prepare("SELECT * FROM user WHERE id=:user");
$stmt->bindParam(':user', $_POST['user']);
$stmt->execute();
if($rows = $stmt->fetch()) {
if($rows["password"] ==  $_POST['password']) {
print "<p>ログイン成功</p>";
}else {
print "<p>ログイン失敗</p>";
}
}else {
print "<p>ログイン失敗</p>";
}
}
?>
こんにちは。そしてこんばんは。
<p>ここをhome画面に置き換える必要がある。</p>