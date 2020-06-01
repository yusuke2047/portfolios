<?php
  session_start();
// ----------------------------------------------------------------------------
// リダイレクトに関する処理
// ----------------------------------------------------------------------------
  // ログインしていない場合、shopページへリダイレクト
  if(!isset($_SESSION["login"])){
    header("location:shop.php");
    exit;
  }
// ----------------------------------------------------------------------------
// ファイル読み込みに関する処理
// ----------------------------------------------------------------------------
  // データベース接続ファイルの読み込み
  require_once("common/dbConnect.php");
  // 関数の入ったファイルの読み込み
  require_once("common/functions.php");
// ----------------------------------------------------------------------------
// 変数に関する処理
// ----------------------------------------------------------------------------
  $email = "";
  $errMsg = "";
  $userId = $_SESSION["login"]["userId"];
// ----------------------------------------------------------------------------
// 操作に関する処理
// ----------------------------------------------------------------------------
  // 退会ボタンがクリックされた場合
  if(isset($_POST["email"])){
    $email = $_POST["email"];
    $password = $_POST["password"];
    // ログイン中のユーザのID、入力したメールアドレス、パスワードと一致するデータの取得
    $sql = "SELECT * FROM users WHERE id = :id AND email = :email AND password = :password;";
    $stmt = $dbInfo->prepare($sql);
    $stmt->bindParam(":id",$userId,PDO::PARAM_INT);
    $stmt->bindParam(":email",$email,PDO::PARAM_STR);
    $stmt->bindParam(":password",$password,PDO::PARAM_STR);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    // ログイン中のユーザのID、入力したメールアドレス、パスワードと一致したデータがあった場合
    if($user){
      // 各テーブルから退会したユーザに関するデータを削除
      $sql = "
        DELETE users,diaries,histories,reviews
        FROM users
        LEFT JOIN diaries
        ON users.id = diaries.user_id
        LEFT JOIN histories
        ON users.id = histories.user_id
        LEFT JOIN reviews
        ON users.id = reviews.user_id
        WHERE users.id = :id
      ";
      $stmt = $dbInfo->prepare($sql);
      $stmt->bindParam(":id",$userId,PDO::PARAM_INT);
      $stmt->execute();
      // cancelCmpページでのリダイレクト処理に使用
      $_SESSION["page"]["fromCancel"] = "cancel.php";
      header("location:cancelCmp.php");
      exit;
      // ログイン中のユーザのID、入力したメールアドレス、パスワードと一致したデータがなかった場合
    } else{
      $errMsg = "メールアドレス、またはパスワードが違います。";
    }
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
<meta name="viewport" content="width=device-width,initial-scale=1">
<meta name="robots" content="noindex">
<title>退会 | Spaghetti Record</title>
<meta name="description" content="退会ページです。">
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
        <div class="cancelPage">
          <h2 class="headingCmn">退会</h2>
          <p class="resultMsgCmn"><?php echo h($errMsg); ?></p>
          <form class="forAccountFormCmn" action="" method="post">
            <p class="item">
              <label>
                <span class="label">メールアドレス</span>
                <input class="email" type="email" placeholder="例：abc@spaghetti.co.jp" value="<?php echo h($email); ?>" name="email" required>
              </label>
            </p>
            <p class="item">
              <label>
                <span class="label">パスワード</span>
                <input class="password" type="password" placeholder="4文字の半角英数字を入力" name="password" required>
              </label>
            </p>
            <input class="btnCmn btnDangerCmn" type="submit" value="退会">
          </form>
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
