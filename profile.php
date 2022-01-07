<?php
require 'common.php';

$selected_user = "";
$Now_Time = date("Y-m-d H:i:s");
$stmt = $dbh->prepare("SELECT * FROM user WHERE userID=:userID");
if (!isset($_POST['Selected_UserID'])) {
    // ログインユーザのプロフィールを表示
    $stmt->bindParam(':userID', $_SESSION["login"]);
} else {
    $_SESSION["Selected_UserID"] = $_POST['Selected_UserID'];
    // 選択されたユーザのプロフィールを表示
    $stmt->bindParam(':userID', $_POST['Selected_UserID']);
}
// SQLの実行＆結果を取得
$stmt->execute();
$result = $stmt->fetch(PDO::FETCH_ASSOC);

if (isset($_POST['request'])) {
    // POSTされてきた場合（他のユーザをクリックした処理）
    $selected_user = $_SESSION["Selected_UserID"];
    $stmt2 = $dbh->prepare("SELECT * FROM r_request WHERE MEMBER_ID=:MEMBER_ID AND REQUEST_USERID=:REQUEST_USERID LIMIT 1");
    $stmt2->bindParam(':MEMBER_ID', $selected_user);
    $stmt2->bindParam(':REQUEST_USERID', $_SESSION["login"]);
    $stmt2->execute();
    $result2 = $stmt->fetch(PDO::FETCH_ASSOC);
    // var_dump($result2['MEMBER_ID']);
    if (!isset($result2['MEMBER_ID'])) {
        $stmt1 = $dbh->prepare("INSERT INTO r_request VALUES(
            :MEMBER_ID,
            :REQUEST_USERID,
            :REQUEST_TIME
        )");
        $stmt1->bindParam(':MEMBER_ID', $selected_user);
        $stmt1->bindParam(':REQUEST_USERID', $_SESSION["login"]);
        $stmt1->bindParam(':REQUEST_TIME', $Now_Time);
        $stmt1->execute();
        echo "友達申請を行いました！";
        exit();
    } else {
        echo "既に申請済みです！";
        exit();
    }
}

?>

<body>
    <div class="d-flex align-items-center justify-content-center h-100">
        <div class="card rounded login-card-width shadow">
            <div class="card-body">
                <div class="profile">
                    <p><img src="<?php echo $result['image']; ?>" width="300" height="300"></p>
                    <?php
                    echo "<br>名前 :" . $result['name'];
                    echo "     性別 : ";
                    if ($result['sex'] == 0) {
                        echo "男";
                    } else {
                        echo "女";
                    }
                    echo "<p>年齢 :" . $result['age'] . "</p>";
                    echo "<p>お住まい:" . $result['address'] . "</p>";
                    echo "<p>身長 : " . $result['height'] . "cm   体重 : " . $result['weight'] . "kg</p>";
                    echo "<p>自己紹介</p><p>" . $result['comment'] . "</p>";

                    echo $selected_user;

                    if (!isset($_POST['Selected_UserID'])) { ?>
                        <a href="profileRegistration.php" class="form-control btn btn-success my-3">編集</a>
                    <?php } else { ?>
                        <form action="profile.php" method="post">
                            <input type="submit" name="request" value="友達申請" class="form-control btn btn-success my-3">
                        </form>
                    <?php
                    } ?>
                </div>
            </div>
        </div>
    </div>

    <div class="footer">
    </div>
</body>
