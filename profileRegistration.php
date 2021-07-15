<?php
//セッションを使うことを宣言
session_start();

$dsn='mysql:dbname=EC;charset=utf8';
$user='root';
$password='';
$dbh = new PDO($dsn,$user,$password);

// 既存会員編集
if(isset($_SESSION["login"])){
  $mode = 1;
  $stmt1 = $dbh->prepare("SELECT * FROM user WHERE userID=:userID");
  $stmt1->bindParam(':userID', $_SESSION["login"]);
  $stmt1->execute();
  $result = $stmt1 -> fetch(PDO::FETCH_ASSOC);
}

// 新規会員登録時
if(isset($_POST['userID']) && !isset($_SESSION["login"])) {
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
$hash_pass = password_hash($_POST['password'], PASSWORD_DEFAULT);
$stmt->bindParam(':userID', $_POST['userID']);
// ハッシュ化したPassをDBに登録するとき：https://teratail.com/questions/143227
$stmt->bindParam(':password', $hash_pass);
$stmt->bindParam(':name', $_POST['name']);
$stmt->bindParam(':sex', $_POST['sex']);
$stmt->bindParam(':address', $_POST['address']);
$stmt->bindParam(':age', $_POST['age']);
$stmt->bindParam(':height', $_POST['height']);
$stmt->bindParam(':weight', $_POST['weight']);
$stmt->bindParam(':comment', $_POST['comment']);
$stmt->execute();
}

// 会員情報編集時
if(isset($_POST['userID']) && isset($_SESSION['login'])) {
  // ここをupdate文に書き換える
  $stmt = $dbh->prepare("UPDATE user SET
                                        name = :name,
                                        sex = :sex,
                                        address = :address,
                                        age = :age,
                                        height = :height,
                                        weight = :weight,
                                        comment = :comment
                                        WHERE UserID = :userID");
  $stmt->bindParam(':name', $_POST['name']);
  $stmt->bindParam(':sex', $_POST['sex']);
  $stmt->bindParam(':address', $_POST['address']);
  $stmt->bindParam(':age', $_POST['age']);
  $stmt->bindParam(':height', $_POST['height']);
  $stmt->bindParam(':weight', $_POST['weight']);
  $stmt->bindParam(':comment', $_POST['comment']);
  $stmt->bindParam(':userID', $_SESSION['login']);
  $stmt->execute();
  header("Location: profile.php");
  exit();
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

    <form action="profileRegistration.php" method="post">
        <h2 id= "mode_change">新規会員登録</h2>
        <div id = "mode_delete">
          <p>会員ID（メールアドレス）：<input type="text" name="userID" ></p>
          <p>パスワード：<input type="password" name="password"></p>
        </div>
        <p>登録名：<input type="text" name="name" value="<?php
if( !empty($result['name']) ){ echo $result['name']; } ?>"></p>
        性別：
        <input type="radio" name="sex" value=0 checked="<?php
 if( $result['sex'] == 0 ){ echo "checked";} ?>">男
        <input type="radio" name="sex" value=1 checked="<?php 
 if( $result['sex'] == 1 ){ echo "checked";} ?>">女</p>
        <p>年齢：<input type="text" name="age" value="<?php
 if( !empty($result['age']) ){ echo $result['age']; } ?>"></p>
        <p>お住まい：<input type="text" name="address" value="<?php
 if( !empty($result['address']) ){ echo $result['address']; } ?>"></p>
        <p>身長：<input type="text" name="height" value="<?php
 if( !empty($result['height']) ){ echo $result['height']; } ?>"></p>
        <p>体重：<input type="text" name="weight" value="<?php
 if( !empty($result['weight']) ){ echo $result['weight']; } ?>"></p>
        <p>comment</p>
      <textarea cols="50" rows="10" name="comment"><?php
 if( !empty($result['comment']) ){ echo $result['comment']; } ?></textarea><br>

        <p><input type="submit" value="登録"></p>
    </from>
    <script>
      // サニタイズ処理済み。
      if (<?php echo htmlspecialchars($mode, ENT_QUOTES, 'UTF-8');?> == 1){
        document.querySelector("title").innerHTML="登録情報変更";
        document.getElementById("mode_change").innerHTML="登録情報変更";
        document.getElementById("mode_delete").style.display="none";
      }
    </script>
</html>