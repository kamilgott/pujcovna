<?php

include_once '../modely/DB.php';
include_once '../modely/Kon.php';

// just so we know it is broken
//error_reporting(E_ALL);

if (isset($_GET['obr_id']) && is_numeric($_GET['obr_id'])) {

//DB::pripoj("127.0.0.1", "bezvachatycz001", "bezva199", "bezvachatycz");
    DB::pripoj(Kon::DB_URL, Kon::DB_LOG, Kon::DB_PASS, Kon::DB_NAZEV);
    $img = DB::dotazJeden("SELECT * from obr where obr_id = ? ", array($_GET['obr_id']));
    header("Content-type: " . $img['typ']);
    header("Content-length: " . $img['velikost']);
    echo  $img['bdata'];
} else {
    echo 'Please use a real id number';
}
?>