<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of KalendarClass
 *
 * @author Administrator
 */
class KalendarClass extends AbstractDAO {

    public static $sloupce = array('id_objekt', 'datum');

    public function __construct() {
        parent::__construct('kalendar');
        $this->data = array_flip(KalendarClass::$sloupce);
        // Doplnime prazne znaky
        foreach ($this->data as &$value)
            $value = '';
    }

    

    //put your code here
    public function delete($id) {
        
    }

    public function select($where) {
        $dotaz = 'SELECT * from kalendar  WHERE  ? ';
        $this->data = DB::dotazVsechny($dotaz, array($where));
        return $this->data;
    }

    public function getMesic($mesic, $rok, $objekt) {
        $dotaz = "SELECT DAYOFMONTH( datum )  FROM kalendar "
                . " WHERE datum BETWEEN STR_TO_DATE('01.$mesic.$rok', '%d.%m.%Y' ) "
                . " AND LAST_DAY( STR_TO_DATE('01.$mesic.$rok', '%d.%m.%Y' ) ) "
                . " AND id_objekt= $objekt ";
        return DB::query($dotaz);
    }

    public function disable($id) {
        ;
    }

    public function JePrechodnyRok($rok) {
        return (boolean) date("L", mktime(0, 0, 0, 1, 1, $rok));
    }

    public function PocetDnu($mesic, $rok) {
        return cal_days_in_month(CAL_GREGORIAN, $mesic, $rok);
    }

    public function PrvniDen($mesic, $rok) {
        $anglickeporadi = date("w", mktime(0, 0, 0, $mesic, 1, $rok));
        return ($anglickeporadi == 0) ? 7 : $anglickeporadi;
    }

    private function Bunka($radek, $sloupec, $PrvniDen, $PocetDnu) {
        $chcivratit = ($radek - 2) * 7 + $sloupec - $PrvniDen + 1;
        if ($chcivratit < 1 || $chcivratit > $PocetDnu)
            return "&nbsp;";
        else
            return $chcivratit;
    }

    public function Kalendar($mesic, $rok, $objekt) {
        $mesice = Array(1 => "leden", "únor", "březen", "duben", "květen", "červen", "červenec", "srpen", "září", "říjen", "listopad", "prosinec");
        //kontroly
        if (!is_numeric($mesic))
            return "Měsíc musí být číslo!";
        if (!is_numeric($rok))
            return "Rok musí být číslo!";
        if ($mesic < 1 || $mesic > 12)
            return "Měsíc musí být číslo od 1 do 12";
        if ($rok < 1980 || $rok > 2050)
            return "Rok musí být číslo od 1980 do 2050";
        // zjištění počtu sloupců
        $PocetDnu = $this->PocetDnu($mesic, $rok);
        $PrvniDen = $this->PrvniDen($mesic, $rok);
        //echo " pocet dnu= " . $PocetDnu;

        $obsazeno = $this->getMesic($mesic, $rok, $objekt);
        if (isset($obsazeno) and count($obsazeno)) {
            foreach ($obsazeno as &$p)
                $p = $p[0];
        }
        $radku = date("W", mktime(0, 0, 0, $mesic, $PocetDnu - 7, $rok)) - date("W", mktime(0, 0, 0, $mesic, 1 + 7, $rok)) + 4;
      //  echo " pocet radku= " . $radku;

        // a tabulka
        echo " <TABLE class='kalendar' rok='$rok' mesic='$mesic' objekt='$objekt' >";
        echo "<TR><TD colspan=7 ><b>" . $mesice[$mesic] . "&nbsp;" . $rok . "</b></TD></TR>\n";
        $dny = Array(1 => "Po", "Út", "St", "Čt", "Pá", "<FONT COLOR='blue'>So</FONT>", "<FONT COLOR='red'>Ne</FONT>");
        echo "<TR>";
        foreach ($dny as $d)
            echo "<TD><b>$d</b></TD>";
        echo "</TR>\n";
        for ($radek = 2; $radek <= $radku; $radek++) {
            echo "<TR>";
            for ($sloupec = 1; $sloupec <= 7; $sloupec++) {
                $den = $this->Bunka($radek, $sloupec, $PrvniDen, $PocetDnu);
                if (is_numeric($den))
                    echo "<TD " . (in_array($den, $obsazeno) ? " class='zadano'" : " class='volno' ") . " >";
                else
                   echo "<TD>";
                echo ($sloupec>5)?"<b>$den</b>":$den;
                echo "</TD>";
            }
        }
        echo "</TR>\n";
        echo "</TABLE>";
    }

}
?>
