<?php
//セッションを使うことを宣言
session_start();

$dsn = 'mysql:dbname=EC;charset=utf8';
$user = 'root';
$password = '';
$dbh = new PDO($dsn, $user, $password);
$mode = 0;

// 既存会員編集
if (isset($_SESSION["login"])) {
  $mode = 1;
  $stmt1 = $dbh->prepare("SELECT * FROM user WHERE userID=:userID");
  $stmt1->bindParam(':userID', $_SESSION["login"]);
  $stmt1->execute();
  $result = $stmt1->fetch(PDO::FETCH_ASSOC);
}

// 保存ボタン押下時の共通処理
// if(isset($_POST['userID'])) {
//   if (strlen($_POST['name']) < 1){
//     echo "名前を記入してください。";
//     exit();
//   }
// }

// 画像ファイルのロジック：https://qiita.com/ryo-futebol/items/11dea44c6b68203228ff
// より分かりやすい画像アップロード：https://note.com/note256/n/n4b391f5457c0
// $_FILES['bbsimg']['name'];      //アップした際の元のファイル名
if (!empty($_FILES['image']['name'])) {
  // uniqueなIDを作成する。
  $image = uniqid(mt_rand(), true);
  // strrchr：アップロードされたファイルの拡張子を取得　→　「.jpg」とだけ抜き出す
  // substr：→　「jpg」とだけ抜き出す
  $image .= '.' . substr(strrchr($_FILES['image']['name'], '.'), 1);
  // 「img/（ユニークなID）.jpg」のようなパスができあがる。
  $file = "img/$image";
  // 画像ファイルかのチェック
  // if (!exif_imagetype($file)) {
  //   echo "画像ファイルを選択してください。";
  //   exit();
  // }
  // imagesディレクトリにファイル保存   $_FILES['bbsimg']['tmp_name'];  //自動的にサーバー上に一時保存されたファイル
  // move_uploaded_file：第一引数（一時フォルダ）第二引数（移動先のパス）
  move_uploaded_file($_FILES['image']['tmp_name'], './img/' . $image);
} else {
  $file = "img/noimage.jpg";
}

// 新規会員登録時
if (isset($_POST['userID']) && !isset($_SESSION["login"])) {
  if (strlen($_POST['userID']) < 5) {
    echo "IDは5文字以上入力してください。";
    exit();
  } elseif (strlen($_POST['password']) < 5) {
    echo "パスワードは5文字以上入力してください。";
    exit();
  }
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
  $stmt->bindParam(':image', $file);
  $stmt->execute();
  header("Location: index.php");
  exit();
}

// 会員情報編集時
if (isset($_POST['name']) && isset($_SESSION['login'])) {
  $stmt = $dbh->prepare("UPDATE user SET
                                        name = :name,
                                        sex = :sex,
                                        address = :address,
                                        age = :age,
                                        height = :height,
                                        weight = :weight,
                                        comment = :comment,
                                        image = :image
                                        WHERE UserID = :userID");
  $stmt->bindParam(':name', $_POST['name']);
  $stmt->bindParam(':sex', $_POST['sex']);
  $stmt->bindParam(':address', $_POST['address']);
  $stmt->bindParam(':age', $_POST['age']);
  $stmt->bindParam(':height', $_POST['height']);
  $stmt->bindParam(':weight', $_POST['weight']);
  $stmt->bindParam(':comment', $_POST['comment']);
  if (!empty($_FILES['image']['name'])) {
    $stmt->bindParam(':image', $file);
    unlink($result['image']);
  } else {
    $stmt->bindParam(':image', $result['image']);
  }
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
  <link rel="stylesheet" type="text/css" href="public/css/bootstrap.min.css" />
  <link rel="stylesheet" type="text/css" href="../public/css/main.css" />
  <style>
    #preview img {
      width: 100px;
    }
  </style>
</head>

<body>
  <div class="d-flex align-items-center justify-content-center h-100">
    <div class="card rounded login-card-width shadow">
      <div class="card-body">
        <form action="profileRegistration.php" method="post" enctype="multipart/form-data">
          <h2 id="mode_change" class="d-flex justify-content-center">新規会員登録</h2>
          <div id="mode_delete" class="my-4">
            <div class="row mt-3">
              <p>ユーザー名：<input type="text" name="userID"></p>
            </div>
            <div class="row mt-3">
              <p>パスワード：<input type="password" name="password"></p>
            </div>
          </div>
          <!-- https://teratail.com/questions/72750 -->
          <p>画像ファイル</p>
          <p><input type="file" name="image" id="file"></p>
          <div id="preview"></div>
          <p>元の画像</p>
          <img src="<?php echo $result['image']; ?>" width="300" height="300">
          <div class="row mt-3">
            <p>登録名：<input type="text" name="name" value="<?php
                                                          if (!empty($result['name'])) {
                                                            echo $result['name'];
                                                          } ?>"></p>
          </div>
          性別：
          <input type="radio" name="sex" value=0 <?php if ($mode == 1) {
                                                    if ($result['sex'] == 0) {
                                                      echo "checked";
                                                    }
                                                  } ?>>男
          <input type="radio" name="sex" value=1 <?php if ($mode == 1) {
                                                    if ($result['sex'] == 1) {
                                                      echo "checked";
                                                    }
                                                  } ?>>女
          <div class="row mt-3">
            <p>年齢：<input type="text" name="age" value="<?php
                                                        if (!empty($result['age'])) {
                                                          echo $result['age'];
                                                        } ?>"></p>
          </div>
          <div class="row mt-3">
            <p>お住まい：<input type="text" name="address" value="<?php
                                                              if (!empty($result['address'])) {
                                                                echo $result['address'];
                                                              } ?>"></p>
          </div>
          <div class="row mt-3">
            <p>身長：<input type="text" name="height" value="<?php
                                                          if (!empty($result['height'])) {
                                                            echo $result['height'];
                                                          } ?>"></p>
          </div>
          <div class="row mt-3">
            <p>体重：<input type="text" name="weight" value="<?php
                                                          if (!empty($result['weight'])) {
                                                            echo $result['weight'];
                                                          } ?>"></p>
          </div>
          <div class="row mt-1">
            <p>プロフィール</p>
          </div>
          <textarea cols="50" rows="10" name="comment"><?php
                                                        if (!empty($result['comment'])) {
                                                          echo $result['comment'];
                                                        } ?></textarea>
      </div>
      <p><button type="button" onclick="history.back()">戻る</button>
        <input type="submit" value="登録">
      </p>
      </from>
    </div>
  </div>
  </div>
</body>
<script>
  // サニタイズ処理済み。
  if (<?php echo htmlspecialchars($mode, ENT_QUOTES, 'UTF-8'); ?> == 1) {
    document.querySelector("title").innerHTML = "登録情報変更";
    document.getElementById("mode_change").innerHTML = "登録情報変更";
    document.getElementById("mode_delete").style.display = "none";
  }
</script>
<script type="text/javascript" src="https://code.jquery.com/jquery-3.4.1.min.js">
  $('#file').change(function() {
    var fr = new FileReader();
    fr.onload = function() {
      var img = $('<img>').attr('src', fr.result);
      $('#preview').append(img);
    };
    fr.readAsDataURL(this.files[0]);
  });
</script>

</html>
