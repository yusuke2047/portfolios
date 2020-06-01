<?php
  class Histories{
    private $title;
    private $artist;
    private $image;
    private $date;

    public function __construct($title,$artist,$image,$date){
      $this->title = $title;
      $this->artist = $artist;
      $this->image = $image;
      $this->date = $date;
    }
    public function getTitle(){
      return $this->title;
    }
    public function getArtist(){
      return $this->artist;
    }
    public function getImage(){
      return $this->image;
    }
    public function getDate(){
      return $this->date;
    }
  }
?>
