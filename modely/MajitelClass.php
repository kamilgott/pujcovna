<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of MajitelClass
 *
 * @author Administrator
 */
class MajitelClass extends AbstractDAO {
public static $sloupce=array('majitel_id','prijmeni', 'jmeno', 'adresa', 'telefon', 'email', 'www','uprava','status','hash','vznik','heslo' );

    public function __construct() {
        parent::__construct('majitel');
         $this->data = array_flip(MajitelClass::$sloupce);
       // Doplnime prazne znaky
        foreach ($this->data as &$value) $value='';
        $this->data['majitel_id'] = 'NULL';
    }

    public function getByHash($hash) {
        $this->data = DB::dotazJeden('SELECT * FROM `' . $this->nazev . '` WHERE `hash` = ?', $hash);
        return $this->data;
   }
   
   public function getByLogin($param) {
        $this->data = DB::dotazJeden('SELECT * FROM ' . $this->nazev . ' WHERE email = ?  and heslo = ? ' , $param );
        return $this->data;
   }
   public function getByEmail($param) {
        $this->data = DB::dotazJeden('SELECT * FROM ' . $this->nazev . ' WHERE email = ? AND majitel_id = (SELECT id_majitel FROM objekt WHERE objekt_id= ? ) ' , $param ); 
        return $this->data;
   }
  
   public function getByObjekt($param) {
        $this->data = DB::dotazJeden('SELECT * FROM ' . $this->nazev . ' WHERE majitel_id = (SELECT id_majitel FROM objekt WHERE objekt_id= ? ) ' , $param );
        return $this->data;
   }
  
   
   public function delete($id) {
        
    }

    public function select($where) {
       $dotaz = 'SELECT * from majitel  WHERE  ? ';
       $this->data = DB::dotazVsechny($dotaz, array($where));
        return $this->data;
    }

    public function disable($id) {
        ;
    }

}

?>
