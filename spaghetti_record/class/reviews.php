<?php
  class Reviews{
    private $reviewId;
    private $text;
    private $userId;
    private $userName;
    
    public function __construct($reviewId,$text,$userId,$userName){
      $this->reviewId = $reviewId;
      $this->text = $text;
      $this->userId = $userId;
      $this->userName = $userName;
    }
    public function getReviewId(){
      return $this->reviewId;
    }
    public function getText(){
      return $this->text;
    }
    public function getUserId(){
      return $this->userId;
    }
    public function getUserName(){
      return $this->userName;
    }
  }
?>
