<?php
if(isset($_POST['userID'])) {
$dsn='mysql:dbname=EC;charset=utf8';
$user='root';
$password='';
$dbh = new PDO($dsn,$user,$password);

$stmt = $dbh->prepare("INSERT INTO USER VALUES(
                                      :userID,
                                      :password,
                                      :name,
                                      :sex,
                                      :address,
                                      :age,
                                      :height,
                                      :weight,
                                      :comment
                      )");
$stmt->bindParam(':userID', $_POST['userID']); // DBのカラム名とここの定義は揃えなくてよい。
// ハッシュ化したパスワードをinsertする方法を探す
$stmt->bindParam(':password', password_hash($_POST['password'], PASSWORD_DEFAULT));
$stmt->bindParam(':name', $_POST['name']);
$stmt->bindParam(':sex', $_POST['sex']);
$stmt->bindParam(':address', $_POST['address']);
$stmt->bindParam(':age', $_POST['age']);
$stmt->bindParam(':height', $_POST['height']);
$stmt->bindParam(':weight', $_POST['weight']);
$stmt->bindParam(':comment', $_POST['comment']);
$stmt->execute();
}
?>

<!DOCTYPE html>
<html lang="ja">
  <head>
    <meta charset="utf-8">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="../../favicon.ico">
    <title>新規会員登録</title>
  </head>

    <form action="new-member.php" method="post">
        <h2>新規会員登録</h2>
        <p>会員ID（メールアドレス）：<input type="text" name="userID"></p>
        <p>パスワード：<input type="password" name="password"></p>
        <p>登録名：<input type="text" name="name"></p>
        性別：
        <input type="radio" name="sex" value=0> 男
        <input type="radio" name="sex" value=1> 女
        <p>年齢：<input type="text" name="age"></p>
        <p>お住まい：<input type="text" name="address"></p>
        <p>身長：<input type="text" name="height"></p>
        <p>体重：<input type="text" name="weight"></p>
        <p>comment</p>
      <textarea cols="50" rows="10" name="comment"></textarea><br>

        <p><input type="submit" value="作成"></p>
    </from>
</html>