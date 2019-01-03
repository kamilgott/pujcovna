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
require_once "ObjektClass.php";

class NpolozkaClass extends AbstractDAO {


    public static $sloupce = array('id','objekt_id', 'nazev','divize','mistnost','kusu', 'hodnota', 'cena');

    public function __construct() {
        parent::__construct('npolozka');
        $this->data = array_flip(NpolozkaClass::$sloupce);
        // Doplnime prazne znaky
        foreach ($this->data as &$value)
            $value = '';
        $this->data['id'] = 'NULL';
    }

    public function setByObjekt($objekt) {
        $this->data["nazev"] = $objekt->data["nazev"];
		$this->data["cena"] = $objekt->data["cena"];
		$this->data["objekt_id"] = $objekt->data["objekt_id"];
		$this->data["hodnota"] = $objekt->data["odprodej"];
		$this->data["kusu"] = 1;
		$kategorie = $objekt->data["kategorie"];
		$this->data["divize"] = (isset($kategorie) and $kategorie > 0) ? CiselnikClass::getGcis('ObjektKategorie', $kategorie, 'hodnota_t') : '' ;

	}

    public function getByID($id) {
        $this->data = DB::dotazJeden('SELECT * FROM ' . $this->nazev . ' WHERE id = ?', array($id));
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
