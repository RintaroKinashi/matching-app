<?php
//セッションを使うことを宣言
session_start();

//ログインされていない場合は強制的にログインページにリダイレクト
if (!isset($_SESSION["login"])) {
  header("Location: index.php");
  exit();
}
$dsn='mysql:dbname=EC;charset=utf8';
$user='root';
$password='';
$dbh = new PDO($dsn,$user,$password);

$stmt = $dbh->prepare("SELECT * FROM user WHERE userID=:userID");
$stmt->bindParam(':userID', $_SESSION["login"]);
$stmt->execute();
$result = $stmt -> fetch(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>test</title>
    <link rel="stylesheet" href="stylesheet.css">
  </head>
  <body>
<div class="header">
      <div class="header-list">
        <ul>
          <li><a href="./home.php">Home</li>
          <li><a href="./question.php">分析</a></li>
          <li><a href="./matching.php">検索</a></li>
          <li><a href="./contactform.php">お問い合わせ</a></li>
          <li><a href="./logout.php">ログアウト</a></li>
        </ul>
      </div>
    </div>

<img src="images/<?php echo $result['image']; ?>" width="300" height="300">
<?php 
    echo "<br>名前 :" . $result['name'];
    echo "     性別 : ";
    if($result['sex'] == 0){echo "男";}else{echo "女";}
    echo "<p>年齢 :" . $result['age']. "</p>";
    echo "<p>お住まい:" . $result['address']. "</p>";
    echo "<p>身長 : " . $result['height'] . "cm   体重 : " . $result['weight']. "kg</p>";
    echo "<p>自己紹介</p><p>" . $result['comment']. "</p>";
?>

<a href="profileRegistration.php">編集</a>

<div class="footer">
    </div>
</body>
