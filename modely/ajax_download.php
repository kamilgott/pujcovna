<?php

// ajaxove volani PHP

if ($_POST) {
    require_once "DB.php";
    require_once "Kon.php";
  
    DB::pripoj(Kon::DB_URL, Kon::DB_LOG, Kon::DB_PASS, Kon::DB_NAZEV);
    
//nahraji obrazek
    //$img = DB::dotazSamotny("SELECT bdata FROM obr where id_objekt= ? and poradi=1 ", array( $_POST["objekt_id"]));
    
    //print $img;
}
?>