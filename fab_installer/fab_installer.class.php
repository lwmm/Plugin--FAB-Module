<?php
class fab_installer extends lw_plugin
{
    public function __construct()
    {
        lw_plugin::__construct();
    }
    
    public function buildPageOutput()
    {
        include_once(dirname(__FILE__).'/../Services/Autoloader/fabAutoloader.php');
        $autoloader = new Fab\Service\Autoloader\fabAutoloader();
        $autoloader->setConfig($this->config);

        if($this->params['showui'] & $this->params['showui'] == 1){
            switch ($this->request->getInt("install")) {
                case 1:
                    $installer = new \Fab\Services\TableInstaller\tableInstaller($this->db);
                    $installer->execute();
                    $this->pageReload($this->config["url"]["client"]."index.php?index=".$this->request->getInt("index"));
                    break;

                case 2:
                    $installer = new \Fab\Services\TableInstaller\tableInstaller($this->db);
                    $installer->execute();
                    $this->installDemoData();
                    $this->pageReload($this->config["url"]["client"]."index.php?index=".$this->request->getInt("index"));
                    break;
                    
                default:
                    return $this->buildTemplate($this->checkTableExistance());
                    break;
            }
        }else{
            $installer = new \Fab\Services\TableInstaller\tableInstaller($this->db);
            $installer->execute();
            if($installer) echo "ERLEDIGT";
        }
    }
    
    
    function buildLink($bool=false)
    {
        $array['index'] = $this->request->getInt("index");
        if($bool) $array['install'] = $bool;
        $url = $this->config["url"]["client"]."index.php?".http_build_query($array);
        $url = str_replace("&amp;", "&", $url);
        return $url;
    }
    
    public function buildTemplate($array)
    {
        $template = file_get_contents(dirname(__FILE__) . '/templates/tableinstaller.tpl.html');
        $tpl = new lw_te($template);
        $tpl->reg("icon_done", $this->config["url"]["pics"]."/fatcow_icons/16x16_0020/accept.png");
        $tpl->reg("icon_error", $this->config["url"]["pics"]."/fatcow_icons/16x16_0400/error.png");
        $tpl->reg("percent", $array["percent"]);
        $tpl->reg("progressbar_width", $array["percent"] * 1.5);
        
        if($array["percent"] == 100){
            $tpl->reg("buttonname", "done !");
            $tpl->reg("button_link", "");
        }else{
            $tpl->reg("buttonname", "install");
            $tpl->reg("button_link", $this->buildLink("1"));
        }
       
        if($array["demodata"] == "done"){
            $tpl->setIfVar("demodata");
            $tpl->reg("class_demodata", $array["demodata"]);
            $tpl->reg("buttonname_demo", "done !");
            $tpl->reg("button_link_demo", "");
        }else{
            $tpl->reg("buttonname_demo", "install");
            $tpl->reg("button_link_demo", $this->buildLink("2"));
        }
        
        $tpl->reg("class_laender", $array["fab_laender"]);
        $tpl->reg("class_import", $array["fab_laender_import"]);
        $tpl->reg("class_tagungen", $array["fab_tagungen"]);
        $tpl->reg("class_teilnehmer", $array["fab_teilnehmer"]);
        $tpl->reg("class_text", $array["fab_text"]);
        
        return $tpl->parse();
    }
    
    public function setParameter($param) {
        $parts = explode("&", $param);
        foreach ($parts as $part) {
            $sub = explode("=", $part);
            $this->params[$sub[0]] = $sub[1];
        }
    }
    
    public function checkTableExistance()
    {
        $i = 0;
        if($this->db->tableExists($this->db->gt("fab_laender"))){
            $laender = "done";
            $i++;
        }else{
            $laender = "error";
        }
        
        if($this->checkImportedFabLaenderData()){
            $import = "done";
            $i++;
        }else{
            $import = "error";
        }
        
        if($this->db->tableExists($this->db->gt("fab_tagungen"))){
            $tagungen = "done";
            $i++;
        }else{
            $tagungen = "error";
        }
        
        if($this->db->tableExists($this->db->gt("fab_teilnehmer"))){
            $teilnehmer = "done";
            $i++;
        }else{
            $teilnehmer = "error";
        }
        
        if($this->db->tableExists($this->db->gt("fab_text"))){
            $text = "done";
            $i++;
        }else{
            $text = "error";
        }
        
        $percent = $i / 5 * 100;
        
        return array(
            "fab_laender" => $laender,
            "fab_laender_import" => $import,
            "fab_tagungen" => $tagungen,
            "fab_teilnehmer" => $teilnehmer,
            "fab_text" => $text,
            "percent" => $percent,
            "demodata" => $this->checkIfDemoDataIsInstalled()
        );
    }
    
