<?php
require 'common.php';

$stmt = $dbh->prepare("SELECT * FROM r_answer WHERE UserID IN(Select REQUEST_USERID FROM r_request WHERE MEMBER_ID = :userID)");
$stmt->bindParam(':userID', $_SESSION["login"]);
$stmt->execute();
$result = $stmt -> fetchAll(PDO::FETCH_ASSOC);

$stmtMe = $dbh->prepare("SELECT * FROM r_answer WHERE UserID=:userID");
$stmtMe->bindParam(':userID', $_SESSION["login"]);
$stmtMe->execute();
$resultMe = $stmtMe -> fetch(PDO::FETCH_ASSOC);

$stmtRequest = $dbh->prepare("SELECT * FROM r_request WHERE MEMBER_ID=:userID");
$stmtRequest->bindParam(':userID', $_SESSION["login"]);
$stmtRequest->execute();
$resultRequest = $stmtRequest -> fetch(PDO::FETCH_ASSOC);

if (!isset($resultRequest['MEMBER_ID'])){
    echo "友達申請がまだ来ていません！";
} else{
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

<?php
    // ユーザ情報の吐き出し
    foreach ($All_list_target as $All_list_targ) { 
        $stmtMe = $dbh->prepare("SELECT * FROM user WHERE UserID=:userID");
        $stmtMe->bindParam(':userID', $All_list_targ[0]);
        $stmtMe->execute();
        $resultMe = $stmtMe -> fetch(PDO::FETCH_ASSOC);
        $i=0;
    ?>
    <div class="User_block">
    <form method="post" action="profile.php">
        <input type="hidden" name="Selected_UserID" value=<?php echo $All_list_targ[0]?>>
    <a href="" onclick="this.parentNode.submit(); return false;" style="color:black;text-decoration:none">
       <table>
            <tr>
            <td><img src="<?php echo $resultMe['image']; ?>" width="150" height="150"></td>
            <td class="User_pro"><?php echo $resultMe['name']. "　　". $resultMe['address'] ?> <p><?php echo nl2br($resultMe['comment']) ?></p></td>
            <td class="User_pro"><?php echo "マッチング率は" . $All_list_targ[1] . "％です！";?> </td>
            </tr>
        </table>
    </a>
    </form>
    </div>
    <?php }
} ?>
    
<div class="footer">
</div>
</body>