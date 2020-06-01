<?php
  session_start();
// ----------------------------------------------------------------------------
// リダイレクトに関する処理
// ----------------------------------------------------------------------------
  // cancelページから以外は、shopページへリダイレクト
  if(!isset($_SESSION["page"]["fromCancel"])){
    header("location:shop.php");
    exit;
  }
  // セッションの削除
  $_SESSION = [];
  session_destroy();
// ----------------------------------------------------------------------------
// ファイル読み込みに関する処理
// ----------------------------------------------------------------------------
  // 関数の入ったファイルの読み込み
  require_once("common/functions.php");
?>
<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<meta name="robots" content="noindex">
<title>退会の完了 | Spaghetti Record</title>
<meta name="description" content="退会の完了ページです。">
<link rel="stylesheet" href="css/commonStyle.css">
<link rel="stylesheet" href="css/style.css">
<link rel="stylesheet" href="css/responsive.css">
</head>
<body>
  <!-- ヘッダ -->
  <?php require_once("common/header.php"); ?>
  <!-- メイン -->
  <div class="mainWrapper">
    <div class="container clearfix">
      <section class="main">
        <div class="cancelCmpPage">
          <h2 class="headingCmn">退会の完了</h2>
          <p class="resultMsgCmn">退会の手続きが完了しました。</p>
          <div class="textRightCmn">
            <a class="underlineCmn" href="shop.php">トップページへ</a>
          </div>
        </div>
      </section>
      <!-- サイドメニュ -->
      <?php require_once("common/sidemenu.html"); ?>
    </div>
  </div>
  <!-- フッタ -->
  <?php require_once("common/footer.html") ?>
  <!-- 背景色を付けるための要素 -->
  <div class="mask"></div>
</body>
</html>
