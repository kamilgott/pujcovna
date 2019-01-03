<?php

/*
 */

// Výchozí kontroler 
class DefaultKontroler extends AbstractKontroler {

    public function zpracuj($parametry) {

        $this->data['_obsah'] = '';
        // Nastavení šablony
       $this->pohled = 'default';
    }

}