<?php
  session_start();
  // ----------------------------------------------------------------------------
  // ファイル読み込みに関する処理
  // ----------------------------------------------------------------------------
  require_once("common/dbConnect.php");
  require_once("common/config.php");
  require_once("common/functions.php");
  // ----------------------------------------------------------------------------
  // 変数に関する処理
  // ----------------------------------------------------------------------------
  // セッション内に格納していた変数の取り出し
  //（リダイレクトにより変数が消えてしまうため、セッション内に格納していた）
  if(isset($_SESSION["slot"]["addPoint"])){
    $addPoint = $_SESSION["slot"]["addPoint"];
  }
  // セッション内に格納していた変数の取り出し
  //（リダイレクトにより変数が消えてしまうため、セッション内に格納していた
  if(isset($_SESSION["slot"]["finalResult"])){
    $finalResult = $_SESSION["slot"]["finalResult"];
  }
  // ----------------------------------------------------------------------------
  // 操作に関する処理
  // ----------------------------------------------------------------------------
  // スロットが終了した場合
  if(isset($_GET["result"])){
    $result = $_GET["result"];
    // BPの計算
    // $maxBpはcommon/config.php内で設定
    if($result == "win"){
      $finalResult = "win";
      $addPoint = $maxBp;
    } elseif($result == "draw"){
      $finalResult = "draw";
      $addPoint = 0;
    } elseif($result == "lose"){
      $finalResult = "lose";
      $addPoint = -$maxBp;
    }
    // $dayStatus、$rateはcommon/config.php内で設定
    if($dayStatus == "special"){
      $addPoint *= $rate;
    }
    // ログインしている場合
    if(isset($userId)){
      // データベース内のスロットのBPに関するデータの更新
      $sql = "UPDATE users SET slot_bp = slot_bp + :addPoint WHERE id = :userId";
      $stmt = $dbInfo->prepare($sql);
      $stmt->bindParam(":addPoint",$addPoint,PDO::PARAM_INT);
      $stmt->bindParam(":userId",$userId,PDO::PARAM_INT);
      $stmt->execute();
      // リダイレクトにより消えてしまう変数をセッション内に格納
      $_SESSION["slot"]["addPoint"] = $addPoint;
      $_SESSION["slot"]["finalResult"] = $finalResult;
      // リロードによる多重操作を防止
      header("location:slot.php");
      exit;
    }
  }
  // CLOSEボタンがクリックされた場合
  if(isset($_POST["reset"])){
    unset($_SESSION["slot"]);
    header("location:slot.php");
    exit;
  }
  // ----------------------------------------------------------------------------
  // データベースの切断に関する処理
  // ----------------------------------------------------------------------------
  $dbInfo = null;
?>
<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="utf-8">
<meta name="robots" content="noindex">
<title>SLOT | WORLD IS MINE</title>
<meta name="description" content="スロットをプレイできます。">
<link rel="stylesheet" href="css/styles.css">
</head>
<body class="slotPage">
  <main class="mainWrapper">
    <div class="slotWrapper">
      <div class="slot">
        <div class="slotImg">
          <img id="slot1" src="images/slot0.png">
        </div>
        <div id="stop1" class="slotBtn" onclick="stop(1)"></div>
      </div>
      <div class="slot">
        <div class="slotImg">
          <img id="slot2" src="images/slot0.png">
        </div>
        <div id="stop2" class="slotBtn" onclick="stop(2)"></div>
      </div>
      <div class="slot">
        <div class="slotImg">
          <img id="slot3" src="images/slot0.png">
        </div>
        <div id="stop3" class="slotBtn" onclick="stop(3)"></div>
      </div>
    </div>
    <p class="btnCmn" onclick="start()">START</p>
    <p class="btnCmn" onclick="undo()">RESET</p>
    <a class="btnCmn" href="index.php">TOP PAGE</a>
    <!-- スロットの最終結果を表示する部分 -->
    <?php if(isset($finalResult)): ?>
      <div class="gameFinalResultCmn">
        <p class="gameFinalResultMsgCmn"><?php echo h($finalResultMsgs[$finalResult]); ?></p>
        <?php if(isset($userId)): ?>
          <p class="gameAddPointMsgCmn"><?php echo h($userName); ?> GETS <span><?php echo h($addPoint); ?></span> BP .</p>
        <?php endif; ?>
        <form action="" method="post">
          <input class="btnCmn" type="submit" name="reset" value="CLOSE">
        </form>
      </div>
    <?php endif; ?>
  </main>
  <div class="mask"></div>
  <script src="js/slot.js"></script>
</body>
</html>
