<?php
  try{
    $dsn = "mysql:dbname=cd_shop;host=localhost;charset=utf8";
    $user = "root";
    $password = "";
    // $dsn = "mysql:dbname=yusuke2047_shop;host=mysql1.php.xdomain.ne.jp;charset=utf8";
    // $user = "yusuke2047_mysql";
    // $password = "yusuke2047";

    $dbInfo = new PDO($dsn,$user,$password);
  } catch(PDOException $e){
    echo "データベースの接続に失敗しました。";
    exit;
  }
 ?>
