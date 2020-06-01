<?php
  session_start();
// ----------------------------------------------------------------------------
// ファイル読み込みに関する処理
// ----------------------------------------------------------------------------
  // データベース接続ファイルの読み込み
  require_once("common/dbConnect.php");
  // 関数の入ったファイルの読み込み
  require_once("common/functions.php");
  // 商品に関するクラスファイルの読み込み
  require_once("class/items.php");
// ----------------------------------------------------------------------------
// 変数に関する処理
// ----------------------------------------------------------------------------
  // 買い物を続けるをクリックした場合の戻るページ設定
  // セッションに戻るページ情報が格納されていた場合、戻るページにそのページを設定(shopページへ元の状態で戻るため)
  // セッションに戻るページ情報が格納されていなかった場合
  $backPage = (isset($_SESSION["page"]["toShop"])) ? $_SESSION["page"]["toShop"] : "shop.php";
// ----------------------------------------------------------------------------
// 操作に関する処理
// ----------------------------------------------------------------------------
  // itemDetailsページでカートに追加ボタンがクリックされた場合
  if(isset($_POST["itemId"])){
    $itemId = $_POST["itemId"];
    $_SESSION["cart"][] = $itemId;
    // 当ページを読み込みなおす
    // (リロードによる多重操作を防止のため)
    header("location:cart.php");
    exit;
    // 削除ボタンがクリックされた場合
  } elseif(isset($_POST["deleteIdx"])){
    // セッション内のカートから受信したインデックスに対応する内容の削除
    $deleteIdx = $_POST["deleteIdx"];
    unset($_SESSION["cart"][$deleteIdx]);
    // 当ページを読み込みなおす
    // (リロードによる多重操作を防止のため)
    header("location:cart.php");
    exit;
    // 購入するボタンがクリックされた場合
  } elseif(isset($_POST["action"]) && $_POST["action"] == "buy"){
    // ログインしている場合
    if(isset($_SESSION["login"])){
      // cartCmpページでのリダイレクト処理に使用
      $_SESSION["page"]["fromCart"] = "cart.php";
      // 購入した商品を購入履歴としてデータベース"histories"に書き込み
      $cart = $_SESSION["cart"];
      $userId = $_SESSION["login"]["userId"];

      foreach($cart as $itemId){
        $sql = "INSERT INTO histories VALUES(\"\",:itemId,:userId,NOW())";
        $stmt = $dbInfo->prepare($sql);
        $stmt->bindParam(":itemId",$itemId,PDO::PARAM_INT);
        $stmt->bindParam(":userId",$userId,PDO::PARAM_INT);
        $stmt->execute();
      }
      header("location:cartCmp.php");
      exit;
      // ログインしていない場合
    } else{
      // ログイン後、当ページに戻ってくるように設定
      $_SESSION["page"]["toCart"] = "cart.php";
      header("location:login.php");
      exit;
    }
  }
// ----------------------------------------------------------------------------
// データベースに関する処理
// ----------------------------------------------------------------------------
  // カートが空でない場合
  if(!empty($_SESSION["cart"])){
    $cart = $_SESSION["cart"];
    // セッション内のカートから一つずつ商品のIDを取り出し、
    // 商品のIDに対応した商品のデータのオブジェクトを配列cartItemsObjに格納
    foreach($cart as $idx=>$itemId){
      $sql = "SELECT * FROM items WHERE id = :id";
      $stmt = $dbInfo->prepare($sql);
      $stmt->bindParam(":id",$itemId,PDO::PARAM_INT);
      $stmt->execute();
      $item = $stmt->fetch(PDO::FETCH_ASSOC);
      // 商品のオブジェクトの生成
      $cartItemsObj[$idx] = new Items($item["id"],$item["title"],$item["artist"],$item["price"],$item["image"],$item["description"],$item["keyword"],$item["movie"]);
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
<title>ショッピングカート | Spaghetti Record</title>
<meta name="description" content="ショッピングカートページです。">
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
        <div class="cartPage">
          <h2 class="headingCmn">カートの内容</h2>
          <!-- カートが空でない場合 -->
          <?php if(isset($cartItemsObj)): ?>
            <table class="cartItems">
              <tr>
                <th class="img"></th>
                <th class="title" scope="col">タイトル</th>
                <th class="artist" scope="col">アーティスト</th>
                <th class="price" scope="col">価格</th>
                <th></th>
              </tr>
              <!-- カート内の商品のデータを一つずつ取り出し、商品ごとに表示欄を生成 -->
              <?php foreach($cartItemsObj as $idx=>$cartItemObj): ?>
                <tr>
                  <td><img src="<?php echo h($cartItemObj->getImage()); ?>" alt=""></td>
                  <td><?php echo h($cartItemObj->getTitle()); ?></td>
                  <td><?php echo h($cartItemObj->getArtist()); ?></td>
                  <td><?php echo h(number_format($cartItemObj->getPrice())); ?> 円</td>
                  <td>
                    <!-- 削除ボタンの生成 -->
                    <form action="cart.php" method="post">
                      <input type="hidden" name="deleteIdx" value="<?php echo $idx; ?>">
                      <input class="btnCmn" type="submit" value="削除する">
                    </form>
                  </td>
                </tr>
              <?php endforeach; ?>
            </table>
            <p class="totalPriceCmn">合計金額 <?php echo h(number_format(Items::getTotalPrice())); ?> 円</p>
            <form class="buy" action="cart.php" method="post">
              <input type="hidden" name="action" value="buy">
              <input class="btnCmn btnDangerCmn" type="submit" value="購入する">
            </form>
          <!-- カートが空である場合 -->
          <?php else: ?>
            <p class="resultMsgCmn">カートに商品がありません。</p>
          <?php endif; ?>
          <div class="textRightCmn">
            <a class="underlineCmn" href="<?php echo h($backPage); ?>">買い物を続ける</a>
          </div>
        </div>
      </section>
      <!-- サイドメニュ -->
      <?php require_once("common/sidemenu.html"); ?>
    </div>
  </div>
  <!-- フッタ -->
  <?php require_once("common/footer.html"); ?>
  <!-- 背景色を付けるための要素 -->
  <div class="mask"></div>
</body>
</html>
