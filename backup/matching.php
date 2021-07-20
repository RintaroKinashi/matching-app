<?php
//セッションを使うことを宣言
session_start();
// $option = array();

//ログインされていない場合は強制的にログインページにリダイレクト
if (!isset($_SESSION["login"])) {
  header("Location: index.php");
  exit();
}
$dsn='mysql:dbname=EC;charset=utf8';
$user='root';
$password='';
$dbh = new PDO($dsn,$user,$password);


$stmtID = $dbh->prepare("SELECT UserID FROM r_answer WHERE UserID!=:userID");
$stmtID->bindParam(':userID', $_SESSION["login"]);
$stmtID->execute();
$resultID = $stmtID -> fetchAll(PDO::FETCH_ASSOC);

echo "<pre>";
var_dump($resultID);
echo "</pre>";

$list_targ = array();
$i = 0;
foreach ($resultID As $row){
    $list_targ[$i] = $row;
    $i++;
}

echo "<pre>";
var_dump($list_targ);
echo "</pre>";






$stmt1 = $dbh->prepare("SELECT * FROM r_answer WHERE UserID=:userID");
$stmt1->bindParam(':userID', $_SESSION["login"]);
$stmt1->execute();
$result1 = $stmt1 -> fetch(PDO::FETCH_ASSOC);


// $a = array_column($resultID, 'userID');
print_r($resultID);
// echo "<pre>";
// var_dump($resultID);
// echo "</pre>";

$stmt2 = $dbh->prepare("SELECT * FROM r_answer WHERE UserID='asahi003003'");
$stmt2->execute();
$result2 = $stmt2 -> fetch(PDO::FETCH_ASSOC);

$point = 0;
$list_target=array();
$All_list_target = array();

$stmt3 = $dbh->prepare("SELECT * FROM user WHERE UserID='asahi003003'");
$stmt3->execute();
$result3 = $stmt3 -> fetch(PDO::FETCH_ASSOC);

$i = 0;
foreach ($result3 As $row){
    $list_target[$i] = $row;
    $i++;
}

for($j=1;$j<4;$j++){
    if ($result1['q'. $j] === $result2['q'. $j]){
        $point++;
    }
}
$list_target[$i] = $point;
echo "<pre>";
var_dump($list_target);
echo "</pre>";
echo round($list_target[$i]/3*100). "%です！";

foreach ($data as $value) {
    $list_target[$i] = $value;
    $i++;
}



























$stmtOtherInfo = $dbh->prepare("SELECT * FROM user");
// $stmtOtherInfo->bindParam(':userID', $row['UserID']);
$stmtOtherInfo->execute();
// 全データの抜き出し方法：http://alphasis.info/2014/10/php-gyakubiki-mysql-table/
// while($resultOtherInfo = $stmtOtherInfo -> fetch(PDO::FETCH_ASSOC)){
//     echo "<br />名前：" . $resultOtherInfo['name'];
//     echo "<br />出身:" . $resultOtherInfo["address"];
//     echo "<br />COMMENT<br />";
//     echo $resultOtherInfo["comment"];
//     echo "<br />-------------<br />";
// }

// $resultOtherInfo = $stmtOtherInfo -> fetchAll(PDO::FETCH_ASSOC|PDO::FETCH_UNIQUE);
// echo "<pre>";
// var_dump($resultOtherInfo);
// echo "</pre>";
//     echo "<br />名前：" . $resultOtherInfo[$_SESSION["login"]]['name'];
//     echo "<br />出身:" . $resultOtherInfo[$_SESSION["login"]]["address"];
//     echo "<br />-------------<br />";
    
    $row = 0;
    $list_target=array();
    $All_list_target = array();
    // ファイルが存在しているかチェックする
    if (($handle = fopen("DBkawari.csv", "r")) !== FALSE) {
    // 1行ずつfgetcsv()関数を使って読み込む
    while (($data = fgetcsv($handle))) {
        // DBからデータの抽出
        $i = 0;
        foreach ($data as $value) {
            // echo "「${value}」";
            $list_target[$i] = $value;
            $i++;
        }
        // 一致率の格納
        $point=0;
        for($j = 2; $j < count($list); $j++){
            if($list_target[$j] === $list[$j]){
                $point++;
            }
        }
        $list_target[count($list)]=round($point/3*100);
        $list_target[count($list)+1]=4**$point;
        $All_list_target[] = $list_target;

        $row++;
    }
    fclose($handle);
    }

    // 指定したキーに対応する値を基準に、配列をソートする
    function sortByKey($key_name, $sort_order, $array) {
        foreach ($array as $key => $value) {
            $standard_key_array[$key] = $value[$key_name];
        }

        array_multisort($standard_key_array, $sort_order, $array);

        return $array;
    }

    // release_dateを昇順ソートする
    $sorted_array = sortByKey(count($list), SORT_DESC, $All_list_target);
    // echo "<pre>";
    // var_dump($sorted_array);
    // echo "</pre>";

    for($j = 0; $j < count($sorted_array); $j++){
        echo "<br />---------------<br />";
        echo $sorted_array[$j][0]. " : " . $sorted_array[$j][1]. "歳<br />";
        echo "あなたとの一致率は" .$sorted_array[$j][count($list)]. "％です！<br />";
        if($sorted_array[$j][count($list)+1] > 16){
            echo "この人と出会える確率は約「" . $sorted_array[$j][count($list)+1] ."」人に1人の確率です！";
        }
        echo "<br />---------------<br />";
    }

    // データを格納する
    if(isset($_POST['send']) === true){
        echo "trueを確認。";
        $fp = fopen('DBkawari.csv', 'a');
        fputcsv($fp, $list);
        fclose($fp);
    }
?>