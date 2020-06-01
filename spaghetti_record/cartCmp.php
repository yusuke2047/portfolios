<?php
  session_start();
// ----------------------------------------------------------------------------
// リダイレクトに関する処理
// ----------------------------------------------------------------------------
  // cartページから以外はshopページへリダイレクト
  if(!isset($_SESSION["page"]["fromCart"])){
    header("location:shop.php");
    exit;
  }
  // cartページから来たというデータは不要なので削除
  // (リダイレクト処理のために使用)
  unset($_SESSION["page"]["fromCart"]);
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
  $cart = $_SESSION["cart"];
  // セッション内のカートデータは$cartに代入し、不要なので削除
  unset($_SESSION["cart"]);
// ----------------------------------------------------------------------------
// データベースに関する処理
// ----------------------------------------------------------------------------
  // カートから一つずつ商品のIDを取り出し、
  // 商品のIDに対応した商品のデータを配列cartItemsに格納
  foreach($cart as $itemId){
    $sql = "SELECT * FROM items WHERE id = :id";
    $stmt = $dbInfo->prepare($sql);
    $stmt->bindParam(":id",$itemId,PDO::PARAM_INT);
    $stmt->execute();
    $item = $stmt->fetch(PDO::FETCH_ASSOC);
    // 商品のオブジェクトの生成
    $cartItemsObj[] = new Items($item["id"],$item["title"],$item["artist"],$item["price"],$item["image"],$item["description"],$item["keyword"],$item["movie"]);
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
<title>購入の完了 | Spaghetti Record</title>
<meta name="description" content="購入の完了ページです。">
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
        <div class="cartCmpPage">
          <h2 class="headingCmn">ご購入ありがとうございます！</h2>
          <p class="resultMsgCmn">以下の内容で注文を受けつけました。</p>
          <table class="confirmTableCmn itemConfirmTableCmn">
            <tr>
              <th></th>
              <th scope="col">タイトル</th>
              <th scope="col">アーティスト</th>
              <th scope="col">価格</th>
            </tr>
            <!-- 購入した商品データを一つずつ取り出し、商品ごとに表示欄を生成 -->
            <?php foreach($cartItemsObj as $cartItemObj): ?>
              <tr>
                <td><img src="<?php echo h($cartItemObj->getImage()); ?>" alt=""></td>
                <td><?php echo h($cartItemObj->getTitle()); ?></td>
                <td><?php echo h($cartItemObj->getArtist()); ?></td>
                <td><?php echo h(number_format($cartItemObj->getPrice())); ?>円</td>
              </tr>
            <?php endforeach; ?>
          </table>
          <p class="totalPriceCmn">合計金額 <?php echo h(number_format(Items::getTotalPrice())); ?> 円</p>
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
  <?php require_once("common/footer.html"); ?>
  <!-- 背景色を付けるための要素 -->
  <div class="mask"></div>
</body>
</html>
