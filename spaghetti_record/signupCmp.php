<?php
  session_start();
// ----------------------------------------------------------------------------
// リダイレクトに関する処理
// ----------------------------------------------------------------------------
  // signupページから以外は、shopページへリダイレクト
  if(!isset($_SESSION["signup"])){
    header("location:shop.php");
    exit;
  }
// ----------------------------------------------------------------------------
// ファイル読み込みに関する処理
// ----------------------------------------------------------------------------
  // 関数の入ったファイルの読み込み
  require_once("common/functions.php");
// ----------------------------------------------------------------------------
// 変数に関する処理
// ----------------------------------------------------------------------------
  // signupページで登録した内容を変数に代入
  // (当ページで表示するため)
  $name = $_SESSION["signup"]["name"];
  $email = $_SESSION["signup"]["email"];
  $password = $_SESSION["signup"]["password"];
  // 代入し終えたら、もう使用しないので削除
  unset($_SESSION["signup"]);
?>
<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<meta name="robots" content="noindex">
<title>新規登録の完了 | Spaghetti Record</title>
<meta name="description" content="新規登録の完了ページです。">
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
        <div class="signupCmpPage">
          <h2 class="headingCmn">新規登録の完了</h2>
          <p class="resultMsgCmn">以下の情報で登録しました。</p>
          <table class="confirmTableCmn signupConfirmTable">
            <tr>
              <th scope="col">名前</th>
              <th scope="col">メールアドレス</th>
              <th scope="col">パスワード</th>
            </tr>
            <tr>
              <td><?php echo h($name); ?></td>
              <td><?php echo h($email); ?></td>
              <td><?php echo h($password); ?></td>
            </tr>
          </table>
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
