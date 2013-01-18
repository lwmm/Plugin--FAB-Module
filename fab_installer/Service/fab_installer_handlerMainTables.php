<?php
namespace Fab\fab_installer\Service;
use \Fab\fab_installer\Service\fab_installer_demoData_check as checkDemoData;
use \Fab\fab_installer\Service\fab_installer_demoData_write as writeDemoData;

class fab_installer_handlerMainTables
{
    public function __construct($db)
    {
        $this->db = $db;
        $this->checkDemoData = new checkDemoData($this->db);
        $this->writeDemoData = new writeDemoData($this->db);
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
        
        #die($this->checkDemoData->checkIfDemoParticipantsAreInstalled());
        return array(
            "fab_laender" => $laender,
            "fab_laender_import" => $import,
            "fab_tagungen" => $tagungen,
            "fab_teilnehmer" => $teilnehmer,
            "fab_text" => $text,
            "percent" => $percent,
            "demoEvent" => $this->checkDemoData->checkIfDemoEventIsInstalled(),
            "demoParticipants" => $this->checkDemoData->checkIfDemoParticipantsAreInstalled()
        );
    }
    
    public function checkImportedFabLaenderData()
    {
        if($this->db->tableExists($this->db->gt("fab_laender"))){
            $countryQH = new \Fab\Domain\Country\Model\countryQueryHandler($this->db);

            $data = array();
            $file = fopen( dirname(__FILE__)."/../../Domain/Country/Model/data/countries.csv", 'r');
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

            if($array === $result){
               return true;
            }else{
                return false;
            }
        }
        return false;
    }
}