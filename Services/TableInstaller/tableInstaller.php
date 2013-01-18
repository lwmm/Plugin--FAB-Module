<?php
namespace Fab\Services\TableInstaller;
use \Fab\Domain\Country\Model\countryCommandHandler as countryCommandHandler;
use \Fab\Domain\Event\Model\eventCommandHandler as eventCommandHandler;
use \Fab\Domain\Participant\Model\participantCommandHandler as participantCommandHandler;
use \Fab\Domain\Text\Model\textCommandHandler as textCommandHandler;

class tableInstaller
{
    public function __construct($db) 
    {
        $this->db = $db;
    }
    
    public function execute()
    {
        $countryCommandHandler = new countryCommandHandler($this->db);
        $eventCommandHandler = new eventCommandHandler($this->db);
        $participantCommandHandler = new participantCommandHandler($this->db);
        $textCommandHandler = new textCommandHandler($this->db);
        
        $ok1 = $countryCommandHandler->createTable();        
        if(!$ok1){
            throw new Exception('CREATE TABLE country');
        }else{
            if(!$this->AreCountriesImported()){
                $ok2 = $countryCommandHandler->importCountries();
                if(!$ok2){
                    throw new Exception('IMPORT COUNTRIES');
                }
            }
        }
        
        $ok3 = $eventCommandHandler->createTable();
        if(!$ok3){
            throw new Exception('CREATE TABLE event');
        }
        
        $ok4 = $participantCommandHandler->createTable();
        if(!$ok4){
            throw new Exception('CREATE TABLE participant');
        }
        
        $ok5 = $textCommandHandler->createTable();
        if(!$ok5){
            throw new Exception('CREATE TABLE text');
        }
        
        return true;
    }
    
    public function AreCountriesImported()
    {
        $data = array();
        $file = fopen( dirname(__FILE__)."/../../Domain/Country/Model/data/countries.csv", 'r');
        while (($line = fgetcsv($file,100,";")) !== FALSE) {
            array_push($data, $line);
        }
        fclose($file);

        $assertedArray = array();
        foreach ($data as $value) {
            array_push($assertedArray,array("land" => $value[0], "bezeichnung" => $value[1]));
        }
        
        foreach ($assertedArray as $key => $value) {
            $land[$key] = strtoupper($value["land"]);
        }
        array_multisort($land, SORT_ASC, $assertedArray);
        
        $countryQH = new \Fab\Domain\Country\Model\countryQueryHandler($this->db);
        $result = $countryQH->getAllCountries();
        
        $diff = array_diff($assertedArray, $result);
        
        if($diff == array()){
            return true;
        }else{
            return false;
        }
    }
}