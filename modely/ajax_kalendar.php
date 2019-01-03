<?php

require_once "DB.php";
require_once "Kon.php";

// ajaxove volani PHP
$akce = (isset($_GET["akce"]) ? $_GET["akce"] : "");
if ($akce == "zadano") {
//budu mazat zaznam o obsazeni v kalendari     
    $objekt = $_GET["objekt"];
    $datum = isset($_GET["datum"]) ? $_GET["datum"] : "0000-00-00";
    DB::pripoj(Kon::DB_URL, Kon::DB_LOG, Kon::DB_PASS, Kon::DB_NAZEV);
    DB::proved("DELETE FROM kalendar WHERE id_objekt = ? and datum = ? ", array($objekt, $datum));
}

if ($akce == "volno") {
//budu mazat zaznam o obsazeni v kalendari     
    $objekt = $_GET["objekt"];
    $datum = isset($_GET["datum"]) ? $_GET["datum"] : "";
    DB::pripoj(Kon::DB_URL, Kon::DB_LOG, Kon::DB_PASS, Kon::DB_NAZEV);
    DB::proved("INSERT INTO kalendar VALUES( ? ,? )", array($objekt, $datum));
}
echo "OK";
?>
