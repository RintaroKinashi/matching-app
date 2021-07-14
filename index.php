<!-- セッションを使ったログインページの作成：https://yama-itech.net/php-login-with-session -->

<?php
// セッションを使うことを宣言
session_start();

// DBへ接続
try {
$dsn='mysql:dbname=EC;charset=utf8';
$user='root';
$password='';
$dbh = new PDO($dsn,$user,$password, [
  // エラー発生時にエラーを投げる。（エラーコードのみ等ではなく）
  PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
  // 結果セットに 返された際のカラム名で添字を付けた配列を返す。
  PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
]);
}
catch (PDOExeption $e) {
  exit ('データベースエラー');
}

//ログイン状態の場合ログイン後のページにリダイレクト
if (isset($_SESSION["login"])) {
  session_regenerate_id(TRUE);
  header("Location: home.php");
  exit();
}

//postされて来なかったとき
if (count($_POST) === 0) {
  $message = "";
}
//postされて来た場合
else {
  //ユーザー名またはパスワードが送信されて来なかった場合
  if(empty($_POST["userID"]) || empty($_POST["pass"])) {
    $message = "ユーザー名とパスワードを入力してください";
  }
  //ユーザー名とパスワードが送信されて来た場合
  else {
    //post送信されてきたユーザー名がデータベースにあるか検索
    try {
      $stmt = $dbh -> prepare('SELECT * FROM user WHERE userID=:userID');
      $stmt->bindParam(':userID', $_POST['userID']);
      $stmt -> execute();
      $result = $stmt -> fetch(PDO::FETCH_ASSOC);
    }
    catch (PDOExeption $e) {
      exit('データベースエラー');
    }

    //検索したユーザー名に対してパスワードが正しいかを検証
    //正しいとき
    if($result["password"] ==  $_POST['pass']) {
      session_regenerate_id(TRUE); //セッションidを再発行
      $_SESSION["login"] = $_POST['userID']; //セッションにログイン情報を登録
      header("Location: home.php"); //ログイン後のページにリダイレクト
      exit();
    }
    //正しくないとき
    else {
      $message="ユーザー名かパスワードが違います";
    }
  }
}
$message = htmlspecialchars($message);
echo $message;
?>

<!DOCTYPE html>
<html lang="ja">
  <head>
    <meta charset="utf-8">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="../../favicon.ico">
    <title>ログイン機能</title>
  </head>

    <form action="index.php" method="post">
        <p>会員ID（メールアドレス）を入力：<input type="text" name="userID" required></p>
        <p>パスワードを入力：<input type="password" name="pass" required></p>
        <p><input type="submit" value="ログイン"></p>
    </form>
  <a href="new-member.php">新規会員登録</a>

</html>
    