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
$dbh = new PDO($dsn,$user,$password, [
  // エラー発生時にエラーを投げる。（エラーコードのみ等ではなく）
  PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
  // 結果セットに 返された際のカラム名で添字を付けた配列を返す。
  PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
]);

// 問題数
const NUMBER_OF_QUESTIONS = 4;
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
          <li><a href="./notice.php">友達申請</a></li>
          <li><a href="./contactform.php">お問い合わせ</a></li>
          <li><a href="./logout.php">ログアウト</a></li>
        </ul>
      </div>
    </div>