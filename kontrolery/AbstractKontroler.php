<?php

/*
 */

// prapredek vsech kontroleru
abstract class AbstractKontroler {

    // Pole, jehož indexy jsou poté viditelné v šabloně jako běžné proměnné
    protected $data = array(
        //hlavicka
        'titulek' => 'Aplikace Půjčovna',
        'klicova_slova' => 'půjčovna, zprostředkování',
        'popis' => 'web pujcovna.cz umožňuje zákazníkům nabídnout nábytek k pronájmu ',
        'nadpis' => 'Vítejte v Aplikaci Půjčovna ',
        'zpravy' => array());
    // Název šablony bez přípony
    protected $pohled = "";

    // Funkce rekurzivně ošetří hodnoty v poli $data tak, aby je bylo možné
    // bezpečně vypsat v šabloně. Ošetřují se řetězce a to i ve vnořených
    // polích. Objekty (které v šabloně většinou nejsou třeba) si musíte ošetřit manuálně.
    private function osetri($x = null) {
        if (!isset($x))
            return null;
        elseif (is_string($x))
            return htmlspecialchars($x);
        elseif (is_array($x)) {
            foreach ($x as $k => $v) {
                $x[$k] = $this->osetri($v);
            }
            return $x;
        }
        else
            return $x;
    }

    // Vyrenderuje pohled
    public function vypisPohled() {
        if ($this->pohled) {
            extract($this->osetri($this->data));
            extract($this->data, EXTR_PREFIX_ALL, "");
            require("pohledy/" . $this->pohled . ".phtml");
        }
    }

    // Přesměruje na dané URL
    public function presmeruj($url) {
        header("Location: ".Kon::BASE.$url);
        header("Connection: close");
        exit;
    }

    // Hlavní metoda controlleru
    abstract function zpracuj($parametry);
}

// Funkce pro zjednodušené manuální zabezpečení vstupů v případě, že chceme
// zabezpečit jen některé části pole $data a ostatní již obsahují HTML výstup
function o($s) {
    return htmlspecialchars($s);
}