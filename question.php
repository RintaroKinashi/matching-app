
<!--
    ■UIを整えること
    ■検索機能の実装
    ■
 -->
 <!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>【公式】世界初の猫汁！</title>
    <link rel="stylesheet" href="stylesheet.css">
  </head>
  <body>
    <div class="header">
      <div class="header-logo" href="./index.php">【公式】世界初の猫汁！</div>
      <div class="header-list">
        <ul>
          <li><a href="https://softhousewave.jp/">企業情報</a></li>
          <li>採用活動</li>
          <li><a href="./contactform.php">お問い合わせ</a></li>
        </ul>
      </div>
    </div>

<form action="matching.php" method="post">
<p>名前：<input type="text" name="name" value=""></p>
<p>年齢：<input type="text" name="age" value=""></p>
    <div class="question">
        <p>将来一緒に過ごすならどの生き物？一番近いものを選択してください。</p>
        <input type="radio" name="q1" value="0"> 犬
        <input type="radio" name="q1" value="1"> 猫
        <input type="radio" name="q1" value="2"> 他の動物
        <input type="radio" name="q1" value="3"> 飼わない
    </div>
    <div class="question">
        <p>一番好きなものを選択してください。</p>
        <input type="radio" name="q2" value="0"> 音楽
        <input type="radio" name="q2" value="1"> ゲーム
        <input type="radio" name="q2" value="2"> 酒
        <input type="radio" name="q2" value="3"> その他
    </div>
    <div class="question">
        <p>一番好きなものを選択してください。</p>
        <input type="radio" name="q3" value="0"> 和食
        <input type="radio" name="q3" value="1"> 洋食
        <input type="radio" name="q3" value="2"> ファーストフード
        <input type="radio" name="q3" value="3"> その他
    </div>

    <p><input type="submit" name="send" value="送信する"></p>
</form>
<div class="footer">
      <div class="footer-logo">SoftHouse</div>
      <div class="footer-list">
        <ul>
            <li>企業情報</li>
            <li>採用活動</li>
            <li><a href="contactform.php">お問い合わせ</a></li>
        </ul>
      </div>
    </div>
  </body>
</html>