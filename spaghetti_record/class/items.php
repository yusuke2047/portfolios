<?php
  class Items{
    private static $totalPrice = 0;
    private $id;
    private $title;
    private $artist;
    private $price;
    private $image;
    private $description;
    private $keyword;
    private $movie;

    public function __construct($id,$title,$artist,$price,$image,$description,$keyword,$movie){
      $this->id = $id;
      $this->title = $title;
      $this->artist = $artist;
      $this->price = $price;
      $this->image = $image;
      $this->description = $description;
      $this->keyword = $keyword;
      $this->movie = $movie;
      
      self::$totalPrice += $price;
    }
    public static function getTotalPrice(){
      return self::$totalPrice;
    }
    public function getId(){
      return $this->id;
    }
    public function getTitle(){
      return $this->title;
    }
    public function getArtist(){
      return $this->artist;
    }
    public function getPrice(){
      return $this->price;
    }
    public function getImage(){
      return $this->image;
    }
    public function getDescription(){
      return $this->description;
    }
    public function getKeyword(){
      return $this->keyword;
    }
    public function getMovie(){
      return $this->movie;
    }
  }
?>
