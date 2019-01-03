<?php

// Kontroler pro padministraci

class AdminKontroler extends AbstractKontroler {

    static $typakce = array('kontakt', 'onas', 'debug', 'unset', 'logout', 'MajitelMail');

    public function zpracuj($parametry) {
        //co provede defaulne novy
        $akce = "onas";

        // URL jako parametr
        // bude-li odeslano take z formulare ma _POST prednost pred URL
        if (isset($_POST['akce'])) {
            $akce = $_POST['akce'];
        } else {
            if (isset($parametry[0]) and in_array($parametry[0], AdminKontroler::$typakce))
                $akce = $parametry[0];
        };

        Debug::p($akce);
        // Nastavení šablony
        $this->pohled = 'onas';

        // Hlavička stránky
        $this->hlavicka['titulek'] = 'onas';
        // Byl odeslán formulář
        if ($akce == "onas") {
            // Proměnné pro šablonu
            $this->data['titulek'] = 'O nás ';
            // $this->data['uzivatel_id'] = '';
            $this->data['telefon'] = (Debug::deb) ? '603 164 507' : '';
            $this->data['email'] = (Debug::deb) ? 'kamil.gottinger@seznam.cz' : '@';
            $this->data['www'] = 'www.bezva.cz';
            $this->pohled = 'onas';
        }
        if ($akce == "kontakt") {
            //$klice = array('prijmeni', 'jmeno', 'adresa', 'telefon', 'email', 'www');
            $this->data['akce'] = 'poslat';
            $this->data['zprava'] = isset($_POST['zprava']) ? $_POST['zprava'] : '';
            $this->data['email'] = isset($_POST['email']) ? $_POST['email'] : '@';
            $this->pohled = 'mail';
        }
        if ($akce == "poslat") {
            //$klice = array('prijmeni', 'jmeno', 'adresa', 'telefon', 'email', 'www');
            $this->data['akce'] = 'kontakt';
            $this->data['zprava'] = $_POST['zprava'];
            $this->data['email'] = $_POST['email'];

            if ($_POST['a'] == date("j")) {
                if (Posta::posli($_POST['email'], "Půjčovna ubytko kontaktní mail", $_POST['zprava'])) {
                    Zprava::zobraz('Kontaktní mail odeslán.');
                    $this->presmeruj('/index.php');
                }
            } else {
                Zprava::zobraz('Špatně vyplněný antispam.');
            }
            $this->pohled = 'mail';
        }

        if ($akce == "MajitelMail") {
            $this->data['akce'] = 'poslatMajiteli';
            $this->data['objekt_id'] = $parametry[1];
            $this->data['zprava'] = isset($_POST['zprava']) ? $_POST['zprava'] : '';
            $this->data['email'] = isset($_POST['email']) ? $_POST['email'] : '@';
         
            $this->pohled = 'MajitelMail';
        }
        if ($akce == "poslatMajiteli") {
            $this->data['akce'] = 'MajitelMail';
            $this->data['zprava'] = $_POST['zprava'];
            $this->data['email'] = $_POST['email'];
            $this->data['objekt_id'] =$_POST['objekt_id'];
         
            if ($_POST['a'] == date("j")) {
                $majitel = new MajitelClass();
                $majitel->getByObjekt(array($_POST['objekt_id']));
                //posleme majiteli a administratorovi a take zajemci              
                $adresati = $majitel->data['email'].','. Kon::NAS_EMAIL;
                if (Posta::posli($adresati, "Poptávka z aplikace ", Posta::text(Posta::MAJITELI, $parametry))
                    && (Posta::posli($_POST['email'], "Váš požadavek v aplikaci ", Posta::text(Posta::ZAJEMCI, $parametry))))
                {
                    Zprava::zobraz('Kontaktní mail odeslán.');
                    $this->presmeruj('/index.php');
                    
                }
            } else {
                Zprava::zobraz('Špatně vyplněný antispam.');
            }
            $this->pohled = 'MajitelMail';
        }

        
        

        if ($akce == "debug") {
            //$klice = array('prijmeni', 'jmeno', 'adresa', 'telefon', 'email', 'www');
            if (isset($_SESSION['s_debug']) and $_SESSION['s_debug'])
                $_SESSION['s_debug'] = !$_SESSION['s_debug'];
            else
                $_SESSION['s_debug'] = TRUE;
            Debug::p($_SESSION['s_debug']);
            Zprava::zobraz('Debbugovani je ' . $_SESSION['s_debug']);
            $this->presmeruj('/index.php');
        }
        if ($akce == "unset") {
            session_unset();
            Zprava::zobraz('Session byla zrušena!');
            $this->presmeruj('/index.php');
        }
        if ($akce == "logout") {
            unset($_SESSION['s_majitel']);
            Zprava::zobraz('Byl jste odhlášen!');
            $this->presmeruj('/index.php');
        }
    }

}