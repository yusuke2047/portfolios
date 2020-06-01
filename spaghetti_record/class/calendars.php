<?php
  class Calendars{
    private $year;
    private $month;

    public function __construct($year,$month){
      $this->year = $year;
      $this->month = $month;
    }
    public function createCalendar(){
      $endOfMonth = date("t",strtotime($this->year . $this->month . "01"));         // 月末日
      $firstWeek = date("w",strtotime($this->year . $this->month . "01"));          // 1日の曜日
      $lastWeek = date("w",strtotime($this->year . $this->month . $endOfMonth));    // 月末日の曜日
      // 1日より前の穴埋め
      $j = 0;
      for($i = 0;$i < $firstWeek;$i++){
        $calendar[$j][] = "";
      }
      // 1日から月末日までを埋める
      for($i = 1;$i <= $endOfMonth;$i++){
        if(isset($calendar[$j]) && count($calendar[$j]) == 7){
          $j++;
        }
        // 当日の場合、当日であることを示すtrueも一緒に格納
        // (カレンダで当日だけ背景に色を付けるため)
        if(date("Ymd") == $this->year . $this->month . sprintf("%02d",$i)){
          $calendar[$j][] = [$i,true];
          // 当日でない場合、日にちだけを格納
        } else {
          $calendar[$j][] = $i;
        }
      }
      // 月末日より後の穴埋め
      for($i = count($calendar[$j]);$i < 7;$i++){
        $calendar[$j][] = "";
      }
      return $calendar;
    }
  }
?>
