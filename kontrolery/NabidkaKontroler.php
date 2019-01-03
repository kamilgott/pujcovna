<?php

// Controller pro praci s Objektem

class NabidkaKontroler extends AbstractKontroler {
	// zde jsou povolene akce
    static $typakce = array('smazFoto', 'view', 'new', 'edit', 'delete', 'insert', 'list', 'save', 'seznam');

    public function zpracuj($parametry) {
        //co se provede , defaulne novy
        $akce = "new";

        // bude-li odeslano take z formulare ma _POST prednost pred URL
        if (isset($_POST['akce'])&& (!empty($_POST['akce']))) {
            $akce = $_POST['akce'];
        } else {
            if (isset($parametry[0]) and in_array($parametry[0], ObjektKontroler::$typakce))
                $akce = $parametry[0];
        };

        if ((!isset($_POST['list'])) and isset($parametry[1]))
            $_POST['list'] = $parametry[1];

		//Debug::p($akce);

        $this->pohled = 'nabidka';     // Nastavení implicitni šablony
        // Hlavička stránky
        $this->hlavicka['titulek'] = 'Editor nabidky';
        $nab = new NhlavickaClass();
		$nab->data["nazev"]="Nabidka XY";
		$nab->data["id"] = $nab->insert($nab->data);
		$nab->data["nazev"]="Nabidka ".$nab->data["id"] ;
        // Byl odeslán formulář
        if ($akce == "new") {
			if ( empty($_POST['list'])){

			}else{
				//je to seznam objektu oddelenych carkou "15,12,78"
				$polozky = explode(",",$_POST['list']);
				foreach ($polozky as $polID){
					$obj = new ObjektClass();
					$obj->get($polID);
					$pol = new NpolozkaClass();
					$pol->setByObjekt($obj);
					$pol->insert();
				}
			}
            $this->data = $nab->data;   // Proměnné pro šablonu
            $this->data['titulek'] = 'Nová nabídka';
			// po odeslani formulare uloz
            $this->data['akce'] = 'insert';
			//   $akce = 'insert';      // po odeslani formulare uloz
        }

        if ($akce == "view") {
            $nab->get($parametry[1]);
            $nab->data = array_intersect_key(array_merge(array_flip(NhlavickaClass::$sloupce), $nab->data), $nab->data);
            $this->data = $nab->data;
            $this->data['akce'] = 'save';
            $this->data['titulek'] = 'Detail nabídky ' . $nab->data['nazev'];
            Zprava::zobraz('Objekt k detailu byl vybrán');
            $this->pohled = 'DetailObjekt';
        }
        if ($akce == "ObnovSkryj") {
            DB::proved("UPDATE objekt SET status = (- status) WHERE objekt_id = ? ", array($parametry[1]));
            Zprava::zobraz('Objekt byl zneplatněn');
            $this->presmeruj('/objekt/edit/' . $parametry[1]);
        }
        if ($akce == "delete") {
            DB::proved("DELETE FROM obr WHERE id_objekt= ? ", array($parametry[1]));
			DB::proved("DELETE FROM objekt WHERE objekt_id = ? ", array($parametry[1]));
            Zprava::zobraz('Objekt byl smazán');
            $this->presmeruj('/');
        }


        if ($akce == "insert") {

            $nab->data = array_intersect_key($_POST, array_flip(NhlavickaClass::$sloupce));
            $nab->data['status'] = 1;
            $this->data = $nab->data;
            // zaloz noveho
            $this->data['objekt_id'] = $nab->insert($nab->data);
            $this->data['titulek'] = 'Editace objektu';
            // budu ho pak editovat
            $this->data['akce'] = 'edit';
            Zprava::zobraz('Objekt byl založen');
            $this->presmeruj('/nabidka/edit/' . $this->data['id']);
        }

        if ($akce == "edit") {
            //najdu objekt podle objekt_id
            $obj->get($_POST['objekt_id']);
            $obj->data = array_intersect_key(array_merge(array_flip(NhlavickaClass::$sloupce), $obj->data), $obj->data);
            $this->data = $obj->data;
            $this->data['akce'] = 'save';
            $this->data['titulek'] = 'Editace objektu ' . $obj->data['nazev'];
            Zprava::zobraz('Objekt byl vybrán');
        }
        if ($akce == "save") {
			//najdu objekt podle objekt_id
			//  $obj->get($_POST['objekt_id']);
            $obj->data = array_intersect_key($_POST, array_flip(NhlavickaClass::$sloupce));
            $this->data = $obj->data;
            $obj->data['status'] = 2;
            $obj->update($obj->data);
            $this->data['akce'] = 'edit';
            $this->data['titulek'] = 'Editace objektu ' . $obj->data['nazev'];
            Zprava::zobraz('Objekt byl upraven');
			//            //pridam obrazky  udelam to pres Ajax
			//            $img = new ObrClass();
			//            $img->data['id_objekt'] = $obj->data['objekt_id'];
			//            $img->insertFILES();
            $this->presmeruj('/nabidka/edit/' . $this->data['objekt_id']);
        }

        if ($akce == "seznam") {
            //seznam vsech objektu
            $obj->select( ' status >= ? ', Array(0) );
            //    Debug::p($obj);
            $this->data['seznam'] = $obj->data;
            $this->data['akce'] = 'seznam';
            $this->data['titulek'] = 'Seznam objektů';
            Zprava::zobraz('Vybráno ' . count($this->data['seznam']) . ' objektů');
            //   $this->presmeruj('/objekt');
            $this->pohled = 'objekty';
            if (isset($parametry[1]) and $parametry[1] === 'T')
                $this->pohled = 'objektyTab';
        }
    }

}