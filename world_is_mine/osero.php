<?php
  session_start();

  require_once("common/dbConnect.php");
  require_once("common/functions.php");


  $dbInfo = null;
?>
<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="utf-8">
<meta name="robots" content="noindex">
<title>OSERO | WORLD IS MINE</title>
<meta name="description" content="オセロをプレイできます。">
<link rel="stylesheet" href="css/styles.css">
</head>
<body class="oseroPage">
  <main class="mainWrapper">
    <a class="btnCmn" href="index.php">TOP PAGE</a>
  </main>
  <div class="mask"></div>
</body>
</html>
