<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of CiselnikClass
 *
 * @author Administrator
 */
class CiselnikClass extends AbstractDAO {

    public static $sloupce = array(
        'ciselnik_id', 'p_id', 'hodnota_c', 'hodnota_t', 'popis');

    public function __construct() {
        parent::__construct('ciselnik');
        $this->data = array_flip(CiselnikClass::$sloupce);
        // Doplnime prazne znaky
        foreach ($this->data as &$value)
            $value = '';
        $this->data['ciselnik_id'] = 'NULL';
    }

    //put your code here
    public function delete($id) {

    }

    public function select($typ) {
        $dotaz = 'SELECT * from ciselnik  WHERE  p_id = '
                . '( SELECT d.ciselnik_id  FROM ciselnik d WHERE d.p_id = 0 AND d.hodnota_t = ?  ) order by hodnota_c';
        $this->data = DB::dotazVsechny($dotaz, array($typ));
    }

    public function getBylPID($pid) {
        $dotaz = 'SELECT ciselnik_id, p_id, hodnota_c, hodnota_t, popis  from ciselnik  WHERE  p_id = ? order by hodnota_c';
        $this->data = DB::dotazVsechny($dotaz, array($pid));
    }

    public static function getGcis($nazev, $id, $sloupec) {
        if (isset($_SESSION['s_ciselniky'][$nazev][$id][$sloupec])) {
            return $_SESSION['s_ciselniky'][$nazev][$id][$sloupec];
        } else {
            return '';
        }
    }

    public static function getGNazev($nazev) {
        if (isset($_SESSION['s_ciselniky'][$nazev])) {
            return $_SESSION['s_ciselniky'][$nazev];
        } else {
            return '';
        }
    }



    public function disable($id) {
        ;
    }

    public static function setCis() {

     //   if ( !isset($_SESSION['s_ciselniky'])) {
            $cis = new CiselnikClass();
            $pcis = new CiselnikClass();
 // Debug::p('pocitam g_ciselniky');

            $cis->getBylPID(0);  // vezmu vsechny ciselniky a preindexuji
            for ($i = 0; $i < count($cis->data); $i++) {
                $pcis->select($cis->data[$i]['hodnota_t']);
                for ($j = 0; $j < count($pcis->data); $j++) {
                     $_SESSION['s_ciselniky'][$cis->data[$i]['hodnota_t']][$pcis->data[$j]['ciselnik_id']] = $pcis->data[$j];
                }
            }

     //   }
    }

}

?>
