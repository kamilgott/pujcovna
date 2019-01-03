<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ObjektClass
 *
 * @author Administrator
 */
class ObjektClass extends AbstractDAO {

    public static $vybaveni = array('vybaveni_pokoj', 'vybaveni_koupelna', 'vybaveni_sluzby',
        'vybaveni_deti', 'vybaveni_zabava', 'vybaveni_sport', 'vybaveni_doprava',
        'vybaveni_strava', 'vybaveni_ostatni', 'vybaveni_tema');
    public static $sloupce = array('objekt_id', 'nazev', 'kategorie',
        'popis', 'cena','cena_tyden','cena_den','status',
        'popis','delka','sirka','hloubka','pocet','sklad','odprodej','cena_popis','doprava_popis');

    public function __construct() {
        parent::__construct('objekt');
        $this->data = array_flip(ObjektClass::$sloupce);
        // Doplnime prazne znaky
        foreach ($this->data as &$value)
            $value = '';
        $this->data['objekt_id'] = 'NULL';
        $this->nulujVybaveni();
    }

    private function nulujVybaveni() {
        foreach (ObjektClass::$vybaveni as $p)
            $this->data[$p] = Kon::STR_VYBER;
    }

    public function setVybaveni(&$pole) {

//        $cis->data = CiselnikClass::getGNazev($nazev);
//        foreach ($cis->data as $p)  $str .= isset($_POST[$nazev.$p['hodnota_c']])?"1":"0" ;
        foreach (ObjektClass::$vybaveni as $nazev) {
            $str = Kon::STR_VYBER;
            if (isset($pole[$nazev])) {
                foreach ($pole[$nazev] as $p) {
                    $str[$p - 1] = "1";
                }
            }
            $pole[$nazev] = $str;
        }
    }

    public function getByMajitel($id_majitel) {
        $this->data = DB::dotazJeden('SELECT * FROM `' . $this->nazev . '` WHERE `id_majitel` = ?', array($id_majitel));
        return $this->data;
    }
    


    public function select($where,$parametry) {
        $dotaz = 'SELECT * from objekt  WHERE  '.$where ;
        $this->data = DB::dotazVsechny($dotaz, $parametry);
        return $this->data;
    }

    //put your code here
    public function delete($id) {

    }

    public function disable($id) {
        ;
    }

}

?>
