<!-- セッションを使ったログインページの作成：https://yama-itech.net/php-login-with-session -->

<?php
// セッションを使うことを宣言
session_start();

// DBへ接続
try {
$dsn='mysql:dbname=EC;charset=utf8';
$user='root';
$password='';
$message = "";
$cookie_userID ="";

// // cookie面倒なので一時削除
// if(isset($_COOKIE["userID"])){
//   $cookie_userID = $_COOKIE["userID"];
// }else{
//   $cookie_userID = "";
// }

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
  header("Location: profile.php");
  exit();
}

//postされて来きたとき
if (count($_POST) !== 0) {
  if(empty($_POST["userID"]) || empty($_POST["pass"])) {
    $message = "ユーザー名とパスワードを入力してください";
  }
  else {
    //post送信されてきたユーザー名がDBにあるか検索
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
    //正しくないとき
    if (!password_verify($_POST['pass'], $result['password'])) {
      $message="ユーザー名かパスワードが違います";
    }
    //正しいとき
    else {
      // // cookieのセット 保存期間：１日
      // setcookie('UserID', $_POST['userID'],time()+60*60*24);
      // セッションidを再発行
      // session_regenerate_id(TRUE);
      $_SESSION["login"] = $_POST['userID']; //セッションにログイン情報を登録
      header("Location: profile.php"); //ログイン後のページにリダイレクト
      exit();
    }
  }
}
// メッセージ出力
$message = htmlspecialchars($message);
echo $message;
?>

<!DOCTYPE html>
<html lang="ja">
  <head>
    <meta charset="utf-8">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>ログイン機能</title>
  </head>

    <form action="index.php" method="post">
        <p>会員ID（メールアドレス）を入力：<input type="text" name="userID"  required value=<?php
        echo $cookie_userID;
        ?>></p>
        <p>パスワードを入力：<input type="password" name="pass" required></p>
        <p><input type="submit" value="ログイン"></p>
    </form>
  <a href="profileRegistration.php">新規会員登録</a>

</html>
    