    public function checkImportedFabLaenderData()
    {
        if($this->db->tableExists($this->db->gt("fab_laender"))){
            $countryQH = new \Fab\Domain\Country\Model\countryQueryHandler($this->db);

            $data = array();
            $file = fopen( dirname(__FILE__)."/../Domain/Country/Model/data/countries.csv", 'r');
            while (($line = fgetcsv($file,100,";")) !== FALSE) {
                array_push($data, $line);
            }
            fclose($file);

            $array = array();
            foreach ($data as $value) {
                array_push($array,array("land" => $value[0], "bezeichnung" => $value[1]));
            }

            foreach ($array as $key => $value) {
                $land[$key] = strtoupper($value["land"]);
            }
            array_multisort($land, SORT_ASC, $array);

            $result = $countryQH->getAllCountries();

            $diff = array_diff($array, $result);

            if($diff == array()){
               return true;
            }else{
                return false;
            }
        }
        return false;
    }
    
    public function checkIfDemoDataIsInstalled()
    {
        if($this->db->tableExists($this->db->gt("fab_tagungen"))){
            $this->db->setStatement("SELECT * FROM t:fab_tagungen WHERE buchungskreis = :buchungskreis AND v_schluessel = :v_schluessel AND auftragsnr = :auftragsnr AND bezeichnung = :bezeichnung AND v_land = :v_land AND v_ort = :v_ort AND anmeldefrist_beginn = :anmeldefrist_beginn AND anmeldefrist_ende = :anmeldefrist_ende AND v_beginn = :v_beginn AND v_ende = :v_ende AND cpd_konto = :cpd_konto AND erloeskonto = :erloeskonto AND steuerkennzeichen = :steuerkennzeichen AND steuersatz = :steuersatz AND ansprechpartner = :ansprechpartner AND ansprechpartner_tel = :tel_ansprechpartner AND organisationseinheit = :organisationseinheit AND ansprechpartner_mail = :mail_ansprechpartner AND stellvertreter_mail = :stellvertreter_mail AND standardbetrag = :standardbetrag ");
            $this->db->bindParameter("buchungskreis", "s", "15");
            $this->db->bindParameter("v_schluessel", "s", "65038462");
            $this->db->bindParameter("auftragsnr", "s", "45135060");
            $this->db->bindParameter("bezeichnung", "s", "Tagung 1");
            $this->db->bindParameter("v_land", "s", "de");
            $this->db->bindParameter("v_ort", "s", "Juelich");
            $this->db->bindParameter("anmeldefrist_beginn", "i", "20131220");
            $this->db->bindParameter("anmeldefrist_ende", "i", "20131224");
            $this->db->bindParameter("v_beginn", "i", "20131228");
            $this->db->bindParameter("v_ende", "i", "20131229");
            $this->db->bindParameter("cpd_konto", "s", "200270");
            $this->db->bindParameter("erloeskonto", "s", "4510");
            $this->db->bindParameter("steuerkennzeichen", "s", "98");
            $this->db->bindParameter("steuersatz", "s", "9");
            $this->db->bindParameter("ansprechpartner", "s", "Max Mustermann");
            $this->db->bindParameter("tel_ansprechpartner", "i", "1111");
            $this->db->bindParameter("organisationseinheit", "s", "GB-F");
            $this->db->bindParameter("mail_ansprechpartner", "s", "m.mustermann@fz-juelich.de");
            $this->db->bindParameter("stellvertreter_mail", "s", "s.vertreter@fz-juelich.de");
            $this->db->bindParameter("standardbetrag", "s", "100");

            $result = $this->db->pselect1();
            
            if($result == array()){
                return "error";
            }else{
                return "done";
            }
        }
    }
    
    public function getPerparedDemoDataArray()
    {
        $data = array();
        $file = fopen( dirname(__FILE__)."/../Domain/Participant/Model/demodata/teilnehmer_demo.csv", 'r');
        while (($line = fgetcsv($file,2000,";")) !== FALSE) {
            array_push($data, $line);
        }
        fclose($file);
        
        foreach($data as $value){
            $value["unternehmenshortcut"]   = $value[0];
            $value["unternehmen"]           = $value[1];
            $value["institut"]              = $value[2];
            $value["strasse"]               = $value[3];
            $value["plz"]                   = $value[4];
            $value["ort"]                   = $value[5];
            $value["land"]                  = $value[6];
            $value["anrede"]                = $value[7];
            $value["titel"]                 = $value[8];
            $value["vorname"]               = $value[9];
            $value["nachname"]              = $value[10];
            $value["mail"]                  = $value[11];
            $value["teilnehmer_intern"]     = $value[12];
            $value["sprache"]               = $value[13];
            $value["ust_id_nr"]             = $value[14];
            $value["betrag"]                = $value[15];
            $value["zahlweise"]             = $value[16];
            $value["event_id"]              = "1";
            
            for($i = 0; $i <= 16; $i++){
                unset($value[$i]);
            }
            $reconstructedArray[] = $value;
        }
        unset($reconstructedArray[0]);
        
        return $reconstructedArray;
    }

