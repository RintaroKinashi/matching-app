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
    $list_target[2] = 4 ** $point;

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

<?php
        $percentage = 0;
        foreach ($All_list_target as $All_list_targ) { 
            $stmtMe = $dbh->prepare("SELECT * FROM user WHERE UserID=:userID");
            $stmtMe->bindParam(':userID', $All_list_targ[0]);
            $stmtMe->execute();
            $resultMe = $stmtMe -> fetch(PDO::FETCH_ASSOC);
            // $i=0;
            
             if($percentage == 0 or $percentage > $All_list_targ[1]){
                $percentage = $All_list_targ[1];
                if($percentage == 0){
                    $percentage = -1;
                }
    ?>
    <div class="Partition_Block">
        <table>
            <tr>
                <td><?php echo "↓マッチング率が" . $All_list_targ[1] . "％の人です！";?></td>
                <td><?php if($percentage>0){echo $All_list_targ[2] . "人に1人の確率で出会えます！";}?></td>
            </tr>
        </table>
    </div>
    <?php }?>
    <div class="User_block">
        <form method="post" action="profile.php">
        <input type="hidden" name="Selected_UserID" value=<?php echo $All_list_targ[0]?>>
        <a href="" onclick="this.parentNode.submit(); return false;" style="color:black;text-decoration:none">
        <table>
                <tr>
                    <td><img src="<?php echo $resultMe['image']; ?>" width="150" height="150"></td>
                    <td class="User_pro"><?php echo $resultMe['name']. "　　". $resultMe['address'] ?> <p><?php echo nl2br($resultMe['comment']) ?></p></td>
                </tr>
            </table>
            </a>
        </form>
    </div>
    <?php } ?>
    
<div class="footer">
</div>
</body>