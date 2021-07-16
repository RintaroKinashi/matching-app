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
          <li><a href="./profile.php">MyProfile</li>
          <li><a href="./question.php">分析</a></li>
          <li><a href="./matching.php">検索</a></li>
          <li><a href="./contactform.php">お問い合わせ</a></li>
          <li><a href="./logout.php">ログアウト</a></li>
        </ul>
      </div>
    </div>

<form action="question.php" method="post">
<?php
  for ($i=1;$i<3;$i++){
  $stmtq = $dbh->prepare("SELECT * FROM m_question WHERE questionID=$i");
  $stmtq->execute();
  $resultq = $stmtq -> fetch(PDO::FETCH_ASSOC);

    echo "<div class='question'>";
    echo "<p>" . $resultq["sentence"] . "</p>";
    for ($j=0;$j<4;$j++){
      echo "<input type='radio' name='q2' value='" . $j ."'>" . $resultq["Choice".$j+1];
    }
    echo "</div>";
  }
?>
    <p><input type="submit" name="send" value="送信する"></p>
</form>
</body>
</html>