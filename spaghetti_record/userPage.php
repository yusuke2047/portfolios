<?php
  session_start();
// ----------------------------------------------------------------------------
// リダイレクトに関する処理
// ----------------------------------------------------------------------------
  // ログインしていない場合、loginページへリダイレクト
  if(!isset($_SESSION["login"])){
    $_SESSION["page"]["toUserPage"] = "userPage.php";
    header("location:login.php");
    exit;
  }
// ----------------------------------------------------------------------------
// ファイル読み込みに関する処理
// ----------------------------------------------------------------------------
  // データベース接続ファイルを読み込み
  require_once("common/dbConnect.php");
  // 関数の入ったファイルの読み込み
  require_once("common/functions.php");
  // 購入履歴に関するクラスファイルの読み込み
  require_once("class/histories.php");
  // カレンダに関するクラスファイルの読み込み
  require_once("class/calendars.php");
// ----------------------------------------------------------------------------
// 変数に関する処理
// ----------------------------------------------------------------------------
  $userId = $_SESSION["login"]["userId"];
  $userName = $_SESSION["login"]["userName"];
// ----------------------------------------------------------------------------
// 操作に関する処理
// ----------------------------------------------------------------------------
// ---- 購入履歴に関する処理 ----
  // 表示する日にちの設定
  // カレンダアイコンがクリック(日にちが選択)された場合、選択された日にちに設定
  // 特に何もしていない場合、強制的にすべての日にちに設定(初期設定)
  $date = (isset($_POST["date"])) ? $_POST["date"] . "%" : "%";
  // 選択した日にちの購入履歴データをデータベースから取得
  $sql = "
    SELECT
      items.title AS title,
      items.artist AS artist,
      items.image AS image,
      histories.date AS date
    FROM histories
    JOIN items
    ON histories.item_id = items.id
    JOIN users
    ON histories.user_id = users.id
    WHERE
      histories.user_id = :userId
    AND
      histories.date LIKE :date
    ORDER BY
      histories.date DESC
  ;";
  $stmt = $dbInfo->prepare($sql);
  $stmt->bindParam(":userId",$userId,PDO::PARAM_INT);
  $stmt->bindParam(":date",$date,PDO::PARAM_STR);
  $stmt->execute();
  $histories = $stmt->fetchAll(PDO::FETCH_ASSOC);

  if(!empty($histories)){
    // 購入履歴のオブジェクトの生成
    foreach($histories as $history){
      $historiesObj[] = new Histories($history["title"],$history["artist"],$history["image"],$history["date"]);
    }
  }

// ---- 日記に関する処理(カレンダの内容の生成) ----
  // 初期表示する年月(現在)の設定
  $year = date("Y");
  $month = date("m");
  // カレンダアイコンがクリック(表示するカレンダの年月が選択)された場合
  // (選択された年月の設定)
  if(isset($_POST["year"])){
    $year = $_POST["year"];
    $month = $_POST["month"];
    // 日記に関する処理がされた場合は、強制的に日記を表示
    // (通常は日記は非表示)
    $_SESSION["diary"] = "show";
  }
  // カレンダの生成
  $calendar = new Calendars($year,$month);
  $calendar = $calendar->createCalendar();

  // 選択された年月の日記のデータを取得
  $sql = "
    SELECT text,day
    FROM diaries
    WHERE year = :year
    AND month = :month
    AND user_id = :userId
    ORDER BY day
  ;";
  $stmt = $dbInfo->prepare($sql);
  $stmt->bindParam(":year",$year,PDO::PARAM_INT);
  $stmt->bindParam(":month",$month,PDO::PARAM_INT);
  $stmt->bindParam(":userId",$userId,PDO::PARAM_INT);
  $stmt->execute();
  $diaries = $stmt->fetchAll(PDO::FETCH_ASSOC);
