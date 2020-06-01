<?php
  session_start();
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
// ----------------------------------------------------------------------------
// 操作に関する処理
// ----------------------------------------------------------------------------
  // ログインボタンがクリックされた場合
  if(isset($_POST["email"])){
    $email = $_POST["email"];
    $password = $_POST["password"];
    // 入力したメールアドレス、パスワードと一致するデータの取得
    $sql = "SELECT id,name FROM users WHERE email = :email AND password = :password;";
    $stmt = $dbInfo->prepare($sql);
    $stmt->bindParam(":email",$email,PDO::PARAM_STR);
    $stmt->bindParam(":password",$password,PDO::PARAM_STR);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    // 入力したメールアドレス、パスワードと一致するデータがあった場合
    if($user){
      // ログイン用のセッションにユーザのID、名前を格納
      // (ログイン中かどうかの判定に使用)
      $_SESSION["login"]["userId"] = $user["id"];
      $_SESSION["login"]["userName"] = $user["name"];
      // cartページから来た場合、そのページにリダイレクト
      if(isset($_SESSION["page"]["toCart"])){
        $page = $_SESSION["page"]["toCart"];
        unset($_SESSION["page"]["toCart"]);
        header("location:{$page}");
        // itemDetailsページ内のレビュから来た場合、そのページにリダイレクト
      } elseif(isset($_SESSION["page"]["toReview"])){
        $page = $_SESSION["page"]["toReview"];
        unset($_SESSION["page"]["toReview"]);
        header("location:{$page}");
        // userPageページから来た場合、そのページにリダイレクト
      } elseif(isset($_SESSION["page"]["toUserPage"])){
        $page = $_SESSION["page"]["toUserPage"];
        unset($_SESSION["page"]["toUserPage"]);
        header("location:{$page}");
        // それ以外のページから来た場合、shop.phpにリダイレクト
      } else{
        header("location:shop.php");
      }
      exit;
      // 入力したメールアドレス、パスワードと一致するデータがなかった場合
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
<title>ログイン | Spaghetti Record</title>
<meta name="description" content="ログインページです。">
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
        <div class="loginPage">
          <h2 class="headingCmn">ログイン</h2>
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
            <input class="btnCmn" type="submit" value="ログイン">
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
