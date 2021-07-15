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

// 保存ボタン押下時の共通処理
if(isset($_POST['userID'])) {
  if (strlen($_POST['name']) < 0){
    echo "名前を記入してください。";
    exit();
  }
}


// 新規会員登録時
if(isset($_POST['userID']) && !isset($_SESSION["login"])) {
      if (strlen($_POST['userID']) < 5){
        echo "IDは5文字以上入力してください。";
        exit();
      }
      elseif (strlen($_POST['password']) < 5){
        echo "パスワードは5文字以上入力してください。";
        exit();
      }
      // // 画像ファイルのロジック：https://qiita.com/ryo-futebol/items/11dea44c6b68203228ff
      // if (isset($_POST['image'])){
      //   $image = uniqid(mt_rand(), true);
      //   //アップロードされたファイルの拡張子を取得 ↓どゆこと？　$変数[][]：二次元配列
      //   $image .= '.' . substr(strrchr($_FILES['image']['name'], '.'), 1);
      //   $file = "images/$image";
      // }

    $stmt = $dbh->prepare("INSERT INTO USER VALUES(
                                          :userID,
                                          :password,
                                          :name,
                                          :sex,
                                          :address,
                                          :age,
                                          :height,
                                          :weight,
                                          :comment,
                                          :image
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
    $stmt->bindParam(':image', $_POST['image']);
    //ファイルが選択されていれば$imageにファイル名を代入
    if (!empty($_FILES['image']['name'])) {
      //imagesディレクトリにファイル保存
      move_uploaded_file($_FILES['image']['tmp_name'], './images/' . $image);
      //画像ファイルかのチェック
      if (!exif_imagetype($file)) {
        echo "画像ファイルを選択してください。";
        exit();
      }
    $stmt->execute();
    header("Location: index.php");
    exit();
  }
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

    <form action="profileRegistration.php" method="post" enctype="multipart/form-data">
        <h2 id= "mode_change">新規会員登録</h2>
        <div id = "mode_delete">
          <p>会員ID（メールアドレス）：<input type="text" name="userID" ></p>
          <p>パスワード：<input type="password" name="password"></p>
        </div>
        <p>画像ファイル</p>
        <input type="file" name="image1"></p>
        <input type="file" name="image2"></p>
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
 if( !empty($result['comment']) ){ echo $result['comment']; } ?></textarea>
        <p><button type="button" onclick="history.back()">戻る</button>
        <input type="submit" value="登録"></p>
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