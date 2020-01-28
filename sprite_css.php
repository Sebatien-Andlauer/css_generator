<?php
require_once("css_generator");
if($argc == 1)
return man();
$f = new Sprite();
$f->option();
$f->primary_css_generator($argv[$argc -1]);
function man()
{
  $a = fopen('man.txt','r');
  $mann = fread($a, filesize('man.txt'));
  fclose($a);
  echo $mann. "\n";
}
//fonction scandir pour chercher dans un dossier avec la recursive si l'option
//est selectionne ...
$array_files = [];
function my_scandir($dir, $Recursive = false, &$array_files)
{
    $nom_repository = $dir;
    if(is_dir($dir))
    {
    $scan = opendir($dir);
    while (false !== ($files = readdir($scan)))
    {
        if(($files != '.') && ($files != '..'))
        {
        if ($Recursive == true && is_dir($nom_repository.'/'.$files))
            {
                my_scandir($nom_repository.'/'.$files,true, $array_files);
            }
        else if (!is_dir($nom_repository.'/'.$files) &&
         @exif_imagetype("$nom_repository/$files") === IMAGETYPE_PNG)
            {
              $array_files[] = $nom_repository.'/'.$files;
            }
          }
    }
    closedir($scan);
  }
  return $array_files;
}

//creation de la fonction qui vas prendre les parametre de taille de mes images
//et qui vas creer le fichier d'aceuil et copier les images de dans le ficher...

function my_merge_image(&$array_files, $dest)
{
  $pos = 0;
  $maxwidth = 0;
  $maxheight = 0;
  foreach($array_files as $value){
    $imagesize = getimagesize($value);
    if($imagesize[1]>$maxheight)
      $maxheight = $imagesize[1];
      $maxwidth += $imagesize[0];
  }
  $image = imagecreatetruecolor($maxwidth, $maxheight);
  $background = imagecolorallocatealpha($image, 255, 255, 255, 127);
  imagefill($image, 0, 0, $background);
  imagesavealpha($image, true);
  foreach ($array_files as $file) {
    $tmp = imagecreatefrompng($file);
    imagecopy($image, $tmp, $pos, 0, 0, 0, imagesx($tmp), imagesy($tmp));
    $pos += imagesx($tmp);
    imagedestroy($tmp);
    }
    imagepng($image, $dest);
}

// creation de la fonction qui vas creer la feuille de style

function css_generator(&$array_files, $file, $dest)
{
$position = 0;
  $html="<!DOCTYPE HTML>\n\n<html>\n\t\t<head>\n\t\t";
  $html.="<title>CSS-Generator</title>\n";
  $html .= "<link rel=\"stylesheet\" href=".$file.">\n\n</head>\n\n<body>\n\t\t";
  $css = ".sprite {\nbackground-image: url(".$dest.");\n";
  $css .= "background-repeat: no-repeat;\ndisplay: inline-block;\n}";
  foreach($array_files as $value)
  {
    if(is_file($value)){
    $size = getimagesize($value);
    $name = rtrim(trim(str_replace("/", "_", $value), "."), ".png");
    $html .= "<i class=\"sprite sprite-$name\"></i>\n\t\t";
    $css .= "\n.sprite-$name{\nwidth: ".$size[0]. "px;\nheight: ".$size[1]."px;\n";
    $css .= "background-position: -".$position."px 0px;\n}";
    $position += $size[0];
    }
  }
  $html .= "\n</body>\n</html>";
 fwrite(fopen("css_gen.html", 'w'), $html);
 fwrite(fopen($file, 'w'), $css);
}
?>
