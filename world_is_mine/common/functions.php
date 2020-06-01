<?php
  function h($s){
    return htmlspecialchars($s,ENT_QUOTES,"utf-8");
  }
  // 数値を最大$high、最小$lowの範囲内に丸める関数
  function withinRange($value,$high,$low){
    return max(min($value,$high),$low);
  }
?>
