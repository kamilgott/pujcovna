<?php

// Router je speciální typ controlleru, který podle URL adresy zavolá
// správný controller a jím vytvořený pohled vloží do šablony stránky

class SmerovacKontroler extends AbstractKontroler {

    // Instance controlleru
    protected $kontroler;

    // Metoda převede pomlčkovou variantu controlleru na název třídy
    private function pomlckyDoVelbloudiNotace($text) {
        $veta = str_replace('-', ' ', $text);
        $veta = ucwords($veta);
        $veta = str_replace(' ', '', $veta);
        return $veta;
    }

    // Naparsuje URL adresu podle lomítek a vrátí pole parametrů
    private function parsujURL($url) {
        // Naparsuje jednotlivé části URL adresy do asociativního pole
        //      Debug::p($url);
        if (!isset($url) or strlen($url)< 2)
            $url = "/objekt/seznam/T";
        $naparsovanaURL = parse_url($url);
        //       Debug::p($naparsovanaURL);
        // Rozbití řetězce podle lomítek
        $rozdelenaCesta = explode("/", $naparsovanaURL["path"]);

        // Odstranění bílého místa z částí řetězce
        for ($i = 0; $i < count($rozdelenaCesta); $i++)
            $rozdelenaCesta[$i] = trim($rozdelenaCesta[$i]);


        // Odstranění počátečního lomítka a Bezva }/Bezva
        array_shift($rozdelenaCesta);
        if (mb_strpos($naparsovanaURL["path"], "/Bezva") === 0)
            array_shift($rozdelenaCesta);

        Debug::p($rozdelenaCesta);
        return $rozdelenaCesta;
    }

    // Naparsování URL adresy a vytvoření příslušného controlleru
    public function zpracuj($parametry) {
        $naparsovanaURL = $this->parsujURL($parametry[0]);

        // Výchozí URL pokud je zadána prázdná
        // predpokladam, ze je to jiz ve tvaru server.nekde.cz/index.php
        // pak je treba 
        if (!isset($naparsovanaURL[1])) {
            //neexistuje 2parametr
            $this->kontroler = new DefaultKontroler;
        }
        // Vytvoření controlleru
        // Pokud existuje objket s URL co je v 1. parametru kontroler je typu ObjektKontroler
        // Objekt nenalezen, kontroler je 1. parametr URL
        $tridaKontroleru = $this->pomlckyDoVelbloudiNotace(array_shift($naparsovanaURL)) . 'Kontroler';
        if (file_exists('kontrolery/' . $tridaKontroleru . '.php'))
            $this->kontroler = new $tridaKontroleru;
        else {
            // neexistuje dame defaultni
            $this->kontroler = new DefaultKontroler;
          
        }
        // Volání controlleru
        $this->kontroler->zpracuj($naparsovanaURL);
        //nastavim pohled podle vygenerovaneho
        // Nastavení hlavní šablony
        // Nastavení proměnných pro šablonu
        $this->data['zpravy'] = Zprava::vratZpravy();
        $this->pohled = 'rozlozeni';
      //   $this->data = $this->kontroler->data;
    }

}