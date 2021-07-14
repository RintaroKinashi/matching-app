<p>呼び出されたPHPプログラム</p>


<p><input type="submit" name="search" value="検索" /></p>

<?php
    $list = [
        $_POST['name'],
        $_POST['age'],
        $_POST['q1'],
        $_POST['q2'],
        $_POST['q3']
    ];
    echo "私は". $list[0] . "です。<br />";
    print_r($list);
    echo "<br />";

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