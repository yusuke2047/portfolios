<?php
  session_start();
// ----------------------------------------------------------------------------
// 操作に関する処理
// ----------------------------------------------------------------------------
  // ログアウトがクリックされた場合
  if(isset($_GET["action"]) && $_GET["action"] == "logout"){
    // セッションの削除
    $_SESSION = [];
    session_destroy();
  }
  header("location:shop.php");
  exit;

 ?>