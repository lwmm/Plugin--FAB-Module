<?php
namespace Fab\fab_installer\Service;

class fab_installer_demoData_write
{
    public function __construct($db)
    {
        $this->db = $db;
    }
    
//    public function execute()
//    {
//        $checkDemoData = new \Fab\fab_installer\Service\fab_installer_demoData_check($this->db);
//        if($checkDemoData->checkIfDemoEventIsInstalled() == "error"){
//            $this->installDemoEvent();
//        }
//        if($checkDemoData->checkIfDemoParticipantsAreInstalled() == "error"){
//            $this->installDemoParticipants($checkDemoData->getDemoEventId());
//        }
//    }
            
    public function installDemoEvent()
    { 
        $checkDemoData = new \Fab\fab_installer\Service\fab_installer_demoData_check($this->db);
        if($checkDemoData->checkIfDemoEventIsInstalled() == "error"){
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

            $ok = $this->db->pdbquery();
            if(!$ok){
                throw new Exception('DB INSERT ERROR OF DEMO EVENT');
            }
        }
    }
    
    public function installDemoParticipants()
    {
        $checkDemoData = new \Fab\fab_installer\Service\fab_installer_demoData_check($this->db);
        if($checkDemoData->checkIfDemoParticipantsAreInstalled() == "error"){
            
            $preparedArray = $this->getPerparedDemoParticipantArray($checkDemoData->getDemoEventId());
            
            $participantV  = new \Fab\Domain\Participant\Service\participantValidate();
            foreach ($preparedArray as $entry) {
                
                $participantV->setValues($entry);
                
                if($participantV->validate()){
                    $this->db->setStatement("INSERT INTO t:fab_teilnehmer ( event_id, anrede, sprache, titel, nachname, vorname, institut, unternehmen, unternehmenshortcut, strasse, plz, ort, land, mail, ust_id_nr, zahlweise, teilnehmer_intern, betrag, first_date, last_date ) VALUES ( :event_id, :anrede, :sprache, :titel, :nachname, :vorname, :institut, :unternehmen, :shortcut, :strasse, :plz, :ort, :land, :mail, :ust_id_nr, :zahlweise, :teilnehmer_intern, :betrag, :first_date, :last_date ) ");
                    $this->db->bindParameter("event_id", "i", $entry["event_id"]);
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
                        throw new Exception('DB INSERT ERROR OF DEMO PARTICIPANT');
                    }
                };
            }
            
        }
        
    }
    
    public function getPerparedDemoParticipantArray($event_id)
    {
        $data = array();
        $file = fopen( dirname(__FILE__)."/../../Domain/Participant/Model/demodata/teilnehmer_demo.csv", 'r');
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
            $value["event_id"]              = $event_id;
            
            for($i = 0; $i <= 16; $i++){
                unset($value[$i]);
            }
            $reconstructedArray[] = $value;
        }
        unset($reconstructedArray[0]);        
        return $reconstructedArray;
    }
}