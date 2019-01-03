<?php

// ajaxove volani PHP

if ($_POST) {
    require_once "DB.php";
    require_once "Kon.php";
    $img = $_POST['image'];
 //   $img = str_replace('data:image/jpeg;base64,', '', $img);
    $img = str_replace(' ', '+', $img);
    //  $img = base64_decode($img);
    $objekt = $_POST["objekt"];
    $poradi = $_POST["poradi"];
    $velikost = strlen($img);
    $nazev = $_POST["nazev"];
    $typ = $_POST["typ"];
    
  
//smazu stary obrazek
    DB::pripoj(Kon::DB_URL, Kon::DB_LOG, Kon::DB_PASS, Kon::DB_NAZEV);
    DB::dotaz("DELETE FROM obr WHERE id_objekt = ? and poradi = ? ", array($objekt, $poradi));
//vlozim novy obrazek
    $success = DB::dotaz("INSERT INTO obr VALUES( ?,?,?,?,?,?,?,? )",
            array("NULL", $objekt, $nazev, $typ, $poradi, $img, 1, $velikost));
        
    print $success ? 'Obrázek '.$nazev.' uložen.' : 'Chyba při ukládání obrázku.';
}
?>