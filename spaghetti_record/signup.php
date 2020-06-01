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
  $name = "";
  $email = "";
  $errMsg = "";
// ----------------------------------------------------------------------------
// 操作に関する処理
// ----------------------------------------------------------------------------
  // 登録ボタンがクリックされた場合
  if(isset($_POST["email"])){
    // 送信された内容を変数に代入
    $name = $_POST["name"];
    $email = $_POST["email"];
    $password = $_POST["password"];
    // 登録時の重複確認
    // 送信されたメールアドレスと同じメールアドレスのデータの取得
    $sql = "SELECT * FROM users WHERE email = :email";
    $stmt = $dbInfo->prepare($sql);
    $stmt->bindParam(":email",$email,PDO::PARAM_STR);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    // 同じメールアドレスのデータがなかった場合
    if(!$user){
      // 送信された内容で登録
      $sql = "INSERT INTO users VALUES(\"\",:name,:email,:password)";
      $stmt = $dbInfo->prepare($sql);
      $stmt->bindParam(":name",$name,PDO::PARAM_STR);
      $stmt->bindParam(":email",$email,PDO::PARAM_STR);
      $stmt->bindParam(":password",$password,PDO::PARAM_STR);
      $stmt->execute();
      // 登録確認ページで内容を表示させるため、セッションに登録内容を格納
      $_SESSION["signup"]["name"] = $name;
      $_SESSION["signup"]["email"] = $email;
      $_SESSION["signup"]["password"] = $password;
      header("location:signupCmp.php");
      exit;
      // 同じメールアドレスのデータがあった場合
    } else{
      $errMsg = "既に登録しています。";
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
<title>新規登録 | Spaghetti Record</title>
<meta name="description" content="新規登録ページです。">
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
        <div class="signupPage">
          <h2 class="headingCmn">新規登録</h2>
          <p class="resultMsgCmn"><?php echo h($errMsg); ?></p>
          <form class="forAccountFormCmn" action="" method="post">
            <p class="item">
              <label>
                <span class="label">名前</span>
                <input class="name" type="text" placeholder="例：すぱ田 げてぃ太郎" maxlength="10" value="<?php echo h($name); ?>" name="name" required>
              </label>
            </p>
            <p class="item">
              <label>
                <span class="label">メールアドレス</span>
                <input class="email" type="email" placeholder="例：abc@spaghetti.co.jp" maxlength="30" value="<?php echo h($email); ?>" name="email" required>
              </label>
            </p>
            <p class="item">
              <label>
                <span class="label">パスワード</span>
                <input class="password" type="password" placeholder="4文字の半角英数字を入力" maxlength="4" pattern="^[0-9A-za-z]{4}$" name="password" required>
              </label>
            </p>
            <input class="btnCmn" type="submit" value="登録">
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