// ---- 日記に関する処理(投稿、削除) ----
  // 日記の投稿ボタンがクリックされた場合
  if(isset($_POST["action"]) && $_POST["action"] == "createDiary"){
    $diaryText = $_POST["diaryText"];
    $year = $_POST["year"];
    $month = $_POST["month"];
    $day = $_POST["day"];
    // 日記に関する処理がされた場合は、強制的に日記を表示
    // (通常は日記は非表示)
    $_SESSION["diary"] = "show";

    $sql = "INSERT INTO diaries VALUES(\"\",:diaryText,:year,:month,:day,:userId);";
    $stmt = $dbInfo->prepare($sql);
    $stmt->bindParam(":diaryText",$diaryText,PDO::PARAM_STR);
    $stmt->bindParam(":year",$year,PDO::PARAM_INT);
    $stmt->bindParam(":month",$month,PDO::PARAM_INT);
    $stmt->bindParam(":day",$day,PDO::PARAM_INT);
    $stmt->bindParam(":userId",$userId,PDO::PARAM_INT);
    $stmt->execute();
    $createResult = $stmt->rowCount();
    // 当ページを読み込みなおす
    // (リロードによる多重操作を防止のため)
    header("location:userPage.php?createResult={$createResult}#forIconRight");
    exit;
    // 日記の削除ボタンがクリックされた場合
  } elseif(isset($_POST["action"]) && $_POST["action"] == "deleteDiary"){
    $year = $_POST["year"];
    $month = $_POST["month"];
    $day = $_POST["day"];
    // 日記に関する処理がされた場合は、強制的に日記を表示
    // (通常は日記は非表示)
    $_SESSION["diary"] = "show";

    $sql = "
      DELETE FROM diaries
      WHERE year = :year
      AND month = :month
      AND day = :day
      AND user_id = :userId
    ;";
    $stmt = $dbInfo->prepare($sql);
    $stmt->bindParam(":year",$year,PDO::PARAM_INT);
    $stmt->bindParam(":month",$month,PDO::PARAM_INT);
    $stmt->bindParam(":day",$day,PDO::PARAM_INT);
    $stmt->bindParam(":userId",$userId,PDO::PARAM_INT);
    $stmt->execute();
    $deleteResult = $stmt->rowCount();
    // 当ページを読み込みなおす
    // (リロードによる多重操作を防止のため)
    header("location:userPage.php?deleteResult={$deleteResult}#forIconRight");
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
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>ユーザページ | Spaghetti Record</title>
<meta name="description" content="ユーザページです。購入履歴の確認のほか、日記機能もご利用いただけます。">
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
        <div class="userPage">
          <h2 class="headingCmn"><?php echo h($userName); ?> 様のスペース</h2>
          <ul class="iconListCmn">
            <!-- 左アイコン(購入履歴) -->
            <li id="iconLeft">
              <i class="fas fa-history"></i>
            </li>
            <!-- 右アイコン(日記) -->
            <li id="iconRight">
              <i class="fas fa-user-edit"></i>
            </li>
          </ul>
          <div class="textRightCmn">
            <a class="underlineCmn" href="shop.php">トップページへ</a>
          </div>
          <!-- 購入履歴機能 -->
          <!-- 左アイコン(購入履歴)に対応する要素 -->
          <section id="forIconLeft" class="historyBox positionAdjustCmn">
            <h3 class="headingCmn">購入履歴</h3>
            <form class="searchBoxCmn selectDateForm" action="userPage.php?#forIconLeft" method="post">
              <input class="keyword" type="date" name="date" required>
              <input class="btn" type="image" src="images/calendar.png" value="検索">
            </form>
            <!-- 購入履歴がある場合 -->
            <?php if(isset($historiesObj)): ?>
            <table class="confirmTableCmn itemConfirmTableCmn">
              <tr>
                <th scope="col"></th>
                <th scope="col">タイトル</th>
                <th scope="col">アーティスト</th>
                <th scope="col">購入日</th>
              </tr>
              <!-- 選択した日にちに購入した商品のオブジェクトを一つずつ取り出し、テーブルの行要素を生成 -->
              <?php foreach($historiesObj as $historyObj): ?>
              <tr>
                <td><img src="<?php echo h($historyObj->getImage()); ?>" alt=""></td>
                <td><?php echo h($historyObj->getTitle()); ?></td>
                <td><?php echo h($historyObj->getArtist()); ?></td>
                <td><?php echo h($historyObj->getDate()); ?></td>
              </tr>
              <?php endforeach; ?>
            </table>
            <!-- 購入履歴がない場合 -->
            <?php else: ?>
              <p class="resultMsgCmn">購入履歴がありません。</p>
            <?php endif; ?>
          </section>
          <!-- 日記機能 -->
          <!-- 右アイコン(日記)に対応する要素 -->
          <section id="forIconRight" class="positionAdjustCmn hidden">
            <h3 class="headingCmn">日記</h3>
            <!-- 日記を投稿、削除したときのメッセージ -->
            <?php if(isset($_GET["createResult"]) && $_GET["createResult"] == 1): ?>
              <p class="resultMsgCmn">投稿しました。</p>
            <?php elseif(isset($_GET["deleteResult"]) && $_GET["deleteResult"] == 1): ?>
              <p class="resultMsgCmn">削除しました。</p>
            <?php endif; ?>
            <!-- 表示する年月を選択するフォーム -->
            <form class="searchBoxCmn selectDateForm" action="userPage.php#forIconRight" method="post">
              <select class="keyword" name="year" required>
                <option value="">年</option>
                <?php for($i = 2000;$i <= 2100;$i++): ?>
                <option value="<?php echo h($i); ?>">
                  <?php echo h($i); ?>
                </option>
                <?php endfor; ?>
              </select>
              <select class="keyword" name="month" required>
                <option value="">月</option>
                <?php for($i = 1;$i <= 12;$i++): ?>
                <option value="<?php echo h(sprintf("%02d",$i)); ?>">
                  <?php echo h(sprintf("%02d",$i)); ?>
                </option>
                <?php endfor; ?>
              </select>
              <input class="btn" type="image" src="images/calendar.png" value="検索">
            </form>
            <!-- カレンダの要素の生成 -->
            <table class="confirmTableCmn calendar">
              <caption class="calendarTitle"><?php echo h($year) . " 年 " . h($month) . " 月 "; ?></caption>
              <tr>
                <th>日</th>
                <th>月</th>
                <th>火</th>
                <th>水</th>
                <th>木</th>
                <th>金</th>
                <th>土</th>
              </tr>
              <!-- カレンダから週を一つずつ取り出す -->
              <?php foreach($calendar as $week): ?>
                <tr>
                  <!-- 週から日を一つずつ取り出す -->
                  <?php foreach($week as $day): ?>
                    <!-- 取り出した日が当日だった場合 -->
                    <?php if(isset($day[1])): ?>
                      <!-- $day = [日にち,true]の配列になってるので -->
                      <!-- 当日判定部分のtrueは$todayFlagに代入 -->
                      <?php $todayFlag = $day[1]; ?>
                      <!-- 日にち部分はもともとは入っていた変数$dayに代入 -->
                      <?php $day = $day[0]; ?>
                    <?php endif; ?>
                    <!-- その日に書いた日記があるか判定するための変数$writedFlag -->
                    <!-- true : ある -->
                    <!-- false : ない -->
                    <?php $writedFlag = false; ?>
                    <!-- 日記が空でなかった場合 -->
                    <?php if(!empty($diaries)): ?>
                      <!-- 日記から一つずつ取り出す -->
                      <?php foreach($diaries as $diary): ?>
                        <!-- 日記の日にちとカレンダの日にちが一致した場合 -->
                        <!-- (その日に書いた日記がある) -->
                        <?php if($diary["day"] == $day): ?>
                          <?php $writedFlag = true; ?>
                          <?php break; ?>
                        <?php endif; ?>
                      <?php endforeach; ?>
                    <?php endif; ?>
                    <!-- その日に書いた日記がある場合 -->
                    <?php if($writedFlag == true): ?>
                      <!-- 当日だった場合 -->
                      <?php if(isset($todayFlag)): ?>
                        <!-- tdタグに背景色をつけるためのクラスwrited、todayを追加 -->
                        <td class="calendarItem writed today">
                        <?php unset($todayFlag); ?>
                      <!-- 当日以外の場合 -->
                      <?php else: ?>
                        <!-- tdタグに背景色をつけるためのクラスwritedを追加 -->
                        <td class="calendarItem writed">
                      <?php endif; ?>
                      <span class="diaryDay"><?php echo h($day); ?></span>
                      <div class="diaryModal hidden">
                        <!-- 書いた日記と削除ボタンの表示 -->
                        <form class="postFormCmn" action="userPage.php" method="post">
                          <p><?php echo h($year) . "年" . h($month) . "月" . h(sprintf("%02d",$day)) . "日"; ?></p>
                          <textarea name="diaryText" readonly><?php echo h($diary["text"]); ?></textarea>
                          <input type="hidden" name="year" value="<?php echo h($year); ?>">
                          <input type="hidden" name="month" value="<?php echo h($month); ?>">
                          <input type="hidden" name="day" value="<?php echo h($day); ?>">
                          <input type="hidden" name="action" value="deleteDiary">
                          <input class="btnCmn btnDangerCmn" type="submit" value="削除">
                    <!-- その日に書いた日記がない場合 -->
                    <?php else: ?>
                      <!-- 当日だった場合 -->
                      <?php if(isset($todayFlag)): ?>
                        <!-- tdタグに背景色をつけるためのクラスtodayを追加 -->
                        <td class="calendarItem today">
                        <?php unset($todayFlag); ?>
                      <!-- 当日以外の場合 -->
                      <?php else: ?>
                        <td class="calendarItem">
                      <?php endif; ?>
                      <span class="diaryDay"><?php echo h($day); ?></span>
                      <div class="diaryModal hidden">
                        <!-- 日記記入欄と投稿ボタンを表示 -->
                        <form class="postFormCmn" action="userPage.php" method="post">
                          <p><?php echo h($year) . "年" . h($month) . "月" . h(sprintf("%02d",$day)) . "日"; ?></p>
                          <textarea name="diaryText" placeholder="日記を書く。" required></textarea>
                          <input type="hidden" name="year" value="<?php echo h($year); ?>">
                          <input type="hidden" name="month" value="<?php echo h($month); ?>">
                          <input type="hidden" name="day" value="<?php echo h($day); ?>">
                          <input type="hidden" name="action" value="createDiary">
                          <input class="btnCmn" type="submit" value="投稿">
                    <?php endif; ?>
                          <p class="diaryModalHidden">閉じる</p>
                        </form>
                      </div>
                    </td>
                  <?php endforeach; ?>
                </tr>
              <?php endforeach; ?>
            </table>
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
  <!-- 購入履歴や日記などの表示を制御するためのJavaScriptの読み込み -->
  <!-- 日記に関する操作がされた場合 -->
  <?php if(isset($_SESSION["diary"])): ?>
    <!-- 日記を強制的に表示するためのJavaScriptの読み込み -->
    <script src="js/showDiary.js"></script>
    <?php unset($_SESSION["diary"]); ?>
  <?php endif; ?>
  <script src="js/showHiddenByIcon.js"></script>
  <!-- カレンダの日にちをクリックするとその日の日記を表示するためのJavaScriptの読み込み -->
  <script src="js/showDiaryModal.js"></script>
</body>
</html>
