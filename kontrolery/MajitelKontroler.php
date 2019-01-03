<?php

// Controller pro Majitele

class MajitelKontroler extends AbstractKontroler {

    static $typakce = array('new', 'edit', 'delete', 'insert', 'ObnovSkryj',
        'seznam', 'save', 'verifikace', 'logout', 'heslo', 'login');

    public function zpracuj($parametry) {
        //co provede defaulne novy
        $akce = "new";
        $majitel_id = 0;

        // URL jako parametr
        // bude-li odeslano take z formulare ma _POST prednost pred URL
        if (isset($_POST['akce']))
            $akce = $_POST['akce'];
        elseif (isset($parametry[0]) and in_array($parametry[0], MajitelKontroler::$typakce))
            $akce = $parametry[0];

        // majitel_id bude-li odeslano take z formulare ma _POST prednost pred URL
        // potom ze session aktualne prihlaseneho majitele
        if (isset($_POST['majitel_id']))
            $majitel_id = $_POST['majitel_id'];
        elseif (isset($parametry[1]))
            $majitel_id = $parametry[1];
        elseif (isset($_SESSION['s_majitel']['majite_id']))
            $majitel_id = $_SESSION['s_majitel']['majite_id'];


      //  Debug::p($akce);
        // Nastavení šablony
        $this->pohled = 'majitel';

        // Hlavička stránky
        $this->hlavicka['titulek'] = 'Editor majitele';
        $mj = new MajitelClass();
        // Byl odeslán formulář
        if ($akce == "new") {
            // Proměnné pro šablonu
            $this->data = $mj->data;
            $this->data['titulek'] = 'Registrace nového majitele objektu';
            // $this->data['uzivatel_id'] = '';
            $this->data['telefon'] = '';
            $this->data['email'] = '@';
            $this->data['www'] = 'www';
            // po odeslani formulare uloz
            $this->data['akce'] = 'insert';
        }
        if ($akce == "ObnovSkryj") {
            DB::proved("UPDATE majitel SET status = (- status) WHERE majitel_id = ? ", Array($parametry[1]));
            Zprava::zobraz('Majitel byl zneplatněn');
            $this->presmeruj('/majitel/edit/' . $parametry[1]);
        }
        if ($akce == "delete") {
           DB::proved("DELETE FROM obr WHERE id_objekt IN (SELECT objekt_id FROM objekt WHERE id_majitel = ? ) ", Array($parametry[1]));
           DB::proved("DELETE FROM objekt WHERE id_majitel = ? ", Array($parametry[1]));
           DB::proved("DELETE FROM majitel WHERE majitel_id= ? ", Array($parametry[1]));
           
           Zprava::zobraz('Majitel byl smazán');
           $this->presmeruj('/');
        }
        
        
        if ($akce == "insert") {
            //$klice = array('prijmeni', 'jmeno', 'adresa', 'telefon', 'email', 'www');
            $mj->data = array_intersect_key($_POST, array_flip(MajitelClass::$sloupce));
            $this->data = $mj->data;

            $mj->data['hash'] = md5($mj->data['email'] . microtime());
            $this->data['majitel_id'] = $mj->insert($mj->data);   // zaloz noveho
            $this->data['titulek'] = 'Editace majitele';
            // budu ho pak editovat    
            $this->data['akce'] = 'edit';
            Zprava::zobraz('Majitel byl založen');
            Posta::posli($mj->data['email'], "Půjčovna ubytování ověření pravosti údajů", Posta::text(Posta::VERIFIKACE, $mj->data['hash']));
            Zprava::zobraz('Majitel ID=' . $this->data['majitel_id'] . ' byl založen a verifikační mail odeslán.');
            $this->presmeruj('/majitel/edit/' . $this->data['majitel_id']);
        }
        if ($akce == "edit") {
            //najdu majitele podle majitel_id
            $mj->get($majitel_id);
            $mj->data = array_intersect_key($mj->data, array_flip(MajitelClass::$sloupce));
            $this->data = $mj->data;
            $this->data['akce'] = ($mj->data['status'] === 1) ? 'heslo':'save';
            
            $this->data['titulek'] = 'Editace majitele';
            Zprava::zobraz('Majitel byl vybrán');
        }
        if ($akce == "save") {
            //ulozim majitele podle majitel_id
            //   $klice = array('prijmeni', 'jmeno', 'adresa', 'telefon', 'email', 'www');
            $mj->data = array_intersect_key($_POST, array_flip(MajitelClass::$sloupce));
            if (isset($_POST['heslo1']) and isset($_POST['heslo2'])) {
                //zmena hesla 
                $mj->data['heslo'] = md5($_POST['heslo1'] . 'NaCl');
                $mj->data['status'] = 12;
            }
            $this->data = $mj->data;
            $mj->update($mj->data);
            if ($mj->data['status'] > 0)
                $_SESSION['s_majitel'] = $mj->data;  //ulozim majitele do SESSION
            $this->data['akce'] = 'save';
            $this->data['titulek'] = 'Editace majitele';
            Zprava::zobraz('Majitel byl upraven');
            //$this->presmeruj('/Bezva/majitel');
        }


        if ($akce == "seznam") {
            // Proměnné pro šablonu 
            $this->data['seznam'] = $mj->select('1=1');
            $this->data['titulek'] = 'Seznam majitelů';
            $this->data['akce'] = 'seznam';
            Zprava::zobraz('Vybráno ' . count($this->data['seznam']) . ' majitelů');
            $this->pohled = 'majitele';
        }

        if ($akce == "verifikace") {
      //najdu majitele podle Hash
            $mj->getByHash(array($parametry[1]));
            if ($mj->data) {
                $mj->data = array_intersect_key($mj->data, array_flip(MajitelClass::$sloupce));
                $mj->data['status'] = 1;  //nastavim status           
                $mj->update($mj->data);  //ulozim
                $this->data = $mj->data; //sablona
                $_SESSION['s_majitel'] = $mj->data;  //zalozim majitele do SESSION
                $this->data['titulek'] = 'Verifikace majitele';
                $this->data['akce'] = 'heslo';
                Zprava::zobraz('Váše údaje byly úspěšně verifikovány');
            } else {
                unset($_SESSION['s_majitel']);
                Zprava::zobraz('Váše verifikační údaje nebyly nalezeny');

                $this->presmeruj('/majitel');
            }
        }
        if ($akce === "ResetHesla") {
      //najdu majitele podle majlu            
            $mj->getByEmail(array($_POST['email'],$_POST['objekt_id']));
            if ($mj->data) {
                $mj->data = array_intersect_key($mj->data, array_flip(MajitelClass::$sloupce));
            //  $mj->data['status'] = 0;  //nastavim status           
            //    $mj->update($mj->data);  //ulozim
            //    $this->data = $mj->data; //sablona
                Posta::posli($mj->data['email'], "Bezva ubytování obnovení hesla", Posta::text(Posta::RESETHESLA, $mj->data['hash']));
                $this->data['_obsah'] = 'Byl odeslán email s požadavkem na změnu hesla. Zkontrolujte Vaši emailovou schránku '.$_POST['email'];
                Zprava::zobraz('Byl odeslán mail pro změnu hesla');
                $this->pohled = 'default';
            } else {
                Zprava::zobraz('Váše údaje nebyly nalezeny');
                $this->presmeruj('/majitel/login');
            }
        }
      
        if ($akce === "login") {
            $this->data['titulek'] = 'Přihlášení majitele';
            $this->data['akce'] = 'login';
            //najdu majitele podle login heslo

            if (isset($_POST['email']) and isset($_POST['heslo'])) {
                //existuje kombinace login heslo 
                $mj->getByLogin(array($_POST['email'], md5($_POST['heslo'] . 'NaCl')));
                if ($mj->data) {
                    $_SESSION['s_majitel'] = $mj->data;  //zalozim majitele do SESSION
                    Zprava::zobraz('Byl jste úspěšně přihlášen');
                    $this->presmeruj('/');
                } else {
                    Zprava::zobraz('Neexistuje taková kombinace emailu a hesla');
                }
            }
            $this->pohled = 'login';
        }
        // Proměnné pro šablonu
        // $this->data['uzivatel_id'] = '';
        // po odeslani formulare uloz
        //     $this->data['akce'] = 'save';
        //   $this->presmeruj('/Bezva/objekt/new/');
    }

}