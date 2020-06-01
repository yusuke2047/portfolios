<?php
// ヘッダにユーザ名を表示するための変数
// ログインしている場合はユーザ名、ログインしていない場合はゲストを代入
  $userNameForHeader = (isset($_SESSION["login"])) ? $_SESSION["login"]["userName"] : "ゲスト";
?>
<header class="header">
  <div class="container clearfix">
      <!-- ヘッダ左側 -->
    <div class="headerLeft">
      <h1 class="headerLogo">
        <a href="shop.php">
          <img src="images/headerLogo.png" alt="スパゲッティ レコード">
        </a>
      </h1>
    </div>
    <!-- ヘッダ右側 -->
    <div class="headerRight">
      <ul class="headerList">
        <li class="toUserPage">
          <a href="userPage.php">ようこそ <?php echo h($userNameForHeader); ?> 様</a>
        </li>
        <li class="cart">
          <a href="cart.php">
            <img src="images/cart.png" alt="ショッピングカート">
          </a>
        </li>
        <!-- ログインしている場合 -->
        <?php if(isset($_SESSION["login"])): ?>
          <li class="logout">
            <a href="logout.php?action=logout">ログアウト</a>
          </li>
          <li class="cancel">
            <a href="cancel.php">退会</a>
          </li>
        <!-- ログインしていない場合 -->
        <?php else: ?>
          <li class="login">
            <a href="login.php">ログイン</a>
          </li>
          <li class="signup">
            <a href="signup.php">新規登録</a>
          </li>
        <?php endif; ?>
      </ul>
      <!-- 商品検索フォーム -->
      <form class="searchBoxCmn" action="shop.php" method="get">
        <input class="keyword" type="search" placeholder="商品検索" name="keyword" required>
        <input class="btn" type="image" src="images/search.png" value="検索">
      </form>
    </div>
    <!-- レスポンシブ対応 -->
    <!-- ハンバーガメニュ -->
    <div class="hamburger">
      <img id="hamburgerIcon" class="hamburgerIcon" src="images/hamburger.png" alt="ハンバーガメニュ">
      <ul id="hamburgerList" class="hamburgerList">
        <li>
          <a class="underlineCmn" href="userPage.php"><?php echo h($userNameForHeader); ?> 様</a>
        </li>
        <li id="showSearch">
          <a class="underlineCmn">商品検索</a>
        </li>
        <li class="cart">
          <a class="underlineCmn" href="cart.php">カート</a>
        </li>
        <!-- ログインしている場合 -->
        <?php if(isset($_SESSION["login"])): ?>
          <li class="logout">
            <a class="underlineCmn" href="logout.php?action=logout">ログアウト</a>
          </li>
          <li class="cancel">
            <a class="underlineCmn" href="cancel.php">退会</a>
          </li>
        <!-- ログインしていない場合 -->
        <?php else: ?>
          <li class="login">
            <a class="underlineCmn" href="login.php">ログイン</a>
          </li>
          <li class="signup">
            <a class="underlineCmn" href="signup.php">新規登録</a>
          </li>
        <?php endif; ?>
      </ul>
      <!-- 商品検索フォーム -->
      <div id="searchForHamburger" class="searchForHamburger">
        <form class="searchBoxCmn" action="shop.php" method="get">
          <input class="keyword" type="search" placeholder="商品検索" name="keyword" required>
          <input class="btn" type="image" src="images/search.png" value="検索">
        </form>
        <p id="hiddenSearch" class="underlineCmn hiddenSearch">閉じる</p>
      </div>
    </div>
  </div>
</header>
<!-- ハンバーガメニュの表示を制御するためのJavaScriptファイルの読み込み -->
<script src="js/hamburger.js"></script>
