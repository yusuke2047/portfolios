<?php
  session_start();
  // ----------------------------------------------------------------------------
  // ファイル読み込みに関する処理
  // ----------------------------------------------------------------------------
  require_once("common/dbConnect.php");
  require_once("common/config.php");
  require_once("common/functions.php");
  // ----------------------------------------------------------------------------
  // 変数に関する処理
  // ----------------------------------------------------------------------------
  // ログイン、サインアップ(新規登録)、キャンセル(退会)に関するエラーメッセージ
  $errMsg = ["login"=>"","signup"=>"","cancel"=>""];
  // ----------------------------------------------------------------------------
  // 操作に関する処理
  // ----------------------------------------------------------------------------
  // レーダチャートのアイコンがクリックされた場合
  if(isset($_POST["action"]) && $_POST["action"] == "radarChart"){
    // データベースからユーザの各ゲームのクリアに必要なBP（クリアポイント）に対する現時点でのBPの割合（％）を取得
    //
    $sql = "
      SELECT
        (janken_bp / :clearPoint) * 100 AS jankenProgress,
        (osero_bp / :clearPoint) * 100 AS oseroProgress,
        (slot_bp / :clearPoint) * 100 AS slotProgress,
        (memory_bp / :clearPoint) * 100 AS memoryProgress,
        (ten_seconds_bp / :clearPoint) * 100 AS tenSecondsProgress
      FROM users
      WHERE id = :userId
    ;";
    $stmt = $dbInfo->prepare($sql);
    // $clearPointはcommon/config.php内で設定
    $stmt->bindParam(":clearPoint",$clearPoint,PDO::PARAM_INT);
    $stmt->bindParam(":userId",$userId,PDO::PARAM_INT);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    // 各ゲームのクリアに必要なBP（クリアポイント）に対する現時点でのBPの割合（％）を０～１００の範囲内に丸める
    foreach($result as $key=>$value){
      $userProgress[$key] = withinRange($value,100,0);
    }
    // JavaScriptに渡すために、変換（レーダチャートを描画するのに使用)
    $userProgressJson = json_encode($userProgress);
    // 強制的にモーダルを表示させるためのJavaScriptを呼び出す変数
    $showModal = "radarChartModal";
    // メッセージのアイコンがクリックされた場合
  } elseif(isset($_POST["action"]) && $_POST["action"] == "userMsg"){
    // データベースからユーザへ送信されたメッセージに関するデータを取得
    $sql = "
      SELECT messages.message,messages.date,users.name
      FROM messages
      JOIN users
      ON messages.send_user_id = users.id
      WHERE receive_user_id = :userId
      ORDER BY date DESC;
    ";
    $stmt = $dbInfo->prepare($sql);
    $stmt->bindParam(":userId",$userId,PDO::PARAM_INT);
    $stmt->execute();
    $userMsgs = $stmt->fetchAll(PDO::FETCH_ASSOC);
    // 強制的にモーダルを表示させるためのJavaScriptを呼び出す変数
    $showModal = "userMsgModal";
    // RANKINGボタンがクリックされた場合
  } elseif(isset($_POST["action"]) && $_POST["action"] == "ranking"){
    // データベースからすべてのユーザのトータルBPなどに関するデータを取得
    $sql = "
      SELECT
        id,
        name,
        (janken_bp + osero_bp + slot_bp + memory_bp + ten_seconds_bp) AS totalBp
      FROM users
      ORDER BY totalBp DESC";
    $stmt = $dbInfo->query($sql);
    $rankingList = $stmt->fetchAll(PDO::FETCH_ASSOC);
    // 強制的にモーダルを表示させるためのJavaScriptを呼び出す変数
    $showModal = "rankingModal";
    // RANKINGモーダル内でメッセージを送信したいユーザ名をクリックした場合
  } elseif(isset($_POST["action"]) && $_POST["action"] == "sendMsg"){
    $receiveUserId = $_POST["receiveUserId"];
    // 強制的にモーダルを表示させるためのJavaScriptを呼び出す変数
    $showModal = "sendMsgModal";
    // SEND MESSAGE（送信）ボタンがクリックされた場合
  } elseif(isset($_POST["action"]) && $_POST["action"] == "sendMsgDo"){
    $receiveUserId = $_POST["receiveUserId"];
    $message = $_POST["message"];
    // データベースへ投稿されたメッセージに関するデータの書き込み
    $sql = "INSERT INTO messages VALUES(\"\",:message,:sendUserId,:receiveUserId,now())";
    $stmt = $dbInfo->prepare($sql);
    $stmt->bindParam(":message",$message,PDO::PARAM_STR);
    $stmt->bindParam(":sendUserId",$userId,PDO::PARAM_INT);
    $stmt->bindParam(":receiveUserId",$receiveUserId,PDO::PARAM_INT);
    $stmt->execute();
    // リロードによる多重操作を防止
    header("location:index.php");
    exit;
    // LOG INボタンがクリックされた場合
  } elseif(isset($_POST["action"]) && $_POST["action"] == "login"){
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
      // (ログインしているかどうかの判定に使用)
      $_SESSION["worldIsMine"]["login"]["userId"] = $user["id"];
      $_SESSION["worldIsMine"]["login"]["userName"] = $user["name"];
      header("location:index.php");
      exit;
    } else{
      $errMsg["login"] = "メールアドレスまたはパスワードが違います。";
      // 強制的にモーダルを表示させるためのJavaScriptを呼び出す変数
      $showModal = "loginModal";
    }
    // SIGN UPボタン（新規登録）がクリックされた場合
  } elseif(isset($_POST["action"]) && $_POST["action"] == "signup"){
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
      $sql = "INSERT INTO users VALUES(\"\",:name,:email,:password,0,0,0,0,0)";
      $stmt = $dbInfo->prepare($sql);
      $stmt->bindParam(":name",$name,PDO::PARAM_STR);
      $stmt->bindParam(":email",$email,PDO::PARAM_STR);
      $stmt->bindParam(":password",$password,PDO::PARAM_STR);
      $stmt->execute();
      // リロードによる多重操作を防止
      header("location:index.php");
      exit;
      // 同じメールアドレスのデータがあった場合
    } else{
      $errMsg["signup"] = "既に登録しています。";
      // 強制的にモーダルを表示させるためのJavaScriptを呼び出す変数
      $showModal = "signupModal";
    }
    // LOG OUTボタンがクリックされた場合
  } elseif(isset($_POST["action"]) && $_POST["action"] == "logout"){
    // セッションの削除
    $_SESSION = [];
    session_destroy();
    header("location:index.php");
    exit;
    // CANCEL（退会）ボタンがクリックされた場合
  } elseif(isset($_POST["action"]) && $_POST["action"] == "cancel"){
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
        DELETE users,messages
        FROM users
        LEFT JOIN messages
        ON users.id = messages.send_user_id
        OR users.id = messages.receive_user_id
        WHERE users.id = :id;
      ";
      $stmt = $dbInfo->prepare($sql);
      $stmt->bindParam(":id",$userId,PDO::PARAM_INT);
      $stmt->execute();
      // セッションの削除
      $_SESSION = [];
      session_destroy();
      // リロードによる多重操作を防止
      header("location:index.php");
      exit;
    }
    // ログイン中のユーザのID、入力したメールアドレス、パスワードと一致したデータがなかった場合
    else{
      $errMsg["cancel"] = "メールアドレス、またはパスワードが違います。";
      // 強制的にモーダルを表示させるためのJavaScriptを呼び出す変数
      $showModal = "cancelModal";
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
<meta name="robots" content="noindex">
<title>TOP PAGE | WORLD IS MINE</title>
<meta name="description" content="ゲームアプリ&quot;WORLD IS MINE&quot;のトップページです。いろいろなゲームを用意していますので、心おきなくお楽しみください。">
<link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="css/styles.css">
</head>
<body class="indexPage">
  <!-- メイン -->
  <main class="mainWrapper">
    <!-- トップ部分 -->
    <header class="header">
      <h1 class="hidden">WORLD IS MINE</h1>
      <!-- ログインしている場合、ユーザ名、各アイコンを表示 -->
      <?php if(isset($userId)): ?>
        <p class="userName"><?php echo h($userName); ?> &#39;s WORLD</p>
        <form class="radarChartForm" action="" method="post">
          <input type="hidden" name="action" value="radarChart">
          <input type="image" src="images/progress.png" value="PROGRESS">
        </form>
        <form class="userMsgForm" action="" method="post">
          <input type="hidden" name="action" value="userMsg">
          <input type="image" src="images/message.png" value="MESSAGE">
        </form>
      <?php endif; ?>
      <!-- $dayStatusはcommon/config.php内で設定 -->
      <?php if($dayStatus == "special"): ?>
        <p class="dayStatus">SPECIAL DAY</p>
      <?php endif; ?>
    </header>
    <!-- ミドル部分 -->
    <section class="games">
      <h2 class="hidden">GAMES</h2>
      <div class="jankenBox">
        <a class="link" href="janken.php">JANKEN</a>
      </div>
      <div class="oseroBox">
        <a class="link" href="osero.php">OSERO</a>
      </div>
      <div class="slotBox">
        <a class="link" href="slot.php">SLOT</a>
      </div>
      <div class="memoryBox">
        <a class="link" href="memory.php">MEMORY</a>
      </div>
      <div class="tenSecondsBox">
        <a class="link" href="tenSeconds.php">TEN SECONDS</a>
      </div>
    </section>
    <!-- ボトム部分 -->
    <nav class="nav">
      <p id="about" class="btnCmn">ABOUT</p>
      <form class="rankingForm" action="" method="post">
        <input type="hidden" name="action" value="ranking">
        <input class="btnCmn" type="submit" value="RANKING">
      </form>
      <!-- ログインしている場合-->
      <?php if(isset($userId)): ?>
        <form class="logoutForm" action="" method="post">
          <input type="hidden" name="action" value="logout">
          <input class="btnCmn" type="submit" value="LOG OUT">
        </form>
        <p id="cancel" class="btnCmn">CANCEL</p>
      <!-- ログインしていない場合 -->
      <?php else: ?>
        <p id="login" class="btnCmn">LOG IN</p>
        <p id="signup" class="btnCmn">SIGN UP</p>
      <?php endif; ?>
    </nav>
  </main>
  <!-- 以下、モーダル要素×８ -->
  <section>
    <h2 class="hidden">MODALS</h2>
    <section id="radarChartModal" class="forAccountModal hidden">
      <div class="modalContents">
        <h3 class="modalTitle">PROGRESS</h3>
        <canvas id="myRadarChart" class="myRadarChart" width＝100 height=100></canvas>
        <p id="closeRadarChartModal" class="btnCmn">CLOSE</p>
      </div>
      <div class="mask"></div>
    </section>
    <section id="userMsgModal" class="forAccountModal hidden">
      <div class="modalContents">
        <h3 class="modalTitle">MESSAGE TO <?php echo h($userName); ?></h3>
        <table class="forAccountTable messageTable">
          <tr>
            <th scope="col">MESSAGE</th>
            <th scope="col">FROM</th>
            <th scope="col">DATE</th>
          </tr>
          <!-- ユーザへ送信されたメッセージのデータが空でない場合 -->
          <?php if(!empty($userMsgs)): ?>
            <?php foreach($userMsgs as $userMsg): ?>
              <tr>
                <td><?php echo h($userMsg["message"]); ?></td>
                <td><?php echo h($userMsg["name"]); ?></td>
                <td><?php echo h($userMsg["date"]); ?></td>
              </tr>
            <?php endforeach; ?>
          <?php endif; ?>
        </table>
        <p id="closeUserMsgModal" class="btnCmn">CLOSE</p>
      </div>
      <div class="mask"></div>
    </section>
    <section id="aboutModal" class="forAccountModal hidden">
      <div class="modalContents">
        <h3 class="modalTitle">ABOUT</h3>
        <h4 class="modalHeading">STORY</h4>
        <p class="modalText">20XX年、地球外生命体の侵略により、地球の人口の99%が消滅した。<br>残ったのは荒れ果てた世界と生存した1%の人間のみ。<br>倫理、道徳、モラルは崩壊し、そこに広がるのは、人類が誕生して間もない頃と何のかわりもない無法地帯だった。<br>己の身は、己で守る。やるかやられるか。この世界で信じられるのは、己のみ。</p>
        <p class="modalText">あなたは、XX都市に送りこまれた。すでに、何者かによって支配されているようだ。<br>支配者Xの独裁社会に、人々の表情からすべての光が失われつつある。このままでは、命の危険にさらされてしまう。<br>The time has come.<br>今こそ、行動を起こす時だ!!あなたは新しい世界をSURVIVEできるか!?</p>
        <h4 class="modalHeading">MISSION</h4>
        <p class="modalText">まずは、ビルに仕掛けられた支配者Xからの数々のGAMEをクリアすることだ。<br>GAMEに勝ってBP（バトルポイント）をため、基準値を超えるとクリアになる。<br>すべてのGAMEをクリアすると・・・平和な世界が戻ってくる・・・<br>かは、あなたの目で確かめてほしい。幸運を祈る!!</p>
        <p id="closeAboutModal" class="btnCmn">CLOSE</p>
      </div>
      <div class="mask"></div>
    </section>
    <section id="rankingModal" class="forAccountModal hidden">
      <div class="modalContents">
        <h3 class="modalTitle">RANKING</h3>
        <table class="forAccountTable rankingTable">
          <tr>
            <th scope="col">RANK</th>
            <th scope="col">NAME</th>
            <th scope="col">TOTAL BP</th>
          </tr>
          <!-- ランキングリストに関するデータが空でない場合 -->
          <?php if(!empty($rankingList)): ?>
            <?php $rankingListCnt = count($rankingList); ?>
            <?php for($i = 0;$i < $rankingListCnt;$i++): ?>
              <tr>
                <?php if($i == 0): ?>
                  <td class="rank">
                    <img src="images/rank1.png" alt="1">
                  </td>
                <?php elseif($i == 1): ?>
                  <td class="rank">
                    <img src="images/rank2.png" alt="2">
                  </td>
                <?php elseif($i == 2): ?>
                  <td class="rank">
                    <img src="images/rank3.png" alt="3">
                  </td>
                <?php else: ?>
                  <td><?php echo h(($i + 1)); ?></td>
                <?php endif; ?>
                <!-- ログインしていて、かつログインしているユーザのデータでない場合-->
                <?php if(isset($userId) && $rankingList[$i]["id"] != $userId): ?>
                  <td class="rankingUserName">
                    <form class="sendMsg" action="" method="post">
                      <input type="hidden" name="action" value="sendMsg">
                      <input type="hidden" name="receiveUserId" value="<?php echo h($rankingList[$i]["id"]); ?>">
                      <input type="submit" value="<?php echo h($rankingList[$i]["name"]); ?>">
                    </form>
                  </td>
                <!-- ログインしていない、またはログインしているユーザのデータの場合-->
                <?php else: ?>
                  <td><?php echo h($rankingList[$i]["name"]); ?></td>
                <?php endif; ?>
                <td><?php echo h(number_format($rankingList[$i]["totalBp"])); ?></td>
              </tr>
            <?php endfor; ?>
          <?php endif; ?>
        </table>
        <p id="closeRankingModal" class="btnCmn">CLOSE</p>
      </div>
      <div class="mask"></div>
    </section>
    <section id="sendMsgModal" class="forAccountModal hidden">
      <div class="modalContents">
        <h3 class="modalTitle">SEND MESSAGE</h3>
        <form class="forAccountForm" action="" method="post">
          <textarea name="message" placeholder="MESSAGE" maxlength="200" required></textarea>
          <input type="hidden" name="action" value="sendMsgDo">
          <input type="hidden" name="receiveUserId" value="<?php echo h($receiveUserId); ?>">
          <input class="btnCmn" type="submit" value="SEND MESSAGE">
          <p id="closeSendMsgModal" class="btnCmn">CLOSE</p>
        </form>
      </div>
      <div class="mask"></div>
    </section>
    <section id="loginModal" class="forAccountModal hidden">
      <div class="modalContents">
        <h3 class="modalTitle">LOG IN</h3>
        <p class="AccountErrMsg"><?php echo h($errMsg["login"]); ?></p>
        <form class="forAccountForm" action="" method="post">
          <input type="email" name="email" placeholder="EMAIL" autocomplete="off" required>
          <input type="password" name="password" placeholder="PASSWORD" required>
          <input type="hidden" name="action" value="login">
          <input class="btnCmn" type="submit" value="LOG IN">
          <p id="closeLoginModal" class="btnCmn">CLOSE</p>
        </form>
      </div>
      <div class="mask"></div>
    </section>
    <sectoin id="signupModal" class="forAccountModal hidden">
      <div class="modalContents">
        <h3 class="modalTitle">SIGN UP</h3>
        <p class="errMsg"><?php echo h($errMsg["signup"]); ?></p>
        <form class="forAccountForm" action="" method="post">
          <input type="text" name="name" placeholder="NAME" maxlength="10" autocomplete="off" required>
          <input type="email" name="email" placeholder="EMAIL" maxlength="50" autocomplete="off" required>
          <input type="password" name="password" placeholder="PASSWORD" maxlength="4" required>
          <input type="hidden" name="action" value="signup">
          <input class="btnCmn" type="submit" value="SIGN UP">
          <p id="closeSignupModal" class="btnCmn">CLOSE</p>
        </form>
      </div>
      <div class="mask"></div>
    </section>
    <section id="cancelModal" class="forAccountModal hidden">
      <div class="modalContents">
        <h3 class="modalTitle">CANCEL</h3>
        <p class="errMsg"><?php echo h($errMsg["cancel"]); ?></p>
        <form class="forAccountForm" action="" method="post">
          <input type="email" name="email" placeholder="EMAIL" autocomplete="off" required>
          <input type="password" name="password" placeholder="PASSWORD" required>
          <input type="hidden" name="action" value="cancel">
          <input class="btnCmn" type="submit" value="CANCEL">
          <p id="closeCancelModal" class="btnCmn">CLOSE</p>
        </form>
      </div>
      <div class="mask"></div>
    </section>
  </section>
  <!-- 以下、JavaScriptファイルの読み込み -->
  <!-- レーダチャート描画のためのJavaScript -->
  <?php if(isset($_POST["action"]) && $_POST["action"] == "radarChart"): ?>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.2/Chart.bundle.js"></script>
    <script>
      // データベースから取り出したユーザのBP（バトルポイント）に関するデータをJavaScriptに渡す
      const userProgress = <?php echo $userProgressJson;?>;
    </script>
    <script src="js/chart.js"></script>
  <?php endif; ?>
  <!-- ログインしているかいないかで、必要なモーダルが異なることによる処理 -->
  <!-- 共通で必要なモーダル -->
  <script src="js/modalsCmn.js"></script>
  <!-- ログインしている場合-->
  <?php if(isset($userId)): ?>
    <script src="js/modals1.js"></script>
  <!-- ログインしていない場合-->
  <?php else: ?>
    <script src="js/modals2.js"></script>
  <?php endif; ?>
  <!-- 強制的にモーダルを表示させるための処理 -->
  <!-- どのモーダルを表示させるかの情報に関する変数がある場合 -->
  <?php if(isset($showModal)): ?>
    <script>
      // どのモーダルを表示させるかの情報に関する変数をJavaScriptに渡す
      const showModal = '<?php echo $showModal; ?>';
    </script>
    <script src="js/showModal.js"></script>
  <?php endif; ?>
</body>
</html>
