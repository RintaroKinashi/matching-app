<?php
require 'common.php';

$stmt = $dbh->prepare("SELECT * FROM r_answer WHERE UserID!=:userID");
$stmt->bindParam(':userID', $_SESSION["login"]);
$stmt->execute();
$result = $stmt -> fetchAll(PDO::FETCH_ASSOC);

$stmtMe = $dbh->prepare("SELECT * FROM r_answer WHERE UserID=:userID");
$stmtMe->bindParam(':userID', $_SESSION["login"]);
$stmtMe->execute();
$resultMe = $stmtMe -> fetch(PDO::FETCH_ASSOC);

if (!isset($resultMe['UserID'])){
    header("Location: question.php");
    exit();
}

// ユーザIDとマッチング率を配列に格納する。
$i=0;
$list_target=array();
$All_list_target = array();

foreach($result as $re) {
    $list_target[0] = $re['UserID'];
    $point = 0;
    for($j=1;$j<=NUMBER_OF_QUESTIONS;$j++){
        if ($re['q'. $j] == $resultMe['q'. $j]){
            $point++;
        }
    }
    $list_target[1] = round($point/NUMBER_OF_QUESTIONS*100);

    $All_list_target[$i] = $list_target;
    $i++;
}
// マッチング率順に降順に並び替えを行う。
foreach($All_list_target as $key => $value){
    $sort_keys[$key] = $value[1];
}
array_multisort($sort_keys, SORT_DESC, $All_list_target);
// echo "<pre>";
// print_r($All_list_target);
// echo "</pre>";
?>

<table>
    <?php
        foreach ($All_list_target as $All_list_targ) { 
            $stmtMe = $dbh->prepare("SELECT * FROM user WHERE UserID=:userID");
            $stmtMe->bindParam(':userID', $All_list_targ[0]);
            $stmtMe->execute();
            $resultMe = $stmtMe -> fetch(PDO::FETCH_ASSOC);
    ?>
    <tr>
      <td>
        <img src="<?php echo $resultMe['image']; ?>" width="100" height="100">
      </td>
      <td>
        <p class="goods"><?php echo $resultMe['name']. "　　". $resultMe['address'] ?></p>
        <p><?php echo nl2br($resultMe['comment']) ?></p>
      </td>
      <td>
          <p><?php echo "マッチング率は" . $All_list_targ[1] . "％です！";?></p>
      </td>
    </tr>
    <?php } ?>
</table>
    
<div class="footer">
    </div>
</body>