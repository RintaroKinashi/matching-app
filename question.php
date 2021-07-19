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

// 問題数
const NUMBER_OF_QUESTIONS = 3;

$stmt1 = $dbh->prepare("SELECT * FROM r_answer WHERE UserID=:userID");
$stmt1->bindParam(':userID', $_SESSION["login"]);
$stmt1->execute();
$result1 = $stmt1 -> fetch(PDO::FETCH_ASSOC);

if(isset($_POST['q1'])){
  if (empty($result1["UserID"])){
    // IDが空ならinsert
    $stmt = $dbh->prepare("INSERT INTO r_answer VALUES(
        :userID,
        :q1,
        :q2,
        :q3
    )");
  }
  else{
    $stmt = $dbh->prepare("UPDATE r_answer SET
        q1 = :q1,
        q2 = :q2,
        q3 = :q3
        WHERE UserID = :userID");
  }
    $stmt->bindParam(':userID', $_SESSION["login"]);
    for ($i=1;$i<NUMBER_OF_QUESTIONS+1;$i++){
      $stmt->bindParam(':q'. $i, $_POST['q'. $i]);
    }
    $stmt->execute();
    header("Location: profile.php");
    exit();
}


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
  for ($i=1;$i<NUMBER_OF_QUESTIONS+1;$i++){
  $stmtq = $dbh->prepare("SELECT * FROM m_question WHERE questionID=$i");
  $stmtq->execute();
  $resultq = $stmtq -> fetch(PDO::FETCH_ASSOC);

    echo "<div class='question'>";
    echo "<p>" . $resultq["sentence"] . "</p>";
    for ($j=0;$j<4;$j++){
      $k = "";
      if($result1['q'. $i]==$j){$k = "checked";}
      echo "<input type='radio' name='q". $i ."' value='" . $j ."' ". $k .">" . $resultq["Choice".$j+1]; // if(!empty($result1["UserID"])){if($result1['q'. $i]==$j){echo "checked";}}
    }
    echo "</div>";
  }
?>
    <p><input type="submit" name="send" value="送信する"></p>
</form>
</body>
</html>