<?php

// Controller pro praci s Objektem

class ObjektKontroler extends AbstractKontroler {
  // zde jsou povolene akce
    static $typakce = array('smazFoto', 'view', 'new', 'edit', 'delete', 'insert', 'ObnovSkryj', 'list', 'save', 'seznam');

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

        if ((!isset($_POST['objekt_id'])) and isset($parametry[1]))
            $_POST['objekt_id'] = $parametry[1];

       // Debug::p($akce);
//Debug::p($_POST);
        $this->pohled = 'objekt';     // Nastavení implicitni šablony
        // Hlavička stránky
        $this->hlavicka['titulek'] = 'Editor objektu';
        $obj = new ObjektClass();

        // Byl odeslán formulář
        if ($akce == "new") {
            $this->data = $obj->data;   // Proměnné pro šablonu
            $this->data['titulek'] = 'Nový objekt';
            //zadam majitele ze session
           // $this->data['id_majitel'] = isset($_SESSION['s_majitel']) ? $_SESSION['s_majitel']['majitel_id'] : 'NULL';
          //KG            $obj->getByID($this->data['id_majitel']);
            if (isset($obj) and $obj->data['objekt_id'] > 0) {  //existuje jiz objekt ktomuto majiteli, presmeruji rovnou na objekt
                $this->presmeruj('/objekt/edit/' . $obj->data['objekt_id']);
            }
            else{
                // po odeslani formulare uloz
                $this->data['akce'] = 'insert';
//   $akce = 'insert';      // po odeslani formulare uloz
            }

        }
        if ($akce == "EmailMajitel") {
            $this->data['akce'] = $akce;
            $this->data['id_majitel'] = $_POST['id_majitel'];
            $this->data['objekt_id'] = $_POST['objekt_id'];
            $this->pohled = 'MajitelMail';
            $this->presmeruj('/admin/MajitelMail/' . $parametry[1]);
        }
        if ($akce == "view") {
            $obj->get($parametry[1]);
            $obj->data = array_intersect_key(array_merge(array_flip(ObjektClass::$sloupce), $obj->data), $obj->data);
            $this->data = $obj->data;
            $this->data['akce'] = 'save';
            $this->data['titulek'] = 'Detail objektu ' . $obj->data['nazev'];
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

        if ($akce == "smazFoto") {
            DB::proved("DELETE FROM obr WHERE id_objekt= ? ", array($parametry[1]));
            Zprava::zobraz('Fotografie byly smazany');
            $this->presmeruj('/objekt/edit/' . $parametry[1]);
        }
        if ($akce == "insert") {

            $obj->data = array_intersect_key($_POST, array_flip(ObjektClass::$sloupce));
            $obj->data['status'] = 1;
            $this->data = $obj->data;
            // zaloz noveho
            $this->data['objekt_id'] = $obj->insert($obj->data);
            $this->data['titulek'] = 'Editace objektu';
            // budu ho pak editovat
            $this->data['akce'] = 'edit';
            Zprava::zobraz('Objekt byl založen');
            $this->presmeruj('/objekt/edit/' . $this->data['objekt_id']);
        }

        if ($akce == "edit") {
            //najdu objekt podle objekt_id
            $obj->get($_POST['objekt_id']);
            $obj->data = array_intersect_key(array_merge(array_flip(ObjektClass::$sloupce), $obj->data), $obj->data);
            $this->data = $obj->data;
            $this->data['akce'] = 'save';
            $this->data['titulek'] = 'Editace objektu ' . $obj->data['nazev'];
            Zprava::zobraz('Objekt byl vybrán');
        }
        if ($akce == "save") {
			//najdu objekt podle objekt_id
          //  $obj->get($_POST['objekt_id']);
            $obj->data = array_intersect_key($_POST, array_flip(ObjektClass::$sloupce));
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
            $this->presmeruj('/objekt/edit/' . $this->data['objekt_id']);
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