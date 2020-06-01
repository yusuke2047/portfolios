<?php
  // エラーメッセージを非表示
  ini_set("display_errors",0);
  // ----------------------------------------------------------------------------
  // 変数に関する設定
  // ----------------------------------------------------------------------------
  $userName = "GUEST";
  $finalResultMsgs = [
    "win"=>"YOU ARE WINNER!!!!",
    "lose"=>"YOU ARE LOSER!!!!",
    "draw"=>"DRAW!!!!"
  ];
  // ログインしている場合
  if(isset($_SESSION["worldIsMine"]["login"])){
    $userId = $_SESSION["worldIsMine"]["login"]["userId"];
    $userName = $_SESSION["worldIsMine"]["login"]["userName"];
  }
  // ----------------------------------------------------------------------------
  // BPに関する設定
  // ----------------------------------------------------------------------------
  // 各ゲームで一回につきもらえるBPの最高値
  $maxBp = 50;
  // 各ゲームでクリアするために必要なBP
  $clearPoint = 1000;
  // $dayStatus = "special" 、$rate = 2 だった場合、その日のBPは2倍になる
  // 水曜日だった場合
  if(date(w) == 3){
    $dayStatus = "special";
    $rate = 2;
  } else{
    $dayStatus = "normal";
  }
?>
