<?php
if(isset($_POST['userID'])) {
$dsn='mysql:dbname=EC;charset=utf8';
$user='root';
$password='';
$dbh = new PDO($dsn,$user,$password);

$stmt = $dbh->prepare("SELECT * FROM user WHERE userID=:userID");
$stmt->bindParam(':userID', $_POST['userID']);
$stmt->execute();
if($rows = $stmt->fetch()) {
if($rows["password"] ==  $_POST['password']) {
print "<p>ログイン成功</p>";
}else {
    header("Location: ./index.php");
}
}else {
    header("Location: ./index.php");
}
}
?>

<img src="img/asahi.jpg" alt="asahichan">
<?php 
echo "<br>名前 :" . $rows['name'];
echo "     性別 : ";
if($rows['sex'] == 0){echo "男";}else{echo "女";}
echo "<p>年齢 :" . $rows['age']. "</p>";
echo "<p>お住まい:" . $rows['address']. "</p>";
echo "<p>身長 : " . $rows['height'] . "cm   体重 : " . $rows['weight']. "kg</p>";
echo "<p>自己紹介</p><p>" . $rows['comment']. "</p>";

?>
