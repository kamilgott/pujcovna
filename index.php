<?php

/*
  vstup do aplikace
  vsechny pozadavky jsou zde zpracovany
 */


// Hlavička pro session
session_start();
if (!isset($_SESSION['spusteno'])) {
    // jeste neni session jde o prvni pozadavek 
    $_SESSION['spusteno'] = true;
    // nastavim to na vyber objkektu jako dlazdice
    $_SERVER['REQUEST_URI'] = "/objekt/seznam/T";
}


//session_unset();
error_reporting(E_ALL);

// Nastavení interního kódování pro funkce pro práci s řetězci
mb_internal_encoding("UTF-8");

function fixObject(&$object) {
    if (!is_object($object) && gettype($object) == 'object')
        return ($object = unserialize(serialize($object)));
    return $object;
}

// Callback pro automatické načítání tříd controllerů a modelů
function autoloadFunkce($trida) {
    // Končí název třídy řetězcem "Kontroler" ?
    if ((mb_strlen($trida) >= mb_strlen("Kontroler")) && (mb_strpos($trida, "Kontroler", mb_strlen($trida) - mb_strlen("Kontroler"))) !== false)
        require("kontrolery/" . $trida . ".php");
    else
        require("modely/" . $trida . ".php");
}

// Registrace callbacku (Pod starým PHP 5.2 je nutné nahradit fcí __autoload())
spl_autoload_register("autoloadFunkce");
$s_majitel = fixObject($_SESSION['s_majitel']);


// Připojení k databázi
DB::pripoj(Kon::DB_URL, Kon::DB_LOG, Kon::DB_PASS, Kon::DB_NAZEV);

//DB::pripoj("127.0.0.1", "bezvachatycz001", "bezva199", "bezvachatycz");
//banan  DB::pripoj("localhost", "bezvaubytovani.uvadi.cz", "bezva199", "bezvaubytovani_uvadi_cz");
// DB::pripoj("127.0.0.1", "root", "", "databaze_pro_web");
//nastaveni ciselniku do session
CiselnikClass::setCis();

// Vytvoření routeru a zpracování parametrů od uživatele z URL
$smerovac = new SmerovacKontroler();
$smerovac->zpracuj(array($_SERVER['REQUEST_URI']));

// Vyrenderování šablony
$smerovac->vypisPohled();
?>
