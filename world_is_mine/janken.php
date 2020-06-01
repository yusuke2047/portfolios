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
  // 先に何回勝てば(負ければ)終了か設定
  $gameSet = 5;
  $handMenu = ["グー","チョキ","パー"];
  $handImgs = ["images/gu.png","images/choki.png","images/pa.png"];
  $gameTable = [
  // 相手の手
  //  グー     チョキ    パー        // 自分の手
    ["draw" , "win"  , "lose"],     // グー
    ["lose" , "draw" , "win" ],     // チョキ
    ["win"  , "lose" , "draw"]      // パー
  ];
  $resultMsgs = [
    "win"=>"YOU WIN!!",
    "lose"=>"YOU LOSE!!",
    "draw"=>"DRAW"
  ];
  // セッション内に格納していた変数の取り出し
  //（リダイレクトにより変数が消えてしまうため、セッション内に格納していた
  if(isset($_SESSION["janken"]["addPoint"])){
    $addPoint = $_SESSION["janken"]["addPoint"];
  }
  // ----------------------------------------------------------------------------
  // 操作に関する処理
  // ----------------------------------------------------------------------------
  // 出す手がクリックされた場合
  if(isset($_POST["selectHand"])){
    // 二回戦目以降の場合
    if(isset($_SESSION["janken"])){
      // セッションからじゃんけんに関する情報の取り出し
      $gameCount = $_SESSION["janken"]["gameCount"];
      $myHand = $_SESSION["janken"]["myHand"];
      $compHand = $_SESSION["janken"]["compHand"];
      $result = $_SESSION["janken"]["result"];
      $myWin = $_SESSION["janken"]["myWin"];
      $myLose = $_SESSION["janken"]["myLose"];
      // 一回戦目の場合
    } else{
      // 変数の初期設定
      $gameCount = 0;
      $myWin = 0;
      $myLose = 0;
      $_SESSION["janken"]["reloadCnt"] = 0;
    }

    $gameCount++;
    $myHand = $_POST["selectHand"];
    $compHand = rand(0,2);
    $result = $gameTable[$myHand][$compHand];
    // じゃんけんに勝った場合
    if($result == "win"){
      $myWin++;
      // じゃんけんに負けた場合
    } elseif($result == "lose"){
      $myLose++;
    }
    // セッションのじゃんけんに関する情報の更新
    $_SESSION["janken"]["gameCount"] = $gameCount;
    $_SESSION["janken"]["myHand"] = $myHand;
    $_SESSION["janken"]["compHand"] = $compHand;
    $_SESSION["janken"]["result"] = $result;
    $_SESSION["janken"]["myWin"] = $myWin;
    $_SESSION["janken"]["myLose"] = $myLose;
    // リロードによる多重操作を防止
    header("location:janken.php");
    exit;
  }
  // セッション内にじゃんけんに関するデータがある場合
  if(isset($_SESSION["janken"])){
    // セッションからじゃんけんに関する情報の取り出し
    $gameCount = $_SESSION["janken"]["gameCount"];
    $myHand = $_SESSION["janken"]["myHand"];
    $compHand = $_SESSION["janken"]["compHand"];
    $result = $_SESSION["janken"]["result"];
    $myWin = $_SESSION["janken"]["myWin"];
    $myLose = $_SESSION["janken"]["myLose"];
    // じゃんけんの決着がついた場合
    // $gameSetは先に何回勝てば(負ければ)終了かについての変数（当ファイル上部で設定）
    if($myWin == $gameSet || $myLose == $gameSet){
      $_SESSION["janken"]["reloadCnt"]++;
      // ユーザが勝った場合
      if($myWin == $gameSet){
        $finalResult = "win";
      // ユーザが負けた場合
      } elseif($myLose == $gameSet){
        $finalResult = "lose";
      }
      // ログインしていて、かつBP加算が一回目の場合
      if(isset($userId) && $_SESSION["janken"]["reloadCnt"] ==  1){
        // BPの計算
        // $dayStatus、$rateはcommon/config.php内で設定
        if($dayStatus == "special"){
          $addPoint = round($maxBp * (1 / $gameSet) * ($myWin - $myLose) * $rate);
        } else{
          $addPoint = round($maxBp * (1 / $gameSet) * ($myWin - $myLose));
        }
        // データベース内のじゃんけんのBPに関するデータの更新
        $sql = "UPDATE users SET janken_bp = janken_bp + :addPoint WHERE id = :userId";
        $stmt = $dbInfo->prepare($sql);
        $stmt->bindParam(":addPoint",$addPoint,PDO::PARAM_INT);
        $stmt->bindParam(":userId",$userId,PDO::PARAM_INT);
        $stmt->execute();
        // リダイレクトにより消えてしまう変数をセッション内に格納
        $_SESSION["janken"]["addPoint"] = $addPoint;
        // リロードによる多重操作を防止
        header("location:janken.php");
        exit;
      }
    }
  }
  // RESETボタン、またはCLOSEボタンがクリックされた場合
  if(isset($_POST["reset"])){
    unset($_SESSION["janken"]);
    header("location:janken.php");
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
<title>JANKEN | WORLD IS MINE</title>
<meta name="description" content="じゃんけんをプレイできます。">
<link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="css/styles.css">
</head>
<body class="jankenPage">
  <main class="mainWrapper">
    <div class="selectHandWrapper">
      <!-- 出す手を選択する部分 -->
      <p class="selectHandMsg">SELECT YOUR HAND</p>
      <div class="selectHand">
        <form class="selectHandForm" action="" method="post">
          <input type="hidden" name="selectHand" value="0">
          <input type="image" src="images/gu.png" value="グー">
        </form>
        <form class="selectHandForm" action="" method="post">
          <input type="hidden" name="selectHand" value="1">
          <input type="image" src="images/choki.png" value="チョキ">
        </form>
        <form class="selectHandForm" action="" method="post">
          <input type="hidden" name="selectHand" value="2">
          <input type="image" src="images/pa.png" value="パー">
        </form>
      </div>
    </div>
    <!-- じゃんけんの結果を表示する部分 -->
    <?php if(isset($_SESSION["janken"])): ?>
      <div class="jankenWrapper">
        <p class="jankenCount"><?php echo h($gameCount); ?> ROUND</p>
        <div class="janken">
          <div class="jankenItem">
            <p class="jankenName"><?php echo h($userName); ?></p>
            <img class="jankenHandImg" src="<?php echo h($handImgs[$myHand]); ?>" alt="<?php echo h($handMenu["myHand"]); ?>">
            <meter class="jankenMeter" value="<?php echo h(($gameSet - $myLose)); ?>" min="0" max="5" low="2" high="4" optimum="5"></meter>
          </div>
          <div class="jankenItem">
            <img class="jankenIcon" src="images/vs.png" alt="VS">
          </div>
          <div class="jankenItem">
            <p class="jankenName">COMPUTER</p>
            <img class="jankenHandImg" src="<?php echo h($handImgs[$compHand]); ?>" alt="<?php echo h($handMenu[$compHand]); ?>">
            <meter class="jankenMeter" value="<?php echo h(($gameSet - $myWin)); ?>" min="0" max="5" low="2" high="4" optimum="5"></meter>
          </div>
        </div>
        <p class="jankenResultMsg"><?php echo h($resultMsgs[$result]); ?></p>
      </div>
      <form class="jankenResetForm" action="" method="post">
        <input class="btnCmn" type="submit" name="reset" value="RESET">
      </form>
    <?php endif; ?>
    <a class="btnCmn" href="index.php">TOP PAGE</a>
    <!-- じゃんけんの最終結果を表示する部分 -->
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
</body>
</html>
