<?php
include("css_generator.php");
class Sprite
{
  //Recupere mes options de commande pour simplifier la tache plus tard
    public $opts = array();
    public $dest = "sprite.png";
    public $file = "style.css";
  function option()
  {
    $shortopts = "";
    $shortopts .= "r";
    $shortopts .= "i:";
    $shortopts .= "s:";

    $longopts = array(
      "recursive",
      "output-image:",
      "output-style:");
      $this->opts = getopt($shortopts, $longopts);
  }
  //fonction principale qui lie toutes mes fonction entre elles
  function primary_css_generator($dir)
  {
    if(isset($this->opts['r']) || isset($this->opts["recursive"])){
        my_scandir($dir, true, $array_files);}
    else{
        my_scandir($dir, false, $array_files);}
    if(isset($this->opts['i'])){
      $this->dest = $this->opts['i'];
        my_merge_image($array_files, $this->dest);}
    elseif(isset($this->opts["output-image"])){
      $this->dest = $this->opts["output-image"];
      my_merge_image($array_files, $this->dest);}
    else{
        my_merge_image($array_files, $this->dest);}
    if(isset($this->opts['s'])){
      $this->file = $this->opts['s'];
      css_generator($array_files, $this->file, $this->dest);}
    elseif(isset($this->opts["output-style"])){
      $this->file = $this->opts["output-style"];
      css_generator($array_files, $this->file, $this->dest);}
  else{
      css_generator($array_files, $this->file, $this->dest);}
  }
}
 ?>
