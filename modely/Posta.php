<?php

//require "class.phpmailer.php";
// Pomocná třída, poskytující metody pro odeslání emailu
class Posta {

    const VERIFIKACE = "VERIFIKACE";
    const MAJITELI = "MAJITELI";
    const ZAJEMCI = "ZAJEMCI";
    const RESETHESLA = "RESETHESLA";


    // Odešle email jako HTML, lze tedy používat základní HTML tagy a nové
    // řádky je třeba psát jako <br /> nebo používat odstavce. Kódování je
    // odladěno pro UTF-8.
    public static function odesli($komu, $predmet, $zprava, $od) {
        $hlavicka = "From: " . $od;
        $hlavicka .= "\nMIME-Version: 1.0\n";
        $hlavicka .= "Content-Type: text/html; charset=\"utf-8\"\n";
        return mb_send_mail($komu, $predmet, $zprava, $hlavicka);
    }

    public static function posli($adresat, $predmet, $telo) {

        $ok = TRUE;
        //definice hlavicek
        $headers = "From:" . Kon::NAS_EMAIL . "\n";
        $headers .= "MIME-Version: 1.0\n";
        $headers .= "Content-Type: text/plain; charset=utf-8\n";
        $headers .= "Content-Transfer-Encoding: 8bit\n";
        $headers .= "Return-Path:" . Kon::NAS_EMAIL . "\n";

        //samotne odeslani
        $ok = mail($adresat, $predmet, $telo, $headers);


//
//        $mail = new PHPMailer();
//        $mail->IsSMTP();  // k odeslání e-mailu použijeme SMTP server
//        $mail->Host = 'smtp.cesky-hosting.cz'; //Kon::SMPT_SER;  // zadáme adresu SMTP serveru
//        $mail->SMTPAuth = true; // Kon::SMTPAuth;               // nastavíme true v případě, že server vyžaduje SMTP autentizaci
//        $mail->Username = 'info@bezvachaty.cz';   //Kon::SMTPUsername;   // uživatelské jméno pro SMTP autentizaci
//        $mail->Password = 'bezva199';     //Kon::SMTPPassword;            // heslo pro SMTP autentizaci
//        $mail->From = 'info@bezvachaty.cz';  // Kon::NAS_EMAIL;   // adresa odesílatele skriptu
//        $mail->FromName = "BezvaChaty"; // jméno odesílatele skriptu (zobrazí se vedle adresy odesílatele)
//        $mail->AddAddress($adresat);  // přidáme příjemce
//        // $mail->AddAddress("kamil.gottinger@quick.cz", "Jméno druhého příjemce");  // a klidně i druhého, včetně jména
//        $mail->Subject = $predmet;    // nastavíme předmět e-mailu
//        $mail->Body = $telo;
//        //$mail->WordWrap = 50;   // je vhodné taky nastavit zalomení (po 50 znacích)
//        $mail->CharSet = "utf-8";   // nastavíme kódování, ve kterém odesíláme e-mail
//        $ok = $mail->Send();

        if ($ok) {  // odešleme e-mail
            Zprava::zobraz('E-mail byl v pořádku odeslán.');
        } else {
            Zprava::zobraz('Došlo k chybě při odeslání e-mailu.');
            //Zprava::zobraz('Chybová hláška: ' . $mail->ErrorInfo);
        }
        return $ok;
    }

    public static function text($typ, $parametry) {
        if ($typ == Posta::VERIFIKACE) {
            return " Dobrý den, \n\nděkujeme za registraci na našem webu http://" . $_SERVER['HTTP_HOST'] . " ! \n\n"
                    . "Pro potvrzení Vaší identity použijte následující odkaz: \n\n"
                    . " http://" . $_SERVER['HTTP_HOST'] . "/majitel/verifikace/" . $parametry . "  \n\n"
                    . "Na tento e-mail prosím neodpovídejte. Slouží pouze pro Vaši informaci.\n\n"
                    . "Pěkný den a těšíme se na spolupráci.  \n"
                    . "Pracovníci portálu Půjčovna!";
        }
        if ($typ == Posta::RESETHESLA) {
            return " Dobrý den, \n\nposíláme Vám odkaz pro změnu hesla k přístupu na našem webu http://" . $_SERVER['HTTP_HOST'] . " ! \n\n"
                    . "Pro potvrzení požadavku na změnu Vašeho hesla použijte následující odkaz: \n\n"
                    . " http://" . $_SERVER['HTTP_HOST'] . "/majitel/verifikace/" . $parametry . "  \n\n"
                    . "Na tento e-mail prosím neodpovídejte. Slouží pouze pro Vaši informaci.\n\n"
                    . "Pěkný den a těšíme se na spolupráci.  \n"
                    . "Pracovníci portálu Půjčovna!";
        }

        if ($typ == Posta::MAJITELI) {
            return " Dobrý den, \n z aplikace Bezva-ubytko přišla poptávka na ubytování ve Vašem objektu(ID=".$parametry[1]."). "
                    . "\n  Jméno: " . $_POST['jmeno'] . "\n  Email: " . $_POST['email'] . "\n  Telefon: " . $_POST['telefon']
                    . "\n  Termín: " . $_POST['termin'] . "\n  počet osob: " . $_POST['pocet']. "\n  počet dětí: " . $_POST['deti']
                    . "\n\n" . $_POST['zprava']
                    . " \n\n Na tento e-mail prosím neodpovídejte. Slouží pouze pro Vaši informaci.\n\n"
                    . "Pěkný den a těšíme se na spolupráci. \n"
                    . "Pracovníci Půjčovna!";
        }
        if ($typ == Posta::ZAJEMCI) {
            return " Dobrý den, \nděkujeme za využívání služeb aplikace Bezva-ubytko."
                    . "\nVaše poptávka na ubytování (objekt ID=".$parametry[1].") byla předána majiteli, který se s Vámi bude kontaktovat, jakmile to bude možné.\n "
                    . "Majiteli byly předány tyto Vaše údaje:\n  Jméno: " . $_POST['jmeno'] . "\n  Email: " . $_POST['email'] . "\n  Telefon: " . $_POST['telefon']
                    . "\n  Termín: " . $_POST['termin'] . "\n  počet osob: " . $_POST['pocet'] . "\n  počet dětí: " . $_POST['deti']
                    . "\n  Vaše zpráva:\n " . $_POST['zprava']
                    . " \n\n Na tento e-mail prosím neodpovídejte. Slouží pouze pro Vaši informaci.\n\n"
                    . "Pěkný den a těšíme se na spolupráci. \n"
                    . "Pracovníci portálu Půjčovna!";
        }
    }

}

?>