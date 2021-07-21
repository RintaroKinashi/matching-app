<?php
require 'common.php';

$mode = "login_user";
$stmt = $dbh->prepare("SELECT * FROM user WHERE userID=:userID");
if(!isset($_POST['Selected_UserID'])){
    // ログインユーザのプロフィールを表示
    $stmt->bindParam(':userID', $_SESSION["login"]);
}else{
    $mode = "selected_user";
    // 選択されたユーザのプロフィールを表示
    $stmt->bindParam(':userID', $_POST['Selected_UserID']);
}
$stmt->execute();
$result = $stmt -> fetch(PDO::FETCH_ASSOC);

?>

<div class = "profile">
    <img src="<?php echo $result['image']; ?>" width="300" height="300">
    <?php 
        echo "<br>名前 :" . $result['name'];
        echo "     性別 : ";
        if($result['sex'] == 0){echo "男";}else{echo "女";}
        echo "<p>年齢 :" . $result['age']. "</p>";
        echo "<p>お住まい:" . $result['address']. "</p>";
        echo "<p>身長 : " . $result['height'] . "cm   体重 : " . $result['weight']. "kg</p>";
        echo "<p>自己紹介</p><p>" . $result['comment']. "</p>";

        if($mode != "selected_user"){?>
            <a href="profileRegistration.php">編集</a>
            <?php } else{?>
            <a href="profileRegistration.php">友達申請</a>
    <?php }
    ?>

</div>

<div class="footer">
</div>
</body>
