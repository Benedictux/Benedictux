<?php
// src/AppBundle/Utils/Scanner.php

namespace AppBundle\Utils;

class Scanner
{
  public function scanDirectory($directory)
  {
    $entites = array();
    $nb_entite = 0;

    $contenu = scandir($directory);
    foreach( $contenu as $entite ){
      if($entite != '.' AND $entite != '..'){
          $nb_entite++; // On incrémente le compteur de 1
          $entites[] = $entite;
      }
      //if( is_dir($fichier) ){
      //  switch($fichier) { case '.': case '..': continue; default: /* ne rien faire */}
      //  $entites[] = $fichier;
      //}
    }
    return $entites;
  }

  public function scanDir($directory)
  {  
    $iterator = new FilesystemIterator($directory, FilesystemIterator::SKIP_DOTS);

    return $iterator;
  }
}
