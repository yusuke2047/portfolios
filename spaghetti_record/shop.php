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
  // 他のページから当ページへ元の状態で戻るためのデータの削除
  // (当ページに来た時点で不要になるため)
  unset($_SESSION["page"]["toShop"]);
// ----------------------------------------------------------------------------
// 操作に関する処理
// ----------------------------------------------------------------------------
  // 検索ボタン、または商品カテゴリをクリックした場合、送信された内容をキーワードに設定
  // 特に何もしていない場合、強制的に新着商品をキーワードに設定(初期設定)
  $keyword = (isset($_GET["keyword"])) ? $_GET["keyword"] : "新着商品";
// ----------------------------------------------------------------------------
// データベースに関する処理
// ----------------------------------------------------------------------------
  // ページネーションの生成
  // キーワードに対応する商品の総数をカウント
  $sql = "SELECT count(*) AS count FROM items WHERE FIND_IN_SET(:keyword,keyword)";
  $stmt = $dbInfo->prepare($sql);
  $stmt->bindParam(":keyword",$keyword,PDO::PARAM_STR);
  $stmt->execute();
  $result = $stmt->fetch(PDO::FETCH_ASSOC);
  $count = $result["count"];
  if($count > 0){
    // 一度に表示させる商品数を設定
    $itemNum = 5;
    // 総ページ数
    $maxPage = ceil($count / $itemNum);
    // 表示するページの設定
    // ページ番号がクリックされた場合、そのページ番号に設定
    // 特に何もしていない場合、強制的にページ番号を"1"に設定(初期設定)
    $itemShowPage = (isset($_GET["itemShowPage"])) ? $_GET["itemShowPage"] : 1;
    // 何番目のレビュからデータを取得するか
    // 例：表示させるレビュ数が"5"、ページ番号が"2"の場合
    // (2-1) * 5 = 5
    // となり、データ取得する際のオフセットの値は"5"となる(6番目のレビュからデータを取得)
    $pageStart = ($itemShowPage - 1) * $itemNum;
    // キーワードに対応した商品のデータをデータベースから取得
    // (クリックされたページ番号分だけ)
    $sql = "
    SELECT *
    FROM items
    WHERE FIND_IN_SET(:keyword,keyword)
    ORDER BY id DESC
    LIMIT :itemNum
    OFFSET :pageStart
    ;";
    $stmt = $dbInfo->prepare($sql);
    $stmt->bindParam(":keyword",$keyword,PDO::PARAM_STR);
    $stmt->bindParam(":itemNum",$itemNum,PDO::PARAM_INT);
    $stmt->bindParam(":pageStart",$pageStart,PDO::PARAM_INT);
    $stmt->execute();
    $items = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if(!empty($items)){
      // 商品のオブジェクトの生成
      foreach($items as $item){
        $itemsObj[] = new Items($item["id"],$item["title"],$item["artist"],$item["price"],$item["image"],$item["description"],$item["keyword"],$item["movie"]);
      }
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
<title>商品検索 | Spaghetti Record</title>
<meta name="description" content="商品検索ページです。">
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
        <div class="shopPage">
          <h2 class="headingCmn">商品一覧</h2>
          <!-- 検索した商品がある場合 -->
          <?php if(isset($itemsObj)): ?>
            <!-- キーワードが新着商品だった場合 -->
            <?php if($keyword == "新着商品"): ?>
              <h3 class="resultMsgCmn"><?php echo h($keyword); ?></h3>
            <?php endif; ?>
            <!-- キーワードに対応する商品データを一つずつ取り出し、商品ごとに表示欄を生成 -->
            <?php foreach($itemsObj as $itemObj): ?>
              <div class="shopItem">
                <a class="clearfix" href="itemDetails.php?itemId=<?php echo h($itemObj->getId()); ?>&keyword=<?php echo h($keyword); ?>&itemShowPage=<?php echo h($itemShowPage); ?>">
                  <img src="<?php echo h($itemObj->getImage()); ?>" alt="">
                  <div class="contents">
                    <h3 class="title"><?php echo h(mb_strimwidth($itemObj->getTitle(),0,26,"...")); ?></h3>
                    <p class="artist"><?php echo h(mb_strimwidth($itemObj->getArtist(),0,26,"...")); ?></p>
                  </div>
                </a>
              </div>
            <?php endforeach; ?>
            <!-- ページネーションの生成 -->
            <div class="pagenationCmn">
            <?php $pageNum = 1; ?>
            <?php for($i = 1;$i <= $maxPage;$i++): ?>
              <a class="pageNum" href="shop.php?keyword=<?php echo h($keyword); ?>&itemShowPage=<?php echo h($pageNum); ?>"><?php echo h($pageNum); ?></a>
              <?php $pageNum++; ?>
            <?php endfor; ?>
            </div>
          <!-- 検索した商品がない場合 -->
          <?php else: ?>
            <p class="resultMsgCmn">該当する商品がありません。</p>
          <?php endif; ?>
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