    public function installDemoData()
    { 
        $this->db->setStatement("INSERT INTO t:fab_tagungen ( buchungskreis, v_schluessel, auftragsnr, bezeichnung, v_land, v_ort, anmeldefrist_beginn, anmeldefrist_ende, v_beginn, v_ende, cpd_konto, erloeskonto, steuerkennzeichen, steuersatz, ansprechpartner, ansprechpartner_tel, organisationseinheit, ansprechpartner_mail, stellvertreter_mail, standardbetrag, first_date, last_date ) VALUES ( :buchungskreis, :v_schluessel, :auftragsnr, :bezeichnung, :v_land, :v_ort, :anmeldefrist_beginn, :anmeldefrist_ende, :v_beginn, :v_ende, :cpd_konto, :erloeskonto, :steuerkennzeichen, :steuersatz, :ansprechpartner, :tel_ansprechpartner, :organisationseinheit, :mail_ansprechpartner, :stellvertreter_mail, :standardbetrag, :first_date, :last_date ) ");
        $this->db->bindParameter("buchungskreis", "s", "15");
        $this->db->bindParameter("v_schluessel", "s", "65038462");
        $this->db->bindParameter("auftragsnr", "s", "45135060");
        $this->db->bindParameter("bezeichnung", "s", "Tagung 1");
        $this->db->bindParameter("v_land", "s", "de");
        $this->db->bindParameter("v_ort", "s", "Juelich");
        $this->db->bindParameter("anmeldefrist_beginn", "i", date("Y")."1220");
        $this->db->bindParameter("anmeldefrist_ende", "i", date("Y")."1224");
        $this->db->bindParameter("v_beginn", "i", date("Y")."1228");
        $this->db->bindParameter("v_ende", "i", date("Y")."1229");
        $this->db->bindParameter("cpd_konto", "s", "200270");
        $this->db->bindParameter("erloeskonto", "s", "4510");
        $this->db->bindParameter("steuerkennzeichen", "s", "98");
        $this->db->bindParameter("steuersatz", "s", "9");
        $this->db->bindParameter("ansprechpartner", "s", "Max Mustermann");
        $this->db->bindParameter("tel_ansprechpartner", "i", "1111");
        $this->db->bindParameter("organisationseinheit", "s", "GB-F");
        $this->db->bindParameter("mail_ansprechpartner", "s", "m.mustermann@fz-juelich.de");
        $this->db->bindParameter("stellvertreter_mail", "s", "s.vertreter@fz-juelich.de");
        $this->db->bindParameter("standardbetrag", "s", "100");
        $this->db->bindParameter("first_date", "i", date("YmdHis"));
        $this->db->bindParameter("last_date", "i", date("YmdHis"));
        
        $newId = $this->db->pdbinsert($this->db->gt("fab_tagungen"));

        $participantV  = new \Fab\Domain\Participant\Service\participantValidate();
        foreach ($this->getPerparedDemoDataArray() as $entry) {
            $participantV->setValues($entry);
            if($participantV->validate()){
                $this->db->setStatement("INSERT INTO t:fab_teilnehmer ( event_id, anrede, sprache, titel, nachname, vorname, institut, unternehmen, unternehmenshortcut, strasse, plz, ort, land, mail, ust_id_nr, zahlweise, teilnehmer_intern, betrag, first_date, last_date ) VALUES ( :event_id, :anrede, :sprache, :titel, :nachname, :vorname, :institut, :unternehmen, :shortcut, :strasse, :plz, :ort, :land, :mail, :ust_id_nr, :zahlweise, :teilnehmer_intern, :betrag, :first_date, :last_date ) ");
                $this->db->bindParameter("event_id", "i", $newId);
                $this->db->bindParameter("anrede", "s", $entry['anrede']);
                $this->db->bindParameter("sprache", "s", $entry['sprache']);
                $this->db->bindParameter("titel", "s", $entry['titel']);
                $this->db->bindParameter("nachname", "s", $entry['nachname']);
                $this->db->bindParameter("vorname", "s", $entry['vorname']);
                $this->db->bindParameter("institut", "s", $entry['institut']);
                $this->db->bindParameter("unternehmen", "s", $entry['unternehmen']);
                $this->db->bindParameter("shortcut", "s", $entry['unternehmenshortcut']);
                $this->db->bindParameter("strasse", "s", $entry['strasse']);
                $this->db->bindParameter("plz", "s", $entry['plz']);
                $this->db->bindParameter("ort", "s", $entry['ort']);
                $this->db->bindParameter("land", "s", $entry['land']);
                $this->db->bindParameter("mail", "s", $entry['mail']);
                $this->db->bindParameter("ust_id_nr", "s", $entry['ust_id_nr']);
                $this->db->bindParameter("zahlweise", "s", $entry['zahlweise']);
                $this->db->bindParameter("teilnehmer_intern", "i", $entry['teilnehmer_intern']);
                $this->db->bindParameter("betrag", "s", $entry['betrag']);
                $this->db->bindParameter("first_date", "i", date("YmdHis"));
                $this->db->bindParameter("last_date", "i", date("YmdHis"));
                
                $ok = $this->db->pdbquery();
                if(!$ok){
                    throw new Exception('DB INSERT ERROR OF DEMO DATA');
                }
            };
        }
    }
}