<?php
namespace Fab\fab_installer\Service;

class fab_installer_demoData_check
{
    public function __construct($db)
    {
        $this->db = $db;
    }
    
    public function checkIfDemoEventIsInstalled()
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

            $this->result = $this->db->pselect1();
            
            if($this->result == array()){
                return "error";
            }else{
                return "done";
            }
        }
    }
    
    public function checkIfDemoParticipantsAreInstalled()
    {
        $this->checkIfDemoEventIsInstalled();
        if(!empty($this->result)){
            $writeDemoData = new \Fab\fab_installer\Service\fab_installer_demoData_write($this->db);
            $event_id = $this->result["id"];

            $this->db->setStatement("SELECT * FROM t:fab_teilnehmer WHERE event_id = :event_id ");
            $this->db->bindParameter("event_id", "i", $event_id);
            $result = $this->db->pselect();
            
            $reconstructedArray = array();
            foreach($result as $value){
                unset($value["id"]);
                unset($value["first_date"]);
                unset($value["last_date"]);
                $reconstructedArray[] = $value;
            }
            $preparedParticipantArray = $writeDemoData->getPerparedDemoParticipantArray($event_id);
            
            /*
             * count/count vergleich nich optimal
             * array_diff funktion ergab nur leeres array,auch nach array manipulation
             */
            if(count($reconstructedArray) == count($preparedParticipantArray)){
                return "done";
            }else{
                return "error";
            }
        }else{
            return "error";
        }
    }
    
    public function getDemoEventId()
    {
        $this->checkIfDemoEventIsInstalled();
        if(!empty($this->result)){
            return $this->result["id"];
        }
    }
}