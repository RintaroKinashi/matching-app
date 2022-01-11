<?php

require 'common.php';

$stmt1 = $dbh->prepare("SELECT * FROM r_answer WHERE UserID=:userID");
$stmt1->bindParam(':userID', $_SESSION["login"]);
$stmt1->execute();
$result1 = $stmt1->fetch(PDO::FETCH_ASSOC);

if (isset($_POST['q1'])) {
  if (empty($result1["UserID"])) {
    // 質問に未回答(IDが空)ならinsert
    $SQL = "INSERT INTO r_answer VALUES(:userID,";
    for ($i = 1; $i < $NUMBER_OF_QUESTIONS; $i++) {
      $SQL .= ":q" . $i . ",";
    }
    $SQL .= ":q" . $NUMBER_OF_QUESTIONS . ");";
    echo $SQL;
    $stmt = $dbh->prepare($SQL);
  } else {
    $SQL = "UPDATE r_answer SET ";
    for ($i = 1; $i < $NUMBER_OF_QUESTIONS; $i++) {
      $SQL .= "q" . $i . " = :q" . $i . ", ";
    }
    $SQL .= "q" . $NUMBER_OF_QUESTIONS . " = :q" . $NUMBER_OF_QUESTIONS . " WHERE UserID = :userID;";
    echo $SQL;
    $stmt = $dbh->prepare($SQL);
  }
  $stmt->bindParam(':userID', $_SESSION["login"]);
  for ($i = 1; $i < $NUMBER_OF_QUESTIONS + 1; $i++) {
    $stmt->bindParam(':q' . $i, $_POST['q' . $i]);
  }
  $stmt->execute();
  header("Location: matching.php");
  exit();
}
?>

<form action="question.php" method="post">
  <?php
  for ($i = 1; $i < $NUMBER_OF_QUESTIONS + 1; $i++) {
    $stmtq = $dbh->prepare("SELECT * FROM m_question WHERE questionID=$i");
    $stmtq->execute();
    $resultq = $stmtq->fetch(PDO::FETCH_ASSOC);

    echo "<div class='question'>";
    echo "<div class='Partition_Block'>";
    echo "<p><b>Q" . $i . "：" . $resultq["sentence"] . "</b></p>";
    echo "</div>";
    echo "<div class='mx-3'>";
    for ($j = 0; $j < 4; $j++) {
      $k = "";
      if (isset($result1['UserID']) && $result1['q' . $i] == $j) {
        $k = "checked";
      }
      echo "<input type='radio' name='q" . $i . "' value='" . $j . "' " . $k . ">" . $resultq["Choice" . (string)($j + 1)];
      echo "</br>";
    }
    echo "</br></div></div>";
  }
  ?>
  <center>
    <p><input type="submit" name="send" value="送信する"></p>
  </center>
</form>
</body>

</html>
