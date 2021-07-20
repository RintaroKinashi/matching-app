<?php
require 'common.php';

$stmt = $dbh->prepare("SELECT * FROM user WHERE userID!=:userID");
$stmt->bindParam(':userID', $_SESSION["login"]);
$stmt->execute();
$result = $stmt -> fetchAll(PDO::FETCH_ASSOC);

?>


<table>
    <?php foreach ($result as $re) { ?>
<tr>
      <td>
        <img src="<?php echo $re['image']; ?>" width="100" height="100">
      </td>
      <td>
        <p class="goods"><?php echo $re['name']. "　　". $re['address'] ?></p>
        <p><?php echo nl2br($re['comment']) ?></p>
      </td>
</tr>
<?php } ?>
</table>
  <?php 
  
      ?>
    
<div class="footer">
    </div>
</body>