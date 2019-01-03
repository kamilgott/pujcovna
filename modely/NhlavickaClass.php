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
class NhlavickaClass extends AbstractDAO {


    public static $sloupce = array('id', 'nazev', 'popis', 'cena','status','datum');

    public function __construct() {
        parent::__construct('nhlavicka');
        $this->data = array_flip(NhlavickaClass::$sloupce);
        // Doplnime prazne znaky
        foreach ($this->data as &$value)
            $value = '';
        $this->data['id'] = 'NULL';
      
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
