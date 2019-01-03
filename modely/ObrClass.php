<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ObrClass
 *
 * @author Administrator
 */
class ObrClass extends AbstractDAO {

    public static $sloupce = array('obr_id', 'id_objekt', 'nazev', 'typ', 'poradi', 'bdata', 'status', 'velikost','dbata');

    public function __construct() {
        parent::__construct('obr');
        $this->data = array_flip(ObrClass::$sloupce);
        // Doplnime prazne znaky
        foreach ($this->data as &$value)
            $value = '';
        $this->data['obr_id'] = 'NULL';
    }

    public function insertFILES() {
        for ($i = 1; $i < 10; $i++) {
            
            if (isset($_FILES['foto'.$i]) and $_FILES['foto'.$i]['size'] > 0 and $_FILES['foto'.$i]['error'] === 0) {
                $this->data['velikost'] = $_FILES['foto'.$i]['size'];
                $this->data['nazev'] = $_FILES['foto'.$i]['name'];
                $this->data['typ'] = $_FILES['foto'.$i]['type'];
                $this->data['poradi'] = $i;
                $this->data['bdata'] = file_get_contents($_FILES['foto'.$i]['tmp_name']);
                $this->insert($this->data);
            }
        }
    }

    public function getByID($id) {
        $this->data = DB::dotazJeden('SELECT * FROM `' . $this->nazev . '` WHERE `obr_id` = ?', array($id));
        return $this->data;
    }

    public function select($where) {
        $dotaz = 'SELECT obr_id, id_objekt, nazev, typ, poradi, status ,velikost,bdata from obr WHERE ';
        $this->data = DB::query($dotaz . $where);
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
