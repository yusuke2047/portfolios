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
  // 神経衰弱のクリア時間の基準値（難易度設定）
  // もらえるBP（バトルポイント）は、クリア時間が、基準値未満なら + 、基準値超過なら - の値になる
  $clearTime = 90;
  // セッション内に格納していた変数の取り出し
  //（リダイレクトにより変数が消えてしまうため、セッション内に格納していた）
  if(isset($_SESSION["memory"]["addPoint"])){
    $addPoint = $_SESSION["memory"]["addPoint"];
  }
  // セッション内に格納していた変数の取り出し
  //（リダイレクトにより変数が消えてしまうため、セッション内に格納していた
  if(isset($_SESSION["memory"]["finalResult"])){
    $finalResult = $_SESSION["memory"]["finalResult"];
  }
  // ----------------------------------------------------------------------------
  // 操作に関する処理
  // ----------------------------------------------------------------------------
  // 神経衰弱をクリアした場合
  if(isset($_GET["finishTime"])){
    $finishTime = $_GET["finishTime"];
    // クリア時間が基準値より早い場合
    if($finishTime < $clearTime){
      $finalResult = "win";
      // クリア時間が基準値より遅い場合
    } elseif($finishTime > $clearTime){
      $finalResult = "lose";
      // クリア時間が基準値と同じ場合
    } else{
      $finalResult = "draw";
    }
    // ログインしている場合
    if(isset($userId)){
      // BPの計算
      // $dayStatus、$rateはcommon/config.php内で設定
      if($dayStatus == "special"){
        $addPoint = round((-($maxBp / $clearTime) * $finishTime + $maxBp) * $rate);
      } else{
        $addPoint = round(-($maxBp / $clearTime) * $finishTime + $maxBp);
      }
      // データベース内の神経衰弱のBPに関するデータの更新
      $sql = "UPDATE users SET memory_bp = memory_bp + :addPoint WHERE id = :userId";
      $stmt = $dbInfo->prepare($sql);
      $stmt->bindParam(":addPoint",$addPoint,PDO::PARAM_INT);
      $stmt->bindParam(":userId",$userId,PDO::PARAM_INT);
      $stmt->execute();
      // リダイレクトにより消えてしまう変数をセッション内に格納
      $_SESSION["memory"]["addPoint"] = $addPoint;
      $_SESSION["memory"]["finalResult"] = $finalResult;
      // リロードによる多重操作を防止
      header("location:memory.php");
      exit;
    }
  }
  // CLOSEボタンがクリックされた場合
  if(isset($_POST["reset"])){
    unset($_SESSION["memory"]);
    header("location:memory.php");
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
<title>MEMORY | WORLD IS MINE</title>
<meta name="description" content="神経衰弱をプレイできます。">
<link rel="stylesheet" href="css/styles.css">
</head>
<body class="memoryPage">
  <main class="mainWrapper">
    <div class="memoryWrapper">
      <!-- 神経衰弱の結果を表示する部分 -->
      <div class="memory">
        <div id="score">0.00</div>
        <div id="stage">
        </div>
      </div>
    </div>
    <a href="" id="restart">RESET</a>
    <a class="btnCmn" href="index.php">TOP PAGE</a>
    <!-- 神経衰弱の最終結果を表示する部分 -->
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
  <script src="js/memory.js"></script>
</body>
</html>
