<?php
  session_start();
// ----------------------------------------------------------------------------
// リダイレクトに関する処理
// ----------------------------------------------------------------------------
  // 直接当ページに来た場合は、shopページへリダイレクト
  // (当ページには、shopページから商品を選択した場合のみ来れる)
  if(!isset($_GET["itemId"])){
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
  // 商品に関するクラスファイルの読み込み
  require_once("class/items.php");
  // レビュに関するクラスファイルの読み込み
  require_once("class/reviews.php");
// ----------------------------------------------------------------------------
// 変数に関する処理
// ----------------------------------------------------------------------------
  // --------------------------------------------------------------------------
  // shopページから選択した商品のIDと検索ワードとページ番号を受信
  // --------------------------------------------------------------------------
  // $itemId(商品のID) : 当ページで商品を表示するためのデータ取得に使用
  $itemId = $_GET["itemId"];

  if(isset($_GET["keyword"])){
    // $keyword(キーワード) : shopページへ元の状態で戻るために使用
    $keyword = $_GET["keyword"];
    // $itemShowPage(ページ番号) : shopページへ元の状態で戻るために使用
    $itemShowPage = $_GET["itemShowPage"];
    // セッション内にshopページ元の状態のURLを格納
    $_SESSION["page"]["toShop"] = "shop.php?keyword={$keyword}&itemShowPage={$itemShowPage}";
  }
  $backPage = $_SESSION["page"]["toShop"];
  // ログインしている場合はユーザID、ログインしていない場合はnullを$userIdに代入
  $userId = (isset($_SESSION["login"])) ? $_SESSION["login"]["userId"] : null;
  // レビュの本文を代入するのに使用
  $myReview = "";
// ----------------------------------------------------------------------------
// 操作に関する処理
// ----------------------------------------------------------------------------
  // --------------------------------------------------------------------------
  // レビュの投稿処理
  // --------------------------------------------------------------------------
  // レビュー投稿ボタンがクリックされた場合
  if(isset($_POST["action"]) && $_POST["action"] == "createReview"){
    // ログインしている場合
    if(isset($userId)){
      $myReview = $_POST["myReview"];
      $sql = "INSERT INTO reviews VALUES(\"\",:myReview,:itemId,:userId);";
      $stmt = $dbInfo->prepare($sql);
      $stmt->bindParam(":myReview",$myReview,PDO::PARAM_STR);
      $stmt->bindParam(":itemId",$itemId,PDO::PARAM_INT);
      $stmt->bindParam(":userId",$userId,PDO::PARAM_INT);
      $stmt->execute();
      $createResult = $stmt->rowCount();
      // 当ページを読み込みなおす
      // (リロードによる多重操作を防止のため)
      header("location:itemDetails.php?itemId={$itemId}&createResult={$createResult}#forIconLeft");
      exit;
      // ログインしていない場合
    } else{
      // ログイン後、当ページ元の状態に戻って来れるよう設定
      $_SESSION["page"]["toReview"] = "itemDetails.php?itemId={$itemId}#forIconLeft";
      // loginページへリダイレクト
      header("location:login.php");
      exit;
    }
    // --------------------------------------------------------------------------
    // レビュの削除処理
    // --------------------------------------------------------------------------
    // 削除アイコンがクリックされた場合
  } elseif(isset($_GET["action"]) && $_GET["action"] == "deleteReview"){
    $deleteId = $_GET["deleteId"];

    $sql = "DELETE FROM reviews WHERE id = :deleteId";
    $stmt = $dbInfo->prepare($sql);
    $stmt->bindParam(":deleteId",$deleteId,PDO::PARAM_INT);
    $stmt->execute();
    $deleteResult = $stmt->rowCount();
    // 当ページを読み込みなおす
    // (リロードによる多重操作を防止のため)
    header("location:itemDetails.php?itemId={$itemId}&deleteResult={$deleteResult}#forIconLeft");
    exit;
    // --------------------------------------------------------------------------
    // レビュの更新処理
    // --------------------------------------------------------------------------
  } else{
    // 更新アイコンがクリックされた場合(レビュ本文の編集)
    if(isset($_GET["action"]) && $_GET["action"] == "updateReview" ){
      $myReview = $_GET["myReview"];
      $updateId = $_GET["updateId"];
      // 更新ボタンがクリックされた場合(更新の実行)
    } elseif(isset($_POST["action"]) && $_POST["action"] == "updateDoReview"){
      $myReview = $_POST["myReview"];
      $updateId = $_POST["updateId"];

      $sql = "UPDATE reviews SET text = :myReview WHERE id = :updateId";
      $stmt = $dbInfo->prepare($sql);
      $stmt->bindParam(":myReview",$myReview,PDO::PARAM_STR);
      $stmt->bindParam(":updateId",$updateId,PDO::PARAM_INT);
      $stmt->execute();
      $updateResult = $stmt->rowCount();
      // // 当ページを読み込みなおす
      // // (リロードによる多重操作を防止のため)
      header("location:itemDetails.php?itemId={$itemId}&updateResult={$updateResult}#forIconLeft");
      exit;
    }
  }
// ----------------------------------------------------------------------------
// データベースに関する処理
// ----------------------------------------------------------------------------
  // shopページで選択した商品のデータをデータベースから取得
  $sql = "SELECT * FROM items WHERE id = :itemId";
  $stmt = $dbInfo->prepare($sql);
  $stmt->bindParam(":itemId",$itemId,PDO::PARAM_INT);
  $stmt->execute();
  $item = $stmt->fetch(PDO::FETCH_ASSOC);
  // 商品のオブジェクトの生成
  $itemObj = new Items($item["id"],$item["title"],$item["artist"],$item["price"],$item["image"],$item["description"],$item["keyword"],$item["movie"]);
  // --------------------------------------------------------------------------
  // shopページで選択した商品に対応するレビュのデータをデータベースから取得
  // --------------------------------------------------------------------------
  // ページネーションの生成
  // 総レビュ数をカウント
  $sql = "
    SELECT count(*) AS count
    FROM reviews
    WHERE item_id = :itemId
  ;";
  $stmt = $dbInfo->prepare($sql);
  $stmt->bindParam(":itemId",$itemId,PDO::PARAM_INT);
  $stmt->execute();
  $result = $stmt->fetch(PDO::FETCH_ASSOC);
  $count = $result["count"];
  // レビュがある場合
  if($count > 0){
    // 一度に表示させるレビュ数を設定
    $reviewNum = 3;
    // 総ページ数
    $maxPage = ceil($count / $reviewNum);
    // ページ番号がクリックされた場合はそのページ番号、時に何もしていなければ 1 (初期値)を代入
    $reviewShowPage = (isset($_GET["reviewShowPage"])) ? $_GET["reviewShowPage"] : 1;
    // 何番目のレビュからデータを取得するか
    // 例：表示させるレビュ数が"5"、ページ番号が"2"の場合
    // (2-1) * 5 = 5
    // となり、データ取得する際のオフセットの値は"5"となる(6番目のレビュからデータを取得)
    $pageStart = ($reviewShowPage-1) * $reviewNum;
    // レビュのデータを取得
    // (クリックされたページ番号分だけ)
    $sql = "
      SELECT reviews.id AS reviewId,reviews.text AS text,reviews.user_id AS userId,users.name AS userName
      FROM reviews
      JOIN items
      ON reviews.item_id = items.id
      JOIN users
      ON reviews.user_id = users.id
      WHERE items.id = :itemId
      ORDER BY reviews.id DESC
      LIMIT :itemNum
      OFFSET :pageStart
    ;";
    $stmt = $dbInfo->prepare($sql);
    $stmt->bindParam(":itemId",$itemId,PDO::PARAM_INT);
    $stmt->bindParam(":itemNum",$reviewNum,PDO::PARAM_INT);
    $stmt->bindParam(":pageStart",$pageStart,PDO::PARAM_INT);
    $stmt->execute();
    $reviews = $stmt->fetchAll(PDO::FETCH_ASSOC);
    // レビュのオブジェクトの生成
    foreach($reviews as $review){
      $reviewsObj[] = new Reviews($review["reviewId"],$review["text"],$review["userId"],$review["userName"]);
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
<title>商品詳細 | Spaghetti Record</title>
<meta name="description" content="商品詳細ページです。レビュや動画も載せていますので、ぜひご参考ください。">
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css">
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
        <div class="itemDetailsPage">
          <h2 class="headingCmn">商品詳細</h2>
          <img class="itemImg" src="<?php echo h($itemObj->getImage()); ?>" alt="">
          <dl class="itemDetailsList">
            <dt>タイトル</dt>
            <dd><?php echo h($itemObj->getTitle()); ?></dd>
            <dt>アーティスト</dt>
            <dd><?php echo h($itemObj->getArtist()); ?></dd>
            <dt>価格</dt>
            <dd><?php echo h(number_format($itemObj->getPrice())); ?> 円</dd>
          </dl>
          <p class="itemDescription"><?php echo h($itemObj->getDescription()); ?></p>
          <!-- カートに追加機能 -->
          <form class="addItemForm" action="cart.php" method="post">
            <!-- cartページに追加する商品のIDを送信 -->
            <input type="hidden" name="itemId" value="<?php echo h($itemId); ?>">
            <input class="btnCmn" type="submit" value="カートに追加">
          </form>
          <!-- 戻る機能 -->
          <a class="underlineCmn" href="<?php echo h($backPage); ?>">戻る</a>
          <ul class="iconListCmn">
            <!-- 左アイコン(レビュ) -->
            <li>
              <i id="iconLeft" class="far fa-comment-dots"></i>
            </li>
            <!-- 右アイコン(Youtube) -->
            <li>
              <i id="iconRight" class="fab fa-youtube"></i>
            </li>
          </ul>
          <!-- レビュ機能 -->
          <!-- 左アイコン(レビュ)に対応する要素 -->
          <section id="forIconLeft" class="positionAdjustCmn">
            <h3>レビュー</h3>
            <?php if(isset($_GET["createResult"]) && $_GET["createResult"] == 1): ?>
              <p class="resultMsgCmn">投稿しました。</p>
            <?php elseif(isset($_GET["updateResult"]) && $_GET["updateResult"] == 1): ?>
              <p class="resultMsgCmn">更新しました。</p>
            <?php elseif(isset($_GET["deleteResult"]) && $_GET["deleteResult"] == 1): ?>
              <p class="resultMsgCmn">削除しました。</p>
            <?php endif; ?>
            <div class="reviews">
            <!-- レビュがある場合 -->
            <?php if(isset($reviewsObj)): ?>
              <?php foreach($reviewsObj as $reviewObj): ?>
                <div class="review">
                  <p class="userName"><?php echo h($reviewObj->getUserName()); ?></p>
                  <p class="text"><?php echo h($reviewObj->getText()); ?></p>
                  <!-- ログインしている、かつレビュのユーザIDがログインしているユーザIDと一致した場合 -->
                  <!-- ログインしているユーザのレビュにのみ更新、削除アイコンを表示 -->
                  <?php if (isset($userId) && $reviewObj->getUserId() == $userId): ?>
                    <ul class="iconForReview">
                      <li>
                        <a href="itemDetails.php?itemId=<?php echo h($itemId); ?>&action=updateReview&updateId=<?php echo h($reviewObj->getReviewId()); ?>&myReview=<?php echo h($reviewObj->getText()); ?>#forIconLeft">
                          <img src="images/update.png" alt="更新">
                        </a>
                      </li>
                      <li>
                        <a href="itemDetails.php?&itemId=<?php echo h($itemId); ?>&action=deleteReview&deleteId=<?php echo h($reviewObj->getReviewId()); ?>#forIconLeft">
                          <img src="images/delete.png" alt="削除">
                        </a>
                      </li>
                    </ul>
                  <?php endif; ?>
                </div>
              <?php endforeach; ?>
              <!-- ページネーションの生成 -->
              <div class="pagenationCmn">
              <?php $pageNum = 1; ?>
              <?php for($i = 1;$i <= $maxPage;$i++): ?>
                <a class="pageNum" href="itemDetails.php?itemId=<?php echo h($itemId); ?>&reviewShowPage=<?php echo h($pageNum); ?>#forIconLeft"><?php echo h($pageNum); ?></a>
                <?php $pageNum++; ?>
              <?php endfor; ?>
              </div>
            <?php endif; ?>
              <!-- レビュ書き込みフォーム -->
              <form class="postFormCmn" action="itemDetails.php?itemId=<?php echo h($itemId); ?>#forIconLeft" method="post">
                <textarea type="text" name="myReview" maxlength="200" placeholder="レビューを書く。" required><?php echo h($myReview); ?></textarea>
                <?php if(isset($_GET["action"]) && $_GET["action"] == "updateReview"): ?>
                  <input type="hidden" name="action" value="updateDoReview">
                  <input type="hidden" name="updateId" value="<?php echo h($updateId); ?>">
                  <input class="btnCmn btnWarningCmn" type="submit" value="更新">
                <?php else: ?>
                  <input type="hidden" name="action" value="createReview">
                  <input class="btnCmn" type="submit" value="投稿">
                <?php endif; ?>
              </form>
            </div>
          </section>
          <!-- Youtube機能 -->
          <!-- 右アイコン(Youtube)に対応する要素 -->
          <!-- アイコンのクリックで表示on、off -->
          <section id="forIconRight" class="youtubeBox  hidden">
            <h3>YouTube</h3>
            <iframe class="youtube" width="320" height="240" src="<?php echo h($item["movie"]); ?>" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
          </section>
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
  <!-- レビュやYoutubeの表示を制御するためのJavaScriptファイルの読み込み -->
  <script src="js/showHiddenByIcon.js"></script>
</body>
</html>
