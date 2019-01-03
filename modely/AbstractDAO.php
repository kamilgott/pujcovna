<?php

/*
 */

// abstrakní třída pro DAO objekty
// prapredek objektu mapovanych na databazovou tabulku
abstract class AbstractDAO {

    public $nazev;
    public $data;

    // Pokud objekt v databázi existuje, vrátí hodnotu vyšší než 0
    public function get($id) {
        $this->data = DB::dotazJeden('SELECT * FROM `' . $this->nazev . '` WHERE `'.$this->nazev.'_id` = ?', array($id));
        return $this->data;
    }
	
	public function getByID($id) {
        $this->data = DB::dotazJeden('SELECT * FROM ' . $this->nazev . ' WHERE id= ?', array($id));
        return $this->data;
    }

    public function update($data){
        DB::zmen($this->nazev, $this->data, 'WHERE `'.$this->nazev.'_id` = ?', array($this->data[$this->nazev.'_id']) );
    }

    public function insert($data=null){
		$data = $data ?? $this->data;
      return DB::vlozAvrat($this->nazev, $data);
    }

    abstract public function delete($id);

//    abstract public function select($where,$parametry);

    public function __construct($nazev) {
        $this->nazev = $nazev;
    }



}